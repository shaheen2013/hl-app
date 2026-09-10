function fetchNewUserData(hotel_id, seconds, usersNum) {
    var fromTime = moment().utc().subtract(seconds, 'seconds').format();
    $.ajax({
        url: '/lib/webservices/usersFeed.php',
        type: 'POST',
        dataType: 'json',
        data: "hotel_id=" + hotel_id + "&from=" + fromTime + "&getUsersFeed=true&usersNum=" + usersNum,
        success: function (data) {
            if (data) {
                // flip items
                data.reverse();
                // Perform operation on return value
                data.forEach(function (row) {
                    var date = moment.parseZone(row.last_login).local().format();
                    if(!row.sexo){
                        row.sexo = "male";
                    }

                    if (row.facebook_img) {
                        row.img = row.facebook_img;
                        row.source = 'facebook';
                        row.icon = 'facebook square';
                    } else {
                        row.source = 'form';
                        row.icon = 'wpforms';
                        row.img = '/public/images/' + row.sexo + '_avatar_placeholder.svg';
                    }

                    $(".feed").prepend('<div class="ui segment event bg" style="padding:1rem 2rem; background: white; margin-bottom:.4rem; display: none"><div class="label"><img src="' + row.img + '"></div><div class="content"><div class="summary"><a class="user">' + row.nombre + '</a> Connected with your hotel<div class="date">' + moment(date).fromNow() + '</div></div><div class="meta"><div class="ui horizontal bulleted link list"><a class="item">' + row.location + '</a><a class="item"><i class="' + row.icon + ' icon"></i> From ' + row.source + '</a> <a class="item"><i class="birthday cake icon"></i> ' + row.fecha_nacimiento + '</a></div></div></div></div>');
                    $('.event:first-child').transition('fly down');
                });
            }
        },
        complete: function (data) {
            setTimeout(fetchNewUserData, 60000, hotel_id, 60, null);
        }
    });
}