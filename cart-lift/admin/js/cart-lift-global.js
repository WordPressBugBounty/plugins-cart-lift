(function( $ ) {
    'use strict';

    /**
     * all of the code for your admin-facing javascript source
     * should reside in this file.
     *
     * note: it has been assumed you will write jquery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * this enables you to define handlers, for when the dom is ready:
     *
     * $(function() {
     *
     * });
     *
     * when the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * ideally, it is not considered best practise to attach more than a
     * single dom-ready or window-load handler for a particular page.
     * although scripts in the wordpress core, plugins and themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    function hide_notice(event) {
        event.preventDefault();
        var $payload = {};

        wpAjaxHelperRequest( 'hide-paddle-notice', $payload )
            .success( function( response ) {
                $(".cl-paddle-notice").remove();
            })
            .error( function( response ) {

            });

    }
    $(document).on('click', '.cl-paddle-notice .notice-dismiss', hide_notice);


     // ------window on scroll add class to comparison table header------
     $(window).on('scroll', function() {
        var $header = $('.cart-lift-compare .list-header');

        if ($header.length > 0) {
            var headerOffset = $header.offset().top - $(window).scrollTop();
    
            if (headerOffset < 27) {
                $header.addClass('sticked');
            } else {
                $header.removeClass('sticked');
            }
        }
    });


    $(document).on("click", ".twilio-disabled,.cl-btn.unsubscribe.cl-free,.cl-btn.disabled,.cl-switcher .disabled,.email-info .pro-tag,.cart-info .pro-tag,.cl-switcher .pro-tag ~ label", function (event) {
        event.preventDefault();
        $("#cartlift_premium_feature_popup").show();
    });


    $(document).on("click", "#cartlift_premium_feature_close", function () {
        $("#cartlift_premium_feature_popup").hide();
    });

})( jQuery );