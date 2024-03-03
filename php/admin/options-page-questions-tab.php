<?php
/**
 * This file handles the "Questions" tab when editing a survey/survey
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds the settings for questions tab to the survey Settings page.
 *
 * @return void
 * @since 4.4.0
 */
function iCODE_settings_questions_tab() {
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_settings_tabs( __( 'Questions', 'icode-survey-master' ), 'iCODE_options_questions_tab_content' );
}
add_action( "plugins_loaded", 'iCODE_settings_questions_tab', 5 );


/**
 * Adds the content for the options for questions tab.
 *
 * @return void
 * @since 4.4.0
 */
function iCODE_options_questions_tab_content() {

	global $wpdb;
	global $mlwiCodesurveyMaster;
	$survey_id = intval( $_GET['survey_id'] );

	$json_data = array(
		'surveyID'         => $survey_id,
		'answerText'     => __( 'Answer', 'icode-survey-master' ),
		'nonce'          => wp_create_nonce( 'wp_rest' ),
		'pages'          => $mlwiCodesurveyMaster->pluginHelper->get_survey_setting( 'pages', array() ),
	);

	// Scripts and styles.
	wp_enqueue_script( 'micromodal_script', plugins_url( '../../js/micromodal.min.js', __FILE__ ) );
	wp_enqueue_script( 'iCODE_admin_question_js', plugins_url( '../../js/iCODE-admin-question.js', __FILE__ ), array( 'backbone', 'underscore', 'jquery-ui-sortable', 'wp-util', 'micromodal_script' ), $mlwiCodesurveyMaster->version, true );
	wp_localize_script( 'iCODE_admin_question_js', 'iCODEQuestionSettings', $json_data );
	wp_enqueue_style( 'iCODE_admin_question_css', plugins_url( '../../css/iCODE-admin-question.css', __FILE__ ), array(), $mlwiCodesurveyMaster->version  );
	wp_enqueue_script( 'math_jax', '//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML' );
	wp_enqueue_editor();
	wp_enqueue_media();

	// Load Question Types.
	$question_types = $mlwiCodesurveyMaster->pluginHelper->get_question_type_options();

	// Display warning if using competing options.
	$pagination = $mlwiCodesurveyMaster->pluginHelper->get_section_setting( 'survey_options', 'pagination' );
	if ( 0 != $pagination ) {
		?>
		<div class="notice notice-warning">
			<p>This survey has the "How many questions per page would you like?" option enabled. The pages below will not be used while that option is enabled. To turn off, go to the "Options" tab and set that option to 0.</p>
		</div>
		<?php
	}
	$from_total = $mlwiCodesurveyMaster->pluginHelper->get_section_setting( 'survey_options', 'question_from_total' );
	if ( 0 != $from_total ) {
		?>
		<div class="notice notice-warning">
			<p>This survey has the "How many questions should be loaded for survey?" option enabled. The pages below will not be used while that option is enabled. To turn off, go to the "Options" tab and set that option to 0.</p>
		</div>
		<?php
	}
	$randomness = $mlwiCodesurveyMaster->pluginHelper->get_section_setting( 'survey_options', 'randomness_order' );
	if ( 0 != $randomness ) {
		?>
		<div class="notice notice-warning">
			<p>This survey has the "Are the questions random?" option enabled. The pages below will not be used while that option is enabled. To turn off, go to the "Options" tab and set that option to "No".</p>
		</div>
		<?php
	}
	?>
	<h3>Questions</h3>
	<p>Use this tab to create and modify the different pages of your survey or survey as well as the questions on each page. Click "Create New Page" to get started!</p>
	<div class="questions-messages"></div>
	<a href="#" class="new-page-button button">Create New Page</a>
	<a href="#" class="save-page-button button-primary">Save Questions</a>
	<p class="search-box">
		<label class="screen-reader-text" for="question_search">Search Questions:</label>
		<input type="search" id="question_search" name="question_search" value="">
		<a href="#" class="button">Search Questions</a>
	</p>
	<div class="questions"></div>

	<!-- Popup for question bank -->
	<div class="iCODE-popup iCODE-popup-slide iCODE-popup-bank" id="modal-2" aria-hidden="true">
		<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
			<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
				<header class="iCODE-popup__header">
					<h2 class="iCODE-popup__title" id="modal-2-title">Add Question From Question Bank</h2>
					<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
				</header>
				<main class="iCODE-popup__content" id="modal-2-content">
					<input type="hidden" name="add-question-bank-page" id="add-question-bank-page" value="">
					<div id="question-bank"></div>
				</main>
				<footer class="iCODE-popup__footer">
					<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window">Cancel</button>
				</footer>
			</div>
		</div>
	</div>


	<!-- Popup for editing question -->
	<div class="iCODE-popup iCODE-popup-slide" id="modal-1" aria-hidden="true">
		<div class="iCODE-popup__overlay" tabindex="-1" data-micromodal-close>
			<div class="iCODE-popup__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
				<header class="iCODE-popup__header">
					<h2 class="iCODE-popup__title" id="modal-1-title">Edit Question</h2>
					<a class="iCODE-popup__close" aria-label="Close modal" data-micromodal-close></a>
				</header>
				<main class="iCODE-popup__content" id="modal-1-content">
					<input type="hidden" name="edit_question_id" id="edit_question_id" value="">
					<div class="iCODE-row">
						<label><?php _e( 'Question Type', 'icode-survey-master' ); ?></label>
						<select name="question_type" id="question_type">
							<?php
							foreach ( $question_types as $type ) {
								echo "<option value='{$type['slug']}'>{$type['name']}</option>";
							}
							?>
						</select>
					</div>
					<p id="question_type_info"></p>
					<div class="iCODE-row">
						<textarea id="question-text"></textarea>
					</div>
					<div class="iCODE-row">
						<label><?php _e( 'Answers', 'icode-survey-master' ); ?></label>
						<div class="correct-header"><?php _e( 'Correct', 'icode-survey-master' ); ?></div>
						<div class="answers" id="answers">

						</div>
						<a href="#" class="button" id="new-answer-button"><?php _e( 'Add New Answer!', 'icode-survey-master'); ?></a>
					</div>
					<div id="correct_answer_area" class="iCODE-row">
						<label><?php _e( 'Correct Answer Info', 'icode-survey-master' ); ?></label>
						<input type="text" name="correct_answer_info" value="" id="correct_answer_info" />
					</div>
					<div id="hint_area" class="iCODE-row">
						<label><?php _e( 'Hint', 'icode-survey-master' ); ?></label>
						<input type="text" name="hint" value="" id="hint"/>
					</div>
					<div id="comment_area" class="iCODE-row">
						<label><?php _e( 'Comment Field', 'icode-survey-master' ); ?></label>
						<select name="comments" id="comments">
							<option value="0"><?php _e('Small Text Field', 'icode-survey-master'); ?></option>
							<option value="2"><?php _e('Large Text Field', 'icode-survey-master'); ?></option>
							<option value="1" selected="selected"><?php _e('None', 'icode-survey-master'); ?></option>
						<select>
					</div>
					<div id="required_area" class="iCODE-row">
						<label><?php _e( 'Required?', 'icode-survey-master' ); ?></label>
						<select name="required" id="required">
							<option value="0" selected="selected"><?php _e( 'Yes', 'icode-survey-master' ); ?></option>
							<option value="1"><?php _e( 'No', 'icode-survey-master' ); ?></option>
						</select>
					</div>
					<div id="category_area" class="iCODE-row">
						<label><?php _e( 'Category', 'icode-survey-master' ); ?></label>
						<div id="categories">
							<input type="radio" name="category" class="category-radio" id="new_category_new" value="new_category"><label for="new_category_new">New: <input type='text' id='new_category' value='' /></label>
						</div>
					</div>
				</main>
				<footer class="iCODE-popup__footer">
					<button id="save-popup-button" class="iCODE-popup__btn iCODE-popup__btn-primary">Save Question</button>
					<button class="iCODE-popup__btn" data-micromodal-close aria-label="Close this dialog window">Cancel</button>
				</footer>
			</div>
		</div>
	</div>

	<!--Views-->

	<!-- View for Notices -->
	<script type="text/template" id="tmpl-notice">
		<div class="notice notice-{{data.type}}">
			<p>{{data.message}}</p>
		</div>
	</script>

	<!-- View for Page -->
	<script type="text/template" id="tmpl-page">
		<div class="page page-new">
			<div class="page-header">
				<div><span class="dashicons dashicons-move"></span></div>
				<div class="page-header-buttons">
					<a href="#" class="new-question-button button">Create New Question</a>
					<a href="#" class="add-question-bank-button button">Add Question From Question Bank</a>
				</div>
				<div><a href="#" class="delete-page-button"><span class="dashicons dashicons-trash"></span></a></div>
			</div>
		</div>
	</script>

	<!-- View for Question -->
	<script type="text/template" id="tmpl-question">
		<div class="question question-new" data-question-id="{{data.id }}">
			<div class="question-content">
				<div><span class="dashicons dashicons-move"></span></div>
				<div><a href="#" class="edit-question-button"><span class="dashicons dashicons-edit"></span></a></div>
				<div><a href="#" class="duplicate-question-button"><span class="dashicons dashicons-controls-repeat"></span></a></div>
				<div class="question-content-text">{{{data.question}}}</div>
				<div><# if ( 0 !== data.category.length ) { #> Category: {{data.category}} <# } #></div>
				<div><a href="#" class="delete-question-button"><span class="dashicons dashicons-trash"></span></a><div>
			</div>
		</div>
	</script>

	<!-- View for question in question bank -->
	<script type="text/template" id="tmpl-single-question-bank-question">
		<div class="question-bank-question" data-question-id="{{data.id}}">
			<div><a href="#" class="import-button button">Add This Question</a></div>
			<div><p>{{{data.question}}}</p></div>
		</div>
	</script>

	<!-- View for single category -->
	<script type="text/template" id="tmpl-single-category">
		<div class="category">
			<input type="radio" name="category" class="category-radio" value="{{data.category}}"><label>{{data.category}}</label>
		</div>
	</script>

	<!-- View for single answer -->
	<script type="text/template" id="tmpl-single-answer">
		<div class="answers-single">
			<div><a href="#" class="delete-answer-button"><span class="dashicons dashicons-trash"></span></a></div>
			<div class="answer-text-div"><input type="text" class="answer-text" value="{{data.answer}}" placeholder="Your answer"/></div>
			<div><input type="text" class="answer-points" value="{{data.points}}" placeholder="Points"/></div>
			<div><input type="checkbox" class="answer-correct" value="1" <# if ( 1 == data.correct ) { #> checked="checked"/> <# } #></div>
		</div>
	</script>
	<?php
}


add_action( 'wp_ajax_iCODE_save_pages', 'iCODE_ajax_save_pages' );
add_action( 'wp_ajax_nopriv_iCODE_save_pages', 'iCODE_ajax_save_pages' );


/**
 * Saves the pages and order from the Questions tab
 *
 * @since 5.2.0
 */
function iCODE_ajax_save_pages() {
	global $mlwiCodesurveyMaster;
	$json = array(
		'status' => 'error',
	);
	$survey_id = intval( $_POST['survey_id'] );
	$mlwiCodesurveyMaster->pluginHelper->prepare_survey( $survey_id );
	$response = $mlwiCodesurveyMaster->pluginHelper->update_survey_setting( 'pages', $_POST['pages'] );
	if ( $response ) {
		$json['status'] = 'success';
	}
	echo wp_json_encode( $json );
	wp_die();
}

add_action( 'wp_ajax_iCODE_load_all_survey_questions', 'iCODE_load_all_survey_questions_ajax' );
add_action( 'wp_ajax_nopriv_iCODE_load_all_survey_questions', 'iCODE_load_all_survey_questions_ajax' );

/**
 * Loads all the questions and echos out JSON
 *
 * @since 0.1.0
 * @return void
 */
function iCODE_load_all_survey_questions_ajax() {
	global $wpdb;
	global $mlwiCodesurveyMaster;

	// Loads questions.
	$questions = $wpdb->get_results( "SELECT question_id, question_name FROM {$wpdb->prefix}mlw_questions WHERE deleted = '0' ORDER BY question_id DESC" );

	// Creates question array.
	$question_json = array();
	foreach ( $questions as $question ) {
		$question_json[] = array(
			'id'       => $question->question_id,
			'question' => $question->question_name,
		);
	}

	// Echos JSON and dies.
	echo wp_json_encode( $question_json );
	wp_die();
}

?>
