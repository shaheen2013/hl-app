var googlePlace = function () {
    var hotelData = {
        placeAdmArea: '',
        placeName: '',
        placeId: '',
        lat: 0,
        lng: 0,
        placeCountry: '',
        countryName: '',
        viewPort: null
    };

    var init = function () {
        setFromForm();
        getInfoFromCity();
        getInfoFromAddress();
    };

    var getInfoFromCity = function () {
        var input = document.getElementById('hotelCity');
        var autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['(cities)'],
        });

        preventEnterKey(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var thisplace = autocomplete.getPlace();
            var arrayComponents = thisplace.address_components;

            hotelData.placeName = arrayComponents[0].short_name;
            hotelData.placeId = thisplace.place_id;
            hotelData.lat = thisplace.geometry.location.lat();
            hotelData.lng = thisplace.geometry.location.lng();
            hotelData.viewPort = thisplace.geometry.viewport;

            for (var index = 0; index < arrayComponents.length; ++ index) {
                if (arrayComponents[index].types[0] == 'administrative_area_level_1') {
                    hotelData.placeAdmArea = arrayComponents[index].short_name;
                } else if (arrayComponents[index].types[0] == 'country') {
                    hotelData.placeCountry = arrayComponents[index].short_name;
                    hotelData.countryName = arrayComponents[index].long_name;
                }
            }

            updateForm();
            getInfoFromAddress();
        });
    };

    var getInfoFromAddress = function () {
        var input = document.getElementById('hotelStreet');
        var cityBounds = null;
        var autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['address'],
        });
        preventEnterKey(input);

        if (hotelData.viewPort) {
            $(input).unbind('input');
            cityBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(hotelData.viewPort.Wa.i, hotelData.viewPort.Ra.i),
                new google.maps.LatLng(hotelData.viewPort.Wa.j, hotelData.viewPort.Ra.j)
            );
        } else {
            $(input).unbind('input');
            $(input).on('input', function () {
                var str = input.value;
                var prefix = hotelData.placeName ? hotelData.placeName + ', ' : '';

                if (str.indexOf(prefix) < 0) {
                    if (prefix.indexOf(str) >= 0) {
                        input.value = prefix;
                    } else {
                        input.value = prefix + str;
                    }
                }
            });
        }

        autocomplete.setOptions({
            bounds: cityBounds,
            strictBounds: true,
        });

        autocomplete.setComponentRestrictions({
            country: [hotelData.placeCountry],
        });
        autocomplete.setFields(['address_components', 'geometry', 'name']);
        autocomplete.addListener('place_changed', function () {
            var thisplace = this.getPlace();

            try {
                hotelData.lat = thisplace.geometry.location.lat();
                hotelData.lng = thisplace.geometry.location.lng();

                updateForm();

                try {
                    HotelSuggest.changeCoordinates(hotelData.lat, hotelData.lng);
                } catch (e) {
                    console.warn(e.message);
                }

            } catch (e) {
                console.warn(e.message);
            }
        });
    };

    var preventEnterKey = function (input) {
        google.maps.event.addDomListener(input, 'keydown', function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    };

    var updateForm = function () {
        $('#place_adm_area').val(hotelData.placeAdmArea);
        $('#place_name').val(hotelData.placeName);
        $('#place_id').val(hotelData.placeId);
        $('#lat').val(hotelData.lat);
        $('#lng').val(hotelData.lng);
        $('#place_country').val(hotelData.placeCountry);
        $('#country_name').val(hotelData.countryName);
    };

    var setFromForm = function () {
        hotelData = {
            placeAdmArea: $('#place_adm_area').val(),
            placeName: $('#place_name').val(),
            placeId: $('#place_id').val(),
            lat: $('#lat').val(),
            lng: $('#lng').val(),
            placeCountry: $('#place_country').val(),
            countryName: $('#country_name').val(),
        };
    };

    return {
        init: init,
    };

}();

$(document).ready(function () {
    googlePlace.init();
});
