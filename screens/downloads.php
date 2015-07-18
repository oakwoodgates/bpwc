<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (is_user_logged_in()){
add_action( 'bp_setup_nav', 'woo_downloads_submenu' , 310 );
function woo_downloads_submenu() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' => __('My Downloads', 'woocommerce'),
			'slug' => 'downloads',
			'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['orders']['slug'] . '/',
			'parent_slug' => $bp->bp_nav['orders']['slug'],
			'position' => 20,
			'show_for_displayed_user' => false,
			'screen_function' => 'woo_downloads_screen',
		));
}
	
// Load My Downloads template
function woo_downloads_screen() {
	add_action( 'bp_template_content', 'woo_downloads_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display My Downloads screen
function woo_downloads_screen_content() {
	bpwc_template_finder( 'downloads' );
}
}