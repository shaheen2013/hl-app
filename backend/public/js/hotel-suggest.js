var HotelSuggest = function () {
    var hotels = [];
    var endPoint = '';
    var searchFields = ['name', 'place', 'country'];
    var hotelSuggestInput = $('#hotel-suggest');
    var chainId;
    var staffId;
    var map;
    var tooltipExplanation = "";
    var hotelListLayout = $('#show-hotel-list');
    var show = $('#show-hotel-search');
    var loading = false;
    var callbackDuringInitialLoading = false;

    var minZoomLevel = 2;
    var lastValidCenter;
    var includesApp = (window.location.pathname.indexOf('/app/') > -1);

    var init = function (id, staff, apiEndPoint, tooltipText) {
        chainId = id;
        staffId = staff;
        endPoint = staffId ? apiEndPoint + '&staff_id=' + staffId : apiEndPoint;
        tooltipExplanation = tooltipText;

        hotels = JSON.parse(sessionStorage.getItem('suggest' + chainId + 'staff' + staffId)) || [];
        buttonsEvent();
        suggestEvent();

        if (!hotels.length) {
            getHotels(function () {
            });
        }
    };

    var buttonsEvent = function () {
        var actionMethod = function (callback) {
            if (endPoint && !hotels.length) {
                $('#hotelSuggestLoading').toggleClass('show');
                getHotels(callback);
            } else if (hotels.length) {
                callback();
            }
        };

        $('button.hotel-list').on('click', function (e) {
            actionMethod(showHotels);
        });

        $('button.hotel-map').on('click', function (e) {
            actionMethod(showMap);
        });

        $('button.hotel-search-hotel').on('click', function (e) {
            setTimeout(function () {
                actionMethod(showSearch);
                $('input[name="hotel-suggest"]').focus();
            }, 100);
        });

        $(document).on('focus click', 'input[name="hotel-suggest"]', function (e) {
            $('#show-hotel-list').removeClass('show');
            $('#show-hotel-map').removeClass('show');
            $('#show-hotel-search').addClass('expanse');

            if (loading) {
                $('#loadingHotelInput').show();
                $('#searchIconHotelInput').hide();
            }
        });

        $(document).on('blur', 'input[name="hotel-suggest"]', function (e) {
            $('#show-hotel-search').removeClass('expanse');
        });

    };

    var setMap = function (tries) {
        $('html').css('overflow', 'hidden');

        var actualHotel;

        for (var i = 0; i < hotels.length; i ++) {
            if (hotels[i].id == sessionStorage.getItem('actualHotel')) {
                actualHotel = hotels[i];
                break;
            }
        }

        try {
            map = new google.maps.Map(document.getElementById('show-hotel-map'), {
                zoom: 5,
                center: new google.maps.LatLng(actualHotel.lat, actualHotel.lng),
                zoomControl: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false,
                minZoom: 2,
                gestureHandling: 'greedy',
                styles: [
                    {
                        'stylers': [
                            {
                                'color': '#e2dfff',
                            },
                        ],
                    },
                    {
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#e6e6ff',
                            },
                        ],
                    },
                    {
                        'elementType': 'labels.icon',
                        'stylers': [
                            {
                                'visibility': 'off',
                            },
                        ],
                    },
                    {
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#616161',
                            },
                        ],
                    },
                    {
                        'elementType': 'labels.text.stroke',
                        'stylers': [
                            {
                                'color': '#f5f5f5',
                            },
                        ],
                    },
                    {
                        'featureType': 'administrative',
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#e2dfff',
                            },
                        ],
                    },
                    {
                        'featureType': 'administrative.country',
                        'elementType': 'geometry.fill',
                        'stylers': [
                            {
                                'color': '#e2dfff',
                            },
                        ],
                    },
                    {
                        'featureType': 'administrative.country',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#6454d1',
                            },
                        ],
                    },
                    {
                        'featureType': 'administrative.country',
                        'elementType': 'labels.text.stroke',
                        'stylers': [
                            {
                                'visibility': 'off',
                            },
                        ],
                    },
                    {
                        'featureType': 'administrative.land_parcel',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#bdbdbd',
                            },
                        ],
                    },
                    {
                        'featureType': 'poi',
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#755dff',
                            },
                        ],
                    },
                    {
                        'featureType': 'poi',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#757575',
                            },
                        ],
                    },
                    {
                        'featureType': 'poi.park',
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#cbc9ff',
                            },
                        ],
                    },
                    {
                        'featureType': 'poi.park',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#9e9e9e',
                            },
                        ],
                    },
                    {
                        'featureType': 'road',
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#ffffff',
                            },
                        ],
                    },
                    {
                        'featureType': 'road.arterial',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#757575',
                            },
                        ],
                    },
                    {
                        'featureType': 'road.highway',
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#d0cfff',
                            },
                        ],
                    },
                    {
                        'featureType': 'road.highway',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#616161',
                            },
                        ],
                    },
                    {
                        'featureType': 'road.local',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#9e9e9e',
                            },
                        ],
                    },
                    {
                        'featureType': 'water',
                        'elementType': 'geometry',
                        'stylers': [
                            {
                                'color': '#c9c9c9',
                            },
                        ],
                    },
                    {
                        'featureType': 'water',
                        'elementType': 'geometry.fill',
                        'stylers': [
                            {
                                'color': '#fefffd',
                            },
                        ],
                    },
                    {
                        'featureType': 'water',
                        'elementType': 'labels.text.fill',
                        'stylers': [
                            {
                                'color': '#9e9e9e',
                            },
                        ],
                    },
                ],
            });
        } catch (e) {
            if (tries) {
                return;
            }

            $.ajaxSetup({cache: true});
            $.when(
                $.getScript('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en'),
                $.Deferred(function (deferred) {
                    $(deferred.resolve);
                })
            ).done(function () {
                setMap(1);
            });

            return;
        }
        google.maps.event.addListener(map, 'dragend', function () {
            checkLatitude(map);
        });
        google.maps.event.addListener(map, 'idle', function () {
            checkLatitude(map);
        });
        google.maps.event.addListener(map, 'zoom_changed', function () {
            checkLatitude(map);
        });
        var positions = [];
        var markers = hotels.map(function (hotel, i) {

            positions.find(function (position) {
                var current = hotel.lat + hotel.lng;

                if (current == position) {
                    hotel.lat += 0.001;
                    hotel.lng += 0.001;
                }
            });

            positions.push(hotel.lat + hotel.lng);

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(hotel.lat, hotel.lng),
                title: hotel.name,
                map: map,
                icon: '/public/js/googlemarker/single_marker.png',
            });

            var info = new google.maps.InfoWindow({
                content: '<h4>' + hotel.name + '</h4>' +
                         '<div>' + hotel.place + '<span class="hotel-details-zone">' + (includesApp ? '<i class="user icon"></i>' : '<i class="fa fa-users"></i>') + (hotel.users || 0) + ' &nbsp;' + (includesApp ? '<i class="star icon"></i>' : '<i class="fa fa-star"></i>') + (hotel.score || 0) + '</span></div>' +
                         '<div id="hola" data-hotel="' + hotel.id +'" style="text-align: center;margin-top: .5em;"><a>Go to hotel</a></div>',
            });

            $('body').off('click').one('click', '#hola', function a(event) {
                console.log('adios', event.currentTarget.dataset.hotel);
                document.body.removeEventListener('click', this);
                changeHotel(event.currentTarget.dataset.hotel, window.location);
            });

            marker.addListener('click', function () {
                info.open(map, marker, window.location);
            });

            marker.addListener('dblclick', function () {
                changeHotel(hotel.id, window.location);
            });

            return marker;
        });

        new MarkerClusterer(map, markers, {
            styles: [
                {
                    url: '/public/js/googlemarker/cluster_marker.png',
                    width: 52,
                    height: 53,
                    textSize: 20,
                    textColor: 'white',
                },
            ],

        });

        $('body').on('click', 'a.change-hotel', function (e) {
            e.preventDefault();

            changeHotel($(this).data('hotel_id'), window.location);
        });

        $('#show-hotel-map').addClass('show');
    };

    var getHotels = function (callback) {
        if (!loading) {
            loading = true;
            $.ajax({
                url: endPoint,
                success: function (response) {
                    if (response) {
                        hotels = response['child_data'] || [];
    
                        if (hotels.length) {
                            try {
                                sessionStorage.setItem('suggest' + chainId + 'staff' + staffId, JSON.stringify(hotels));
                                sessionStorage.setItem('actualHotel', hotels[0].id);
                                suggestEvent();
                                callback();

                                if (callbackDuringInitialLoading) {
                                    callbackDuringInitialLoading();
                                }
                            } catch (e) {
    
                            }
                        }
                    }
                },
                complete: function(data) {
                    loading = false;
                    $('#hotelSuggestLoading').removeClass('show');
                    $('#loadingHotelInput').hide();
                    $('#searchIconHotelInput').show();
                }
            });
        } else {
            callbackDuringInitialLoading = $('#hotelSuggestLoading').is(":visible") ? callback : false;
        }
    };

    var showMap = function () {

        $('#show-hotel-list').removeClass('show');

        if (map) {
            $('#show-hotel-map').toggleClass('show');
        } else {
            setMap();
        }

        if ($('#show-hotel-map').hasClass('show')) {
            $('html').css('overflow', 'hidden');
        } else {
            $('html').css('overflow', '');
        }
    };

    var showSearch = function () {

        $('#show-hotel-list').removeClass('show');
        $('#show-hotel-map').removeClass('show');

        if (show.find('ul').length) {
            suggestEvent();
        } else {
            $('#show-hotel-search').removeClass('expanse');
            $('#show-hotel-search').toggleClass('ocult');
        }

    };

    var showHotels = function () {
        $('#show-hotel-map').removeClass('show');

        if (hotelListLayout.find('ul').length) {
            hotelListLayout.toggleClass('show');
        } else {
            fillHotelData();
            
            if (!includesApp) {
                $(".userIconList").tooltip();
            } else {
                $('.userIconList').popup({
                    on: 'hover'
                });
            }
        }

        if (hotelListLayout.hasClass('show')) {
            $('html').css('overflow', 'hidden');
        } else {
            $('html').css('overflow', '');
        }

    };

    var fillHotelData = function () {
        var hotelListLayout = $('#show-hotel-list');
        var hotelList, column, item, zone;
        var makeItem = function (hotel) {
            var itemHotel, actionHotel;

            itemHotel = $('<li class="hotels">');
            actionHotel = $('<a>');
            actionHotel.click(function (e) {
                e.preventDefault();
                changeHotel(hotel.id, window.location);
            });

            actionHotel.append('<div class="hotel-list-info" ' +
                (hotel.id == sessionStorage.getItem('actualHotel') ? (includesApp ? 'style="color:white !important; background-color:#7059f6 !important"' : 'style="color:white !important; background-color:#65c3df !important"') : '') +
                '>' + hotel.name + '&nbsp;<span ' +
                (hotel.id == sessionStorage.getItem('actualHotel') ? 'style="color:white !important"' : '') +
                'class="hotel-details-zone hasTooltip userIconList ui has-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title=" ' + tooltipExplanation + '" data-content="' + tooltipExplanation + '" data-variation="wide">' +
                (includesApp ? '<i class="user icon"></i>' : '<i class="fa fa-users"></i>') +
                (hotel.users || 0) + '&nbsp;' +
                (includesApp ? '<i class="star icon"></i>' : '<i class="fa fa-star"></i>') + (hotel.score || 0) + '</span></div>');
            itemHotel.append(actionHotel);

            return itemHotel;
        };

        var map = groupHotelsByPlace(hotels, function (hotel) {
            return hotel.place;
        });

        hotelList = $('<ul>');
        map.forEach(function (value, key, map) {
            column = $('<li class="zones"><h4><b>' + key + '(' + value.length + ')</b></h4></li>');
            zone = $('<ul style="padding:0px;">');
            for (var i = 0; i < value.length; i ++) {
                hotelId = value[i].id;

                item = makeItem(value[i]);
                zone.append(item);
            }

            column.append(zone);
            hotelList.append(column);
        });

        hotelListLayout.append(hotelList);
        hotelListLayout.addClass('show');
    };

    var suggestEvent = function () {
        if (!chainId || !hotelSuggestInput.length) {
            return false;
        }

        hotelSuggestInput.typeahead('destroy');
        hotelSuggestInput.addClass('show-hotel-search-input');
        if (hotels.length) {
            var suggest = new Bloodhound({
                datumTokenizer: function (hl) {
                    var tokens = [];

                    for (var i = 0; i < searchFields.length; i ++) {
                        tokens[tokens.length] = Bloodhound.tokenizers.whitespace(hl[searchFields[i]]);
                    }

                    return [].concat.apply([], tokens);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: hotels,
            });

            suggest.initialize();

            hotelSuggestInput.typeahead({
                hint: true,
                highlight: true,
                minLength: 1,
            }, {
                name: 'name',
                limit: Infinity,
                displayKey: 'name',
                async: false,
                source: suggest.ttAdapter(),
                templates: {
                    suggestion: function (data) {
                        return '<a style="font-size: 13px;color: #565759; display: block;cursor: pointer;width: 100%;">' + data.name + '<span class="hotel-details-zone">' +
                            (includesApp ? '<i class="user icon"></i>' : '<i class="fa fa-users"></i>') + (data.users || 0) + '&nbsp;' +
                            (includesApp ? '<i class="star icon"></i>' : '<i class="fa fa-star"></i>') + (data.score || 0) + '</span></a>';
                    },
                },
            }).on('typeahead:selected', function (obj, datum) {
                changeHotel(datum.id, window.location);
            });

            if (hotelSuggestInput.val()) {
                $('input[name="hotel-suggest"]').focus();
            }
        } else {
            suggestRemote();
        }
    };

    var suggestRemote = function () {
        var query = staffId ? '&' : '?';
        if (!endPoint) {
            return false;
        }

        var suggest = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                rateLimitWait: 100,
                wildcard: '%QUERY',
                url: endPoint + query + 'suggest=%QUERY',
                transform: function (childs) {
                    return childs.child_data;
                },
            },
        });

        suggest.initialize();

        hotelSuggestInput.typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
        }, {
            name: 'name',
            limit: Infinity,
            displayKey: 'name',
            async: false,
            source: suggest.ttAdapter(),
        }).on('typeahead:selected', function (obj, datum) {
            changeHotel(datum.id, window.location);
        });
    };

    var groupHotelsByPlace = function (list, keyGetter) {
        var map = new Map();

        list.forEach(function (item) {
            var key = keyGetter(item);
            var collection = map.get(key);

            if (!collection) {
                map.set(key, [item]);
            } else {
                collection.push(item);
            }
        });

        return map;
    };

    var changeCoordinates = function (lat, lng) {
        var hotelId = sessionStorage.getItem('actualHotel');
        if (hotelId && hotels.length) {
            for (var i = 0; i < hotels.length; i ++) {
                if (hotels[i].id == hotelId) {
                    hotels[i].lng = lng;
                    hotels[i].lat = lat;
                    sessionStorage.setItem('suggest' + chainId + 'staff' + staffId, JSON.stringify(hotels));
                    map = null;

                    break;
                }
            }
        }
    };

    var checkLatitude = function (map) {
        if (minZoomLevel) {
            if (map.getZoom() < minZoomLevel) {
                map.setZoom(parseInt(minZoomLevel));
            }
        }

        var bounds = map.getBounds();
        var sLat = map.getBounds().getSouthWest().lat();
        var nLat = map.getBounds().getNorthEast().lat();
        if (sLat < - 85 || nLat > 85) {
            //the map has gone beyone the world's max or min latitude - gray areas are visible
            //return to a valid position
            if (lastValidCenter) {
                map.setCenter(lastValidCenter);
            }
        } else {
            lastValidCenter = map.getCenter();
        }
    };

    return {
        init: init,
        changeCoordinates: changeCoordinates,
    };
}();

