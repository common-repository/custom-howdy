<?php
/*
Plugin Name: Custom Howdy
Plugin URI: 
Description: Custom WordPress Greeting ('Howdy')
Author: IRGeekSauce
Author URI: https://www.youtube.com/channel/UCyb4v-IDQRCQp_o0YYJ_hSA
Version: 1.0
License: GNU General Public License v2.0
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/*

This plugin comes with NO WARRANTY. 


This is my first plugin. Newer and better versions will probably be released later. 
Hope you enjoy it. 

*/

add_action( 'admin_init', 'customhowdy_register_settings' ); //register the action


function customhowdy_register_settings() {

	// For reference: register_setting( $option_group, $option_name, $sanitize_callback );
	register_setting( 'customhowdy_plugin_op', 'customhowdy_plugin_op', 'validate_op' );
	
	// For reference: add_settings_section( $id, $title, $callback, $page );
	add_settings_section( 'customhowdy_message', '', 'customhowdy_msg_section', 'customhowdy-op' );
	
	// For reference: add_settings_field( $id, $title, $callback, $page, $section, $args );
	add_settings_field( 'customhowdy_change_message', 'New Greeting:', 'new_greeting', 'customhowdy-op', 'customhowdy_message' );

}


function customhowdy_default_op() {

	$options = array(
		'customhowdy_change_message' => 'Howdy'
	);
	
	return $options;

}

register_activation_hook( __FILE__, 'customhowdy_default_op_setup' );

function customhowdy_default_op_setup() {

	global $customhowdy_op;
	$customhowdy_op = get_option( 'customhowdy_plugin_op' );
	
	if ( false === $customhowdy_op ) {
		$customhowdy_op = customhowdy_default_op();
	}
	
	update_option( 'customhowdy_plugin_op', $customhowdy_op );

}

add_action( 'admin_menu', 'customhowdy_plugin_page' );
//activate admin style 
//add_action( 'admin_print_styles-' . $page, 'active_nv_admin_styles' );
	

function customhowdy_plugin_page() {

	// For reference: add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	add_options_page( 'Custom Howdy', 'Custom Howdy', 'manage_options', 'customhowdy-op', 'customhowdy_plugin_op_page' );

}

/*

FUNCTION TO CUSTOMIZE THE SETTINGS PAGE FOR CUSTOM HOWDY
*/
function customhowdy_plugin_op_page() { 

	?>

<!--

Styling 

-->
<style>
	
.notice p { /* SAVE NOTIFICATION UPON SUBMIT */
	
	color: #000 !important; 
}

.button-primary { /* SUBMIT BUTTON */
	/*background-color: #dbdbdb !important; */
	background-color: #c0c0c0 !important;
	color: #000 !important;
	border-radius: 8px !important;
	text-shadow: 0 0px 0px #000 !important;
	border-color: #000 !important;
	box-shadow: 0 5px 0 #000 !important;
	font-size: 14px !important;
	
	
}
.button-primary:hover { /* SUBMIT BUTTON HOVER */
	
	background-color: red !important;
	color: #fff !important;
	
	}
.wrap { /* MAIN CONTENT CONTAINER */
	
	background-color: #00405d;
	color: #fff;
	padding-left: 5px !important;
	width: 80%;

}

h2 { /* MAIN HEADING */
	
	color: #fff;
	font-family: verdana;
	
}
input {
	
	border-radius: 8px !important;
	
}
.form-table th { /* "New Greeting: " */
	
	color: #fff;
	width: 110px;
	
}



</style>
	<div id="customhowdy-op-wrapper">
		<div class="wrap">
		
			<div class="header">
			
				<div class="head-wrap">
					<div id="icon-themes" class="icon32"><br /></div>
					<h2><?php echo 'Welcome to Custom Howdy'; ?></h2>
				</div>
					
			</div>
			
			<?php ?>
			
			<div class="main-content">
				
				<form action="options.php" method="post" enctype="multipart/form-data">
				
				<?php settings_fields( 'customhowdy_plugin_op' ); ?>
				<?php do_settings_sections( 'customhowdy-op' ); ?>
				
					
      				<p class="submit">
        				<input name="customhowdy_plugin_op[submit-customhowdy-op]" type="submit" class="button-primary" value="<?php esc_attr_e( 'SUBMIT', 'inline' ); ?>" />
      				</p>
      				
   				</form>
   				
			</div>
	
		</div>
	</div>

<?php }


function customhowdy_msg_section() {

	echo "<p class='intro'>Enter your new WordPress greeting below.</p>";

}


function new_greeting() {
	
	$options = get_option( 'customhowdy_plugin_op' );
	echo "<input id='customhowdy-message' name='customhowdy_plugin_op[customhowdy_change_message]' size='40' type='text' value='{$options['customhowdy_change_message']}' />";

}

function validate_op( $input ) {
	
	$customhowdy_op = get_option( 'customhowdy_plugin_op' );
	$validate = $customhowdy_op;
	
	$submit_op = ( !empty( $input['submit-customhowdy-op'] ) ? true : false ); 
	
	if ( $submit_op ) {
		
		
		$validate['customhowdy_change_message'] = esc_attr( trim( $input['customhowdy_change_message'] ) );
		
	}
	
	return $validate;

}

add_filter( 'gettext', 'replace_greeting_string' );

function replace_greeting_string( $newText ) {

	if ( is_admin() ) {
		$greeting = get_option( 'customhowdy_plugin_op' ); // Defaults to 'Howdy' if no return value is specified
		$newText = str_replace( 'Howdy', $greeting['customhowdy_change_message'], $newText );
	}
	return $newText;
	
}