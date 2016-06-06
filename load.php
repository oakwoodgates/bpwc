<?php
/*
  Plugin Name: BPWC
  Description:
  Version: 0.1
  Author: WPguru4u
  Author URI: http://www.wpguru4u.com
 */
/*
Supports:
WooCommerce default - My Account
WooCommerce default - My Orders
WooCommerce default - My Downloads
WooCommerce default - My Addresses
WooCommerce plugin  - Subscriptions - http://www.woothemes.com/products/woocommerce-subscriptions/

*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BPWC' ) ) {
	class BPWC {
		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );

			// Include any required files

			// Add our Orders menu to nav
			add_action( 'init', array( $this, 'blaze_of_glory' ) );
			add_action( 'bp_setup_nav', array( $this, 'bpwc_orders_menu' ) );
			add_action( 'bp_setup_nav', array( $this, 'bpwc_goals_menu' ) );
			add_action( 'bp_setup_nav', array( $this, 'bpwc_memberships_menu' ) );
//			add_action( 'bp_setup_admin_bar', array( $this, 'bpwc_add_new_admin_bar' ), 300 );
			add_action( 'bp_setup_admin_bar', array( $this, 'bpwc_admin_bar_add'), 300 );
		//	require_once( $this->directory_path . '/inc/options.php' );
		//	require_once( $this->directory_path . '/inc/functions.php' );
		//	require_once( $this->directory_path . '/inc/template-tags.php' );
			// include CMB2 if it doesn't already exist
		//	if ( ! defined( 'CMB2_LOADED' ) ) {
		//		require_once( $this->directory_path . '/inc/cmb2/init.php' );
		//	}
			// Load Textdomain
		//	load_plugin_textdomain( 'wds-simple-page-builder', false, dirname( $this->basename ) . '/languages' );
			// Make sure we have our requirements, and disable the plugin if we do not have them.
		//	add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );

		}

		public function blaze_of_glory(){

			if ( is_user_logged_in() ) {
				// Include all the screens
				foreach ( glob( $this->directory_path . 'screens/*.php' ) as $file ) {
				    include_once $file;
				}
			}
			require_once( trailingslashit($this->directory_path) . trailingslashit('inc') . 'options.php');
			require_once( trailingslashit($this->directory_path) . trailingslashit('inc') . 'options-otto.php');
		}

		public function bpwc_orders_menu() {
		//	global $bp;
			bp_core_new_nav_item(
				array(
					'name' => __('Orders', 'woocommerce'),
					'slug' => 'orders',
					'position' => 30,
					'show_for_displayed_user' => false,
					'screen_function' => 'woo_orders_screen',
					'default_subnav_slug' => 'my-orders',
			) );
		}

		public function bpwc_goals_menu() {
		//	global $bp;
			bp_core_new_nav_item(
				array(
					'name' => __('Goals', 'woocommerce'),
					'slug' => 'goals',
					'position' => 30,
					'show_for_displayed_user' => false,
					'screen_function' => 'woo_goals_screen',
					'default_subnav_slug' => 'my-goals',
			) );
		}
		public function bpwc_memberships_menu() {
		//	global $bp;
			bp_core_new_nav_item(
				array(
					'name' => __('Memberships', 'woocommerce'),
					'slug' => 'memberships',
					'position' => 30,
					'show_for_displayed_user' => false,
					'screen_function' => 'woo_memberships_screen',
					'default_subnav_slug' => 'my-memberships',
			) );
		}
		public function bpwc_admin_bar_add() {
			global $wp_admin_bar, $bp;

			if ( !bp_use_wp_admin_bar() || defined( 'DOING_AJAX' ) )
			return;

			$user_domain = bp_loggedin_user_domain();
			$item_link = trailingslashit( $user_domain . 'orders' );

			$wp_admin_bar->add_menu( array(
				'parent'  => $bp->my_account_menu_id,
				'id'      => 'orders',
				'title'   => __( 'Orders', 'your-plugin-domain' ),
				'href'    => trailingslashit( $item_link ),
				'meta'    => array( 'class' => 'menupop' )
			) );

			// add submenu item
			$wp_admin_bar->add_menu( array(
				'parent' => 'orders',
				'id'     => 'downloads',
				'title'  => __( 'My Downloads', 'woocommerce' ),
				'href'   => trailingslashit( $item_link ) . 'downloads',
			) );
		}
	}

	// Fire it up
	new BPWC;
}

function bpwc_template_finder( $filename ) {
	if ( locate_template( 'bpwc/' . $filename . '.php' ) != '' ) {
		// yep, load the page template
		locate_template( 'bpwc/' . $filename . '.php', true );
	} else {
		// get the template from our plugin
		$the_file = plugin_dir_path( __FILE__ ) . 'templates/' . $filename . '.php';
		require_once $the_file;
	}

}

// Add button on order detail screen to return to order list
add_action('woocommerce_view_order', 'return_to_bp_order_list');
function return_to_bp_order_list() { ?>
	<?php global $bp; ?>
	<a href="<?php echo $bp->loggedin_user->domain . 'orders/'; ?>" title="<?php _e('View All Orders','woocommerce'); ?>" class="button"><?php _e('View All Orders','woocommerce'); ?></a>
<?php }
