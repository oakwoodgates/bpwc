<?php
/**
 * BPWC options and instructions page
 *
 * Plugin options and instructions will be in wp-admin->Settings->BPWC
 *
 * Thanks to @tommcfarlin - https://github.com/tommcfarlin/WordPress-Settings-Sandbox
 * and to Otto - http://ottopress.com/2009/wordpress-settings-api-tutorial/
 * for their contributions to the community.
 *
 * @package BPWC
 * @since 0.2
 *
 */
/**
 * Put our plugin options into the 'Settings' menu
 * wp-admin->Settings->BPWC
 */
function bpwc_settings_menu() {
	add_options_page(
		'BPWC Options', 		// The title to be displayed in the browser window for this page.
		'BPWC',					// The text to be displayed for this menu item
		'manage_options',		// Which type of users can see this menu item
		'bpwc-admin',			// The unique ID - that is, the slug - for this menu item
		'bpwc_settings_page'	// The name of the function to call when rendering our settings page
	);
}
add_action( 'admin_menu', 'bpwc_settings_menu' );

/**
 * Renders our plugin settings page
 */
function bpwc_settings_page() {
?>
	<div class="wrap">
		<h2><?php _e( 'BPWC Options', 'bpwc' ); ?></h2>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'bpwc_settings' );
			do_settings_sections( 'bpwc-admin' );
			submit_button();			
			?>
		</form>	
	</div>
<?php
}

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 
/**
 * Set default values for our checkbox options
 */
function bpwc_settings_default() {

	$defaults = array(
		'bpwc_downloads'		=>	'',
	);

	return apply_filters( 'bpwc_settings_default', $defaults );

}

/**
 * Initializes our options page by registering the Sections, Fields, and Settings.
 */ 
function bpwc_initialize_options() {

	// If the theme options don't exist, create them and set defaults for our checkboxes
	if( false == get_option( 'bpwc_settings' ) ) {	
		add_option( 'bpwc_settings', apply_filters( 'bpwc_settings_default', bpwc_settings_default() ) );
	}

	// Register our section. All of our plugin options will fall under this section.
	add_settings_section(
		'bpwc_settings_section',			// ID used to identify this section and with which to register options
		__( 'Display Options', 'sandbox' ),	// Title to be displayed on the admin page
		'bpwc_settings_section_callback',	// Callback used to render the description of the section
		'bpwc-admin'						// Page on which to add this section of options
	);
	
	// Hello fields.
	add_settings_field(	
		'bpwc_downloads',						// ID used to identify the field
		__( 'My Downloads', 'woocommerce' ),	// The label to the left of the option interface element
		'bpwc_downloads_callback',				// The name of the function responsible for rendering the option interface
		'bpwc-admin',							// The page on which this option will be displayed
		'bpwc_settings_section',				// The name of the section to which this field belongs
		array(									// The array of arguments to pass to the callback. In this case, just a description.
			__( 'Add "My Downloads" submenu to our "Orders" menu in BuddyPress', 'sandbox' ),
		)
	);

	add_settings_field(
		'bpwc_addresses',
		__( 'My Addresses', 'woocommerce' ),
		'bpwc_addresses_callback',
		'bpwc-admin',
		'bpwc_settings_section',
		array(
			__( 'Add "Addresses" submenu to "Settings" menu in BuddyPress. Allows users to edit WooCommerce billing and shipping addresses from BuddyPress.', 'sandbox' ),
		)
	);

	add_settings_field(
		'bpwc_subscriptions',
		__( 'My Subscriptions', 'woocommerce' ),
		'bpwc_subscriptions_callback',
		'bpwc-admin',
		'bpwc_settings_section',
		array(
			__( 'Add "My Subscriptions" submenu to our "Orders" menu in BuddyPress. Requires WooCommerce Subscriptions plugin from WooThemes', 'sandbox' ),
		)
	);

	add_settings_field(
		'bpwc_sensei_courses',
		__( 'My Courses', 'woocommerce' ),
		'bpwc_sensei_courses_callback',
		'bpwc-admin',
		'bpwc_settings_section',
		array(
			__( 'Add Sensei&#39;s "My Courses" submenu to our "Orders" menu in BuddyPress. Requires Sensei plugin from WooThemes', 'sandbox' ),
		)
	);

	// Register the fields with WordPress
	register_setting(
		'bpwc_settings',
		'bpwc_settings'
	);
	
}
add_action( 'admin_init', 'bpwc_initialize_options' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 
/**
 * This function provides a simple description for the settings page. 
 */
function bpwc_settings_section_callback() {
	echo '<p>' . __( 'Select which areas of WooCommerce to display in BuddyPress.', 'sandbox' ) . '</p>';
	echo '<p>' . __( 'By default, BPWC creates an "Orders" navigation tab in the BuddyPress menu, and adds a "My Orders" submenu showing the user&#39;s recent orders.', 'sandbox' ) . '</p>';
}

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 
/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function bpwc_downloads_callback($args) {
	
	// Read the options collection
	$options = get_option('bpwc_settings');
	
	// Update the name attribute to access this element's ID in the context of the display options array
	// Access the bpwc_downloads element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="bpwc_downloads" name="bpwc_settings[bpwc_downloads]" value="1" ' . checked( 1, isset( $options['bpwc_downloads'] ) ? $options['bpwc_downloads'] : 0, false ) . '/>'; 
	
	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="bpwc_downloads">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
}

function bpwc_addresses_callback($args) {
	
	$options = get_option('bpwc_settings');
	$html = '<input type="checkbox" id="bpwc_addresses" name="bpwc_settings[bpwc_addresses]" value="1" ' . checked( 1, isset( $options['bpwc_addresses'] ) ? $options['bpwc_addresses'] : 0, false ) . '/>'; 
	$html .= '<label for="bpwc_addresses">&nbsp;'  . $args[0] . '</label>'; 
	echo $html;
	
}

function bpwc_subscriptions_callback($args) {
	
	$options = get_option('bpwc_settings');
	$html = '<input type="checkbox" id="bpwc_subscriptions" name="bpwc_settings[bpwc_subscriptions]" value="1" ' . checked( 1, isset( $options['bpwc_subscriptions'] ) ? $options['bpwc_subscriptions'] : 0, false ) . '/>'; 
	
	$html .= '<label for="bpwc_subscriptions">&nbsp;'  . $args[0] . '</label>'; 	
	echo $html;
	
}

function bpwc_sensei_courses_callback($args) {
	
	$options = get_option('bpwc_settings');
	$html = '<input type="checkbox" id="bpwc_sensei_courses" name="bpwc_settings[bpwc_sensei_courses]" value="1" ' . checked( 1, isset( $options['bpwc_sensei_courses'] ) ? $options['bpwc_sensei_courses'] : 0, false ) . '/>'; 
	$html .= '<label for="bpwc_sensei_courses">&nbsp;'  . $args[0] . '</label>'; 
	echo $html;
	
}
?>