<?php
/*
Plugin Name: Woo Completed Orders
Description: This is a simple plugin to pass WooCommerce completed orders to a 3rd party API
Author: Wade Shuler
Text Domain: WooCompletedOrders
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	die('Access Denied!');
}

define('WCO_ROOT_DIR', __DIR__);

function init()
{
	register_activation_hook(__FILE__, array($this,'wco_plugin_activate')); //activate hook
	register_deactivation_hook(__FILE__, array($this,'wco_plugin_deactivate')); //deactivate hook

	// hooks
	add_action( 'woocommerce_add_order_item_meta', 'wco_add_order_item_meta' );
	add_action( 'woocommerce_payment_complete', 'wco_payment_complete' );

	// menus
	add_action( 'admin_menu', 'wco_admin_menu' );
}

function wco_admin_menu()
{
	// $page_title, $menu_title, $capability, $menu_slug, $function
	add_options_page( 'Woo Completed Orders Hook API', 'Woo Completed Orders Hook API', 'manage_options', 'wco-settings.php', 'wco_settings_page' );
}

function wco_settings_page()
{
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	if( isset($_POST['wco_settings_form']) && ($_POST['wco_settings_form'] == '1') )
	{
		$updatedApiUrl = $_POST['api_url'];
		update_option('wco_api_url', $updatedApiUrl);
		echo '<div class="updated"><p><strong>Settings have been saved!</strong></p></div>';
	}

	echo '<div class="wrap">';
	require_once(WCO_ROOT_DIR . '/templates/api-settings.php');
	echo '</div>';
}

// Hooks

function wco_payment_complete( $order_id )
{
    $order = wc_get_order( $order_id );
    $billingEmail = $order->billing_email;
    $products = $order->get_items();

    $items = [];
    foreach($products as $prod){
        $items[$prod['product_id']] = $prod['name'];
    }

    $url = get_option('wco_api_url');

    $response = wp_remote_post( $url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array( 'billingemail' => $billingEmail, 'items' => $items, 'order_details' => $order ),
        'cookies' => array()
        )
    );

	if ( is_wp_error( $response ) )
	{
		$error_message = $response->get_error_message();
		file_put_contents(ABSPATH . '/wco-orders-errors.log', '[WooCompletedOrders] Error: ' . $error_message, FILE_APPEND | LOCK_EX);
	}

}

init();
