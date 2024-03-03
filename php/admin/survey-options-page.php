<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This function allows for the editing of survey options.
*
* @param type description
* @return void
* @since 4.4.0
*/
function iCODE_generate_survey_options() {

	// Check if current user can
	if ( ! current_user_can('moderate_comments') ) {
		return;
	}

	global $wpdb;
	global $mlwiCodesurveyMaster;
	$survey_name = '';

	// Get registered tabs for the options page and set current tab
	$tab_array = $mlwiCodesurveyMaster->pluginHelper->get_settings_tabs();
	$active_tab = strtolower(str_replace( " ", "-", isset( $_GET[ 'tab' ] ) ? stripslashes( $_GET[ 'tab' ] ) : __( 'Questions', 'icode-survey-master' )));

	// Prepares survey.
	$survey_id = isset( $_GET['survey_id'] ) ? intval( $_GET['survey_id'] ) : 0;
	if ( isset( $_GET["survey_id"] ) ) {
		$survey_name = $wpdb->get_var( $wpdb->prepare( "SELECT survey_name FROM {$wpdb->prefix}mlw_surveyzes WHERE survey_id=%d LIMIT 1", $survey_id ) );
		$mlwiCodesurveyMaster->pluginHelper->prepare_survey( $_GET["survey_id"] );
	}

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-effects-blind' );
	wp_enqueue_script( 'jquery-effects-explode' );
	wp_enqueue_style( 'icode_jquery_redmond_theme', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css' );
	wp_enqueue_script( 'math_jax', '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML' );
	?>
	<style>
		.mlw_tab_content {
			padding: 20px 20px 20px 20px;
			margin: 20px 20px 20px 20px;
		}
	</style>
	<div class="wrap">
	<div class='mlw_survey_options'>
		<h1><?php echo $survey_name; ?></h1>
		<?php
		// Put all output from tab into ob_get_contents below.
		ob_start();

		// If the survey is set and not empty
		if ( ! empty( $survey_id ) ) {
			?>
			<h2 class="nav-tab-wrapper">
				<?php
				// Cycle through registered tabs to create navigation
				foreach( $tab_array as $tab ) {
					$active_class = '';
					if ( $active_tab == $tab['slug'] ) {
						$active_class = 'nav-tab-active';
					}
					echo "<a href=\"?page=mlw_survey_options&survey_id=$survey_id&tab=".$tab['slug']."\" class=\"nav-tab $active_class\">".$tab['title']."</a>";
				}
				?>
			</h2>
			<div class="mlw_tab_content">
				<?php
					// Cycle through tabs looking for current tab to create tab's content
					foreach( $tab_array as $tab ) {
						if ( $active_tab == $tab['slug'] ) {
							call_user_func( $tab['function'] );
						}
					}
				?>
			</div>
			<?php
		} else {
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
			<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
			<strong><?php _e('Error!', 'icode-survey-master'); ?></strong> <?php _e('Please go to the surveyzes page and click on the Edit link from the survey you wish to edit.', 'icode-survey-master'); ?></p>
			</div>
			<?php
		}
		$mlw_output = ob_get_contents();
		ob_end_clean();

		// Shows alerts, ads, then tab content
		$mlwiCodesurveyMaster->alertManager->showAlerts();
		echo $mlw_output;
		?>
	</div>
	</div>
<?php
}
?>
