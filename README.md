# WooCommerce Custom Coupon Plugin

This plugin adds a custom coupon functionality to WooCommerce. The plugin provides a percentage discount to the most expensive item in the user's cart.

## Features

- Adds a custom field for coupon percentage in the WooCommerce coupon edit form.
- Saves the custom field value when the coupon is saved.
- Applies a discount to the most expensive product in the cart when the cart page is loaded. The discount is calculated based on the custom coupon percentage.
- Displays a custom message in a popup when a coupon is applied. The message informs the user about the percentage discount applied to the most expensive item in their cart.
- The popup message can be closed by the user or will automatically fade out after 2 seconds.

## JavaScript Functionality

The plugin uses JavaScript (jQuery) to handle the popup message:

- The popup message is displayed when a coupon is applied.
- The popup can be closed by the user by clicking on the close button.
- The popup will automatically fade out after 2 seconds.

## CSS Styling

The plugin includes a CSS file (`coupon_style.css`) that styles the popup message. The CSS file is enqueued in the main PHP file (`coupon_main.php`).


### Usage

1. Install and activate the plugin.
2. When creating a coupon in WooCommerce, you will see a new field for 'Coupon Percentage'. Enter the desired discount percentage here.
3. When a customer applies the coupon, the discount will be applied to the most expensive item in their cart and a custom message will be displayed.

### Author

Cello Rondon (LazTaxon)
[http://www.cello.design](http://www.cello.design)
