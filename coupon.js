// coupon.js
jQuery(document).ready(function($) {
    // Assuming 'woocommerce_applied_coupon' is triggered when a coupon is applied, update this if it's a different event
    $(document).on('woocommerce_applied_coupon', function() {
        setTimeout(function() {
            $('#coupon-popup').addClass('fade-out');
        }, 2000);
    });
});