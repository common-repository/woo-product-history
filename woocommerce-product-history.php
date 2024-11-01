<?php
/**
 * Plugin Name: WooCommerce Product History
 * Plugin URI: https://www.jumptoweb.com
 * Description: This plugin adds a section under My Account page named Product History with a list of all the products the user has bought it.
 * Author: Manny Costales
 * Version: 1
 * Author URI: https://www.mannycostales.com
 */
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
//if ( ! defined( 'ABSPATH' ) ) exit;
// Load plugin class files

/**
 * Returns the main instance of WordPress_Plugin_Template to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object WordPress_Plugin_Template
 */


function Product_History() {
	require_once( 'includes/class-product-history.php' );

	$path = plugin_dir_path(__FILE__);
	$url = plugin_dir_url(__FILE__);
	$instance = new ProductHistory($path, $url);

}
add_action('plugins_loaded', 'Product_History');
