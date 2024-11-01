<?php
/**
 * Plugin Name: WB Product Enquiry
 * Description: Turn your WooCommerce store into a beautiful catalog. Let your customers quickly ask about products they like using enquiry forms on products!
 * Version: 1.25
 * Author: Wojciech Borowicz
 * Author URI: https://borowicz.me
 */
 
 /*
 
	V change input option to checkbox
	V add second checkbox for enable product enquiries
		X select contact form on popup / slider - new setting
		X select who should be affected, logged in users? admin?
	V Do the catalog mode, function for disabling all add to carts, redirects on cart page and checkout page
	V Add a filter with a contact form to single product page
	V Code a contact form with AJAX, which creates posts then saved in the back end
	V Add columns onto enquiries page 
	V Register custom fields for custom post type 
	V Create a simple chart with enquiries per each day 
		- Style email template 
	V Set recipients option
	V Disable admin email option
	V Option to disable saving enquiries, may not be needed by everyone.
		- WPDB function for checking how many per day
 */
 
 



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'wb-product-enquiry', '1.1' ); // plugin version 

// Plugin init hook.
add_action( 'plugins_loaded', 'wb_product_enquiry_init', 5 );

/**
 * Initialize plugin.
 */
function wb_product_enquiry_init() {

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wb_product_enquiry_woo_error' );
		return;
	}

	// Load main plugin class.
	require_once 'includes/wb-product-enquiry-frontend.php';
	require_once 'includes/wb-product-enquiry-admin.php';
	require_once 'includes/wb-product-enquiry-mail.php';
	require_once 'includes/wb-product-enquiry-general.php';
}


function wb_product_enquiry_woo_error() {
	echo '<div class="error"><p>' . sprintf( esc_html__( 'WB Product Enquiry plugin requires %s to be installed and active.', 'text-domain' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
}