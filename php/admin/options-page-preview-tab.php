<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Adds the Settings Preview tab to the survey Settings page.
*
* @return void
* @since 4.4.0
*/
function icode_settings_preview_tab()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_settings_tabs(__("Preview", 'icode-survey-master'), 'mlw_options_preview_tab_content');
}
add_action("plugins_loaded", 'icode_settings_preview_tab', 5);

/**
* Adds the options preview content to the Options preview tab.
*
* @return void
* @since 4.4.0
*/
function mlw_options_preview_tab_content()
{
	?>
	<div id="tabs-preview" class="mlw_tab_content">
		<?php
		echo do_shortcode( '[mlw_surveymaster survey='.intval($_GET["survey_id"]).']' );
		?>
	</div>
	<?php
}
?>
