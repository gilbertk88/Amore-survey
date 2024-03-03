<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * icode survey Creator Class
 *
 * This class handles survey creation, update, and deletion from the admin panel
 *
 * The survey Creator class handles all the survey management functions that is done from the admin panel
 *
 * @since 3.7.1
 */
class icodesurveyCreator {

	/**
	 * icode ID of survey
	 *
	 * @var object
	 * @since 3.7.1
	 */
	private $survey_id;

	/**
	 * If the survey ID is set, store it as the class survey ID
	 *
	 * @since 3.7.1
	 */
	public function __construct() {
		if ( isset( $_GET['survey_id'] ) ) {
			$this->survey_id = intval( $_GET['survey_id'] );
		}
	}

	/**
	 * Sets survey ID
	 *
	 * @since 3.8.1
	 * @param int $survey_id The ID of the survey.
	 * @access public
	 * @return void
	 */
	public function set_id( $survey_id ) {
		$this->survey_id = intval( $survey_id );
	}

	/**
	 * Gets the survey ID stored (for backwards compatibility)
	 *
	 * @since 5.0.0
	 * @return int|false The ID of the survey stored or false
	 */
	public function get_id() {
		if ( $this->survey_id ) {
			return intval( $this->survey_id );
		} else {
			return false;
		}
	}

	/**
	 * Creates a new survey with the default settings
	 *
	 * @access public
	 * @since 3.7.1
	 * @param string $survey_name The name of the new survey.
	 * @return void
	 */
	public function create_survey( $survey_name ) {
		global $mlwiCodesurveyMaster;
		global $wpdb;
		$results = $wpdb->insert(
			$wpdb->prefix . 'mlw_surveyzes',
			array(
				'survey_name'                => $survey_name,
				'message_before'           => 'Welcome to your %survey_NAME%',
				'message_after'            => 'Thanks for submitting your response! You can edit this message on the "Results Pages" tab. <br>%CONTACT_ALL% <br>%QUESTIONS_ANSWERS%',
				'message_comment'          => 'Please fill in the comment box below.',
				'message_end_template'     => '',
				'user_email_template'      => '%QUESTIONS_ANSWERS%',
				'admin_email_template'     => '%QUESTIONS_ANSWERS%',
				'submit_button_text'       => 'Submit',
				'name_field_text'          => 'Name',
				'business_field_text'      => 'Business',
				'email_field_text'         => 'Email',
				'phone_field_text'         => 'Phone Number',
				'comment_field_text'       => 'Comments',
				'email_from_text'          => 'Wordpress',
				'question_answer_template' => '%QUESTION%<br /> Answer Provided: %USER_ANSWER%<br /> Correct Answer: %CORRECT_ANSWER%<br /> Comments Entered: %USER_COMMENTS%<br />',
				'leaderboard_template'     => '',
				'system'                   => 0,
				'randomness_order'         => 0,
				'loggedin_user_contact'    => 0,
				'show_score'               => 0,
				'send_user_email'          => 0,
				'send_admin_email'         => 0,
				'contact_info_location'    => 0,
				'user_name'                => 2,
				'user_comp'                => 2,
				'user_email'               => 2,
				'user_phone'               => 2,
				'admin_email'              => get_option( 'admin_email', 'Enter email' ),
				'comment_section'          => 1,
				'question_from_total'      => 0,
				'total_user_tries'         => 0,
				'total_user_tries_text'    => 'You are only allowed 1 try and have already submitted your survey.',
				'certificate_template'     => '',
				'social_media'             => 0,
				'social_media_text'        => 'I just scored %CORRECT_SCORE%% on %survey_NAME%!',
				'pagination'               => 0,
				'pagination_text'          => 'Next',
				'timer_limit'              => 0,
				'survey_stye'                => '',
				'question_numbering'       => 0,
				'survey_settings'            => '',
				'theme_selected'           => 'primary',
				'last_activity'            => current_time( 'mysql' ),
				'require_log_in'           => 0,
				'require_log_in_text'      => 'This survey is for logged in users only.',
				'limit_total_entries'      => 0,
				'limit_total_entries_text' => 'Unfortunately, this survey has a limited amount of entries it can recieve and has already reached that limit.',
				'scheduled_timeframe'      => '',
				'scheduled_timeframe_text' => '',
				'survey_views'               => 0,
				'survey_taken'               => 0,
				'deleted'                  => 0,
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%s',
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
				'%d',
				'%s',
				'%d',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
			)
		);
		if ( false !== $results ) {
			$new_survey = $wpdb->insert_id;
			$current_user = wp_get_current_user();
			$survey_post = array(
				'post_title'   => $survey_name,
				'post_content' => "[mlw_surveymaster survey=$new_survey]",
				'post_status'  => 'publish',
				'post_author'  => $current_user->ID,
				'post_type'    => 'survey',
			);
			$survey_post_id = wp_insert_post( $survey_post );
			add_post_meta( $survey_post_id, 'survey_id', $new_survey );

			$mlwiCodesurveyMaster->alertManager->newAlert(__('Your new survey or survey has been created successfully. To begin editing, click the Edit link.', 'icode-survey-master'), 'success');
			$mlwiCodesurveyMaster->audit_manager->new_audit( "New survey/Survey Has Been Created: $survey_name" );

			// Hook called after new survey or survey has been created. Passes survey_id to hook
			do_action('icode_survey_created', $new_survey);
		} else {
			$mlwiCodesurveyMaster->alertManager->newAlert(sprintf(__('There has been an error in this action. Please share this with the developer. Error Code: %s', 'icode-survey-master'), '0001'), 'error');
			$mlwiCodesurveyMaster->log_manager->add("Error 0001", $wpdb->last_error.' from '.$wpdb->last_query, 0, 'error');
		}
	}

	/**
	 * Deletes a survey with the given survey_id
	 *
	 * @access public
	 * @since 3.7.1
	 * @return void
	 */
	 public function delete_survey($survey_id, $survey_name)
	 {
	 	global $mlwiCodesurveyMaster;
		global $wpdb;
	 	$results = $wpdb->update(
 			$wpdb->prefix . "mlw_surveyzes",
 			array(
 				'deleted' => 1
 			),
 			array( 'survey_id' => $survey_id ),
 			array(
 				'%d'
 			),
 			array( '%d' )
 		);
 		$delete_question_results = $wpdb->update(
 			$wpdb->prefix . "mlw_questions",
 			array(
 				'deleted' => 1
 			),
 			array( 'survey_id' => $survey_id ),
 			array(
 				'%d'
 			),
 			array( '%d' )
 		);
		if ($results != false)
		{
			$my_query = new WP_Query( array('post_type' => 'survey', 'meta_key' => 'survey_id', 'meta_value' => $survey_id) );
			if( $my_query->have_posts() )
			{
			  while( $my_query->have_posts() )
				{
			    $my_query->the_post();
					$my_post = array(
				      'ID'           => get_the_ID(),
				      'post_status' => 'trash'
				  );
					wp_update_post( $my_post );
			  }
			}
			wp_reset_postdata();
			$mlwiCodesurveyMaster->alertManager->newAlert(__('Your survey or survey has been deleted successfully.', 'icode-survey-master'), 'success');
			$mlwiCodesurveyMaster->audit_manager->new_audit( "survey/Survey Has Been Deleted: $survey_name" );
		}
		else
		{
			$mlwiCodesurveyMaster->alertManager->newAlert(sprintf(__('There has been an error in this action. Please share this with the developer. Error Code: %s', 'icode-survey-master'), '0002'), 'error');
			$mlwiCodesurveyMaster->log_manager->add("Error 0002", $wpdb->last_error.' from '.$wpdb->last_query, 0, 'error');
		}

		// Hook called after survey or survey is deleted. Hook passes survey_id to function
		do_action('icode_survey_deleted', $survey_id);
	 }

	/**
	 * Edits the name of the survey with the given ID
	 *
	 * @access public
	 * @since 3.7.1
	 * @param int    $survey_id The ID of the survey.
	 * @param string $survey_name The new name of the survey.
	 * @return void
	 */
	public function edit_survey_name( $survey_id, $survey_name ) {
		global $mlwiCodesurveyMaster;
		global $wpdb;
		$results = $wpdb->update(
			$wpdb->prefix . 'mlw_surveyzes',
			array(
				'survey_name' => $survey_name,
			),
			array( 'survey_id' => $survey_id ),
			array(
				'%s',
			),
			array( '%d' )
		);
		if ( false !== $results ) {
			$mlwiCodesurveyMaster->alertManager->newAlert( __( 'The name of your survey or survey has been updated successfully.', 'icode-survey-master' ), 'success' );
			$mlwiCodesurveyMaster->audit_manager->new_audit( "survey/Survey Name Has Been Edited: $survey_name" );
		} else {
			$error = $wpdb->last_error;
			if ( empty( $error ) ) {
				$error = __( 'Unknown error', 'icode-survey-master' );
			}
			$mlwiCodesurveyMaster->alertManager->newAlert( __( 'An error occurred while trying to update the name of your survey or survey. Please try again.', 'icode-survey-master' ), 'error' );
			$mlwiCodesurveyMaster->log_manager->add( 'Error when updating survey name', "Tried {$wpdb->last_query} but got $error", 0, 'error' );
		}

		// Fires when the name of a survey/survey is edited.
		do_action( 'iCODE_survey_name_edited', $survey_id, $survey_name );

		// Legacy code.
		do_action( 'icode_survey_name_edited', $survey_id );
	}

	 /**
	 * Duplicates the survey with the given ID and gives new survey the given survey name
	 *
	 * @access public
	 * @since 3.7.1
	 * @return void
	 */
	 public function duplicate_survey($survey_id, $survey_name, $is_duplicating_questions)
	 {
	 	global $mlwiCodesurveyMaster;
		global $wpdb;

		$table_name = $wpdb->prefix . "mlw_surveyzes";
		$mlw_icode_duplicate_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE survey_id=%d", $survey_id ) );
		$results = $wpdb->insert(
				$table_name,
				array(
					'survey_name' => $survey_name,
					'message_before' => $mlw_icode_duplicate_data->message_before,
					'message_after' => $mlw_icode_duplicate_data->message_after,
					'message_comment' => $mlw_icode_duplicate_data->message_comment,
					'message_end_template' => $mlw_icode_duplicate_data->message_end_template,
					'user_email_template' => $mlw_icode_duplicate_data->user_email_template,
					'admin_email_template' => $mlw_icode_duplicate_data->admin_email_template,
					'submit_button_text' => $mlw_icode_duplicate_data->submit_button_text,
					'name_field_text' => $mlw_icode_duplicate_data->name_field_text,
					'business_field_text' => $mlw_icode_duplicate_data->business_field_text,
					'email_field_text' => $mlw_icode_duplicate_data->email_field_text,
					'phone_field_text' => $mlw_icode_duplicate_data->phone_field_text,
					'comment_field_text' => $mlw_icode_duplicate_data->comment_field_text,
					'email_from_text' => $mlw_icode_duplicate_data->email_from_text,
					'question_answer_template' => $mlw_icode_duplicate_data->question_answer_template,
					'leaderboard_template' => $mlw_icode_duplicate_data->leaderboard_template,
					'system' => $mlw_icode_duplicate_data->system,
					'randomness_order' => $mlw_icode_duplicate_data->randomness_order,
					'loggedin_user_contact' => $mlw_icode_duplicate_data->loggedin_user_contact,
					'show_score' => $mlw_icode_duplicate_data->show_score,
					'send_user_email' => $mlw_icode_duplicate_data->send_user_email,
					'send_admin_email' => $mlw_icode_duplicate_data->send_admin_email,
					'contact_info_location' => $mlw_icode_duplicate_data->contact_info_location,
					'user_name' => $mlw_icode_duplicate_data->user_name,
					'user_comp' => $mlw_icode_duplicate_data->user_comp,
					'user_email' => $mlw_icode_duplicate_data->user_email,
					'user_phone' => $mlw_icode_duplicate_data->user_phone,
					'admin_email' => get_option( 'admin_email', 'Enter email' ),
					'comment_section' => $mlw_icode_duplicate_data->comment_section,
					'question_from_total' => $mlw_icode_duplicate_data->question_from_total,
					'total_user_tries' => $mlw_icode_duplicate_data->total_user_tries,
					'total_user_tries_text' => $mlw_icode_duplicate_data->total_user_tries_text,
					'certificate_template' => $mlw_icode_duplicate_data->certificate_template,
					'social_media' => $mlw_icode_duplicate_data->social_media,
					'social_media_text' => $mlw_icode_duplicate_data->social_media_text,
					'pagination' => $mlw_icode_duplicate_data->pagination,
					'pagination_text' => $mlw_icode_duplicate_data->pagination_text,
					'timer_limit' => $mlw_icode_duplicate_data->timer_limit,
					'survey_stye' => $mlw_icode_duplicate_data->survey_stye,
					'question_numbering' => $mlw_icode_duplicate_data->question_numbering,
					'survey_settings' => $mlw_icode_duplicate_data->survey_settings,
					'theme_selected' => $mlw_icode_duplicate_data->theme_selected,
					'last_activity' => date("Y-m-d H:i:s"),
					'require_log_in' => $mlw_icode_duplicate_data->require_log_in,
					'require_log_in_text' => $mlw_icode_duplicate_data->require_log_in_text,
					'limit_total_entries' => $mlw_icode_duplicate_data->limit_total_entries,
					'limit_total_entries_text' => $mlw_icode_duplicate_data->limit_total_entries_text,
					'scheduled_timeframe' => $mlw_icode_duplicate_data->scheduled_timeframe,
					'scheduled_timeframe_text' => $mlw_icode_duplicate_data->scheduled_timeframe_text,
					'survey_views' => 0,
					'survey_taken' => 0,
					'deleted' => 0
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%s',
					'%d',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
				)
			);
		$mlw_new_id = $wpdb->insert_id;
		if ( false != $results ) {
			$current_user = wp_get_current_user();
			$survey_post = array(
				'post_title'    => $survey_name,
				'post_content'  => "[mlw_surveymaster survey=$mlw_new_id]",
				'post_status'   => 'publish',
				'post_author'   => $current_user->ID,
				'post_type' => 'survey'
			);
			$survey_post_id = wp_insert_post( $survey_post );
			add_post_meta( $survey_post_id, 'survey_id', $mlw_new_id );
			$mlwiCodesurveyMaster->alertManager->newAlert(__('Your survey or survey has been duplicated successfully.', 'icode-survey-master'), 'success');
			$mlwiCodesurveyMaster->audit_manager->new_audit( "New survey/Survey Has Been Created: $survey_name" );
			do_action('icode_survey_duplicated', $survey_id, $mlw_new_id);
		}
		else
		{
			$mlwiCodesurveyMaster->alertManager->newAlert(sprintf(__('There has been an error in this action. Please share this with the developer. Error Code: %s', 'icode-survey-master'), '0011'), 'error');
			$mlwiCodesurveyMaster->log_manager->add("Error 0011", $wpdb->last_error.' from '.$wpdb->last_query, 0, 'error');
		}
		if ($is_duplicating_questions)
		{
			$table_name = $wpdb->prefix."mlw_questions";
			$mlw_current_questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE deleted=0 AND survey_id=%d", $survey_id ) );
			foreach ($mlw_current_questions as $mlw_question)
			{
				$question_results = $wpdb->insert(
					$table_name,
					array(
						'survey_id' => $mlw_new_id,
						'question_name' => $mlw_question->question_name,
						'answer_array' => $mlw_question->answer_array,
						'answer_one' => $mlw_question->answer_one,
						'answer_one_points' => $mlw_question->answer_one_points,
						'answer_two' => $mlw_question->answer_two,
						'answer_two_points' => $mlw_question->answer_two_points,
						'answer_three' => $mlw_question->answer_three,
						'answer_three_points' => $mlw_question->answer_three_points,
						'answer_four' => $mlw_question->answer_four,
						'answer_four_points' => $mlw_question->answer_four_points,
						'answer_five' => $mlw_question->answer_five,
						'answer_five_points' => $mlw_question->answer_five_points,
						'answer_six' => $mlw_question->answer_six,
						'answer_six_points' => $mlw_question->answer_six_points,
						'correct_answer' => $mlw_question->correct_answer,
						'question_answer_info' => $mlw_question->question_answer_info,
						'comments' => $mlw_question->comments,
						'hints' => $mlw_question->hints,
						'question_order' => $mlw_question->question_order,
						'question_type_new' => $mlw_question->question_type_new,
						'question_settings' => $mlw_question->question_settings,
						'category' => $mlw_question->category,
						'deleted' => 0
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%s',
						'%s',
						'%d'
					)
				);
				if ($question_results == false)
				{
					$mlwiCodesurveyMaster->alertManager->newAlert(sprintf(__('There has been an error in this action. Please share this with the developer. Error Code: %s', 'icode-survey-master'), '0020'), 'error');
					$mlwiCodesurveyMaster->log_manager->add("Error 0020", $wpdb->last_error.' from '.$wpdb->last_query, 0, 'error');
				}
			}
		}
	}

	/**
	 * Retrieves setting store in survey_settings
	 *
	 * @deprecated 6.0.3 Use the get_survey_setting function in the pluginHelper object.
	 * @since 3.8.1
	 * @access public
	 * @param string $setting_name The slug of the setting.
	 * @return string The value of the setting
	 */
	public function get_setting( $setting_name ) {
		global $wpdb;
		$icode_settings_array = '';
		$icode_survey_settings = $wpdb->get_var( $wpdb->prepare( "SELECT survey_settings FROM " . $wpdb->prefix . "mlw_surveyzes" . " WHERE survey_id=%d", $this->survey_id ) );
		if ( is_serialized( $icode_survey_settings ) && is_array( @unserialize( $icode_survey_settings ) ) ) {
			$icode_settings_array = @unserialize( $icode_survey_settings );
		}
		if ( is_array( $icode_settings_array ) && isset( $icode_settings_array[ $setting_name ] ) ) {
			return $icode_settings_array[ $setting_name ];
		} else {
			return '';
		}

	}

	/**
	 * Updates setting stored in survey_settings
	 *
	 * @deprecated 6.0.3 Use the update_survey_setting function in the pluginHelper object.
	 * @since 3.8.1
	 * @access public
	 * @param string $setting_name The slug of the setting.
	 * @param mixed  $setting_value The value for the setting.
	 * @return bool True if update was successful
	 */
	public function update_setting( $setting_name, $setting_value ) {
		global $wpdb;
		$icode_settings_array = array();
		$icode_survey_settings = $wpdb->get_var( $wpdb->prepare( "SELECT survey_settings FROM " . $wpdb->prefix . "mlw_surveyzes" . " WHERE survey_id=%d", $this->survey_id ) );
		if (is_serialized($icode_survey_settings) && is_array(@unserialize($icode_survey_settings)))
		{
			$icode_settings_array = @unserialize($icode_survey_settings);
		}
		$icode_settings_array[$setting_name] = $setting_value;
		$icode_serialized_array = serialize($icode_settings_array);
		$results = $wpdb->update(
			$wpdb->prefix . "mlw_surveyzes",
			array(
			 	'survey_settings' => $icode_serialized_array
			),
			array( 'survey_id' => $this->survey_id ),
			array(
			 	'%s'
			),
			array( '%d' )
		);
		if ($results != false)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Deletes setting stored in survey_settings
	 *
	 * @deprecated 6.0.3
	 * @since 3.8.1
	 * @access public
	 * @return void
	 */
	public function delete_setting( $setting_name ) {
		global $wpdb;
		$icode_settings_array = array();
		$icode_survey_settings = $wpdb->get_var( $wpdb->prepare( "SELECT survey_settings FROM " . $wpdb->prefix . "mlw_surveyzes" . " WHERE survey_id=%d", $this->survey_id ) );
		if (is_serialized($icode_survey_settings) && is_array(@unserialize($icode_survey_settings)))
		{
			$icode_settings_array = @unserialize($icode_survey_settings);
		}
		if (is_array($icode_settings_array) && isset($icode_settings_array[$setting_name]))
		{
			unset($icode_settings_array[$setting_name]);
		}
		$icode_serialized_array = serialize($icode_settings_array);
		$results = $wpdb->update(
			$wpdb->prefix . "mlw_surveyzes",
			array(
			 	'survey_settings' => $icode_serialized_array
			),
			array( 'survey_id' => $this->survey_id ),
			array(
			 	'%s'
			),
			array( '%d' )
		);
	}
}