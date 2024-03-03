<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action("plugins_loaded", 'icode_question_type_multiple_choice');

/**
* Registers the multiple choice type
*
* @return void
* @since 4.4.0
*/
function icode_question_type_multiple_choice()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Multiple Choice", 'icode-survey-master'), 'icode_multiple_choice_display', true, 'icode_multiple_choice_review', null, null, 0);
}

/**
* This function shows the content of the multiple choice question.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_multiple_choice_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredRadio";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<div class='icode_radio_answers $mlw_requireClass'>";
  if (is_array($answers))
  {
    $mlw_answer_total = 0;
    foreach($answers as $answer)
    {
      $mlw_answer_total++;
      if ($answer[0] != "")
      {
				$question_display .= "<div class='icode_mc_answer_wrap' id='question".$id."-".esc_attr($answer[0])."'>";
        $question_display .= "<input type='radio' class='icode_survey_radio' name='question".$id."' id='question".$id."_".$mlw_answer_total."' value='".htmlentities(esc_attr($answer[0]))."' /> <label for='question".$id."_".$mlw_answer_total."'>".htmlspecialchars_decode($answer[0], ENT_QUOTES)."</label>";
				$question_display .= "</div>";
      }
    }
    $question_display .= "<input type='radio' style='display: none;' name='question".$id."' id='question".$id."_none' checked='checked' value='No Answer Provided' />";
  }
  $question_display .= "</div>";
  return $question_display;
}

/**
* This function determines how the multiple choice question is graded.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_multiple_choice_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  if ( isset( $_POST["question".$id] ) ) {
    $mlw_user_answer = stripslashes( $_POST["question".$id] );
  } else {
    $mlw_user_answer = " ";
  }
  foreach($answers as $answer)
  {
    if ( $mlw_user_answer == esc_attr( $answer[0] ) )
    {
      $return_array["points"] = $answer[1];
      $return_array["user_text"] = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
      if ($answer[2] == 1)
      {
        $return_array["correct"] = "correct";
      }
    }
    if ($answer[2] == 1)
    {
      $return_array["correct_text"] = htmlspecialchars_decode($answer[0], ENT_QUOTES);
    }
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_horizontal_multiple_choice');

/**
* This function registers the horizontal multiple choice type.
*
* @return void
* @since 4.4.0
*/
function icode_question_type_horizontal_multiple_choice()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Horizontal Multiple Choice", 'icode-survey-master'), 'icode_horizontal_multiple_choice_display', true, 'icode_horizontal_multiple_choice_review', null, null, 1);
}

/**
* This function shows the content of the horizontal multiple choice question.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_horizontal_multiple_choice_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredRadio";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<div class='icode_radio_answers $mlw_requireClass'>";
  if (is_array($answers))
  {
    $mlw_answer_total = 0;
    foreach($answers as $answer)
    {
      $mlw_answer_total++;
      if ($answer[0] != "")
      {
        $question_display .= "<span class='mlw_horizontal_choice'><input type='radio' class='icode_survey_radio' name='question".$id."' id='question".$id."_".$mlw_answer_total."' value='".esc_attr($answer[0])."' /><label for='question".$id."_".$mlw_answer_total."'>".htmlspecialchars_decode($answer[0], ENT_QUOTES)."</label></span>";
      }
    }
    $question_display .= "<input type='radio' style='display: none;' name='question".$id."' id='question".$id."_none' checked='checked' value='No Answer Provided' />";
  }
  $question_display .= "</div>";
  return $question_display;
}

/**
* This function determines how the question is graded.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_horizontal_multiple_choice_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  if ( isset( $_POST["question".$id] ) ) {
    $mlw_user_answer = htmlspecialchars( stripslashes( $_POST["question".$id] ), ENT_QUOTES );
  } else {
    $mlw_user_answer = " ";
  }
  foreach($answers as $answer)
  {
    if ( $mlw_user_answer == esc_attr( $answer[0] ) )
    {
      $return_array["points"] = $answer[1];
      $return_array["user_text"] = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
      if ($answer[2] == 1)
      {
        $return_array["correct"] = "correct";
      }
    }
    if ($answer[2] == 1)
    {
      $return_array["correct_text"] = htmlspecialchars_decode($answer[0], ENT_QUOTES);
    }
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_drop_down');

/**
* This function registers the drop down question type
*
* @return void
* @since 4.4.0
*/
function icode_question_type_drop_down()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Drop Down", 'icode-survey-master'), 'icode_drop_down_display', true, 'icode_drop_down_review', null, null, 2);
}

/**
* This function shows the content of the multiple choice question.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_drop_down_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
	$required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting( $id, 'required' );
  if ( 0 == $required ) {
		$require_class = "iCODERequiredSelect";
	} else {
		$require_class = "";
	}
  $question_display .= "<span class='mlw_icode_question'>" . do_shortcode( htmlspecialchars_decode( $question, ENT_QUOTES ) ) . "</span>";
  $question_display .= "<select class='iCODE_select $require_class' name='question".$id."'>";
	$question_display .= "<option value='No Answer Provided' selected='selected'>&nbsp;</option>";
  if (is_array($answers))
  {
    $mlw_answer_total = 0;
    foreach($answers as $answer)
    {
      $mlw_answer_total++;
      if ($answer[0] != "")
      {
        $question_display .= "<option value='".esc_attr($answer[0])."'>".htmlspecialchars_decode($answer[0], ENT_QUOTES)."</option>";
      }
    }
  }
  $question_display .= "</select>";
  return $question_display;
}

/**
* This function determines how the question is graded
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_drop_down_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  if (isset($_POST["question".$id])) {
    $mlw_user_answer = htmlspecialchars( stripslashes( $_POST["question".$id] ), ENT_QUOTES );
  } else {
    $mlw_user_answer = " ";
  }
  foreach($answers as $answer)
  {
    if ( $mlw_user_answer == esc_attr( $answer[0] ) )
    {
      $return_array["points"] = $answer[1];
      $return_array["user_text"] = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
      if ($answer[2] == 1)
      {
        $return_array["correct"] = "correct";
      }
    }
    if ($answer[2] == 1)
    {
      $return_array["correct_text"] = htmlspecialchars_decode($answer[0], ENT_QUOTES);
    }
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_small_open');

/**
* This function registers the small open question type
*
* @return void
* @since 4.4.0
*/
function icode_question_type_small_open()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Small Open Answer", 'icode-survey-master'), 'icode_small_open_display', true, 'icode_small_open_review', null, null, 3);
}

/**
* This function shows the content of the small open answer question.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_small_open_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredText";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<input type='text' class='mlw_answer_open_text $mlw_requireClass' name='question".$id."' />";
  return $question_display;
}

/**
* This function reviews the small open answer.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_small_open_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  if ( isset( $_POST["question".$id] ) ) {
    $decode_user_answer = strval( stripslashes( htmlspecialchars_decode( $_POST["question".$id], ENT_QUOTES ) ) );
    $mlw_user_answer = trim( preg_replace( '/\s\s+/', ' ', str_replace( "\n", " ", $decode_user_answer ) ) );
  } else {
    $mlw_user_answer = " ";
  }
  $return_array['user_text'] = $mlw_user_answer;
  foreach($answers as $answer)
  {
    $decode_correct_text = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
    $return_array['correct_text'] = trim( preg_replace( '/\s\s+/', ' ', str_replace( "\n", " ", $decode_correct_text ) ) );
    if (mb_strtoupper($return_array['user_text']) == mb_strtoupper($return_array['correct_text']))
    {
      $return_array['correct'] = "correct";
      $return_array['points'] = $answer[1];
      break;
    }
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_multiple_response');

/**
* This function registers the multiple response question type
*
* @return void
* @since 4.4.0
*/
function icode_question_type_multiple_response()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Multiple Response", 'icode-survey-master'), 'icode_multiple_response_display', true, 'icode_multiple_response_review', null, null, 4);
}

/**
* This function shows the content of the multiple response question
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_multiple_response_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredCheck";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<div class='icode_check_answers $mlw_requireClass'>";
  if (is_array($answers))
  {
    $mlw_answer_total = 0;
    foreach($answers as $answer)
    {
      $mlw_answer_total++;
      if ($answer[0] != "")
      {
				$question_display .= '<div class="iCODE_check_answer">';
        $question_display .= "<input type='hidden' name='question".$id."' value='This value does not matter' />";
        $question_display .= "<input type='checkbox' name='question".$id."_".$mlw_answer_total."' id='question".$id."_".$mlw_answer_total."' value='".esc_attr($answer[0])."' /> <label for='question".$id."_".$mlw_answer_total."'>".htmlspecialchars_decode($answer[0], ENT_QUOTES)."</label>";
				$question_display .= '</div>';
      }
    }
  }
  $question_display .= "</div>";
  return $question_display;
}

/**
* This function determines how the multiple response is graded,
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_multiple_response_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  $user_correct = 0;
  $total_correct = 0;
  $total_answers = count($answers);
  foreach($answers as $answer)
  {
    for ($i = 1; $i <= $total_answers; $i++)
    {
        if (isset($_POST["question".$id."_".$i]) && htmlspecialchars(stripslashes($_POST["question".$id."_".$i]), ENT_QUOTES) == esc_attr($answer[0]))
        {
          $return_array["points"] += $answer[1];
          $return_array["user_text"] .= strval(htmlspecialchars_decode($answer[0], ENT_QUOTES)).".";
          if ($answer[2] == 1)
          {
            $user_correct += 1;
          }
          else
          {
            $user_correct = -1;
          }
        }
    }
    if ($answer[2] == 1)
    {
      $return_array["correct_text"] .= htmlspecialchars_decode($answer[0], ENT_QUOTES).".";
      $total_correct++;
    }
  }
  if ($user_correct == $total_correct)
  {
    $return_array["correct"] = "correct";
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_large_open');

/**
* This function registers the large open question type.
*
* @since 4.4.0
*/
function icode_question_type_large_open()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Large Open Answer", 'icode-survey-master'), 'icode_large_open_display', true, 'icode_large_open_review', null, null, 5);
}

/**
* This function displays the content of the large open question.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_large_open_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredText";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<textarea class='mlw_answer_open_text $mlw_requireClass' cols='70' rows='5' name='question".$id."' /></textarea>";
  return $question_display;
}

/**
* This function determines how the large open question is graded
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_large_open_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  if ( isset( $_POST["question".$id] ) ) {
    $decode_user_answer = strval( stripslashes( htmlspecialchars_decode( $_POST["question".$id], ENT_QUOTES ) ) );
    $mlw_user_answer = trim( preg_replace( '/\s\s+/', ' ', str_replace( "\n", " ", $decode_user_answer ) ) );
  } else {
    $mlw_user_answer = " ";
  }
  $return_array['user_text'] = $mlw_user_answer;
  foreach($answers as $answer)
  {
    $decode_correct_text = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
    $return_array['correct_text'] = trim( preg_replace( '/\s\s+/', ' ', str_replace( "\n", " ", $decode_correct_text ) ) );
    if (mb_strtoupper($return_array['user_text']) == mb_strtoupper($return_array['correct_text']))
    {
      $return_array['correct'] = "correct";
      $return_array['points'] = $answer[1];
      break;
    }
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_text_block');

/**
* This function registers the text block question type
*
* @return void
* @since 4.4.0
*/
function icode_question_type_text_block()
{
	global $mlwiCodesurveyMaster;
	$edit_args = array(
		'inputs' => array(
			'question'
		),
		'information' => '',
		'extra_inputs' => array(),
		'function' => ''
	);
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Text/HTML Section", 'icode-survey-master'), 'icode_text_block_display', false, null, $edit_args, null, 6);
}


/**
* This function displays the contents of the text block question type.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_text_block_display($id, $question, $answers)
{
  $question_display = '';
  $question_display .= do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES));
  return $question_display;
}

add_action("plugins_loaded", 'icode_question_type_number');

/**
* This function registers the number question type
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return void
* @since 4.4.0
*/
function icode_question_type_number()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Number", 'icode-survey-master'), 'icode_number_display', true, 'icode_number_review', null, null, 7);
}


/**
* This function shows the content of the multiple choice question.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_number_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredNumber";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<input type='number' class='mlw_answer_number $mlw_requireClass' name='question".$id."' />";
  return $question_display;
}


/**
* This function determines how the number question type is graded.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_number_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  if ( isset( $_POST["question".$id] ) ) {
    $mlw_user_answer = strval( stripslashes( htmlspecialchars_decode( $_POST["question".$id], ENT_QUOTES ) ) );
  } else {
    $mlw_user_answer = " ";
  }
  $return_array['user_text'] = $mlw_user_answer;
  foreach($answers as $answer)
  {
    $return_array['correct_text'] = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
    if (strtoupper($return_array['user_text']) == strtoupper($return_array['correct_text']))
    {
      $return_array['correct'] = "correct";
      $return_array['points'] = $answer[1];
      break;
    }
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_accept');

/**
* This function registers the accept question type.
*
* @return void Description
* @since 4.4.0
*/
function icode_question_type_accept()
{
	global $mlwiCodesurveyMaster;
	$edit_args = array(
		'inputs' => array(
			'question',
			'required'
		),
		'information' => '',
		'extra_inputs' => array(),
		'function' => ''
	);
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Accept", 'icode-survey-master'), 'icode_accept_display', false, null, $edit_args, null, 8);
}

/**
* This function displays the accept question
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_accept_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredAccept";} else {$mlw_requireClass = "";}
	$question_display .= "<div class='icode_accept_answers'>";
  $question_display .= "<input type='checkbox' id='mlwAcceptance' class='$mlw_requireClass ' />";
  $question_display .= "<label for='mlwAcceptance'><span class='icode_accept_text'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span></label>";
  $question_display .= "</div>";
  return $question_display;
}

add_action("plugins_loaded", 'icode_question_type_captcha');

/**
* This function registers the captcha question
*
*
* @since 4.4.0
*/
function icode_question_type_captcha()
{
	global $mlwiCodesurveyMaster;
	$edit_args = array(
		'inputs' => array(
			'question',
			'required'
		),
		'information' => '',
		'extra_inputs' => array(),
		'function' => ''
	);
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Captcha", 'icode-survey-master'), 'icode_captcha_display', false, null, $edit_args, null, 9);
}


/**
* This function displays the captcha question
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_captcha_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredCaptcha";} else {$mlw_requireClass = "";}
  $question_display .= "<div class='mlw_captchaWrap'>";
  $question_display .= "<canvas alt='' id='mlw_captcha' class='mlw_captcha' width='100' height='50'></canvas>";
  $question_display .= "</div>";
  $question_display .= "<span class='mlw_icode_question'>";
  $question_display .= do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<input type='text' class='mlw_answer_open_text $mlw_requireClass' id='mlw_captcha_text' name='mlw_user_captcha'/>";
  $question_display .= "<input type='hidden' name='mlw_code_captcha' id='mlw_code_captcha' value='none' />";
  $question_display .= "<script>
  var mlw_code = '';
  var mlw_chars = '0123456789ABCDEFGHIJKL!@#$%^&*()MNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
  var mlw_code_length = 5;
  for (var i=0; i<mlw_code_length; i++) {
          var rnum = Math.floor(Math.random() * mlw_chars.length);
          mlw_code += mlw_chars.substring(rnum,rnum+1);
      }
      var mlw_captchaCTX = document.getElementById('mlw_captcha').getContext('2d');
      mlw_captchaCTX.font = 'normal 24px Verdana';
      mlw_captchaCTX.strokeStyle = '#000000';
      mlw_captchaCTX.clearRect(0,0,100,50);
      mlw_captchaCTX.strokeText(mlw_code,10,30,70);
      mlw_captchaCTX.textBaseline = 'middle';
      document.getElementById('mlw_code_captcha').value = mlw_code;
      </script>
      ";
  return $question_display;
}

add_action("plugins_loaded", 'icode_question_type_horizontal_multiple_response');

/**
* This function registers the horizontal multiple response question
*
* @return void
* @since 4.4.0
*/
function icode_question_type_horizontal_multiple_response()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Horizontal Multiple Response", 'icode-survey-master'), 'icode_horizontal_multiple_response_display', true, 'icode_horizontal_multiple_response_review', null, null, 10);
}


/**
* This function displays the content of the multiple response question type
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Contains all the content of the question
* @since 4.4.0
*/
function icode_horizontal_multiple_response_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredCheck";} else {$mlw_requireClass = "";}
  $question_display .= "<span class='mlw_icode_question'>".do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES))."</span>";
  $question_display .= "<div class='icode_check_answers $mlw_requireClass'>";
  if (is_array($answers))
  {
    $mlw_answer_total = 0;
    foreach($answers as $answer)
    {
      $mlw_answer_total++;
      if ($answer[0] != "")
      {
        $question_display .= "<input type='hidden' name='question".$id."' value='This value does not matter' />";
        $question_display .= "<span class='mlw_horizontal_multiple'><input type='checkbox' name='question".$id."_".$mlw_answer_total."' id='question".$id."_".$mlw_answer_total."' value='".esc_attr($answer[0])."' /> <label for='question".$id."_".$mlw_answer_total."'>".htmlspecialchars_decode($answer[0], ENT_QUOTES)."&nbsp;</label></span>";
      }
    }
  }
  $question_display .= "</div>";
  return $question_display;
}


/**
* This function determines how the multiple response is graded.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the Results page
* @since 4.4.0
*/
function icode_horizontal_multiple_response_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
  $user_correct = 0;
  $total_correct = 0;
  $total_answers = count($answers);
  foreach($answers as $answer)
  {
    for ($i = 1; $i <= $total_answers; $i++)
    {
        if (isset($_POST["question".$id."_".$i]) && htmlspecialchars(stripslashes($_POST["question".$id."_".$i]), ENT_QUOTES) == esc_attr($answer[0]))
        {
          $return_array["points"] += $answer[1];
          $return_array["user_text"] .= strval(htmlspecialchars_decode($answer[0], ENT_QUOTES)).".";
          if ($answer[2] == 1)
          {
            $user_correct += 1;
          }
          else
          {
            $user_correct = -1;
          }
        }
    }
    if ($answer[2] == 1)
    {
      $return_array["correct_text"] .= htmlspecialchars_decode($answer[0], ENT_QUOTES).".";
      $total_correct++;
    }
  }
  if ($user_correct == $total_correct)
  {
    $return_array["correct"] = "correct";
  }
  return $return_array;
}

add_action("plugins_loaded", 'icode_question_type_fill_blank');

/**
* This function registers the fill in the blank question type
*
* @return void
* @since 4.4.0
*/
function icode_question_type_fill_blank()
{
	global $mlwiCodesurveyMaster;
	$edit_args = array(
		'inputs' => array(
			'question',
			'answer',
			'hint',
			'correct_info',
			'comments',
			'category',
			'required'
		),
		'information' => __('For fill in the blank types, use %BLANK% to represent where to put the text box in your text.', 'icode-survey-master'),
		'extra_inputs' => array(),
		'function' => ''
	);
	$mlwiCodesurveyMaster->pluginHelper->register_question_type(__("Fill In The Blank", 'icode-survey-master'), 'icode_fill_blank_display', true, 'icode_fill_blank_review', $edit_args );
}


/**
* This function displays the fill in the blank question
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $question_display Returns the content of the question
* @since 4.4.0
*/
function icode_fill_blank_display($id, $question, $answers)
{
  $question_display = '';
  global $mlwiCodesurveyMaster;
  $required = $mlwiCodesurveyMaster->pluginHelper->get_question_setting($id, 'required');
  if ($required == 0) {$mlw_requireClass = "mlwRequiredText";} else {$mlw_requireClass = "";}
	$input_text = "<input type='text' class='icode_fill_blank $mlw_requireClass' name='question".$id."' />";
	if (strpos($question, '%BLANK%') !== false)
	{
		$question = str_replace( "%BLANK%", $input_text, do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES)));
	}
  $question_display = "<span class='mlw_icode_question'>$question</span>";

  return $question_display;
}


/**
* This function determines how the fill in the blank question is graded.
*
* @params $id The ID of the multiple choice question
* @params $question The question that is being edited.
* @params @answers The array that contains the answers to the question.
* @return $return_array Returns the graded question to the results page
* @since 4.4.0
*/
function icode_fill_blank_review($id, $question, $answers)
{
  $return_array = array(
    'points' => 0,
    'correct' => 'incorrect',
    'user_text' => '',
    'correct_text' => ''
  );
	if (strpos($question, '%BLANK%') !== false)
	{
		$return_array['question_text'] = str_replace( "%BLANK%", "__________", do_shortcode(htmlspecialchars_decode($question, ENT_QUOTES)));
	}
  if ( isset( $_POST["question".$id] ) ) {
    $decode_user_answer = strval( stripslashes( htmlspecialchars_decode( $_POST["question".$id], ENT_QUOTES ) ) );
    $mlw_user_answer = trim( preg_replace( '/\s\s+/', ' ', str_replace( "\n", " ", $decode_user_answer ) ) );
  } else {
    $mlw_user_answer = " ";
  }
  $return_array['user_text'] = $mlw_user_answer;
  foreach($answers as $answer)
  {
    $decode_correct_text = strval(htmlspecialchars_decode($answer[0], ENT_QUOTES));
    $return_array['correct_text'] = trim( preg_replace( '/\s\s+/', ' ', str_replace( "\n", " ", $decode_correct_text ) ) );
    if (mb_strtoupper($return_array['user_text']) == mb_strtoupper($return_array['correct_text']))
    {
      $return_array['correct'] = "correct";
      $return_array['points'] = $answer[1];
      break;
    }
  }
  return $return_array;
}
?>
