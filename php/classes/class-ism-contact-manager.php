<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This class handles the contact fields for the survey
 *
 * @since 5.0.0
 */
class iCODE_Contact_Manager {

	/** @var array The fields loaded for the survey. */
	private static $fields = array();


	/**
	 * Displays the contact fields during form
	 *
	 * @since 5.0.0
	 * @param object $options The survey options.
	 * @return string The HTML for the contact fields
	 */
	public static function display_fields( $options ) {

		$fields_hidden = false;
		$name          = '';
		$email         = '';

		ob_start();

		// Prepares name and email if user is logged in.
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$name         = $current_user->display_name;
			$email        = $current_user->user_email;
		}

		// If user is logged in and the option to allow users to edit is set to no...
		if ( is_user_logged_in() && 1 == $options->loggedin_user_contact ) {
			// ..then, hide the fields.
			$fields_hidden = true;
			?>
			<div style="display:none;">
			<?php
		}

		// Loads fields.
		$fields = self::load_fields();

		// If fields are empty and backwards-compatible fields are turned on then, use older system.
		if ( ( empty( $fields ) || ! is_array( $fields ) ) && ( 2 != $options->user_name || 2 != $options->user_comp || 2 != $options->user_email || 2 != $options->user_phone ) ) {

			// Check for name field.
			if ( 2 != $options->user_name ) {
				$class = '';
				if ( 1 == $options->user_name && ! $fields_hidden ) {
					$class = 'mlwRequiredText iCODE_required_text';
				}
				?>
				<span class='mlw_icode_question iCODE_question'><?php echo htmlspecialchars_decode( $options->name_field_text, ENT_QUOTES ); ?></span>
				<input type='text' class='<?php echo esc_attr( $class ); ?>' x-webkit-speech name='mlwUserName' value='<?php echo esc_attr( $name ); ?>' />
				<?php
			}

			// Check for comp field.
			if ( 2 != $options->user_comp ) {
				$class = '';
				if ( 1 == $options->user_comp && ! $fields_hidden ) {
					$class = 'mlwRequiredText iCODE_required_text';
				}
				?>
				<span class='mlw_icode_question iCODE_question'><?php echo htmlspecialchars_decode( $options->business_field_text, ENT_QUOTES ); ?></span>
				<input type='text' class='<?php echo esc_attr( $class ); ?>' x-webkit-speech name='mlwUserComp' value='' />
				<?php
			}

			// Check for email field.
			if ( 2 != $options->user_email ) {
				$class = '';
				if ( 1 == $options->user_email && ! $fields_hidden ) {
					$class = 'mlwRequiredText iCODE_required_text';
				}
				?>
				<span class='mlw_icode_question iCODE_question'><?php echo htmlspecialchars_decode( $options->email_field_text, ENT_QUOTES ); ?></span>
				<input type='email' class='mlwEmail <?php echo esc_attr( $class ); ?>' x-webkit-speech name='mlwUserEmail' value='<?php echo esc_attr( $email ); ?>' />
				<?php
			}

			// Check for phone field.
			if ( 2 != $options->user_phone ) {
				$class = '';
				if ( 1 == $options->user_phone && ! $fields_hidden ) {
					$class = 'mlwRequiredText iCODE_required_text';
				}
				?>
				<span class='mlw_icode_question iCODE_question'><?php echo htmlspecialchars_decode( $options->phone_field_text, ENT_QUOTES ); ?></span>
				<input type='text' class='<?php echo esc_attr( $class ); ?>' x-webkit-speech name='mlwUserPhone' value='' />
				<?php
			}
		} elseif ( ! empty( $fields ) && is_array( $fields ) ) {

			// Cycle through each of the contact fields.
			$total_fields = count( $fields );
			for ( $i = 0; $i < $total_fields; $i++ ) {

				$class = '';
				$value = '';
				?>
				<div class="iCODE_contact_div iCODE-contact-type-<?php echo esc_attr( $fields[ $i ]['type'] ); ?>">
					<?php
					if ( 'name' == $fields[ $i ]['use'] ) {
						$value = $name;
					}
					if ( 'email' == $fields[ $i ]['use'] ) {
						$value = $email;
					}

					// Switch for contact field type.
					switch ( $fields[ $i ]['type'] ) {
						case 'text':
							if ( ( 'true' === $fields[ $i ]["required"] || true === $fields[ $i ]["required"] ) && ! $fields_hidden ) {
								$class = 'mlwRequiredText iCODE_required_text';
							}
							?>
							<span class='mlw_icode_question iCODE_question'><?php echo $fields[ $i ]['label']; ?></span>
							<input type='text' class='<?php echo esc_attr( $class ); ?>' x-webkit-speech name='contact_field_<?php echo $i; ?>' value='<?php echo esc_attr( $value ); ?>' />
							<?php
							break;

						case 'email':
							if ( ( 'true' === $fields[ $i ]["required"] || true === $fields[ $i ]["required"] ) && ! $fields_hidden ) {
								$class = 'mlwRequiredText iCODE_required_text';
							}
							?>
							<span class='mlw_icode_question iCODE_question'><?php echo $fields[ $i ]['label']; ?></span>
							<input type='text' class='mlwEmail <?php echo esc_attr( $class ); ?>' x-webkit-speech name='contact_field_<?php echo $i; ?>' value='<?php echo esc_attr( $value ); ?>' />
							<?php
							break;

						case 'checkbox':
							if ( ( 'true' === $fields[ $i ]["required"] || true === $fields[ $i ]["required"] ) && ! $fields_hidden ) {
								$class = 'mlwRequiredAccept iCODE_required_accept';
							}
							?>
							<input type='checkbox' id='contact_field_<?php echo $i; ?>' class='<?php echo esc_attr( $class ); ?>' x-webkit-speech name='contact_field_<?php echo $i; ?>' value='checked' />
							<label class='mlw_icode_question iCODE_question' for='contact_field_<?php echo $i; ?>'><?php echo $fields[ $i ]['label']; ?></label>
							<?php
							break;

						default:
							break;
					}
				?>
				</div>
				<?php
			}
		}

		// Extend contact fields section.
		do_action( 'iCODE_contact_fields_end' );

		// If logged in user should see fields.
		if ( is_user_logged_in() && 1 == $options->loggedin_user_contact ) {
			?>
			</div>
			<?php
		}

		// Return contact field HTML.
		return ob_get_clean();
	}

	/**
	 * Process the contact fields and return the values
	 *
	 * @since 5.0.0
	 * @param object $options The survey options.
	 * @return array An array of all labels and values for the contact fields
	 */
	public static function process_fields( $options ) {

		$responses = array();

		// Loads the fields for the survey.
		$fields = self::load_fields();

		// If fields are empty, check for backwards compatibility.
		if ( ( empty( $fields ) || ! is_array( $fields ) ) && ( 2 != $options->user_name || 2 != $options->user_comp || 2 != $options->user_email || 2 != $options->user_phone ) ) {
			$responses[] = array(
			'label' => 'Name',
			'value' => isset( $_POST["mlwUserName"] ) ? sanitize_text_field( $_POST["mlwUserName"] ) : 'None',
			'use' => 'name'
			);
			$responses[] = array(
			'label' => 'Business',
			'value' => isset( $_POST["mlwUserComp"] ) ? sanitize_text_field( $_POST["mlwUserComp"] ) : 'None',
			'use' => 'comp'
			);
			$responses[] = array(
			'label' => 'Email',
			'value' => isset( $_POST["mlwUserEmail"] ) ? sanitize_text_field( $_POST["mlwUserEmail"] ) : 'None',
			'use' => 'email'
			);
			$responses[] = array(
			'label' => 'Phone',
			'value' => isset( $_POST["mlwUserPhone"] ) ? sanitize_text_field( $_POST["mlwUserPhone"] ) : 'None',
			'use' => 'phone'
			);
		} elseif ( ! empty( $fields ) && is_array( $fields ) ) {
			$total_fields = count( $fields );
			for ( $i = 0; $i < $total_fields; $i++ ) {
				$field_array = array(
					'label' => $fields[ $i ]['label'],
					'value' => isset( $_POST["contact_field_$i"] ) ? sanitize_text_field( $_POST["contact_field_$i"] ) : 'None'
				);
				if ( isset( $fields[ $i ]['use'] ) ) {
					$field_array['use'] = $fields[ $i ]['use'];
				}
				$responses[] = $field_array;
			}
		}

		// For backwards compatibility, use the 'use' fields for setting $_POST values of older version of contact fields.
		foreach ( $responses as $field ) {
			if ( isset( $field['use'] ) ) {
				switch ( $field['use'] ) {
					case 'name':
						$_POST["mlwUserName"] = $field["value"];
						break;

					case 'comp':
						$_POST["mlwUserComp"] = $field["value"];
						break;

					case 'email':
						$_POST["mlwUserEmail"] = $field["value"];
						break;

					case 'phone':
						$_POST["mlwUserPhone"] = $field["value"];
						break;
				}
			}
		}

		return $responses;
	}

	/**
	 * Loads the fields
	 *
	 * @since 5.0.0
	 * @uses icodePluginHelper::get_survey_setting
	 * @return array The array of contact fields
	 */
	public static function load_fields() {
		global $mlwiCodesurveyMaster;
		return maybe_unserialize( $mlwiCodesurveyMaster->pluginHelper->get_survey_setting( 'contact_form' ) );
	}

	/**
	 * Saves the contact fields
	 *
	 * @since 5.0.0
	 * @uses icodePluginHelper::prepare_survey
	 * @uses icodePluginHelper::update_survey_setting
	 * @param int   $survey_id The ID for the survey.
	 * @param array $fields The fields for the survey.
	 */
	public static function save_fields( $survey_id, $fields ) {
		if ( self::load_fields() === $fields ) {
			return true;
		}
		global $mlwiCodesurveyMaster;
		$mlwiCodesurveyMaster->pluginHelper->prepare_survey( intval( $survey_id ) );
		return $mlwiCodesurveyMaster->pluginHelper->update_survey_setting( 'contact_form', serialize( $fields ) );
	}
}
?>
