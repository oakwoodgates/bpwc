<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
add_action( 'bp_setup_nav', 'woo_edit_account_submenu' , 302 );
function woo_edit_account_submenu() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' => __('Account', 'woocommerce'),
			'slug' => 'account',
			'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['settings']['slug'] . '/',
			'parent_slug' => $bp->bp_nav['settings']['slug'],
			'position' => 10,
			'show_for_displayed_user' => false,
			'screen_function' => 'woo_edit_account_screen',
	) );
}

// Load Edit Account template
function woo_edit_account_screen() {
	add_action( 'bp_template_content', 'woo_edit_account_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display edit account screen
function woo_edit_account_screen_content() {
	bpwc_template_finder( 'account' );
}
*/
add_action( 'woocommerce_save_account_details', 'save_account_bp_redirect' );
function save_account_bp_redirect() {
	global $bp;
//	wp_safe_redirect( $bp->loggedin_user->domain . $bp->settings->slug . '/account/' );
	wp_safe_redirect( $bp->loggedin_user->domain . $bp->settings->slug. '/my-address/' );
	exit;
}
