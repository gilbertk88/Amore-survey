<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This function adds the contact tab using our API.
*
* @return type description
* @since 4.7.0
*/
function iCODE_settings_contact_tab() {
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_settings_tabs( __( "Contact", 'icode-survey-master' ), 'iCODE_options_contact_tab_content' );
}
add_action("plugins_loaded", 'iCODE_settings_contact_tab', 5);

/**
* Adds the content for the options for contact tab.
*
* @return void
* @since 4.7.0
*/
function iCODE_options_contact_tab_content() {
  global $wpdb;
  global $mlwiCodesurveyMaster;
  $survey_id = intval( $_GET["survey_id"] );

  $contact_form = iCODE_Contact_Manager::load_fields();

  wp_enqueue_script( 'iCODE_contact_admin_script', plugins_url( '../../js/iCODE-admin-contact.js' , __FILE__ ), array( 'jquery-ui-sortable' ), $mlwiCodesurveyMaster->version );
  wp_localize_script( 'iCODE_contact_admin_script', 'iCODEContactObject', array( 'contactForm' => $contact_form, 'surveyID' => $survey_id ) );
  wp_enqueue_style( 'iCODE_contact_admin_style', plugins_url( '../../css/iCODE-admin-contact.css' , __FILE__ ), array(), $mlwiCodesurveyMaster->version );

  /**
   * Example contact form array
   * array(
   *  array(
   *    'label' => 'Name',
   *    'type' => 'text',
   *    'answers' => array(
   *      'one',
   *      'two'
   *    ),
   *    'required' => true
   *    )
   *  )
   */

  ?>
  <h2><?php _e( 'Contact', 'icode-survey-master' ); ?></h2>
  <div class="contact-message"></div>
  <a class="save-contact button-primary"><?php _e( 'Save Contact Fields', 'icode-survey-master' ); ?></a>
  <div class="contact-form"></div>
   <a class="add-contact-field button-primary"><?php _e( 'Add New Field', 'icode-survey-master' ); ?></a>
  <?php
}

add_action( 'wp_ajax_iCODE_save_contact', 'iCODE_contact_form_admin_ajax' );
add_action( 'wp_ajax_nopriv_iCODE_save_contact', 'iCODE_contact_form_admin_ajax' );

/**
 * Saves the contact form from the survey settings tab
 *
 * @since 0.1.0
 * @return void
 */
function iCODE_contact_form_admin_ajax() {
  global $wpdb;
  global $mlwiCodesurveyMaster;
	$results["status"] =  iCODE_Contact_Manager::save_fields( $_POST["survey_id"], $_POST["contact_form"] );
  echo json_encode( $results );
  die();
}

?>
