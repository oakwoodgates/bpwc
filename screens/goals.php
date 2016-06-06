<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Add submenu My Orders to our BuddyPress Orders tab
add_action( 'bp_setup_nav', 'woo_goal_submenus' , 304 );
function woo_goal_submenus() {
	global $bp;
	bp_core_new_subnav_item(
		array(
			'name' => __('Goals', 'woocommerce'),
			'slug' => 'my-goals',
			'parent_url' => $bp->loggedin_user->domain  . $bp->bp_nav['goals']['slug'] . '/',
			'parent_slug' => $bp->bp_nav['goals']['slug'],
			'position' => 10,
			'show_for_displayed_user' => false,
			'screen_function' => 'woo_goals_screen',
			'show_in_admin_bar' => true,
		)
	);
}

// Load My Orders template
function woo_goals_screen() {
	add_action( 'bp_template_content', 'woo_goals_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Display My Orders screen
function woo_goals_screen_content() {
	bpwc_template_finder( 'goals' );
}