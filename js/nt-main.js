(function ($) {
    $(function () {
        // Menu tabs trigger
        $('.nt-tabs-trigger').click(function (e) {
            e.preventDefault();
            $('.nt-tabs-trigger').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            var board_id = 'tab-' + $(this).attr('id');
            $('.nt-tab-contents').hide();
            $('#' + board_id).show();
            var board_class = $(this).attr('id');
            if (board_class == "nt-custom-settings") {
                $('.nt-setting-form').hide();
            } else {
                $('.nt-setting-form').show();
            }
        });
    });
}(jQuery));