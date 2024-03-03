<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This handles all of the settings data for each individual survey.
 *
 * @since 5.0.0
 */
class iCODE_survey_Settings {

  /**
   * ID of the survey
   *
   * @var int
   * @since 5.0.0
   */
  private $survey_id;

  /**
   * The settings for the survey
   *
   * @var array
   * @since 5.0.0
   */
  private $settings;

  /**
   * The fields that have been registered
   *
   * @var array
   * @since 5.0.0
   */
  private $registered_fields;

  /**
   * Prepares the settings for the supplied survey
   *
   * @since 5.0.0
   * @param int $survey_id the ID of the survey that we are handling the settings data for
   */
  public function prepare_survey( $survey_id ) {
    $this->survey_id = intval( $survey_id );
    $this->load_settings();
  }


  /**
   * Registers a setting be shown on the Options or Text tab
   *
   * @since 5.0.0
   * @param array $field_array An array of the components for the settings field
   */
  public function register_setting( $field_array, $section = 'survey_options' ) {

    /*
      Example field array
      $field_array = array(
        'id' => 'system',
        'label' => 'Which system is this survey graded on?',
        'type' => 'text',
        'options' => array(
          array(
            'label' => '',
            'value' => ''
          )
        ),
        'variables' => array(
          ''
        ),
        'default' => ''
      );
    */

    // Adds field to registered fields
    $this->registered_fields[ $section ][] = $field_array;

  }

  /**
   * Retrieves the registered setting fields
   *
   * @since 5.0.0
   * @param string $section The section whose fields that are being retrieved
   * @return array All the fields registered the the section provided
   */
  public function load_setting_fields( $section = 'survey_options' ) {

    // Checks if section exists in registered fields and returns it if it does
    if ( isset( $this->registered_fields[ $section ] ) ) {
      return $this->registered_fields[ $section ];
    } else {
      return false;
    }
  }

  /**
   * Retrieves a setting value from a section based on name of section and setting
   *
   * @since 5.0.0
   * @param string $section The name of the section the setting is registered in
   * @param string $setting The name of the setting whose value we need to retrieve
   * @param mixed $default What we need to return if no setting exists with given $setting
   * @return $mixed Value set for $setting or $default if setting does not exist
   */
  public function get_section_setting( $section, $setting, $default = false ) {

    // Return if section or setting is empty
    if ( empty( $section ) || empty( $setting ) ) {
      return $default;
    }

    // Get settings in section
    $section_settings = $this->get_setting( $section );

    // Return default if section not found
    if ( ! $section_settings ) {
      return $default;
    }

    // Maybe unserailize
    $section_settings = maybe_unserialize( $section_settings );

    // Check if setting exists
    if ( isset( $section_settings[ $setting ] ) ) {

      // Try to unserialize it and then return it
      return maybe_unserialize( $section_settings[ $setting ] );
    } else {

      // Return the default if no setting exists
      return $default;
    }
  }

  /**
   * Retrieves setting value based on name of setting
   *
   * @since 5.0.0
   * @param string $setting The name of the setting whose value we need to retrieve
   * @param mixed $default What we need to return if no setting exists with given $setting
   * @return $mixed Value set for $setting or $default if setting does not exist
   */
  public function get_setting( $setting, $default = false ) {

    global $mlwiCodesurveyMaster;

    // Return if empty
    if ( empty( $setting ) ) {
      return false;
    }

    // Check if ID is not set, for backwards compatibility
    if ( ! $this->survey_id ) {
      $survey_id = $mlwiCodesurveyMaster->surveyCreator->get_id();

      // If get_id doesn't work, return false
      if ( ! $survey_id ) {
        return false;
      } else {
        $this->prepare_survey( $survey_id );
      }
    }

    // Check if setting exists
    if ( isset( $this->settings[ $setting ] ) ) {

      // Try to unserialize it and then return it
      return maybe_unserialize( $this->settings[ $setting ] );
    } else {

      // Return the default if no setting exists
      return $default;
    }
  }

	/**
	 * Updates a settings value, adding it if it didn't already exist
	 *
	 * @since 5.0.0
	 * @param string $setting The name of the setting whose value we need to retrieve.
	 * @param mixed  $value The value that needs to be stored for the setting.
	 * @return bool True if successful or false if fails
	 */
	public function update_setting( $setting, $value ) {

		global $mlwiCodesurveyMaster;

		// Return if empty.
		if ( empty( $setting ) ) {
			$mlwiCodesurveyMaster->log_manager->add( 'Error when updating setting', 'Setting was empty with value equal to ' . print_r( $value, true ), 0, 'error' );
			return false;
		}

		// Check if ID is not set, for backwards compatibility.
		if ( ! $this->survey_id ) {
			$survey_id = $mlwiCodesurveyMaster->surveyCreator->get_id();

			// If get_id doesn't work, return false.
			if ( ! $survey_id ) {
				$mlwiCodesurveyMaster->log_manager->add( 'Error when updating setting', 'survey ID was not found', 0, 'error' );
				return false;
			} else {
				$this->prepare_survey( $survey_id );
			}
		}

		$old_value = $this->get_setting( $setting );

		// If the old value and new value are the same, return false.
		if ( $value === $old_value ) {
			return true;
		}

		// Try to serialize the value.
		$serialized_value = maybe_serialize( $value );

		// Set the new value.
		$this->settings[ $setting ] = $serialized_value;

		// Update the database.
		global $wpdb;
		$serialized_settings = serialize( $this->settings );
		$results = $wpdb->update(
			$wpdb->prefix . 'mlw_surveyzes',
			array( 'survey_settings' => $serialized_settings ),
			array( 'survey_id' => $this->survey_id ),
			array( '%s' ),
			array( '%d' )
		);

		if ( false === $results ) {
			$mlwiCodesurveyMaster->log_manager->add( 'Error when updating setting', $wpdb->last_error . ' from ' . $wpdb->last_query, 0, 'error' );
			return false;
		} else {
			return true;
		}
	}

  /**
   * Loads the settings for the survey
   *
   * @since 5.0.0
   */
  private function load_settings() {

    global $wpdb;
		$settings_array = array();

    // Loads the settings from the database
		$settings = $wpdb->get_var( $wpdb->prepare( "SELECT survey_settings FROM {$wpdb->prefix}mlw_surveyzes WHERE survey_id=%d", $this->survey_id ) );

    // Unserializes array
		if ( is_serialized( $settings ) && is_array( @unserialize( $settings ) ) ) {
			$settings_array = @unserialize( $settings );
		}

    // If the value is not an array, create an empty array
		if ( ! is_array( $settings_array ) ) {
      $settings_array = array();
		}

    // If some options are missing
    if ( ! isset( $settings_array['survey_options'] ) || ! isset( $settings_array["survey_text"] ) || ! isset( $settings_array["survey_leaderboards"] ) ) {

      // Load the old options system
      $survey_options = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}mlw_surveyzes WHERE survey_id=%d LIMIT 1", $this->survey_id ) );

      // If no leadboard is present
      if ( ! isset( $settings_array["survey_leaderboards"] ) ) {

        $settings_array["survey_leaderboards"] = serialize( array(
          'template' => $survey_options->leaderboard_template
        ) );
      }

      // If no options are present
      if ( ! isset( $settings_array['survey_options'] ) ) {

        // Sets up older scheduled timeframe settings
        if ( is_serialized( $survey_options->scheduled_timeframe) && is_array( @unserialize( $survey_options->scheduled_timeframe ) ) ) {
          $scheduled_timeframe = @unserialize( $survey_options->scheduled_timeframe );
        } else {
          $scheduled_timeframe = array(
            'start' => '',
            'end' => ''
          );
        }

        // Prepares new survey_options section's settings
        $settings_array['survey_options'] = serialize( array(
          'system' => $survey_options->system,
  				'loggedin_user_contact' => $survey_options->loggedin_user_contact,
  				'contact_info_location' => $survey_options->contact_info_location,
  				'user_name' => $survey_options->user_name,
  				'user_comp' => $survey_options->user_comp,
  				'user_email' => $survey_options->user_email,
  				'user_phone' => $survey_options->user_phone,
  				'comment_section' => $survey_options->comment_section,
  				'randomness_order' => $survey_options->randomness_order,
  				'question_from_total' => $survey_options->question_from_total,
  				'total_user_tries' => $survey_options->total_user_tries,
  				'social_media' => $survey_options->social_media,
  				'pagination' => $survey_options->pagination,
  				'timer_limit' => $survey_options->timer_limit,
  				'question_numbering' => $survey_options->question_numbering,
  				'require_log_in' => $survey_options->require_log_in,
  				'limit_total_entries' => $survey_options->limit_total_entries,
          'scheduled_time_start'=> $scheduled_timeframe["start"],
  				'scheduled_time_end' => $scheduled_timeframe["end"],
  				'disable_answer_onselect' => $survey_options->disable_answer_onselect,
          'disable_tab_page' => $survey_options->disable_tab_page,
  				'ajax_show_correct' => $survey_options->ajax_show_correct
        ) );
      }

      // If no text is present
      if ( ! isset( $settings_array["survey_text"] ) ) {

        // Sets up older pagination text
        if ( is_serialized( $survey_options->pagination_text) && is_array( @unserialize( $survey_options->pagination_text ) ) ) {
          $pagination_text = @unserialize( $survey_options->pagination_text );
        } else {
          $pagination_text = array(
            __( 'Previous', 'icode-survey-master' ),
            __( 'Next', 'icode-survey-master' )
          );
        }

        // Sets up older social sharing text
        if ( is_serialized( $survey_options->social_media_text) && is_array( @unserialize( $survey_options->social_media_text ) ) ) {
          $social_media_text = @unserialize( $survey_options->social_media_text );
        } else {
          $social_media_text = array(
            'twitter' => $survey_options->social_media_text,
            'facebook' => $survey_options->social_media_text

          );
        }

        // Prepares new survey_text section's settings
        $settings_array["survey_text"] = serialize( array(
          'message_before' => $survey_options->message_before,
  				'message_comment' => $survey_options->message_comment,
  				'message_end_template' => $survey_options->message_end_template,
  				'comment_field_text' => $survey_options->comment_field_text,
  				'question_answer_template' => $survey_options->question_answer_template,
  				'submit_button_text' => $survey_options->submit_button_text,
  				'name_field_text' => $survey_options->name_field_text,
  				'business_field_text' => $survey_options->business_field_text,
  				'email_field_text' => $survey_options->email_field_text,
  				'phone_field_text' => $survey_options->phone_field_text,
  				'total_user_tries_text' => $survey_options->total_user_tries_text,
  				'twitter_sharing_text' => $social_media_text["twitter"],
          'facebook_sharing_text' => $social_media_text["facebook"],
  				'previous_button_text' => $pagination_text[0],
          'next_button_text' => $pagination_text[1],
  				'require_log_in_text' => $survey_options->require_log_in_text,
  				'limit_total_entries_text' => $survey_options->limit_total_entries_text,
  				'scheduled_timeframe_text' => $survey_options->scheduled_timeframe_text
        ) );
      }

      // Update new settings system
      $serialized_array = serialize( $settings_array );
      $results = $wpdb->update(
  			$wpdb->prefix . "mlw_surveyzes",
  			array( 'survey_settings' => $serialized_array ),
  			array( 'survey_id' => $this->survey_id ),
  			array( '%s' ),
  			array( '%d' )
  		);
    }

    // Cycle through registered settings
    $registered_fields = $this->registered_fields;
    foreach ( $registered_fields as $section => $fields ) {

      // Check if section exists in settings and, if not, set it to empty array
      if ( ! isset( $settings_array[ $section ] ) ) {
        $settings_array[ $section ] = array();
      }

      $unserialized_section = unserialize( $settings_array[ $section ] );

      // Cycle through each setting in section
      foreach ( $fields as $field ) {

        // Check if setting exists in section settings and, if not, set it to the default
        if ( ! isset( $unserialized_section[ $field["id"] ] ) ) {
          $unserialized_section[ $field["id"] ] = $field["default"];
        }
      }

      $settings_array[ $section ] = serialize( $unserialized_section );
    }

    $this->settings = $settings_array;
  }

  /**
   * Loads the old object model of options for backwards compatibility
   *
   * @since 5.0.0
   */
  public function get_survey_options() {
    global $wpdb;

    // Load the old options system
    $survey_options = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}mlw_surveyzes WHERE survey_id=%d LIMIT 1", $this->survey_id ) );

    // Merge all settings into old object
    $survey_override = array_merge( (array) $survey_options, $this->get_setting( 'survey_leaderboards' ), $this->get_setting( 'survey_options' ), $this->get_setting( 'survey_text' ) );

    // Return as old object model
    return (object) $survey_override;
  }
}
?>