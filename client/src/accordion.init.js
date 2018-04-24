;(function ($) {
    $(document).on("ready", function () {
        var accordions = $(".accordion");
        if (accordions.length > 0) {
            accordions.accordion()
        }
    });
})(jQuery);
