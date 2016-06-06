<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'bp_setup_nav', 'woo_subscriptions_submenu' , 303 );
function woo_subscriptions_submenu() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' 						=> __('Subscriptions', 'woocommerce'),
			'slug' 						=> 'subscriptions',
			'parent_url' 				=> $bp->loggedin_user->domain  . $bp->bp_nav['orders']['slug'] . '/',
			'parent_slug' 				=> $bp->bp_nav['orders']['slug'],
			'position' 					=> 20,
			'show_for_displayed_user' 	=> false,
			'screen_function' 			=> 'woo_subscriptions_screen',
			'show_in_admin_bar' 		=> true,
		));
}

// Load My Downloads template
function woo_subscriptions_screen() {
	add_action( 'bp_template_content', 'woo_subscriptions_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display My Downloads screen
function woo_subscriptions_screen_content() {
	bpwc_template_finder( 'subscriptions' );
}
