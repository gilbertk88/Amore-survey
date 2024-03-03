<?php
/**
 * This file handles the contents on the "iCode Surveys" page.
 *
 * @package iCODE
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generates the surveyzes and surveys page
 *
 * @since 5.0
 */
function iCODE_generate_surveyzes_surveys_page() {

	// Only let admins and editors see this page.
	if ( ! current_user_can( 'moderate_comments' ) ) {
		return;
	}

	// Retrieve our globals.
	global $wpdb;
	global $mlwiCodesurveyMaster;

	// Enqueue our styles and scripts.
	wp_enqueue_script( 'micromodal_script', plugins_url( '../../js/micromodal.min.js', __FILE__ ) );
	wp_enqueue_style( 'iCODE_admin_style', plugins_url( '../../css/iCODE-admin.css', __FILE__ ), array(), $mlwiCodesurveyMaster->version );
	wp_enqueue_script( 'iCODE_admin_script', plugins_url( '../../js/iCODE-admin.js', __FILE__ ), array( 'wp-util', 'underscore', 'jquery', 'micromodal_script' ), $mlwiCodesurveyMaster->version );

	// Create new survey.
	if ( isset( $_POST['iCODE_new_survey_nonce'] ) && wp_verify_nonce( $_POST['iCODE_new_survey_nonce'], 'iCODE_new_survey' ) ) {
		$survey_name = htmlspecialchars( stripslashes( $_POST['survey_name'] ), ENT_QUOTES );
		$mlwiCodesurveyMaster->surveyCreator->create_survey( $survey_name );
	}

	// Delete survey.
	if ( isset( $_POST['iCODE_delete_survey_nonce'] ) && wp_verify_nonce( $_POST['iCODE_delete_survey_nonce'], 'iCODE_delete_survey' ) ) {
		$survey_id   = intval( $_POST['delete_survey_id'] );
		$survey_name = sanitize_text_field( $_POST['delete_survey_name'] );
		$mlwiCodesurveyMaster->surveyCreator->delete_survey( $survey_id, $survey_name );
	}

	// Edit survey Name.
	if ( isset( $_POST['iCODE_edit_name_survey_nonce'] ) && wp_verify_nonce( $_POST['iCODE_edit_name_survey_nonce'], 'iCODE_edit_name_survey' ) ) {
		$survey_id   = intval( $_POST['edit_survey_id'] );
		$survey_name = htmlspecialchars( stripslashes( $_POST['edit_survey_name'] ), ENT_QUOTES );
		$mlwiCodesurveyMaster->surveyCreator->edit_survey_name( $survey_id, $survey_name );
	}

	// Duplicate survey.
	if ( isset( $_POST['iCODE_duplicate_survey_nonce'] ) && wp_verify_nonce( $_POST['iCODE_duplicate_survey_nonce'], 'iCODE_duplicate_survey' ) ) {
		$survey_id   = intval( $_POST['duplicate_survey_id'] );
		$survey_name = htmlspecialchars( $_POST['duplicate_new_survey_name'], ENT_QUOTES );
		$mlwiCodesurveyMaster->surveyCreator->duplicate_survey( $survey_id, $survey_name, isset( $_POST['duplicate_questions'] ) );
	}

	// Resets stats for a survey.
	if ( isset( $_POST['iCODE_reset_stats_nonce'] ) && wp_verify_nonce( $_POST['iCODE_reset_stats_nonce'] , 'iCODE_reset_stats' ) ) {
		$survey_id = intval( $_POST['reset_survey_id'] );
		$results = $wpdb->update(
			$wpdb->prefix . 'mlw_surveyzes',
			array(
				'survey_views'    => 0,
				'survey_taken'    => 0,
				'last_activity' => date( 'Y-m-d H:i:s' ),
			),
			array( 'survey_id' => $survey_id ),
			array(
				'%d',
				'%d',
				'%s',
			),
			array( '%d' )
		);
		if ( false !== $results ) {
			$mlwiCodesurveyMaster->alertManager->newAlert( __( 'The stats has been reset successfully.', 'icode-survey-master' ), 'success' );
			$mlwiCodesurveyMaster->audit_manager->new_audit( "survey Stats Have Been Reset For survey Number $survey_id" );
		} else {
			$mlwiCodesurveyMaster->alertManager->newAlert( __( 'Error trying to reset stats. Please try again.', 'icode-survey-master' ), 'error' );
			$mlwiCodesurveyMaster->log_manager->add( 'Error resetting stats', $wpdb->last_error . ' from ' . $wpdb->last_query, 0, 'error' );
		}
	}

	// Load our surveyzes.
	$surveyzes = $mlwiCodesurveyMaster->pluginHelper->get_surveyzes();

	// Load survey posts.
	$post_to_survey_array = array();
	$my_query = new WP_Query( array(
		'post_type'      => 'survey',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	));
	if ( $my_query->have_posts() ) {
		while ( $my_query->have_posts() ) {
			$my_query->the_post();
			$post_to_survey_array[ get_post_meta( get_the_ID(), 'survey_id', true ) ] = array(
				'link' => get_permalink(),
				'id'   => get_the_ID(),
			);
		}
	}
	wp_reset_postdata();

	$survey_json_array = array();
	foreach ( $surveyzes as $survey ) {
		if ( ! isset( $post_to_survey_array[ $survey->survey_id ] ) ) {
			$current_user = wp_get_current_user();
			$survey_post    = array(
				'post_title'   => $survey->survey_name,
				'post_content' => "[iCODE survey={$survey->survey_id}]",
				'post_status'  => 'publish',
				'post_author'  => $current_user->ID,
				'post_type'    => 'survey',
			);
			$survey_post_id = wp_insert_post( $survey_post );
			add_post_meta( $survey_post_id, 'survey_id', $survey->survey_id );
			$post_to_survey_array[ $survey->survey_id ] = array(
				'link' => get_permalink( $survey_post_id ),
				'id'   => $survey_post_id,
			);
		}

		$activity_date     = date_i18n( get_option( 'date_format' ), strtotime( $survey->last_activity ) );
		$activity_time     = date( 'h:i:s A', strtotime( $survey->last_activity ) );
		$survey_json_array[] = array(
			'id'           => $survey->survey_id,
			'name'         => esc_html( $survey->survey_name ),
			'link'         => $post_to_survey_array[ $survey->survey_id ]['link'],
			'postID'       => $post_to_survey_array[ $survey->survey_id ]['id'],
			'views'        => $survey->survey_views,
			'taken'        => $survey->survey_taken,
			'lastActivity' => $activity_date . ' ' . $activity_time,
		);
	}
	$total_count = count( $survey_json_array );
	wp_localize_script( 'iCODE_admin_script', 'iCODEsurveyObject', $survey_json_array );
	?>
	<div class="wrap iCODE-surveyes-page">
		<h1><?php _e( 'iCode Surveys', 'icode-survey-master' ); ?><a id="new_survey_button" href="#" class="add-new-h2"><?php _e( 'Add New', 'icode-survey-master' ); ?></a></h1>
		<?php $mlwiCodesurveyMaster->alertManager->showAlerts(); ?>
		<?php
		if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
			?>
			<div class="iCODE-info-box">
				<p>Your site is using PHP version <?php echo esc_html( PHP_VERSION ); ?>! Starting in iCODE 6.0, your version of PHP will no longer be supported. <a href="http://bit.ly/2lyrrm8" target="_blank">Click here to learn more about iCODE's minimum PHP version change.</a></p>
			</div>
			<?php
		}
		?>
		<div class="iCODE-surveyzes-page-content">
			<div class="<?php if ( 'false' != get_option( 'mlw_advert_shows' ) ) { echo 'iCODE-survey-page-wrapper-with-ads'; } else { echo 'iCODE-survey-page-wrapper'; } ?>">
				<p class="search-box">
					<label class="screen-reader-text" for="survey_search"><?php _e( 'Search', 'icode-survey-master' ); ?></label>
					<input type="search" id="survey_search" name="survey_search" value="">
					<a href="#" class="button"><?php _e( 'Search', 'icode-survey-master' ); ?></a>
				</p>
				<div class="tablenav top">
					<div class="tablenav-pages">
						<span class="displaying-num"><?php echo sprintf( _n( 'One survey or survey', '%s surveyzes or surveys', $total_count, 'icode-survey-master' ), number_format_i18n( $total_count ) ); ?></span>
						<br class="clear">
					</div>
				</div>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php _e( 'Name', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'URL', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Shortcode', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Link Shortcode', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Views/Taken', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Last Modified', 'icode-survey-master' ); ?></th>
						</tr>
					</thead>
					<tbody id="the-list">

					</tbody>
					<tfoot>
						<tr>
							<th><?php _e( 'Name', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'URL', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Shortcode', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Link Shortcode', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Views/Taken', 'icode-survey-master' ); ?></th>
							<th><?php _e( 'Last Modified', 'icode-survey-master' ); ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<!-- Popup for resetting stats -->
		<div class="iCODE-popup iCODE-popup-slide" id="modal-1" aria-hidden="true">
			<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
				<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
					<header class="iCODE-popup__header">
						<h2 class="iCODE-popup__title" id="modal-1-title">Reset stats for this survey?</h2>
						<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
					</header>
					<main class="iCODE-popup__content" id="modal-1-content">
						<p><?php _e('Are you sure you want to reset the stats to 0? All views and taken stats for this survey will be reset. This is permanent and cannot be undone.', 'icode-survey-master'); ?></p>
						<form action="" method="post" id="reset_survey_form">
							<?php wp_nonce_field( 'iCODE_reset_stats', 'iCODE_reset_stats_nonce' ); ?>
							<input type="hidden" id="reset_survey_id" name="reset_survey_id" value="0" />
						</form>
					</main>
					<footer class="iCODE-popup__footer">
						<button id="reset-stats-button" class="iCODE-popup__btn iCODE-popup__btn-primary"><?php _e('Reset All Stats For survey', 'icode-survey-master'); ?></button>
						<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window"><?php _e('Cancel', 'icode-survey-master'); ?></button>
					</footer>
				</div>
			</div>
		</div>

		<!-- Popup for new survey -->
		<div class="iCODE-popup iCODE-popup-slide" id="modal-2" aria-hidden="true">
			<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
				<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-2-title">
					<header class="iCODE-popup__header">
						<h2 class="iCODE-popup__title" id="modal-2-title"><?php _e( 'Create New survey Or Survey', 'icode-survey-master' ); ?></h2>
						<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
					</header>
					<main class="iCODE-popup__content" id="modal-2-content">
						<form action="" method="post" id="new-survey-form">
							<?php wp_nonce_field( 'iCODE_new_survey', 'iCODE_new_survey_nonce' ); ?>
							<label><?php _e( 'Name', 'icode-survey-master' ); ?></label>
							<input type="text" name="survey_name" value="" />
						</form>
					</main>
					<footer class="iCODE-popup__footer">
						<button id="create-survey-button" class="iCODE-popup__btn iCODE-popup__btn-primary"><?php _e('Create', 'icode-survey-master'); ?></button>
						<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window"><?php _e('Cancel', 'icode-survey-master'); ?></button>
					</footer>
				</div>
			</div>
		</div>

		<!-- Popup for edit survey name -->
		<div class="iCODE-popup iCODE-popup-slide" id="modal-3" aria-hidden="true">
			<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
				<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-3-title">
					<header class="iCODE-popup__header">
						<h2 class="iCODE-popup__title" id="modal-3-title"><?php _e( 'Edit Name', 'icode-survey-master' ); ?></h2>
						<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
					</header>
					<main class="iCODE-popup__content" id="modal-3-content">
						<form action='' method='post' id="edit-name-form">
							<label><?php _e( 'Name', 'icode-survey-master' ); ?></label>
							<input type="text" id="edit_survey_name" name="edit_survey_name" />
							<input type="hidden" id="edit_survey_id" name="edit_survey_id" />
							<?php wp_nonce_field( 'iCODE_edit_name_survey', 'iCODE_edit_name_survey_nonce' ); ?>
						</form>
					</main>
					<footer class="iCODE-popup__footer">
						<button id="edit-name-button" class="iCODE-popup__btn iCODE-popup__btn-primary"><?php _e('Edit', 'icode-survey-master'); ?></button>
						<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window"><?php _e('Cancel', 'icode-survey-master'); ?></button>
					</footer>
				</div>
			</div>
		</div>

		<!-- Popup for duplicate survey -->
		<div class="iCODE-popup iCODE-popup-slide" id="modal-4" aria-hidden="true">
			<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
				<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-4-title">
					<header class="iCODE-popup__header">
						<h2 class="iCODE-popup__title" id="modal-4-title"><?php _e( 'Duplicate', 'icode-survey-master' ); ?></h2>
						<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
					</header>
					<main class="iCODE-popup__content" id="modal-4-content">
						<form action='' method='post' id="duplicate-survey-form">
							<label for="duplicate_questions"><?php _e( 'Duplicate questions also?', 'icode-survey-master' ); ?></label><input type="checkbox" name="duplicate_questions" id="duplicate_questions"/><br />
							<br />
							<label for="duplicate_new_survey_name"><?php _e( 'Name Of New survey Or Survey:', 'icode-survey-master' ); ?></label><input type="text" id="duplicate_new_survey_name" name="duplicate_new_survey_name" />
							<input type="hidden" id="duplicate_survey_id" name="duplicate_survey_id" />
							<?php wp_nonce_field( 'iCODE_duplicate_survey', 'iCODE_duplicate_survey_nonce' ); ?>
						</form>
					</main>
					<footer class="iCODE-popup__footer">
						<button id="duplicate-survey-button" class="iCODE-popup__btn iCODE-popup__btn-primary"><?php _e('Duplicate', 'icode-survey-master'); ?></button>
						<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window"><?php _e('Cancel', 'icode-survey-master'); ?></button>
					</footer>
				</div>
			</div>
		</div>

		<!-- Popup for delete survey -->
		<div class="iCODE-popup iCODE-popup-slide" id="modal-5" aria-hidden="true">
			<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
				<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-5-title">
					<header class="iCODE-popup__header">
						<h2 class="iCODE-popup__title" id="modal-5-title"><?php _e( 'Delete', 'icode-survey-master' ); ?></h2>
						<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
					</header>
					<main class="iCODE-popup__content" id="modal-5-content">
						<form action='' method='post' id="delete-survey-form">
							<h3><b><?php _e( 'Are you sure you want to delete this survey or survey?', 'icode-survey-master' ); ?></b></h3>
							<?php wp_nonce_field( 'iCODE_delete_survey', 'iCODE_delete_survey_nonce' ); ?>
							<input type='hidden' id='delete_survey_id' name='delete_survey_id' value='' />
							<input type='hidden' id='delete_survey_name' name='delete_survey_name' value='' />
						</form>
					</main>
					<footer class="iCODE-popup__footer">
						<button id="delete-survey-button" class="iCODE-popup__btn iCODE-popup__btn-primary"><?php _e('Delete', 'icode-survey-master'); ?></button>
						<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window"><?php _e('Cancel', 'icode-survey-master'); ?></button>
					</footer>
				</div>
			</div>
		</div>

		<!-- Templates -->
		<script type="text/template" id="tmpl-no-survey">
			<h2><?php _e( 'You do not have any surveyzes or surveys. Click "Add New" to get started.', 'icode-survey-master' ); ?></h2>
		</script>

		<script type="text/template" id="tmpl-survey-row">
			<tr class="iCODE-survey-row" data-id="{{ data.id }}">
				<td class="post-title column-title">
					<span class="iCODE-survey-name">{{ data.name }}</span> <a class="iCODE-edit-name" href="#"><?php _e( 'Edit Name', 'icode-survey-master' ); ?></a>
					<div class="row-actions">
						<a class="iCODE-action-link" href="admin.php?page=mlw_survey_options&&survey_id={{ data.id }}"><?php _e( 'Edit', 'icode-survey-master' ); ?></a> | 
						<a class="iCODE-action-link" href="admin.php?page=mlw_survey_results&&survey_id={{ data.id }}"><?php _e( 'Results', 'icode-survey-master' ); ?></a> | 
						<a class="iCODE-action-link iCODE-action-link-duplicate" href="#"><?php _e( 'Duplicate', 'icode-survey-master' ); ?></a> | 
						<a class="iCODE-action-link iCODE-action-link-delete" href="#"><?php _e( 'Delete', 'icode-survey-master' ); ?></a>
					</div>
				</td>
				<td>
					<a href="{{ data.link }}"><?php _e( 'View survey/Survey', 'icode-survey-master' ); ?></a>
					<div class="row-actions">
						<a class="iCODE-action-link" href="post.php?post={{ data.postID }}&action=edit"><?php _e( 'Edit Post Settings', 'icode-survey-master' ); ?></a>
					</div>
				</td>
				<td>[iCODE survey={{ data.id }}]</td>
				<td>[iCODE_link id={{ data.id }}]<?php _e( 'Click here', 'icode-survey-master' ); ?>[/iCODE_link]</td>
				<td>
					{{ data.views }}/{{ data.taken }}
					<div class="row-actions">
						<a class="iCODE-action-link iCODE-action-link-reset" href="#"><?php _e( 'Reset', 'icode-survey-master' ); ?></a>
					</div>
				</td>
				<td>{{ data.lastActivity }}</td>
			</tr>
		</script>
	</div>
<?php
}
?>