<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Add submenu My Orders to our BuddyPress Orders tab
add_action( 'bp_setup_nav', 'woo_membership_submenus' , 320 );
function woo_membership_submenus() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' => __('Memberships', 'woocommerce'),
			'slug' => 'my-memberships',
			'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['memberships']['slug'] . '/',
			'parent_slug' => $bp->bp_nav['memberships']['slug'],
			'position' => 10,
			'show_for_displayed_user' => false,
			'screen_function' => 'woo_memberships_screen',
			'show_in_admin_bar' => true,
		)
	);
}

// Load My Orders template
function woo_memberships_screen() {
	add_action( 'bp_template_content', 'woo_memberships_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display My Orders screen
function woo_memberships_screen_content() {
	bpwc_template_finder( 'memberships' );
}


add_action( 'bp_setup_nav', 'bpwc_membership_submenus' , 320 );
function bpwc_membership_submenus() {
	global $bp;
	$customer_memberships = wc_memberships_get_user_memberships();
	$user_id = get_current_user_id();
	if ($customer_memberships) {
		foreach ( $customer_memberships as $customer_membership ) {

			if ( ! $customer_membership->get_plan() ) {
				continue;
			}
			$var = array();
			$var = $customer_membership->get_plan();
		//	print_r($var);
		//	print_r($var->slug);
			$slug = $var->slug;
			$name = $var->name;
		//	add_action('oakwood_cool', 'oakwood_cool_go');
			bp_core_new_subnav_item(
				array(
					'name' => $name,
					'slug' => $slug,
					'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['memberships']['slug'] . '/',
					'parent_slug' => $bp->bp_nav['memberships']['slug'],
					'position' => 10,
					'show_for_displayed_user' => false,
					'screen_function' => 'bpwc_memberships_screen',
					'show_in_admin_bar' => true,
				)
			);
		}
	}
}

function oakwood_cool_go(){

}
/*
add_action( 'bp_setup_nav', 'woo_membership_submenus' , 320 );
function woo_membership_submenus() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' => __('Memberships', 'woocommerce'),
			'slug' => 'my-memberships',
			'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['memberships']['slug'] . '/',
			'parent_slug' => $bp->bp_nav['memberships']['slug'],
			'position' => 10,
			'show_for_displayed_user' => false,
			'screen_function' => 'woo_memberships_screen',
			'show_in_admin_bar' => true,
		)
	);
}
*/
// Load My Orders template
function bpwc_memberships_screen() {
	add_action( 'bp_template_content', 'bpwc_memberships_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display My Orders screen
function bpwc_memberships_screen_content() {
	bpwc_template_finder( 'mtest' );
}