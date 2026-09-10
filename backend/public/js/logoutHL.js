getCookieByName = function (name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) {
        return match[2]
    }
};

var cookie = null;
window.onload = function () {
    cookie = getCookieByName('PHPSESSID');
};

$("form").submit(function (e) {
    var actualCookie = getCookieByName('PHPSESSID');
    if (cookie !== actualCookie) {
        e.preventDefault();
        var getUrl = window.location;
        window.location.href = getUrl.protocol + "//" + getUrl.host +"/hotel-edit-profile-details/?ok=4083";
    }
});