$(document).ready(function() {
    
    // Menu dropdowns
    $(".ui.dropdown").dropdown();
    
    //Toggle
    $(".ui.checkbox").checkbox();
    
    // Sidebar
    $(".btn_sidebar_toggle").click(function() {
        $(".ui.sidebar").sidebar("toggle");
    });

    // When clicking on a button, it shows the loader
    $(".has-loader").on("click", function() {
        $("#loader").show();
    });

    // On submit form, it shows the loader
    $("form").on("submit", function() {
        $("#loader").show();
    });

    // When click hide flash message
    $(".message .close").on("click", function() {
        $(this).closest(".message").transition("fade");
    });
    // Reset dashboard form when reset button is clicked
    $(".dashboard_menu_reset_form_button").click(function(e) {
        e.preventDefault();
        $(".range_start_field").val("");
        $(".range_end_field").val("");
    });
    
    // Has tooltip
    $(".has-tooltip")
        .popup();

    // Tabs
    $('.menu .item')
        .tab();
    adjustWidth();
    adjustHeight();
});

//Sidebar width and height
function adjustWidth() {
    var parentwidth = $("#side_bar").width();
    $("#side_bar_menu").width(parentwidth);
}

function adjustHeight() {
    var parentHeight = $(document).height();
    $("#side_bar").height(parentHeight);
}

$(window).resize(
    function () {
        adjustWidth();
    })