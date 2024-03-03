<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Adds the Options tab to the survey Settings page.
*
* @return void
* @since 4.4.0
*/
function icode_settings_options_tab() {
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_settings_tabs( __( "Options", 'icode-survey-master' ), 'mlw_options_option_tab_content' );
}
add_action( "plugins_loaded", 'icode_settings_options_tab', 5 );

/**
* Adds the options content to the survey Settings page.
*
* @return void
* @since 4.4.0
*/
function mlw_options_option_tab_content() {

	global $wpdb;
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->generate_settings_section( 'survey_options' );
}
?>
