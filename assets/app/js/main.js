(function ($) {

    $(document).ready(function () {
        /**
         * Time Zone Change
         * @author Jahid
         */
        $(document).on('click', '.tfhb-timezone-tabs ul li', function (e) {
            $('.tfhb-timezone-tabs ul li').removeClass('active');
            var $this = $(this);
            $this.addClass('active');
        });


        /**
         * Time Select
         * @author Jahid
         */
        $(document).on('click', '.tfhb-available-times li .time', function (e) {
            $('.tfhb-available-times li .next').remove();
            var $this = $(this);
            $this.parent().append('<span class="next tfhb-flexbox tfhb-gap-8"> Next<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 10L14 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 4.16666L14.8333 9.99999L9 15.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>');
        });
    });

})(jQuery);