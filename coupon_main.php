<?php
/*
Plugin Name: WooCommerce Custom Coupon
Description: A plugin to add custom coupon functionality to WooCommerce. This first release brings a % discount to the most expensive item in the users cart.
Version: 1.0
Author: Cello Rondon (LazTaxon)
Author URI: http://www.cello.design
*/

//Prevent direct access to this file
defined('ABSPATH') or die('Bug off!');

function enqueue_coupon_assets() {
    // Enqueue styles and scripts for the front-end
    wp_enqueue_style('coupon_styles', plugin_dir_url(__FILE__) . 'coupon_style.css');
    wp_enqueue_script('coupon_script', plugin_dir_url(__FILE__) . 'coupon.js', array('jquery'), '1.0', true);

    // Enqueue styles and scripts for the admin area
    add_action('admin_enqueue_scripts', function() {
        wp_enqueue_style('admin_coupon_styles', plugin_dir_url(__FILE__) . 'coupon_style.css');
        wp_enqueue_script('admin_coupon_script', plugin_dir_url(__FILE__) . 'coupon.js', array('jquery'), '1.0', true);
    });
}
add_action('wp_enqueue_scripts', 'enqueue_coupon_assets');

// Add a custom field for coupon percentage in the coupon edit form
add_action('woocommerce_coupon_options', 'custom_coupon_percentage_field', 10, 2);
function custom_coupon_percentage_field($coupon_id, $coupon) {
    // Create a custom input field for the coupon percentage
    woocommerce_wp_text_input(array(
        'id' => 'custom_coupon_percentage',
        'label' => __('Coupon Percentage', 'your-text-domain'),
        'desc_tip' => 'true',
        'description' => __('Enter the custom coupon percentage discount.', 'your-text-domain'),
        'type' => 'number', 
        'class' => 'short',
        'custom_attributes' => array(
            'step' => 'any',
            'min' => '0',
            'max' => '100',
        ),
    ));
}

// Save the custom field value when the coupon is saved
add_action('woocommerce_coupon_options_save', 'save_custom_coupon_percentage_field', 10, 2);
function save_custom_coupon_percentage_field($post_id, $coupon) {
    // Save the custom coupon percentage value to the post meta
    $custom_coupon_percentage = isset($_POST['custom_coupon_percentage']) ? sanitize_text_field($_POST['custom_coupon_percentage']) : '';
    update_post_meta($post_id, 'custom_coupon_percentage', $custom_coupon_percentage);
}

// Add a custom coupon type to WooCommerce
add_action('woocommerce_before_cart', 'custom_coupon_logic');
function custom_coupon_logic() {
    global $woocommerce;

    // Check if WooCommerce and the cart object exist
    if(!is_object($woocommerce) || !is_object($woocommerce->cart)){
        return;
    }

    // Check if any product is in the cart 
    $items = $woocommerce->cart->get_cart();

    // If the cart is empty, exit the function
    if(empty($items)) {
        return;
    }

    // Initialize variables to store the most expensive item and its price
    $max_price = 0;
    $max_item = null;

    // Loop through all items in the cart
    foreach($items as $item) {
        // If the current item's price is higher than the current max price
        if($item['data']->get_price() > $max_price) {
            // Update the max price and the max item
            $max_price = $item['data']->get_price();
            $max_item = $item;
        }
    }

    // Calculate the discount based on the price of the most expensive item and the custom coupon percentage
    // Assuming 'custom_coupon_percentage' is a percentage value (0-100)
    $discount = $max_price * (get_post_meta($max_item['product_id'], 'custom_coupon_percentage', true) / 100);

    // Apply the discount to the cart as a negative fee
    $woocommerce->cart->add_fee(__('Discount', 'your-text-domain'), -$discount);

    // Add an action to display a custom message when a coupon is applied
    add_action('woocommerce_applied_coupon', 'custom_coupon_applied_message');

    // Function to display a custom message when a coupon is applied
// Note: Removed the echo for the inline script, as this will be handled by the external JS file
add_action('woocommerce_applied_coupon', 'custom_coupon_applied_message');
function custom_coupon_applied_message($coupon_code) {
    // Create a new coupon object
    $coupon = new WC_Coupon($coupon_code);

    // Get the custom coupon percentage from the coupon's meta data
    $custom_coupon_percentage = get_post_meta($coupon->get_id(), 'custom_coupon_percentage', true);

    // If the custom coupon percentage is not empty
    if (!empty($custom_coupon_percentage)) {
        // Format the message
        $message = sprintf(__('This coupon will add a %s%% discount to your most expensive item!', 'your-text-domain'), $custom_coupon_percentage);

        // Display the message in a div with id "coupon-popup"
        echo '<div id="coupon-popup">' . $message . '</div>';
    }
}
}