<?php
function icode_register_default_templates() {
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_template( 'Primary', 'icode_primary.css');
	$mlwiCodesurveyMaster->pluginHelper->register_survey_template( 'Amethyst', 'icode_amethyst.css');
	$mlwiCodesurveyMaster->pluginHelper->register_survey_template( 'Emerald', 'icode_emerald.css');
	$mlwiCodesurveyMaster->pluginHelper->register_survey_template( 'Turquoise', 'icode_turquoise.css');
	$mlwiCodesurveyMaster->pluginHelper->register_survey_template( 'Gray', 'icode_gray.css');
}
add_action( 'plugins_loaded', 'icode_register_default_templates' );
?>
