<?php
/**
 * File for the icodesurveyManager class
 *
 * @package iCODE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class generates the contents of the survey shortcode
 *
 * @since 4.0.0
 */
class icodesurveyManager {

	/**
	 * Main Construct Function
	 *
	 * Call functions within class
	 *
	 * @since 4.0.0
	 * @uses icodesurveyManager::add_hooks() Adds actions to hooks and filters
	 * @return void
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add Hooks
	 *
	 * Adds functions to relavent hooks and filters
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function add_hooks() {
		add_shortcode( 'mlw_surveymaster', array( $this, 'display_shortcode' ) );
		add_shortcode( 'iCODE', array( $this, 'display_shortcode' ) );
		add_action( 'wp_ajax_icode_process_survey', array( $this, 'ajax_submit_results' ) );
		add_action( 'wp_ajax_nopriv_icode_process_survey', array( $this, 'ajax_submit_results' ) );
	}

	/**
	 * Generates Content For survey Shortcode
	 *
	 * Generates the content for the [mlw_surveymaster] shortcode
	 *
	 * @since 4.0.0
	 * @param array $atts The attributes passed from the shortcode.
	 * @uses icodesurveyManager:load_questions() Loads questions
	 * @uses icodesurveyManager:create_answer_array() Prepares answers
	 * @uses icodesurveyManager:display_survey() Generates and prepares survey page
	 * @uses icodesurveyManager:display_results() Generates and prepares results page
	 * @return string The content for the shortcode
	 */
	public function display_shortcode( $atts ) {
		extract(shortcode_atts(array(
			'survey'            => 0,
			'question_amount' => 0,
		), $atts));

		ob_start();

		global $wpdb;
		global $mlwiCodesurveyMaster;
		global $icode_allowed_visit;
		global $icode_json_data;
		$icode_json_data = array();
		$icode_allowed_visit = true;
		$mlwiCodesurveyMaster->pluginHelper->prepare_survey( $survey );
		$question_amount = intval( $question_amount );

		// Legacy variable.
		global $mlw_icode_survey;
		$mlw_icode_survey = $survey;

		$return_display = '';
		$icode_survey_options = $mlwiCodesurveyMaster->survey_settings->get_survey_options();

		// If survey options isn't found, stop function.
		if ( is_null( $icode_survey_options ) || empty( $icode_survey_options->survey_name ) ) {
			return __( 'It appears that this survey is not set up correctly', 'icode-survey-master' );
		}
		// Loads survey Template.
          $return_display .= '<style type="text/css">' . $icode_survey_options->survey_stye . '</style>';
			wp_enqueue_style( 'icode_survey_style', plugins_url( '../../css/icode_survey.css', __FILE__ ) );
		// The survey_stye is misspelled because it has always been misspelled and fixing it would break many sites :(.
		// if ( 'default' == $icode_survey_options->theme_selected ) {
			
		// } else {
		// 	$registered_template = $mlwiCodesurveyMaster->pluginHelper->get_survey_templates( $icode_survey_options->theme_selected );
		// 	// Check direct file first, then check templates folder in plugin, then check templates file in theme.
		// 	// If all fails, then load custom styling instead.
		// 	if ( $registered_template && file_exists( $registered_template['path'] ) ) {
		// 		wp_enqueue_style( 'icode_survey_template', $registered_template['path'], array(), $mlwiCodesurveyMaster->version );
		// 	} elseif ( $registered_template && file_exists( plugin_dir_path( __FILE__ ) . '../../templates/' . $registered_template['path'] ) ) {
		// 		wp_enqueue_style( 'icode_survey_template', plugins_url( '../../templates/' . $registered_template['path'], __FILE__ ), array(), $mlwiCodesurveyMaster->version );
		// 	} elseif ( $registered_template && file_exists( get_stylesheet_directory_uri() . '/templates/' . $registered_template['path'] ) ) {
		// 		wp_enqueue_style( 'icode_survey_template', get_stylesheet_directory_uri() . '/templates/' . $registered_template['path'], array(), $mlwiCodesurveyMaster->version );
		// 	} else {
		// 		echo "<style type='text/css'>{$icode_survey_options->survey_stye}</style>";
		// 	}
		//}

		// Starts to prepare variable array for filters.
		$icode_array_for_variables = array(
			'survey_id'     => $icode_survey_options->survey_id,
			'survey_name'   => $icode_survey_options->survey_name,
			'survey_system' => $icode_survey_options->system,
			'user_ip'     => $this->get_user_ip(),
		);

		$return_display .= "<script>
			if (window.icode_survey_data === undefined) {
				window.icode_survey_data = new Object();
			}
		</script>";
		$icode_json_data = array(
			'survey_id'           => $icode_array_for_variables['survey_id'],
			'survey_name'         => $icode_array_for_variables['survey_name'],
			'disable_answer'    => $icode_survey_options->disable_answer_onselect,
			'disable_tab'    => $icode_survey_options->disable_tab_page,
			'ajax_show_correct' => $icode_survey_options->ajax_show_correct,
			'progress_bar'      => $icode_survey_options->progress_bar,
		);

		$return_display = apply_filters( 'icode_begin_shortcode', $return_display, $icode_survey_options, $icode_array_for_variables );

		// Checks if we should be showing survey or results page.
		if ( $icode_allowed_visit && ! isset( $_POST["complete_survey"] ) && ! empty( $icode_survey_options->survey_name ) ) {
			$return_display .= $this->display_survey( $icode_survey_options, $icode_array_for_variables, $question_amount );
		} elseif ( isset( $_POST["complete_survey"] ) && 'confirmation' == $_POST["complete_survey"] && $_POST["icode_survey_id"] == $icode_array_for_variables["survey_id"] ) {
			$return_display .= $this->display_results( $icode_survey_options, $icode_array_for_variables );
		}

		$icode_filtered_json = apply_filters( 'icode_json_data', $icode_json_data, $icode_survey_options, $icode_array_for_variables );

		$return_display .= '<script>
			window.icode_survey_data["' . $icode_json_data["survey_id"] . '"] = ' . json_encode( $icode_json_data ) . '
		</script>';

		$return_display .= ob_get_clean();
		$return_display = apply_filters( 'icode_end_shortcode', $return_display, $icode_survey_options, $icode_array_for_variables );
		return $return_display;
	}

	/**
	 * Loads Questions
	 *
	 * Retrieves the questions from the database
	 *
	 * @since 4.0.0
	 * @param int   $survey_id The id for the survey.
	 * @param array $survey_options The database row for the survey.
	 * @param bool  $is_survey_page If the page being loaded is the survey page or not.
	 * @param int   $question_amount The amount of questions entered using the shortcode attribute.
	 * @return array The questions for the survey
	 * @deprecated 5.2.0 Use new class: iCODE_Questions instead
	 */
	public function load_questions( $survey_id, $survey_options, $is_survey_page, $question_amount = 0 ) {

		// Prepare variables.
		global $wpdb;
		global $mlwiCodesurveyMaster;
		$questions = array();
		$order_by_sql = 'ORDER BY question_order ASC';
		$limit_sql = '';

		// Checks if the questions should be randomized.
		if ( 1 == $survey_options->randomness_order || 2 == $survey_options->randomness_order ) {
			$order_by_sql = 'ORDER BY rand()';
		}

		// Check if we should load all questions or only a selcted amount.
		if ( $is_survey_page && ( 0 != $survey_options->question_from_total || 0 !== $question_amount ) ) {
			if ( 0 !== $question_amount ) {
				$limit_sql = " LIMIT $question_amount";
			} else {
				$limit_sql = ' LIMIT ' . intval( $survey_options->question_from_total );
			}
		}

		// If using newer pages system from 5.2.
		$pages = $mlwiCodesurveyMaster->pluginHelper->get_survey_setting( 'pages', array() );
		// Get all question IDs needed.
		$total_pages = count( $pages );
		if ( $total_pages > 0 ) {
			for ( $i = 0; $i < $total_pages; $i++ ) {
				foreach ( $pages[ $i ] as $question ) {
					$question_ids[] = intval( $question );
				}
			}
			$question_sql = implode( ', ', $question_ids );
			$questions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}mlw_questions WHERE question_id IN ($question_sql) " . $order_by_sql . $limit_sql );
			
			// If we are not using randomization, we need to put the questions in the order of the new question editor.
			// If a user has saved the pages in the question editor but still uses the older pagination options
			// Then they will make it here. So, we need to order the questions based on the new editor.
			if ( 1 != $survey_options->randomness_order && 2 != $survey_options->randomness_order ) {
				$ordered_questions = array();
				foreach ( $questions as $question ) {
					$key = array_search( $question->question_id, $question_ids );
					if ( false !== $key ) {
						$ordered_questions[ $key ] = $question;
					}
				}
				ksort( $ordered_questions );
				$questions = $ordered_questions;
			}
		} else {
			$questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "mlw_questions WHERE survey_id=%d AND deleted=0 " . $order_by_sql . $limit_sql, $survey_id ) );
		}

		// Returns an array of all the loaded questions.
		return $questions;
	}

	/**
	 * Prepares Answers
	 *
	 * Prepares or creates the answer array for the survey
	 *
	 * @since 4.0.0
	 * @param array $questions The questions for the survey.
	 * @param bool  $is_ajax Pass true if this is an ajax call.
	 * @return array The answers for the survey
	 * @deprecated 5.2.0 Use new class: iCODE_Questions instead
	 */
	public function create_answer_array( $questions, $is_ajax = false ) {

		// Load and prepare answer arrays.
		$mlw_icode_answer_arrays = array();
		$question_list = array();
		foreach ( $questions as $mlw_question_info ) {
			$question_list[ $mlw_question_info->question_id ] = get_object_vars( $mlw_question_info );
			if ( is_serialized( $mlw_question_info->answer_array ) && is_array( @unserialize( $mlw_question_info->answer_array ) ) ) {
				$mlw_icode_answer_array_each = @unserialize( $mlw_question_info->answer_array );
				$mlw_icode_answer_arrays[ $mlw_question_info->question_id ] = $mlw_icode_answer_array_each;
				$question_list[ $mlw_question_info->question_id ]["answers"] = $mlw_icode_answer_array_each;
			} else {
				$mlw_answer_array_correct = array(0, 0, 0, 0, 0, 0);
				$mlw_answer_array_correct[$mlw_question_info->correct_answer-1] = 1;
				$mlw_icode_answer_arrays[$mlw_question_info->question_id] = array(
					array($mlw_question_info->answer_one, $mlw_question_info->answer_one_points, $mlw_answer_array_correct[0]),
					array($mlw_question_info->answer_two, $mlw_question_info->answer_two_points, $mlw_answer_array_correct[1]),
					array($mlw_question_info->answer_three, $mlw_question_info->answer_three_points, $mlw_answer_array_correct[2]),
					array($mlw_question_info->answer_four, $mlw_question_info->answer_four_points, $mlw_answer_array_correct[3]),
					array($mlw_question_info->answer_five, $mlw_question_info->answer_five_points, $mlw_answer_array_correct[4]),
					array($mlw_question_info->answer_six, $mlw_question_info->answer_six_points, $mlw_answer_array_correct[5]));
					$question_list[$mlw_question_info->question_id]["answers"] = $mlw_icode_answer_arrays[$mlw_question_info->question_id];
			}
		}
		if ( ! $is_ajax ) {
			global $icode_json_data;
			$icode_json_data["question_list"] = $question_list;
		}
		return $mlw_icode_answer_arrays;
	}

	/**
	 * Generates Content survey Page
	 *
	 * Generates the content for the survey page part of the shortcode
	 *
	 * @since 4.0.0
	 * @param array $options The database row of the survey.
	 * @param array $survey_data The array of results for the survey.
	 * @param int $question_amount The number of questions to load for survey.
	 * @uses icodesurveyManager:display_begin_section() Creates display for beginning section
	 * @uses icodesurveyManager:display_questions() Creates display for questions
	 * @uses icodesurveyManager:display_comment_section() Creates display for comment section
	 * @uses icodesurveyManager:display_end_section() Creates display for end section
	 * @return string The content for the survey page section
	 */
	public function display_survey( $options, $survey_data, $question_amount ) {

		global $icode_allowed_visit;
		global $mlwiCodesurveyMaster;
		$survey_display = '';
		$survey_display = apply_filters( 'icode_begin_survey', $survey_display, $options, $survey_data );
		if ( ! $icode_allowed_visit ) {
			return $survey_display;
		}
		wp_enqueue_script( 'json2' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_style( 'jquery-redmond-theme', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css' );

		global $icode_json_data;
		$icode_json_data['error_messages'] = array(
			'email' => $options->email_error_text,
			'number' => $options->number_error_text,
			'incorrect' => $options->incorrect_error_text,
			'empty' => $options->empty_error_text,
		);

		wp_enqueue_script( 'progress-bar', plugins_url( '../../js/progressbar.min.js', __FILE__ ) );
		wp_enqueue_script( 'iCODE_survey', plugins_url( '../../js/iCODE-survey.js', __FILE__ ), array( 'wp-util', 'underscore', 'jquery', 'jquery-ui-tooltip', 'progress-bar' ), $mlwiCodesurveyMaster->version );
		wp_localize_script( 'iCODE_survey', 'icode_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'math_jax', '//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML' );

		global $icode_total_questions;
		$icode_total_questions = 0;
		global $mlw_icode_section_count;
		$mlw_icode_section_count = 0;

		$survey_display .= "<div class='iCODE-survey-container icode_survey_container mlw_icode_survey'>";
		$survey_display .= "<form name='surveyForm{$survey_data['survey_id']}' id='surveyForm{$survey_data['survey_id']}' action='' method='post' class='iCODE-survey-form icode_survey_form mlw_survey_form' novalidate >";
		$survey_display .= "<div name='mlw_error_message' id='mlw_error_message' class='iCODE-error-message icode_error_message_section'></div>";
		$survey_display .= "<span id='mlw_top_of_survey'></span>";
		$survey_display = apply_filters( 'icode_begin_survey_form', $survey_display, $options, $survey_data );

		// If deprecated pagination setting is not used, use new system...
		$pages = $mlwiCodesurveyMaster->pluginHelper->get_survey_setting( 'pages', array() );
		if ( 0 == $options->randomness_order && 0 == $options->question_from_total && 0 == $options->pagination && 0 !== count( $pages ) ) {
			$survey_display .= $this->display_pages( $options, $survey_data );
		} else {
			// ... else, use older system.
			$questions = $this->load_questions( $survey_data['survey_id'], $options, true, $question_amount );
			$answers = $this->create_answer_array( $questions );
			$survey_display .= $this->display_begin_section( $options, $survey_data );
			$survey_display = apply_filters( 'icode_begin_survey_questions', $survey_display, $options, $survey_data );
			$survey_display .= $this->display_questions( $options, $questions, $answers );
			$survey_display = apply_filters( 'icode_before_comment_section', $survey_display, $options, $survey_data );
			$survey_display .= $this->display_comment_section( $options, $survey_data );
			$survey_display = apply_filters( 'icode_after_comment_section', $survey_display, $options, $survey_data );
			$survey_display .= $this->display_end_section( $options, $survey_data );
		}

		$survey_display .= "<div name='mlw_error_message_bottom' id='mlw_error_message_bottom' class='iCODE-error-message icode_error_message_section'></div>";
		$survey_display .= "<input type='hidden' name='total_questions' id='total_questions' value='$icode_total_questions'/>";
		$survey_display .= "<input type='hidden' name='timer' id='timer' value='0'/>";
		$survey_display .= "<input type='hidden' class='icode_survey_id' name='icode_survey_id' id='icode_survey_id' value='{$survey_data['survey_id']}'/>";
		$survey_display .= "<input type='hidden' name='complete_survey' value='confirmation' />";
		$survey_display = apply_filters( 'icode_end_survey_form', $survey_display, $options, $survey_data );
		$survey_display .= '</form>';
		$survey_display .= '</div>';

		$survey_display = apply_filters( 'icode_end_survey', $survey_display, $options, $survey_data );
		return $survey_display;
	}

	/**
	 * Creates the pages of content for the survey/survey
	 *
	 * @since 5.2.0
	 * @param array $options The settings for the survey.
	 * @param array $survey_data The array of survey data.
	 * @return string The HTML for the pages
	 */
	public function display_pages( $options, $survey_data ) {
		global $mlwiCodesurveyMaster;
		global $icode_json_data;
		ob_start();
		$pages = $mlwiCodesurveyMaster->pluginHelper->get_survey_setting( 'pages', array() );
		$questions = iCODE_Questions::load_questions_by_pages( $options->survey_id );
		$question_list = '';
		$contact_fields = iCODE_Contact_Manager::load_fields();
		if ( count( $pages ) > 1 && ( ! empty( $options->message_before ) || ( 0 == $options->contact_info_location && $contact_fields ) ) ) {
			$icode_json_data['first_page'] = true;
			$message_before = wpautop( htmlspecialchars_decode( $options->message_before, ENT_QUOTES ) );
			$message_before = apply_filters( 'mlw_icode_template_variable_survey_page', $message_before, $survey_data );
			?>
			<section class="iCODE-page">
				<div class="survey_section survey_begin">
					<div class='iCODE-before-message mlw_icode_message_before'><?php echo $message_before; ?></div>
					<?php
					if ( 0 == $options->contact_info_location ) {
						echo iCODE_Contact_Manager::display_fields( $options );
					}
					?>
				</div>
			</section>
			<?php
		}

		// If there is only one page.
		if ( 1 == count( $pages ) ) {
			?>
			<section class="iCODE-page">
				<?php
				if ( ! empty( $options->message_before ) || ( 0 == $options->contact_info_location && $contact_fields ) ) {
					$icode_json_data['first_page'] = false;
					$message_before = wpautop( htmlspecialchars_decode( $options->message_before, ENT_QUOTES ) );
					$message_before = apply_filters( 'mlw_icode_template_variable_survey_page', $message_before, $survey_data );
					?>
					<div class="survey_section survey_begin">
						<div class='iCODE-before-message mlw_icode_message_before'><?php echo $message_before; ?></div>
						<?php
						if ( 0 == $options->contact_info_location ) {
							echo iCODE_Contact_Manager::display_fields( $options );
						}
						?>
					</div>
					<?php
				}
				foreach ( $pages[0] as $question_id ) {
					$question_list .= $question_id . 'Q';
					$question = $questions[ $question_id ];
					?>
					<div class='survey_section question-section-id-<?php echo esc_attr( $question_id ); ?>'>
						<?php 
						echo $mlwiCodesurveyMaster->pluginHelper->display_question( $question['question_type_new'], $question_id, $options );
						if ( 0 == $question['comments'] ) {
							echo "<input type='text' class='iCODE-question-comment iCODE-question-comment-small mlw_icode_question_comment' x-webkit-speech id='mlwComment$question_id' name='mlwComment$question_id' value='" . esc_attr( htmlspecialchars_decode( $options->comment_field_text, ENT_QUOTES ) ) . "' onclick='icodeClearField(this)'/>";
						}
						if ( 2 == $question['comments'] ) {
							echo "<textarea class='iCODE-question-comment iCODE-question-comment-large mlw_icode_question_comment' id='mlwComment$question_id' name='mlwComment$question_id' onclick='icodeClearField(this)'>" . htmlspecialchars_decode( $options->comment_field_text, ENT_QUOTES ) . "</textarea>";
						}
						// Checks if a hint is entered.
						if ( ! empty( $question['hints'] ) ) {
							echo '<div title="' . esc_attr( htmlspecialchars_decode( $question['hints'], ENT_QUOTES ) ) . '" class="iCODE-hint iCODE_hint mlw_icode_hint_link">' . $options->hint_text . '</div>';
						}
						?>
					</div>
					<?php
				}
				if ( 0 == $options->comment_section ) {
					$message_comments = wpautop( htmlspecialchars_decode( $options->message_comment, ENT_QUOTES ) );
					$message_comments = apply_filters( 'mlw_icode_template_variable_survey_page', $message_comments, $survey_data );
					?>
					<div class="survey_section survey_begin">
						<label for='mlwsurveyComments' class='iCODE-comments-label mlw_icode_comment_section_text'><?php echo $message_comments; ?></label>
						<textarea id='mlwsurveyComments' name='mlwsurveyComments' class='iCODE-comments icode_comment_section'></textarea>
					</div>
					<?php
				}
				if ( ! empty( $options->message_end_template ) || ( 1 == $options->contact_info_location && $contact_fields ) ) {
					$message_after = wpautop( htmlspecialchars_decode( $options->message_end_template, ENT_QUOTES ) );
					$message_after = apply_filters( 'mlw_icode_template_variable_survey_page', $message_after, $survey_data );
					?>
					<div class="survey_section active survey_section_send_mail">
						<div class='iCODE-after-message mlw_icode_message_end'><?php echo $message_after; ?></div>
						<?php
						if ( 1 == $options->contact_info_location ) {
							echo iCODE_Contact_Manager::display_fields( $options );
						}
						?>
					</div>
					<?php
				}
				?>
			</section>
			<?php
		} else {
			$icode_survey_options = $mlwiCodesurveyMaster->survey_settings->get_survey_options();
			if($icode_survey_options->disable_tab_page){
				$page_class='iCODE-page-2';
			}else{
				$page_class='iCODE-page';
			}
			foreach ( $pages as $page ) {
				?>
				<section class="<?php echo $page_class; ?>">
					<?php
					foreach ( $page as $question_id ) {
						$question_list .= $question_id . 'Q';
						$question = $questions[ $question_id ];
						?>
						<div class='survey_section question-section-id-<?php echo esc_attr( $question_id ); ?>'>
							<?php 
							echo $mlwiCodesurveyMaster->pluginHelper->display_question( $question['question_type_new'], $question_id, $options );
							if ( 0 == $question['comments'] ) {
								echo "<input type='text' class='iCODE-question-comment iCODE-question-comment-small mlw_icode_question_comment' x-webkit-speech id='mlwComment$question_id' name='mlwComment$question_id' value='" . esc_attr( htmlspecialchars_decode( $options->comment_field_text, ENT_QUOTES ) ) . "' onclick='icodeClearField(this)'/>";
							}
							if ( 2 == $question['comments'] ) {
								echo "<textarea class='iCODE-question-comment iCODE-question-comment-large mlw_icode_question_comment' id='mlwComment$question_id' name='mlwComment$question_id' onclick='icodeClearField(this)'>" . htmlspecialchars_decode( $options->comment_field_text, ENT_QUOTES ) . "</textarea>";
							}
							// Checks if a hint is entered.
							if ( ! empty( $question['hints'] ) ) {
								echo '<div title="' . esc_attr( htmlspecialchars_decode( $question['hints'], ENT_QUOTES ) ) . '" class="iCODE-hint iCODE_hint mlw_icode_hint_link">' . $options->hint_text . '</div>';
							}
							?>
						</div>
						<?php
					}
					?>
				</section>
				<?php
			}
		}

		if ( count( $pages ) > 1 && 0 == $options->comment_section ) {
			$message_comments = wpautop( htmlspecialchars_decode( $options->message_comment, ENT_QUOTES ) );
			$message_comments = apply_filters( 'mlw_icode_template_variable_survey_page', $message_comments, $survey_data );
			?>
			<section class="<?php echo $page_class; ?>">
				<div class="survey_section survey_begin">
					<label for='mlwsurveyComments' class='iCODE-comments-label mlw_icode_comment_section_text'><?php echo $message_comments; ?></label>
					<textarea id='mlwsurveyComments' name='mlwsurveyComments' class='iCODE-comments icode_comment_section'></textarea>
				</div>
			</section>
			<?php
		}
		if ( count( $pages ) > 1 && ( ! empty( $options->message_end_template ) || ( 1 == $options->contact_info_location && $contact_fields ) ) ) {
			$message_after = wpautop( htmlspecialchars_decode( $options->message_end_template, ENT_QUOTES ) );
			$message_after = apply_filters( 'mlw_icode_template_variable_survey_page', $message_after, $survey_data );
			?>
			<section class="<?php echo $page_class; ?>">
				<div class="survey_section">
					<div class='iCODE-after-message mlw_icode_message_end'><?php echo $message_after; ?></div>
					<?php
					if ( 1 == $options->contact_info_location ) {
						echo iCODE_Contact_Manager::display_fields( $options );
					}
					?>
				</div>
				<?php
				// Legacy code.
				do_action( 'mlw_icode_end_survey_section' );
				?>
			</section>
			<?php
		}
		?>
		<!-- View for pagination -->
		<script type="text/template" id="tmpl-iCODE-pagination">
			<div class="iCODE-pagination icode_pagination border margin-bottom">
				<a class="iCODE-btn iCODE-previous icode_btn mlw_icode_survey_link mlw_previous" href="#"><?php echo esc_html( $options->previous_button_text ); ?></a>
				<span class="icode_page_message"></span>
				<div class="icode_page_counter_message"></div>
				<div id="iCODE-progress-bar" style="display:none;"></div>
				<a class="iCODE-btn iCODE-next icode_btn mlw_icode_survey_link mlw_next" href="#"><?php echo esc_html( $options->next_button_text ); ?></a>
				<input type='submit' class='iCODE-btn iCODE-submit-btn icode_btn' value='<?php echo esc_attr( htmlspecialchars_decode( $options->submit_button_text, ENT_QUOTES ) ); ?>' />
			</div>
		</script>
		<input type='hidden' name='icode_question_list' value='<?php echo esc_attr( $question_list ); ?>' />
		<?php
		return ob_get_clean();
	}

	/**
	 * Creates Display For Beginning Section
	 *
	 * Generates the content for the beginning section of the survey page
	 *
	 * @since 4.0.0
	 * @param array $icode_survey_options The database row of the survey.
	 * @param array $icode_array_for_variables The array of results for the survey.
	 * @return string The content for the beginning section
	 * @deprecated 5.2.0 Use new page system instead
	 */
	public function display_begin_section( $icode_survey_options, $icode_array_for_variables ) {
		$section_display = '';
		global $icode_json_data;
		$contact_fields = iCODE_Contact_Manager::load_fields();
		if ( ! empty( $icode_survey_options->message_before ) || ( 0 == $icode_survey_options->contact_info_location && $contact_fields ) ) {
			$icode_json_data["first_page"] = true;
			global $mlw_icode_section_count;
			$mlw_icode_section_count +=1;
			$section_display .= "<div class='survey_section  survey_begin slide$mlw_icode_section_count'>";

			$message_before = wpautop(htmlspecialchars_decode($icode_survey_options->message_before, ENT_QUOTES));
			$message_before = apply_filters( 'mlw_icode_template_variable_survey_page', $message_before, $icode_array_for_variables);

			$section_display .= "<div class='mlw_icode_message_before'>$message_before</div>";
			if ( 0 == $icode_survey_options->contact_info_location ) {
				$section_display .= iCODE_Contact_Manager::display_fields( $icode_survey_options );
			}
			$section_display .= "</div>";
		} else {
			$icode_json_data["first_page"] = false;
		}
		return $section_display;
	}

	/**
	 * Creates Display For Questions
	 *
	 * Generates the content for the questions part of the survey page
	 *
	 * @since 4.0.0
	 * @param array $icode_survey_options The database row of the survey.
	 * @param array $icode_survey_questions The questions of the survey.
	 * @param array $icode_survey_answers The answers of the survey.
	 * @uses icodePluginHelper:display_question() Displays a question
	 * @return string The content for the questions section
	 * @deprecated 5.2.0 Use new page system instead
	 */
	public function display_questions( $icode_survey_options, $icode_survey_questions, $icode_survey_answers ) {
		$question_display = '';
		global $mlwiCodesurveyMaster;
		global $icode_total_questions;
		global $mlw_icode_section_count;
		$question_id_list = '';
		foreach ( $icode_survey_questions as $mlw_question ) {
			$question_id_list .= $mlw_question->question_id."Q";
			$mlw_icode_section_count = $mlw_icode_section_count + 1;
			$question_display .= "<div class='survey_section question-section-id-{$mlw_question->question_id} slide{$mlw_icode_section_count}'>";

			$question_display .= $mlwiCodesurveyMaster->pluginHelper->display_question( $mlw_question->question_type_new, $mlw_question->question_id, $icode_survey_options );

			if ( 0 == $mlw_question->comments ) {
				$question_display .= "<input type='text' class='mlw_icode_question_comment' x-webkit-speech id='mlwComment".$mlw_question->question_id."' name='mlwComment".$mlw_question->question_id."' value='".esc_attr(htmlspecialchars_decode($icode_survey_options->comment_field_text, ENT_QUOTES))."' onclick='icodeClearField(this)'/>";
				$question_display .= "<br />";
			}
			if ( 2 == $mlw_question->comments ) {
				$question_display .= "<textarea cols='70' rows='5' class='mlw_icode_question_comment' id='mlwComment".$mlw_question->question_id."' name='mlwComment".$mlw_question->question_id."' onclick='icodeClearField(this)'>".htmlspecialchars_decode($icode_survey_options->comment_field_text, ENT_QUOTES)."</textarea>";
				$question_display .= "<br />";
			}

			// Checks if a hint is entered.
			if ( ! empty( $mlw_question->hints ) ) {
				$question_display .= "<div title=\"" . esc_attr( htmlspecialchars_decode( $mlw_question->hints, ENT_QUOTES ) ) . "\" class='iCODE_hint mlw_icode_hint_link'>{$icode_survey_options->hint_text}</div>";
				$question_display .= "<br /><br />";
			}
			$question_display .= "</div>";
		}
		$question_display .= "<input type='hidden' name='icode_question_list' value='$question_id_list' />";
		return $question_display;
	}

	/**
	 * Creates Display For Comment Section
	 *
	 * Generates the content for the comment section part of the survey page
	 *
	 * @since 4.0.0
	 * @param array $icode_survey_options The database row of the survey.
	 * @param array $icode_array_for_variables The array of results for the survey.
	 * @return string The content for the comment section
	 * @deprecated 5.2.0 Use new page system instead
	 */
	public function display_comment_section( $icode_survey_options, $icode_array_for_variables ) {
		global $mlw_icode_section_count;
		$comment_display = '';
		if ( 0 == $icode_survey_options->comment_section ) {
			$mlw_icode_section_count = $mlw_icode_section_count + 1;
			$comment_display .= "<div class='survey_section slide".$mlw_icode_section_count."'>";
			$message_comments = wpautop(htmlspecialchars_decode($icode_survey_options->message_comment, ENT_QUOTES));
			$message_comments = apply_filters( 'mlw_icode_template_variable_survey_page', $message_comments, $icode_array_for_variables);
			$comment_display .= "<label for='mlwsurveyComments' class='mlw_icode_comment_section_text'>$message_comments</label><br />";
			$comment_display .= "<textarea cols='60' rows='10' id='mlwsurveyComments' name='mlwsurveyComments' class='icode_comment_section'></textarea>";
			$comment_display .= "</div>";
		}
		return $comment_display;
	}

	/**
	 * Creates Display For End Section Of survey Page
	 *
	 * Generates the content for the end section of the survey page
	 *
	 * @since 4.0.0
	 * @param array $icode_survey_options The database row of the survey.
	 * @param array $icode_array_for_variables The array of results for the survey.
	 * @return string The content for the end section
	 * @deprecated 5.2.0 Use new page system instead
	 */
	public function display_end_section( $icode_survey_options, $icode_array_for_variables ) {
		global $mlw_icode_section_count;
		$section_display = '';
		$section_display .= '<br />';
		$mlw_icode_section_count = $mlw_icode_section_count + 1;
		$section_display .= "<div class='survey_section slide$mlw_icode_section_count survey_end'>";
		if ( ! empty( $icode_survey_options->message_end_template ) ) {
			$message_end = wpautop( htmlspecialchars_decode( $icode_survey_options->message_end_template, ENT_QUOTES ) );
			$message_end = apply_filters( 'mlw_icode_template_variable_survey_page', $message_end, $icode_array_for_variables );
			$section_display .= "<span class='mlw_icode_message_end'>$message_end</span>";
			$section_display .= '<br /><br />';
		}
		if ( 1 == $icode_survey_options->contact_info_location ) {
			$section_display .= iCODE_Contact_Manager::display_fields( $icode_survey_options );
		}

		// Legacy Code.
		ob_start();
		do_action( 'mlw_icode_end_survey_section' );
		$section_display .= ob_get_contents();
		ob_end_clean();

		$section_display .= "<input type='submit' class='iCODE-btn iCODE-submit-btn icode_btn' value='" . esc_attr( htmlspecialchars_decode( $icode_survey_options->submit_button_text, ENT_QUOTES ) ) . "' />";
		$section_display .= "</div>";

		return $section_display;
	}

	/**
	 * Generates Content Results Page
	 *
	 * Generates the content for the results page part of the shortcode
	 *
	 * @since 4.0.0
	 * @param array $options The database row of the survey.
	 * @param array $data The array of results for the survey.
	 * @uses icodesurveyManager:submit_results() Perform The survey/Survey Submission
	 * @return string The content for the results page section
	 */
	public function display_results( $options, $data ) {
		$result = $this->submit_results( $options, $data );
		$results_array = $result;
		return $results_array['display'];
	}

	/**
	 * Calls the results page from ajax
	 *
	 * @since 4.6.0
	 * @uses icodesurveyManager:submit_results() Perform The survey/Survey Submission
	 * @return string The content for the results page section
	 */
	public function ajax_submit_results() {
		global $icode_allowed_visit;
		global $mlwiCodesurveyMaster;
		parse_str( $_POST["surveyData"], $_POST );
		$icode_allowed_visit = true;
		$survey = intval( $_POST["icode_survey_id"] );
		$mlwiCodesurveyMaster->pluginHelper->prepare_survey( $survey );
		$options = $mlwiCodesurveyMaster->survey_settings->get_survey_options();
		$data = array(
			'survey_id' => $options->survey_id,
			'survey_name' => $options->survey_name,
			'survey_system' => $options->system,
		);
		echo json_encode( $this->submit_results( $options, $data ) );
		die();
	}

	/**
	 * Perform The survey/Survey Submission
	 *
	 * Prepares and save the results, prepares and send emails, prepare results page
	 *
	 * @since 4.6.0
	 * @param array $icode_survey_options The database row of the survey.
	 * @param array $icode_array_for_variables The array of results for the survey.
	 * @uses icodesurveyManager:check_answers() Creates display for beginning section
	 * @uses icodesurveyManager:check_comment_section() Creates display for questions
	 * @uses icodesurveyManager:display_results_text() Creates display for end section
	 * @uses icodesurveyManager:display_social() Creates display for comment section
	 * @uses icodesurveyManager:send_user_email() Creates display for end section
	 * @uses icodesurveyManager:send_admin_email() Creates display for end section
	 * @return string The content for the results page section
	 */
	public function submit_results( $icode_survey_options, $icode_array_for_variables ) {
		global $icode_allowed_visit;
		$result_display = '';

		$icode_array_for_variables['user_ip'] = $this->get_user_ip();

		$result_display = apply_filters( 'icode_begin_results', $result_display, $icode_survey_options, $icode_array_for_variables );
		if ( ! $icode_allowed_visit ) {
			return $result_display;
		}

		// Gathers contact information.
		$icode_array_for_variables['user_name'] = 'None';
		$icode_array_for_variables['user_business'] = 'None';
		$icode_array_for_variables['user_email'] = 'None';
		$icode_array_for_variables['user_phone'] = 'None';
		$contact_responses = iCODE_Contact_Manager::process_fields( $icode_survey_options );
		foreach ( $contact_responses as $field ) {
			if ( isset( $field['use'] ) ) {
				if ( 'name' === $field['use'] ) {
					$icode_array_for_variables['user_name'] = $field["value"];
				}
				if ( 'comp' === $field['use'] ) {
					$icode_array_for_variables['user_business'] = $field["value"];
				}
				if ( 'email' === $field['use'] ) {
					$icode_array_for_variables['user_email'] = $field["value"];
				}
				if ( 'phone' === $field['use'] ) {
					$icode_array_for_variables['user_phone'] = $field["value"];
				}
			}
		}

		$mlw_icode_timer = isset($_POST["timer"]) ? intval( $_POST["timer"] ) : 0;
		$icode_array_for_variables['user_id'] = get_current_user_id();
		$icode_array_for_variables['timer'] = $mlw_icode_timer;
		$icode_array_for_variables['time_taken'] = current_time( 'h:i:s A m/d/Y' );
		$icode_array_for_variables['contact'] = $contact_responses;

		if ( !isset( $_POST["mlw_code_captcha"] ) || ( isset( $_POST["mlw_code_captcha"] ) && $_POST["mlw_user_captcha"] == $_POST["mlw_code_captcha"] ) ) {

			$icode_array_for_variables = array_merge( $icode_array_for_variables, $this->check_answers( $icode_survey_options, $icode_array_for_variables ) );
			$result_display = apply_filters('icode_after_check_answers', $result_display, $icode_survey_options, $icode_array_for_variables);
			$icode_array_for_variables['comments'] = $this->check_comment_section($icode_survey_options, $icode_array_for_variables);
			$result_display = apply_filters('icode_after_check_comments', $result_display, $icode_survey_options, $icode_array_for_variables);
			$result_display .= $this->display_results_text($icode_survey_options, $icode_array_for_variables);
			$result_display = apply_filters('icode_after_results_text', $result_display, $icode_survey_options, $icode_array_for_variables);
			$result_display .= $this->display_social($icode_survey_options, $icode_array_for_variables);
			$result_display = apply_filters('icode_after_social_media', $result_display, $icode_survey_options, $icode_array_for_variables);

			// If the store responses in database option is set to Yes.
			if ( 0 != $icode_survey_options->store_responses ) {

				// Creates our results array.
				$results_array = array(
					intval( $icode_array_for_variables['timer'] ),
					$icode_array_for_variables['question_answers_array'],
					htmlspecialchars( stripslashes( $icode_array_for_variables['comments'] ), ENT_QUOTES ),
					'contact' => $contact_responses,
				);
				$results_array = apply_filters( 'iCODE_results_array', $results_array, $icode_array_for_variables );
				$serialized_results = serialize( $results_array );
				//var_dump($icode_array_for_variables['total_points']/$icode_array_for_variables['total_questions']); exit;
				// Inserts the responses in the database.
				global $wpdb;
				$table_name = $wpdb->prefix . "mlw_results";
				$results_insert = $wpdb->insert(
					$table_name,
					array(
						'survey_id'         => $icode_array_for_variables['survey_id'],
						'survey_name'       => $icode_array_for_variables['survey_name'],
						'survey_system'     => $icode_array_for_variables['survey_system'],
						'point_score'     => round($icode_array_for_variables['total_points']/$icode_array_for_variables['total_questions'],2),
						'correct_score'   => $icode_array_for_variables['total_score'],
						'correct'         => $icode_array_for_variables['total_correct'],
						'total'           => $icode_array_for_variables['total_questions'],
						'name'            => $icode_array_for_variables['user_name'],
						'business'        => $icode_array_for_variables['user_business'],
						'email'           => $icode_array_for_variables['user_email'],
						'phone'           => $icode_array_for_variables['user_phone'],
						'user'            => $icode_array_for_variables['user_id'],
						'user_ip'         => $icode_array_for_variables['user_ip'],
						'time_taken'      => $icode_array_for_variables['time_taken'],
						'time_taken_real' => date( 'Y-m-d H:i:s', strtotime( $icode_array_for_variables['time_taken'] ) ),
						'survey_results'    => $serialized_results,
						'deleted'         => 0,
					),
					array(
						'%d',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
					)
				);
			}

			$results_id = $wpdb->insert_id;

			// Hook is fired after the responses are submitted. Passes responses, result ID, survey settings, and response data.
			do_action( 'iCODE_survey_submitted', $results_array, $results_id, $icode_survey_options, $icode_array_for_variables );

			// Sends user email.
			$this->send_user_email( $icode_survey_options, $icode_array_for_variables );
			$result_display = apply_filters( 'icode_after_send_user_email', $result_display, $icode_survey_options, $icode_array_for_variables );

			// Sends admin email.
			$this->send_admin_email($icode_survey_options, $icode_array_for_variables);
			$result_display = apply_filters( 'icode_after_send_admin_email', $result_display, $icode_survey_options, $icode_array_for_variables );
			
			// Last time to filter the HTML results page.
			$result_display = apply_filters( 'icode_end_results', $result_display, $icode_survey_options, $icode_array_for_variables );

			// Legacy Code.
			do_action( 'mlw_icode_load_results_page', $wpdb->insert_id, $icode_survey_options->survey_settings );
		} else {
			$result_display .= 'Thank you.';
		}

		// Checks to see if we need to set up a redirect.
		$redirect     = false;
		$redirect_url = '';
		if ( is_serialized( $icode_survey_options->message_after ) && is_array( @unserialize( $icode_survey_options->message_after ) ) ) {
			$mlw_message_after_array = @unserialize( $icode_survey_options->message_after );

			// Cycles through landing pages.
			foreach( $mlw_message_after_array as $mlw_each ) {
				// Checks to see if not default.
				if ( $mlw_each[0] != 0 || $mlw_each[1] != 0 ) {
					// Checks to see if points fall in correct range.
					if ($icode_survey_options->system == 1 && $icode_array_for_variables['total_points'] >= $mlw_each[0] && $icode_array_for_variables['total_points'] <= $mlw_each[1])
					{
						if (esc_url($mlw_each["redirect_url"]) != '')
						{
							$redirect = true;
							$redirect_url = esc_url( $mlw_each["redirect_url"] );
						}
						break;
					}
					//Check to see if score fall in correct range
					if ($icode_survey_options->system == 0 && $icode_array_for_variables['total_score'] >= $mlw_each[0] && $icode_array_for_variables['total_score'] <= $mlw_each[1])
					{
						if (esc_url($mlw_each["redirect_url"]) != '')
						{
							$redirect = true;
							$redirect_url = esc_url( $mlw_each["redirect_url"] );
						}
						break;
					}
				}
				else
				{
					if (esc_url($mlw_each["redirect_url"]) != '')
					{
						$redirect = true;
						$redirect_url = esc_url( $mlw_each["redirect_url"] );
					}
					break;
				}
			}
		}

		// Prepares data to be sent back to front-end.
		$return_array = array(
			'display'      => $result_display,
			'redirect'     => $redirect,
			'redirect_url' => $redirect_url,
		);

		return $return_array;
	}

	/**
	 * Scores User Answers
	 *
	 * Calculates the users scores for the survey
	 *
	 * @since 4.0.0
	 * @param array $options The database row of the survey
	 * @param array $survey_data The array of results for the survey
	 * @uses icodePluginHelper:display_review() Scores the question
	 * @return array The results of the user's score
	 */
	public function check_answers( $options, $survey_data ) {

		global $mlwiCodesurveyMaster;

		// Load the pages and questions
		$pages = $mlwiCodesurveyMaster->pluginHelper->get_survey_setting( 'pages', array() );
		$questions = iCODE_Questions::load_questions_by_pages( $options->survey_id );
		
		// Retrieve data from submission
		$total_questions = isset( $_POST["total_questions"] ) ? intval( $_POST["total_questions"] ) : 0;
		$question_list   = isset( $_POST["icode_question_list"] ) ? explode( 'Q', $_POST["icode_question_list"] ) : array();

		// Prepare variables
		$points_earned  = 0;
		$total_correct  = 0;
		$total_score    = 0;
		$user_answer    = "";
		$correct_answer = "";
		$correct_status = "incorrect";
		$answer_points  = 0;
		$question_data  = array();

		// If deprecated pagination setting is not used, use new system...
		if ( 0 == $options->question_from_total && 0 !== count( $pages ) ) {

			// Cycle through each page in survey.
			foreach ( $pages as $page ) {

				// Cycle through each question on a page
				foreach ( $page as $page_question_id ) {

					// Cycle through each question that appeared to the user
					foreach ( $question_list as $question_id ) {

						// When the questions are the same...
						if ( $page_question_id == $question_id ) {

							$question = $questions[ $page_question_id ];

							// Reset question-specific variables
							$user_answer    = "";
							$correct_answer = "";
							$correct_status = "incorrect";
							$answer_points  = 0;

							// Send question to our grading function
							$results_array = $mlwiCodesurveyMaster->pluginHelper->display_review( $question['question_type_new'], $question['question_id'] );

							// If question was graded correctly.
							if ( ! isset( $results_array["null_review"] ) ) {
								$points_earned += $results_array["points"];
								$answer_points += $results_array["points"];

								// If the user's answer was correct
								if ( 'correct' == $results_array["correct"] ) {
									$total_correct += 1;
									$correct_status = "correct";
								}
								$user_answer = $results_array["user_text"];
								$correct_answer = $results_array["correct_text"];

								// If a comment was submitted
								if ( isset( $_POST["mlwComment" . $question['question_id'] ] ) ) {
									$comment = htmlspecialchars( stripslashes( $_POST["mlwComment" . $question['question_id'] ] ), ENT_QUOTES );
								} else {
									$comment = "";
								}

								// Get text for question
								$question_text = $question['question_name'];
								if ( isset( $results_array["question_text"] ) ) {
									$question_text = $results_array["question_text"];
								}

								// Save question data into new array in our array
								$question_data[] = apply_filters( 'icode_answer_array', array(
									$question_text, 
									htmlspecialchars( $user_answer, ENT_QUOTES ), 
									htmlspecialchars( $correct_answer, ENT_QUOTES ), 
									$comment, 
									"correct"  => $correct_status, 
									"id"       => $question['question_id'], 
									"points"   => $answer_points, 
									"category" => $question['category'] 
								), $options, $survey_data);
							}
							break;
						}
					}
				}
			}
		} else {
			// Cycle through each page in survey.
			foreach ( $questions as $question ) {

				// Cycle through each question that appeared to the user
				foreach ( $question_list as $question_id ) {

					// When the questions are the same...
					if ( $question['question_id'] == $question_id ) {

						// Reset question-specific variables
						$user_answer    = "";
						$correct_answer = "";
						$correct_status = "incorrect";
						$answer_points  = 0;

						// Send question to our grading function
						$results_array = $mlwiCodesurveyMaster->pluginHelper->display_review( $question['question_type_new'], $question['question_id'] );

						// If question was graded correctly.
						if ( ! isset( $results_array["null_review"] ) ) {
							$points_earned += $results_array["points"];
							$answer_points += $results_array["points"];

							// If the user's answer was correct
							if ( 'correct' == $results_array["correct"] ) {
								$total_correct += 1;
								$correct_status = "correct";
							}
							$user_answer = $results_array["user_text"];
							$correct_answer = $results_array["correct_text"];

							// If a comment was submitted
							if ( isset( $_POST["mlwComment" . $question['question_id'] ] ) ) {
								$comment = htmlspecialchars( stripslashes( $_POST["mlwComment" . $question['question_id'] ] ), ENT_QUOTES );
							} else {
								$comment = "";
							}

							// Get text for question
							$question_text = $question['question_name'];
							if ( isset( $results_array["question_text"] ) ) {
								$question_text = $results_array["question_text"];
							}

							// Save question data into new array in our array
							$question_data[] = apply_filters( 'icode_answer_array', array(
								$question_text, 
								htmlspecialchars( $user_answer, ENT_QUOTES ), 
								htmlspecialchars( $correct_answer, ENT_QUOTES ), 
								$comment, 
								"correct"  => $correct_status, 
								"id"       => $question['question_id'], 
								"points"   => $answer_points, 
								"category" => $question['category'] 
							), $options, $survey_data);
						}
						break;
					}
				}
			}
		}

		// Calculate Total Percent Score And Average Points Only If Total Questions Doesn't Equal Zero To Avoid Division By Zero Error
		if ( 0 !== $total_questions ) {
			$total_score = round( ( ( $total_correct / $total_questions ) * 100 ), 2 );
		} else {
			$total_score = 0;
		}

		// Return array to be merged with main user response array
		return array(
			'total_points'             => $points_earned,
			'total_score'              => $total_score,
			'total_correct'            => $total_correct,
			'total_questions'          => $total_questions,
			'question_answers_display' => '', // Kept for backwards compatibility
			'question_answers_array'   => $question_data,
		);
	}

	/**
	  * Retrieves User's Comments
	  *
	  * Checks to see if the user left a comment and returns the comment
	  *
	  * @since 4.0.0
		* @param array $icode_survey_options The database row of the survey
		* @param array $icode_array_for_variables The array of results for the survey
		* @return string The user's comments
	  */
	public function check_comment_section($icode_survey_options, $icode_array_for_variables)
	{
		$icode_survey_comments = "";
		if ( isset( $_POST["mlwsurveyComments"] ) ) {
			$icode_survey_comments = esc_textarea( stripslashes( $_POST["mlwsurveyComments"] ) );
		}
		return apply_filters( 'icode_returned_comments', $icode_survey_comments, $icode_survey_options, $icode_array_for_variables );
	}


	/**
	  * Displays Results Text
	  *
	  * Prepares and display text for the results page
	  *
	  * @since 4.0.0
		* @param array $icode_survey_options The database row of the survey
		* @param array $icode_array_for_variables The array of results for the survey
		* @return string The contents for the results text
	  */
	public function display_results_text($icode_survey_options, $icode_array_for_variables)
	{
		$results_text_display = '';
		if (is_serialized($icode_survey_options->message_after) && is_array(@unserialize($icode_survey_options->message_after)))
		{
			$mlw_message_after_array = @unserialize($icode_survey_options->message_after);
			//Cycle through landing pages
			foreach($mlw_message_after_array as $mlw_each)
			{
				//Check to see if default
				if ($mlw_each[0] == 0 && $mlw_each[1] == 0)
				{
					$mlw_message_after = htmlspecialchars_decode($mlw_each[2], ENT_QUOTES);
					$mlw_message_after = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message_after, $icode_array_for_variables);
					$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
					$results_text_display .= $mlw_message_after;
					break;
				}
				else
				{
					//Check to see if points fall in correct range
					if ($icode_survey_options->system == 1 && $icode_array_for_variables['total_points'] >= $mlw_each[0] && $icode_array_for_variables['total_points'] <= $mlw_each[1])
					{
						$mlw_message_after = htmlspecialchars_decode($mlw_each[2], ENT_QUOTES);
						$mlw_message_after = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message_after, $icode_array_for_variables);
						$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
						$results_text_display .= $mlw_message_after;
						break;
					}
					//Check to see if score fall in correct range
					if ($icode_survey_options->system == 0 && $icode_array_for_variables['total_score'] >= $mlw_each[0] && $icode_array_for_variables['total_score'] <= $mlw_each[1])
					{
						$mlw_message_after = htmlspecialchars_decode($mlw_each[2], ENT_QUOTES);
						$mlw_message_after = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message_after, $icode_array_for_variables);
						$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
						$results_text_display .= $mlw_message_after;
						break;
					}
				}
			}
		}
		else
		{
			//Prepare the after survey message
			$mlw_message_after = htmlspecialchars_decode($icode_survey_options->message_after, ENT_QUOTES);
			$mlw_message_after = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message_after, $icode_array_for_variables);
			$mlw_message_after = str_replace( "\n" , "<br>", $mlw_message_after);
			$results_text_display .= $mlw_message_after;
		}
		return do_shortcode( $results_text_display );
	}

	/**
	  * Display Social Media Buttons
	  *
	  * Prepares and displays social media buttons for sharing results
	  *
	  * @since 4.0.0
		* @param array $icode_survey_options The database row of the survey
		* @param array $icode_array_for_variables The array of results for the survey
		* @return string The content of the social media button section
	  */
	public function display_social($icode_survey_options, $icode_array_for_variables)
	{
		$social_display = '';
		if ($icode_survey_options->social_media == 1)
		{
			$settings = (array) get_option( 'icode-settings' );
			$mailchimp_app_id = '483815031724529';
			if (isset($settings['mailchimp_app_id']))
			{
				$mailchimp_app_id = esc_js( $settings['mailchimp_app_id'] );
			}

			//Load Social Media Text
			$icode_social_media_text = "";
			if ( is_serialized( $icode_survey_options->social_media_text ) && is_array( @unserialize( $icode_survey_options->social_media_text ) ) ) {
				$icode_social_media_text = @unserialize($icode_survey_options->social_media_text);
			} else {
				$icode_social_media_text = array(
		        		'twitter' => $icode_survey_options->social_media_text,
		        		'facebook' => $icode_survey_options->social_media_text
		        	);
			}
			$icode_social_media_text["twitter"] = apply_filters( 'mlw_icode_template_variable_results_page', $icode_social_media_text["twitter"], $icode_array_for_variables);
			$icode_social_media_text["facebook"] = apply_filters( 'mlw_icode_template_variable_results_page', $icode_social_media_text["facebook"], $icode_array_for_variables);
			$social_display .= "<br />
			<a class=\"mlw_icode_survey_link\" onclick=\"icodeSocialShare('facebook', '".esc_js($icode_social_media_text["facebook"])."', '".esc_js($icode_survey_options->survey_name)."', '$mailchimp_app_id');\">Facebook</a>
			<a class=\"mlw_icode_survey_link\" onclick=\"icodeSocialShare('twitter', '".esc_js($icode_social_media_text["twitter"])."', '".esc_js($icode_survey_options->survey_name)."');\">Twitter</a>
			<br />";
		}
		return apply_filters('icode_returned_social_buttons', $social_display, $icode_survey_options, $icode_array_for_variables);
	}

	/**
	  * Send User Email
	  *
	  * Prepares the email to the user and then sends the email
	  *
	  * @since 4.0.0
		* @param array $icode_survey_options The database row of the survey
		* @param array $icode_array_for_variables The array of results for the survey
		*/
	public function send_user_email($icode_survey_options, $icode_array_for_variables)
	{
		add_filter( 'wp_mail_content_type', 'mlw_icode_set_html_content_type' );
		$mlw_message = "";

		//Check if this survey has user emails turned on
		if ( $icode_survey_options->send_user_email == "0" ) {

			//Make sure that the user filled in the email field
			if ( $icode_array_for_variables['user_email'] != "" ) {

				//Prepare from email and name
				$from_email_array = maybe_unserialize( $icode_survey_options->email_from_text );
				if ( ! isset( $from_email_array["from_email"] ) ) {
					$from_email_array = array(
						'from_name' => $icode_survey_options->email_from_text,
						'from_email' => $icode_survey_options->admin_email,
						'reply_to' => 1
					);
				}

				if ( ! is_email( $from_email_array["from_email"] ) ) {
					if ( is_email( $icode_survey_options->admin_email ) ) {
						$from_email_array["from_email"] = $icode_survey_options->admin_email;
					} else {
						$from_email_array["from_email"] = get_option( 'admin_email ', 'test@example.com' );
					}
				}

				//Prepare email attachments
				$attachments = array();
				$attachments = apply_filters( 'iCODE_user_email_attachments', $attachments, $icode_array_for_variables );

				if ( is_serialized( $icode_survey_options->user_email_template ) && is_array( @unserialize( $icode_survey_options->user_email_template ) ) ) {

					$mlw_user_email_array = @unserialize( $icode_survey_options->user_email_template );

					//Cycle through emails
					foreach( $mlw_user_email_array as $mlw_each ) {

						//Generate Email Subject
						if ( !isset( $mlw_each[3] ) ) {
							$mlw_each[3] = "survey Results For %survey_NAME";
						}
						$mlw_each[3] = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_each[3], $icode_array_for_variables );

						//Check to see if default
						if ( $mlw_each[0] == 0 && $mlw_each[1] == 0 ) {
							$mlw_message = htmlspecialchars_decode( $mlw_each[2], ENT_QUOTES );
							$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables );
							$mlw_message = str_replace( "\n" , "<br>", $mlw_message );
							$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message );
							$mlw_message = str_replace( "<br />" , "<br>", $mlw_message );
							$mlw_headers = 'From: '.$from_email_array["from_name"].' <'.$from_email_array["from_email"].'>' . "\r\n";
							wp_mail( $icode_array_for_variables['user_email'], $mlw_each[3], $mlw_message, $mlw_headers, $attachments );
							break;
						} else {

							//Check to see if this survey uses points and check if the points earned falls in the point range for this email
							if ( $icode_survey_options->system == 1 && $icode_array_for_variables['total_points'] >= $mlw_each[0] && $icode_array_for_variables['total_points'] <= $mlw_each[1] ) {
								$mlw_message = htmlspecialchars_decode( $mlw_each[2], ENT_QUOTES );
								$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables );
								$mlw_message = str_replace( "\n" , "<br>", $mlw_message );
								$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message );
								$mlw_message = str_replace( "<br />" , "<br>", $mlw_message );
								$mlw_headers = 'From: '.$from_email_array["from_name"].' <'.$from_email_array["from_email"].'>' . "\r\n";
								wp_mail( $icode_array_for_variables['user_email'], $mlw_each[3], $mlw_message, $mlw_headers, $attachments );
								break;
							}

							//Check to see if score fall in correct range
							if ( $icode_survey_options->system == 0 && $icode_array_for_variables['total_score'] >= $mlw_each[0] && $icode_array_for_variables['total_score'] <= $mlw_each[1] ) {
								$mlw_message = htmlspecialchars_decode( $mlw_each[2], ENT_QUOTES );
								$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables );
								$mlw_message = str_replace( "\n" , "<br>", $mlw_message );
								$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message );
								$mlw_message = str_replace( "<br />" , "<br>", $mlw_message );
								$mlw_headers = 'From: '.$from_email_array["from_name"].' <'.$from_email_array["from_email"].'>' . "\r\n";
								wp_mail( $icode_array_for_variables['user_email'], $mlw_each[3], $mlw_message, $mlw_headers, $attachments );
								break;
							}
						}
					}
				} else {

					//Uses older email system still which was before different emails were created.
					$mlw_message = htmlspecialchars_decode( $icode_survey_options->user_email_template, ENT_QUOTES );
					$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables );
					$mlw_message = str_replace( "\n" , "<br>", $mlw_message );
					$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message );
					$mlw_message = str_replace( "<br />" , "<br>", $mlw_message );
					$mlw_headers = 'From: '.$from_email_array["from_name"].' <'.$from_email_array["from_email"].'>' . "\r\n";
					wp_mail( $icode_array_for_variables['user_email'], "survey Results For ".$icode_survey_options->survey_name, $mlw_message, $mlw_headers, $attachments );
				}
			}
		}
		remove_filter( 'wp_mail_content_type', 'mlw_icode_set_html_content_type' );
	}

	/**
	  * Send Admin Email
	  *
	  * Prepares the email to the admin and then sends the email
	  *
	  * @since 4.0.0
		* @param array $icode_survey_options The database row of the survey
		* @param arrar $icode_array_for_variables The array of results for the survey
		*/
	public function send_admin_email($icode_survey_options, $icode_array_for_variables)
	{
		//Switch email type to HTML
		add_filter( 'wp_mail_content_type', 'mlw_icode_set_html_content_type' );

		$mlw_message = "";
		if ( $icode_survey_options->send_admin_email == "0" ) {
			if ( $icode_survey_options->admin_email != "" ) {
				$from_email_array = maybe_unserialize( $icode_survey_options->email_from_text );
				if ( ! isset( $from_email_array["from_email"] ) ) {
					$from_email_array = array(
						'from_name' => $icode_survey_options->email_from_text,
						'from_email' => $icode_survey_options->admin_email,
						'reply_to' => 1
					);
				}

				if ( ! is_email( $from_email_array["from_email"] ) ) {
					if ( is_email( $icode_survey_options->admin_email ) ) {
						$from_email_array["from_email"] = $icode_survey_options->admin_email;
					} else {
						$from_email_array["from_email"] = get_option( 'admin_email ', 'test@example.com' );
					}
				}

				$mlw_message = "";
				$mlw_subject = "";
				if (is_serialized($icode_survey_options->admin_email_template) && is_array(@unserialize($icode_survey_options->admin_email_template)))
				{
					$mlw_admin_email_array = @unserialize($icode_survey_options->admin_email_template);

					//Cycle through landing pages
					foreach($mlw_admin_email_array as $mlw_each)
					{

						//Generate Email Subject
						if (!isset($mlw_each["subject"]))
						{
							$mlw_each["subject"] = "survey Results For %survey_NAME";
						}
						$mlw_each["subject"] = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_each["subject"], $icode_array_for_variables);

						//Check to see if default
						if ($mlw_each["begin_score"] == 0 && $mlw_each["end_score"] == 0)
						{
							$mlw_message = htmlspecialchars_decode($mlw_each["message"], ENT_QUOTES);
							$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables);
							$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
							$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message);
							$mlw_message = str_replace( "<br />" , "<br>", $mlw_message);
							$mlw_subject = $mlw_each["subject"];
							break;
						}
						else
						{
							//Check to see if points fall in correct range
							if ($icode_survey_options->system == 1 && $icode_array_for_variables['total_points'] >= $mlw_each["begin_score"] && $icode_array_for_variables['total_points'] <= $mlw_each["end_score"])
							{
								$mlw_message = htmlspecialchars_decode($mlw_each["message"], ENT_QUOTES);
								$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables);
								$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
								$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message);
								$mlw_message = str_replace( "<br />" , "<br>", $mlw_message);
								$mlw_subject = $mlw_each["subject"];
								break;
							}

							//Check to see if score fall in correct range
							if ($icode_survey_options->system == 0 && $icode_array_for_variables['total_score'] >= $mlw_each["begin_score"] && $icode_array_for_variables['total_score'] <= $mlw_each["end_score"])
							{
								$mlw_message = htmlspecialchars_decode($mlw_each["message"], ENT_QUOTES);
								$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables);
								$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
								$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message);
								$mlw_message = str_replace( "<br />" , "<br>", $mlw_message);
								$mlw_subject = $mlw_each["subject"];
								break;
							}
						}
					}
				}
				else
				{
					$mlw_message = htmlspecialchars_decode($icode_survey_options->admin_email_template, ENT_QUOTES);
					$mlw_message = apply_filters( 'mlw_icode_template_variable_results_page', $mlw_message, $icode_array_for_variables);
					$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
					$mlw_message = str_replace( "<br/>" , "<br>", $mlw_message);
					$mlw_message = str_replace( "<br />" , "<br>", $mlw_message);
					$mlw_subject = "survey Results For ".$icode_survey_options->survey_name;
				}
			}
			if ( get_option('mlw_advert_shows') == 'true' ) {$mlw_message .= "<br>This email was generated by the iCode Survey Master script by Frank Corso";}
			$headers = array(
				'From: '.$from_email_array["from_name"].' <'.$from_email_array["from_email"].'>'
			);
			if ( $from_email_array["reply_to"] == 0 ) {
				$headers[] = 'Reply-To: '.$icode_array_for_variables["user_name"]." <".$icode_array_for_variables["user_email"].">";
			}
			$admin_emails = explode( ",", $icode_survey_options->admin_email );
			foreach( $admin_emails as $admin_email ) {
				if ( is_email( $admin_email ) ) {
					wp_mail( $admin_email, $mlw_subject, $mlw_message, $headers );
				}
			}
		}

		//Remove HTML type for emails
		remove_filter( 'wp_mail_content_type', 'mlw_icode_set_html_content_type' );
	}

	/**
	 * Returns the survey taker's IP if IP collection is enabled
	 * 
	 * @since 5.3.0
	 * @return string The IP address or a phrase if not collected
	 */
	private function get_user_ip() {
		$ip = __( 'Not collected', 'icode-survey-master' );
		$settings = (array) get_option( 'icode-settings' );
    	$ip_collection = '0';
		if ( isset( $settings['ip_collection'] ) ) {
			$ip_collection = $settings['ip_collection'];
		}
		if ( '1' != $ip_collection ) {
			if ( $_SERVER['REMOTE_ADDR'] ) {
				$ip = $_SERVER['REMOTE_ADDR'];
			} else {
				$ip = __( 'Unknown', 'icode-survey-master' );
			}
		}
		return $ip;	
	}
}
$icodesurveyManager = new icodesurveyManager();

add_filter('icode_begin_shortcode', 'icode_require_login_check', 10, 3);
function icode_require_login_check($display, $icode_survey_options, $icode_array_for_variables)
{
	global $icode_allowed_visit;
	if ( $icode_survey_options->require_log_in == 1 && !is_user_logged_in() )
	{
		$icode_allowed_visit = false;
		$mlw_message = wpautop(htmlspecialchars_decode($icode_survey_options->require_log_in_text, ENT_QUOTES));
		$mlw_message = apply_filters( 'mlw_icode_template_variable_survey_page', $mlw_message, $icode_array_for_variables);
		$mlw_message = str_replace( "\n" , "<br>", $mlw_message);
		$display .= $mlw_message;
		$display .= wp_login_form( array('echo' => false) );
	}
	return $display;
}

add_filter( 'icode_begin_shortcode', 'iCODE_scheduled_timeframe_check', 10, 3 );
function iCODE_scheduled_timeframe_check( $display, $options, $variable_data ) {
	global $icode_allowed_visit;

	// Checks if the start and end dates have data
	if ( ! empty( $options->scheduled_time_start ) && ! empty( $options->scheduled_time_end ) ) {
		$start = strtotime( $options->scheduled_time_start );
		$end = strtotime( $options->scheduled_time_end ) + 86399;

		// Checks if the current timestamp is outside of scheduled timeframe
		if ( current_time( 'timestamp' ) < $start || current_time( 'timestamp' ) > $end ) {
			$icode_allowed_visit = false;
			$message = wpautop( htmlspecialchars_decode( $options->scheduled_timeframe_text, ENT_QUOTES ) );
			$message = apply_filters( 'mlw_icode_template_variable_survey_page', $message, $variable_data );
			$display .= str_replace( "\n" , "<br>", $message );
		}
	}
	return $display;
}

add_filter('icode_begin_shortcode', 'icode_total_user_tries_check', 10, 3);

/**
 * Checks if user has already reach the user limit of the survey
 *
 * @since 5.0.0
 * @param string $display The HTML displayed for the survey
 * @param array $icode_survey_options The settings for the survey
 * @param array $icode_array_for_variables The array of data by the survey
 * @return string The altered HTML display for the survey
 */
function icode_total_user_tries_check( $display, $icode_survey_options, $icode_array_for_variables ) {

	global $icode_allowed_visit;
	if ( $icode_survey_options->total_user_tries != 0 ) {

		// Prepares the variables
		global $wpdb;
		$mlw_icode_user_try_count = 0;

		// Checks if the user is logged in. If so, check by user id. If not, check by IP.
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$mlw_icode_user_try_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$wpdb->prefix."mlw_results WHERE user=%d AND deleted='0' AND survey_id=%d", $current_user->ID, $icode_array_for_variables['survey_id'] ) );
		} else {
			$mlw_icode_user_try_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$wpdb->prefix."mlw_results WHERE user_ip='%s' AND deleted='0' AND survey_id=%d", $icode_array_for_variables['user_ip'], $icode_array_for_variables['survey_id'] ) );
		}

		// If user has already reached the limit for this survey
		if ( $mlw_icode_user_try_count >= $icode_survey_options->total_user_tries ) {

			// Stops the survey and prepares entered text
			$icode_allowed_visit = false;
			$mlw_message = wpautop( htmlspecialchars_decode( $icode_survey_options->total_user_tries_text, ENT_QUOTES ) );
			$mlw_message = apply_filters( 'mlw_icode_template_variable_survey_page', $mlw_message, $icode_array_for_variables );
			$display .= $mlw_message;
		}
	}
	return $display;
}

add_filter('icode_begin_survey', 'icode_total_tries_check', 10, 3);
function icode_total_tries_check($display, $icode_survey_options, $icode_array_for_variables)
{
	global $icode_allowed_visit;
	if ( $icode_survey_options->limit_total_entries != 0 )
	{
		global $wpdb;
		$mlw_icode_entries_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(survey_id) FROM ".$wpdb->prefix."mlw_results WHERE deleted='0' AND survey_id=%d", $icode_array_for_variables['survey_id'] ) );
		if ($mlw_icode_entries_count >= $icode_survey_options->limit_total_entries)
		{
			$mlw_message = wpautop(htmlspecialchars_decode($icode_survey_options->limit_total_entries_text, ENT_QUOTES));
			$mlw_message = apply_filters( 'mlw_icode_template_variable_survey_page', $mlw_message, $icode_array_for_variables);
			$display .= $mlw_message;
			$icode_allowed_visit = false;
		}
	}
	return $display;
}

add_filter('icode_begin_survey', 'icode_pagination_check', 10, 3);
function icode_pagination_check( $display, $icode_survey_options, $icode_array_for_variables ) {
	if ( $icode_survey_options->pagination != 0 ) {
		global $wpdb;
		global $icode_json_data;
		$total_questions = 0;
		if ( $icode_survey_options->question_from_total != 0 ) {
			$total_questions = $icode_survey_options->question_from_total;
		} else {
			$questions = iCODE_Questions::load_questions_by_pages( $icode_survey_options->survey_id );
			$total_questions = count( $questions );
		}
		$display .= "<style>.survey_section { display: none; }</style>";

		$icode_json_data["pagination"] = array(
			'amount' => $icode_survey_options->pagination,
			'section_comments' => $icode_survey_options->comment_section,
			'total_questions' => $total_questions,
			'previous_text' => $icode_survey_options->previous_button_text,
			'next_text' => $icode_survey_options->next_button_text
		);
	}
	return $display;
}

add_filter( 'icode_begin_survey_form', 'icode_timer_check', 15, 3 );
function icode_timer_check( $display, $icode_survey_options, $icode_array_for_variables ) {
	global $icode_allowed_visit;
	global $icode_json_data;
	if ( $icode_allowed_visit && $icode_survey_options->timer_limit != 0 ) {
		$icode_json_data["timer_limit"] = $icode_survey_options->timer_limit;
		$display .= '<div style="display:none;" id="mlw_icode_timer" class="mlw_icode_timer"></div>';
	}
	return $display;
}

add_filter('icode_begin_survey', 'icode_update_views', 10, 3);
function icode_update_views($display, $icode_survey_options, $icode_array_for_variables)
{
	global $wpdb;
	$mlw_views = $icode_survey_options->survey_views;
	$mlw_views += 1;
	$results = $wpdb->update(
		$wpdb->prefix . "mlw_surveyzes",
		array(
			'survey_views' => $mlw_views
		),
		array( 'survey_id' => $icode_array_for_variables["survey_id"] ),
		array(
			'%d'
		),
		array( '%d' )
	);
	return $display;
}

add_filter('icode_begin_results', 'icode_update_taken', 10, 3);
function icode_update_taken($display, $icode_survey_options, $icode_array_for_variables)
{
	global $wpdb;
	$mlw_taken = $icode_survey_options->survey_taken;
	$mlw_taken += 1;
	$results = $wpdb->update(
		$wpdb->prefix . "mlw_surveyzes",
		array(
			'survey_taken' => $mlw_taken
		),
		array( 'survey_id' => $icode_array_for_variables["survey_id"] ),
		array(
			'%d'
		),
		array( '%d' )
	);
	return $display;
}

/*
This function helps set the email type to HTML
*/
function mlw_icode_set_html_content_type() {
	return 'text/html';
}
?>