(function($) {
    $.fn.backToTop = function(options) {

        // Default options
        var settings = $.extend({
            iconName: 'fas fa-chevron-up',
            trigger: 300,
            fxName: 'fade',
            fxTransitionDuration: 300,
            scrollDuration: 300
        }, options);

        var btn = this,
            trigger = settings.trigger,
            fxName = settings.fxName,
            duration = settings.fxTransitionDuration,
            scrollDuration = settings.scrollDuration;

        this.each(function() {

            btn.addClass(fxName);
            btn.css({
                transitionDuration: duration + 'ms'
            });

            $(window).scroll(function() {

                if ($(window).scrollTop() > trigger) {
                    btn.addClass('cssScrollTop-on');

                } else {
                    btn.removeClass('cssScrollTop-on');
                }

            });

            btn.on('click', function(e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, scrollDuration);
            });

        });

        return this;
    };
})(jQuery);
