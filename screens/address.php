<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Add address sub menu to settings menu
add_action( 'bp_setup_nav', 'woo_address_submenu' , 303 );
function woo_address_submenu() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' => __('Addresses', 'woocommerce'),
			'slug' => 'my-address',
			'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['settings']['slug'] . '/',
			'parent_slug' => $bp->bp_nav['settings']['slug'],
			'position' => 15,
			'show_for_displayed_user' => false,
			'screen_function' => 'woo_address_screen',
		));
}

// Load address template
function woo_address_screen() {
	add_action( 'bp_template_content', 'woo_address_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Load address screen
function woo_address_screen_content() {
	bpwc_template_finder( 'address' );
}

// Go back to Buddypress profile after edits
add_action( 'woocommerce_customer_save_address', 'save_address_bp_redirect' );
function save_address_bp_redirect() {
	global $bp;
	wp_safe_redirect( $bp->loggedin_user->domain . $bp->settings->slug . '/my-address/' );
	exit;
}
