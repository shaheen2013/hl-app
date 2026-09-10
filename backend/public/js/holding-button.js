var holdInterval;
$(".btn-hold").on("touchstart mousedown", function(e) {
    e.preventDefault();
    height = 60;
    console.log('mouse down');
    window.holdInterval = setInterval(function() {
        newHeight = height += 1;
        console.log(newHeight);
        $(".success-layer").css(
            'height', newHeight + "%"
        );
        if ($(".success-layer").offset().top <= 0) {
            console.log('Send form');
            clearInterval(window.holdInterval);
            document.getElementById("redeem_offer_form").submit();
            $(".h3-anim, .p-anim, .btn-anim, .small-anim").addClass("zoomOut");
            setTimeout(function() {
                $(".h3-anim-2, .p-anim-2").removeClass("zoomOut hidden").addClass("zoomIn");
            }, 500);
        }
    }, 60);
});

$(".btn-hold").on("mouseup touchend", function(e) {
    e.preventDefault();
    console.log('mouse up');
    $(".success-layer").css({
        height: 0 + "%"
    });
    clearInterval(window.holdInterval);
    return;
});