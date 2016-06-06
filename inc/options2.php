<?php
add_action( 'admin_menu', 'bpwc1_add_admin_menu' );
add_action( 'admin_init', 'bpwc1_settings_init' );


function bpwc1_add_admin_menu(  ) { 

	add_options_page( 'bpwc1', 'bpwc1', 'manage_options', 'bpwc1', 'bpwc1_options_page' );

}

function sandbox_theme_default_display_option() {
	
	$defaults = array(
		'bpwc1_checkbox_field_0'		=>	'',
		'bpwc1_checkbox_field_1'		=>	'',
		'bpwc1_checkbox_field_2'		=>	'',
		'bpwc1_checkbox_field_3'		=>	'',
		'bpwc1_checkbox_field_4'		=>	'',
	);
	
	return apply_filters( 'sandbox_theme_default_display_options', $defaults );
	
}

function bpwc1_settings_init(  ) { 
	if( false == get_option( 'bpwc1_settings' ) ) {	
		add_option( 'bpwc1_settings', apply_filters( 'sandbox_theme_default_display_option', sandbox_theme_default_display_option() ) );
	}

	add_settings_section(
		'bpwc1_pluginPage_section', 
		__( 'Your section description', 'bpwc1' ), 
		'bpwc1_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'bpwc1_checkbox_field_0', 						// ID
		__( 'Settings field description', 'bpwc1' ),  	// Label
		'bpwc1_checkbox_field_0_render',  				// function to render option
		'pluginPage', 									// page
		'bpwc1_pluginPage_section' 						// name of section
	);

	add_settings_field( 
		'bpwc1_checkbox_field_1', 
		__( 'Settings field description', 'bpwc1' ), 
		'bpwc1_checkbox_field_1_render', 
		'pluginPage', 
		'bpwc1_pluginPage_section' 
	);

	add_settings_field( 
		'bpwc1_checkbox_field_2', 
		__( 'Settings field description', 'bpwc1' ), 
		'bpwc1_checkbox_field_2_render', 
		'pluginPage', 
		'bpwc1_pluginPage_section' 
	);

	add_settings_field( 
		'bpwc1_checkbox_field_3', 
		__( 'Settings field description', 'bpwc1' ), 
		'bpwc1_checkbox_field_3_render', 
		'pluginPage', 
		'bpwc1_pluginPage_section' 
	);

	add_settings_field( 
		'bpwc1_checkbox_field_4', 
		__( 'Settings field description', 'bpwc1' ), 
		'bpwc1_checkbox_field_4_render', 
		'pluginPage', 
		'bpwc1_pluginPage_section' 
	);

	register_setting( 'pluginPage', 'bpwc1_settings' );

}


function bpwc1_checkbox_field_0_render(  ) { 

	$options = get_option( 'bpwc1_settings' );
	?>
	<input type='checkbox' name='bpwc1_settings[bpwc1_checkbox_field_0]' <?php checked( 1, isset( $options['bpwc1_checkbox_field_0'] ) ? $options['bpwc1_checkbox_field_0'] : 0, false ); ?> value='1'>
	<?php

}


function bpwc1_checkbox_field_1_render(  ) { 

	$options = get_option( 'bpwc1_settings' );
	?>
	<input type='checkbox' name='bpwc1_settings[bpwc1_checkbox_field_1]' <?php checked( 1, isset( $options['bpwc1_checkbox_field_1'] ) ? $options['bpwc1_checkbox_field_1'] : 0, false ); ?> value='1'>
	<?php

}


function bpwc1_checkbox_field_2_render(  ) { 

	$options = get_option( 'bpwc1_settings' );
	?>
	<input type='checkbox' name='bpwc1_settings[bpwc1_checkbox_field_2]' <?php checked( 1, isset( $options['bpwc1_checkbox_field_2'] ) ? $options['bpwc1_checkbox_field_2'] : 0, false ); ?> value='1'>
	<?php

}


function bpwc1_checkbox_field_3_render(  ) { 

	$options = get_option( 'bpwc1_settings' );
	?>
	<input type='checkbox' name='bpwc1_settings[bpwc1_checkbox_field_3]' <?php checked( 1, isset( $options['bpwc1_checkbox_field_3'] ) ? $options['bpwc1_checkbox_field_3'] : 0, false ); ?> value='1'>
	<?php

}


function bpwc1_checkbox_field_4_render(  ) { 

	$options = get_option( 'bpwc1_settings' );
	?>
	<input type='checkbox' name='bpwc1_settings[bpwc1_checkbox_field_4]' <?php checked( 1, isset( $options['bpwc1_checkbox_field_4'] ) ? $options['bpwc1_checkbox_field_4'] : 0, false ); ?> value='1'>
	<?php

}


function bpwc1_settings_section_callback(  ) { 

	echo __( 'This section description', 'bpwc1' );

}


function bpwc1_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>BPWC1</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>