<?php
/**
 * BPWC options page
 *
 * Plugin options will be in wp-admin->Settings->BPWC
 * Thanks to @tommcfarlin - https://github.com/tommcfarlin/WordPress-Settings-Sandbox
 * Thanks to Otto for his contributions to the community - http://ottopress.com/2009/wordpress-settings-api-tutorial/
 *
 * @package BPWC
 * @since 0.2
 *
 */

/**
 * Add our plugin options into the 'Settings' menu in the wp-admin
 */
function bpwc_settings_menu() {
	add_options_page(
		'BPWC Options', 		// The title to be displayed in the browser window for this page.
		'BPWC',					// The text to be displayed for this menu item
		'manage_options',		// Which type of users can see this menu item
		'bpwc',					// The unique ID - that is, the slug - for this menu item
		'bpwc_options_page'		// The name of the function to call when rendering this menu's page
	);
}

/**
 * Display our plugin options page
 */
function bpwc_options_page() {
?>
	<div class="wrap">
		<h2><?php _e( 'BPWC Options', 'bpwc' ); ?></h2>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'bpwc_options' );
			do_settings_sections( 'bpwc' );
			submit_button();			
			?>
		</form>	
	</div>
<?php
}

/**
 * Set default values for our checkbox options
 */
function bpwc_options_defaults() {
	
	$defaults = array(
		'show_header'		=>	'',
	);
	
	return apply_filters( 'bpwc_options_defaults', $defaults );
	
}

/**
 * Initializes our options page by registering the Sections, Fields, and Settings.
 *
 */ 
add_action( 'admin_init', 'bpwc_options_init' );
function bpwc_options_init() {

	// If the theme options don't exist, create them and set defaults for our checkboxes
	if( false == get_option( 'bpwc_options' ) ) {	
		add_option( 'bpwc_options', apply_filters( 'bpwc_options_defaults', bpwc_options_defaults() ) );
	}

	// Register the fields with WordPress
	register_setting(
		'bpwc_options',
		'bpwc_options'
	);

	// Register our section. All of our options will fall under this section.
	add_settings_section(
		'bpwc_options_section',				// ID used to identify this section and with which to register options
		__( 'Display Options', 'sandbox' ),	// Title to be displayed on the admin page
		'bpwc_options_section_callback',	// Callback used to render the description of the section
		'bpwc'								// Page on which to add this section of options
	);

	// Hello fields.
	add_settings_field(	
		'show_header',				// ID used to identify the field
		__( 'Header', 'sandbox' ),	// The label to the left of the option interface element
		'show_header_callback',		// The name of the function responsible for rendering the option interface
		'bpwc',						// The page on which this option will be displayed
		'bpwc_options_section',		// The name of the section to which this field belongs
		array(						// The array of arguments to pass to the callback. In this case, just a description.
			__( 'Activate this setting to display the header.', 'sandbox' ),
		)
	);	

}

function show_header_callback($args) {
	
	// First, we read the options collection
	$options = get_option('bpwc_options');
	
	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the show_header element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="show_header" name="bpwc_options[show_header]" value="1" ' . checked( 1, isset( $options['show_header'] ) ? $options['show_header'] : 0, false ) . '/>'; 
	
	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="show_header">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
}