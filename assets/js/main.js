jQuery(function ($) {

    $(document).ready(function () {
        $('#rcp_password_again_wrap label').html('Confirm Password');

    });

    $(window).on("load", function () {
        $(".recipe-wrapper").mCustomScrollbar({
            theme: "dark"
        });
    });

    $(window).on("resize", function () {
        $(".recipe-wrapper").mCustomScrollbar({
            theme: "dark"
        });
    });
});