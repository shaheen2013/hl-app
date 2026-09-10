$(function() {
    //Start sequence
    $(".hotel-stay-share-logo, h3, .animated-p").addClass("animated zoomIn");
    $(".btn-facebook, .mail-link").addClass("animated fadeInUp");

    //CLick on first button group
    $(".btn-facebook").click(function() {
        $(".img-circle, h3, .animated-p,.btn-facebook, .mail-link").addClass("animated zoomOut");
        $(".ripple, .connection-text").addClass("animated dblock bounceIn");
    });

    //Send login form
    $(".send-login-button").click(function() {
        $(".likeButton-content").removeClass("fadeInUp dblock").addClass("fadeOutDown");
        $(".ripple, .connection-text").removeClass("bounceOut").addClass("bounceIn");
    });

    $('.ripple, .connection-text').hide();
    $('.stay-share-content').show();
});
