jQuery(function ($) {

    $(document).ready(function () {
        $('#rcp_password_again_wrap label').html('Confirm Password');

        $('#cbf_logout').on('click', function(e){
            e.preventDefault();
            setCookie('')
            alert($(this).data('url'));
        });
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