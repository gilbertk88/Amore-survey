<?php
/**
 * Handles the functions/views for the "Style" tab when editing a survey or survey
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Style tab to the survey Settings page.
 *
 * @return void
 * @since 6.0.2
 */
function iCODE_settings_style_tab() {
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_settings_tabs( __( 'Style', 'icode-survey-master' ), 'iCODE_options_styling_tab_content' );
}
add_action( 'plugins_loaded', 'iCODE_settings_style_tab', 5 );

/**
 * Adds the Style tab content to the tab.
 *
 * @return void
 * @since 6.0.2
 */
function iCODE_options_styling_tab_content() {
	global $wpdb;
	global $mlwiCodesurveyMaster;

	wp_enqueue_style( 'iCODE_admin_style', plugins_url( '../../css/iCODE-admin.css', __FILE__ ), array(), $mlwiCodesurveyMaster->version );

	$survey_id = intval( $_GET['survey_id'] );
	if ( isset( $_POST['save_style_options'] ) && 'confirmation' == $_POST['save_style_options'] ) {

		$style_survey_id = intval( $_POST['style_survey_id'] );
		$survey_theme = sanitize_text_field( $_POST['save_survey_theme'] );
		$survey_style = htmlspecialchars( stripslashes( $_POST['survey_css'] ), ENT_QUOTES );

		// Saves the new css.
		$results = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}mlw_surveyzes SET survey_stye='%s', theme_selected='%s', last_activity='" . date( 'Y-m-d H:i:s' ) . "' WHERE survey_id=%d", $survey_style, $survey_theme, $style_survey_id ) );
		if ( false !== $results ) {
			$mlwiCodesurveyMaster->alertManager->newAlert( __( 'The style has been saved successfully.', 'icode-survey-master' ), 'success' );
			$mlwiCodesurveyMaster->audit_manager->new_audit( "Styles Have Been Saved For survey Number $style_survey_id" );
		} else {
			$mlwiCodesurveyMaster->alertManager->newAlert( __( 'Error occured when trying to save the styles. Please try again.', 'icode-survey-master' ), 'error' );
			$mlwiCodesurveyMaster->log_manager->add( 'Error saving styles', $wpdb->last_error . ' from ' . $wpdb->last_query, 0, 'error' );
		}
	}

	if ( isset( $_GET['survey_id'] ) ) {
		$table_name = $wpdb->prefix . 'mlw_surveyzes';
		$mlw_survey_options = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE survey_id=%d LIMIT 1", $survey_id ) );
	}
	$registered_templates = $mlwiCodesurveyMaster->pluginHelper->get_survey_templates();
	?>
	<div id="tabs-7" class="mlw_tab_content">
		<script>
			function mlw_icode_theme(theme)
			{
				document.getElementById('save_survey_theme').value = theme;
				jQuery("div.mlw_icode_themeBlockActive").toggleClass("mlw_icode_themeBlockActive");
				jQuery("#mlw_icode_theme_block_"+theme).toggleClass("mlw_icode_themeBlockActive");

			}
		</script>
		<form action='' method='post' name='survey_style_form'>
			<input type='hidden' name='save_style_options' value='confirmation' />
			<input type='hidden' name='style_survey_id' value='<?php echo esc_attr( $survey_id ); ?>' />
			<input type='hidden' name='save_survey_theme' id='save_survey_theme' value='<?php echo esc_attr( $mlw_survey_options->theme_selected ); ?>' />
			<h3><?php _e( 'survey Styles', 'icode-survey-master' ); ?></h3>
			<p><?php _e( 'Choose your style:', 'icode-survey-master' ); ?></p>
			<style>
				div.mlw_icode_themeBlockActive {
					background-color: yellow;
				}
			</style>
			<div class="iCODE-styles">
				<?php
				foreach ( $registered_templates as $slug => $template ) {
					?>
					<div onclick="mlw_icode_theme('<?php echo $slug; ?>');" id="mlw_icode_theme_block_<?php echo $slug; ?>" class="iCODE-info-widget <?php if ($mlw_survey_options->theme_selected == $slug) {echo 'mlw_icode_themeBlockActive';} ?>"><?php echo $template["name"]; ?></div>
					<?php
				}
				?>
				<div onclick="mlw_icode_theme('default');" id="mlw_icode_theme_block_default" class="iCODE-info-widget <?php if ($mlw_survey_options->theme_selected == 'default') {echo 'mlw_icode_themeBlockActive';} ?>"><?php _e('Custom', 'icode-survey-master'); ?></div>
				<script>
					mlw_icode_theme('<?php echo $mlw_survey_options->theme_selected; ?>');
				</script>
			</div>
			<button id="save_styles_button" class="button-primary"><?php _e('Save survey Style', 'icode-survey-master'); ?></button>
			<hr />
			<h3><?php _e('Custom Style CSS', 'icode-survey-master'); ?></h3>
			<p><?php _e('For detailed help and guidance along with a list of different classes used in this plugin, please visit the following link:', 'icode-survey-master'); ?>
			<a target="_blank" href="http://bit.ly/2JDHwA6">Style Guide</a></p>
			<table class="form-table">
				<tr>
					<td><textarea style="width: 100%; height: 700px;" id="survey_css" name="survey_css"><?php echo $mlw_survey_options->survey_stye; ?></textarea></td>
				</tr>
			</table>
			<button id="save_styles_button" class="button-primary"><?php _e('Save survey Style', 'icode-survey-master'); ?></button>
		</form>
	</div>
	<?php
}
?>
