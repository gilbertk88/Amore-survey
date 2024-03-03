<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Adds the Results tab to the survey Settings page.
*
* @return void
* @since 4.4.0
*/
function icode_settings_results_tab()
{
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_survey_settings_tabs(__("Results Pages", 'icode-survey-master'), 'mlw_options_results_tab_content');
}
add_action("plugins_loaded", 'icode_settings_results_tab', 5);

/**
* Adds the Results page content to the Results tab.
*
* @return void
* @since 4.4.0
*/
function mlw_options_results_tab_content()
{
	global $wpdb;
	global $mlwiCodesurveyMaster;
	$survey_id = $_GET["survey_id"];
	//Check to add new results page
	if (isset($_POST["mlw_add_landing_page"]) && $_POST["mlw_add_landing_page"] == "confirmation")
	{
		//Function variables
		$mlw_icode_landing_id = intval($_POST["mlw_add_landing_survey_id"]);
		$mlw_icode_message_after = $wpdb->get_var( $wpdb->prepare( "SELECT message_after FROM ".$wpdb->prefix."mlw_surveyzes WHERE survey_id=%d", $mlw_icode_landing_id ) );
		//Load message_after and check if it is array already. If not, turn it into one
		if (is_serialized($mlw_icode_message_after) && is_array(@unserialize($mlw_icode_message_after)))
		{
			$mlw_icode_landing_array = @unserialize($mlw_icode_message_after);
			$mlw_new_landing_array = array(0, 100, 'Enter Your Text Here', "redirect_url" => '');
			array_unshift($mlw_icode_landing_array , $mlw_new_landing_array);
			$mlw_icode_landing_array = serialize($mlw_icode_landing_array);

		}
		else
		{
			$mlw_icode_landing_array = array(array(0, 0, $mlw_icode_message_after));
			$mlw_new_landing_array = array(0, 100, 'Enter Your Text Here', "redirect_url" => '');
			array_unshift($mlw_icode_landing_array , $mlw_new_landing_array);
			$mlw_icode_landing_array = serialize($mlw_icode_landing_array);
		}

		//Update message_after with new array then check to see if worked
		$mlw_new_landing_results = $wpdb->query( $wpdb->prepare( "UPDATE ".$wpdb->prefix."mlw_surveyzes SET message_after=%s, last_activity='".date("Y-m-d H:i:s")."' WHERE survey_id=%d", $mlw_icode_landing_array, $mlw_icode_landing_id ) );
		if ( false != $mlw_new_landing_results ) {
			$mlwiCodesurveyMaster->alertManager->newAlert(__('The results page has been added successfully.', 'icode-survey-master'), 'success');
			$mlwiCodesurveyMaster->audit_manager->new_audit( "New Results Page Has Been Created For survey Number $mlw_icode_landing_id" );
		} else {
			$mlwiCodesurveyMaster->alertManager->newAlert(sprintf(__('There has been an error in this action. Please share this with the developer. Error Code: %s', 'icode-survey-master'), '0013'), 'error');
			$mlwiCodesurveyMaster->log_manager->add("Error 0013", $wpdb->last_error.' from '.$wpdb->last_query, 0, 'error');
		}
	}

	//Check to save landing pages
	if (isset($_POST["mlw_save_landing_pages"]) && $_POST["mlw_save_landing_pages"] == "confirmation")
	{
		//Function Variables
		$mlw_icode_landing_id = intval($_POST["mlw_landing_survey_id"]);
		$mlw_icode_landing_total = intval($_POST["mlw_landing_page_total"]);

		//Create new array
		$i = 1;
		$mlw_icode_new_landing_array = array();
		while ($i <= $mlw_icode_landing_total)
		{
			if ($_POST["message_after_".$i] != "Delete")
			{
				$mlw_icode_landing_each = array(intval($_POST["message_after_begin_".$i]), intval($_POST["message_after_end_".$i]), htmlspecialchars(stripslashes($_POST["message_after_".$i]), ENT_QUOTES), "redirect_url" => esc_url_raw($_POST["redirect_".$i]));
				$mlw_icode_new_landing_array[] = $mlw_icode_landing_each;
			}
			$i++;
		}
		$mlw_icode_new_landing_array = serialize($mlw_icode_new_landing_array);
		$mlw_new_landing_results = $wpdb->query( $wpdb->prepare( "UPDATE ".$wpdb->prefix."mlw_surveyzes SET message_after='%s', last_activity='".date("Y-m-d H:i:s")."' WHERE survey_id=%d", $mlw_icode_new_landing_array, $mlw_icode_landing_id ) );
		if ( false != $mlw_new_landing_results ) {
			$mlwiCodesurveyMaster->alertManager->newAlert(__('The results page has been saved successfully.', 'icode-survey-master'), 'success');
			$mlwiCodesurveyMaster->audit_manager->new_audit( "Results Pages Have Been Saved For survey Number $mlw_icode_landing_id" );
		} else {
			$mlwiCodesurveyMaster->alertManager->newAlert(sprintf(__('There has been an error in this action. Please share this with the developer. Error Code: %s', 'icode-survey-master'), '0014'), 'error');
			$mlwiCodesurveyMaster->log_manager->add("Error 0014", $wpdb->last_error.' from '.$wpdb->last_query, 0, 'error');
		}
	}

	if (isset($_GET["survey_id"]))
	{
		$table_name = $wpdb->prefix . "mlw_surveyzes";
		$mlw_survey_options = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE survey_id=%d LIMIT 1", $_GET["survey_id"]));
	}

	//Load Landing Pages
	if (is_serialized($mlw_survey_options->message_after) && is_array(@unserialize($mlw_survey_options->message_after)))
	{
    		$mlw_message_after_array = @unserialize($mlw_survey_options->message_after);
	}
	else
	{
		$mlw_message_after_array = array(array(0, 0, $mlw_survey_options->message_after, "redirect_url" => ''));
	}
	wp_enqueue_style( 'icode_admin_style', plugins_url( '../../css/iCODE-admin.css' , __FILE__ ) );
	?>
	<div id="tabs-6" class="mlw_tab_content">
		<script>
			var $j = jQuery.noConflict();
			// increase the default animation speed to exaggerate the effect
			$j.fx.speeds._default = 1000;
			function delete_landing(id)
			{
				var icode_results_editor = tinyMCE.get('message_after_'+id);
				if (icode_results_editor)
				{
					tinyMCE.get('message_after_'+id).setContent('Delete');
				}
				else
				{
					document.getElementById('message_after_'+id).value = "Delete";
				}
				document.mlw_survey_save_landing_form.submit();
			}
		</script>
		<h3 style="text-align: center;"><?php _e("Template Variables", 'icode-survey-master'); ?></h3>
		<div class="template_list_holder">
			<div class="template_variable">
				<span class="template_name">%CONTACT_X%</span> - <?php _e( 'Value user entered into contact field. X is # of contact field. For example, first contact field would be %CONTACT_1%', 'icode-survey-master' ); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%CONTACT_ALL%</span> - <?php _e( 'List user values for all contact fields', 'icode-survey-master' ); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%POINT_SCORE%</span> - <?php _e('Score for the survey when using points', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%AVERAGE_POINT%</span> - <?php _e('The average amount of points user had per question', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%AMOUNT_CORRECT%</span> - <?php _e('The number of correct answers the user had', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%TOTAL_QUESTIONS%</span> - <?php _e('The total number of questions in the survey', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%CORRECT_SCORE%</span> - <?php _e('Score for the survey when using correct answers', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%USER_NAME%</span> - <?php _e('The name the user entered before the survey', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%USER_BUSINESS%</span> - <?php _e('The business the user entered before the survey', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%USER_PHONE%</span> - <?php _e('The phone number the user entered before the survey', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%USER_EMAIL%</span> - <?php _e('The email the user entered before the survey', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%survey_NAME%</span> - <?php _e('The name of the survey', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%QUESTIONS_ANSWERS%</span> - <?php _e('Shows the question, the answer the user provided, and the correct answer', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%COMMENT_SECTION%</span> - <?php _e('The comments the user entered into comment box if enabled', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%TIMER_MINUTES%</span> - <?php _e('The amount of time user spent taking survey in minutes', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%TIMER%</span> - <?php _e('The amount of time user spent taking survey in seconds', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%CATEGORY_POINTS%%/CATEGORY_POINTS%</span> - <?php _e('The amount of points a specific category earned.', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<spane class="template_name">%AVERAGE_CATEGORY_POINTS%%/AVERAGE_CATEGORY_POINTS%</span> - <?php _e('The average amount of points a specific category earned.', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%CATEGORY_SCORE%%/CATEGORY_SCORE%</span> - <?php _e('The score a specific category earned.', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%CATEGORY_AVERAGE_POINTS%</span> - <?php _e('The average points from all categories.', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%CATEGORY_AVERAGE_SCORE%</span> - <?php _e('The average score from all categories.', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%FACEBOOK_SHARE%</span> - <?php _e('Displays button to share on Facebook.', 'icode-survey-master'); ?>
			</div>
			<div class="template_variable">
				<span class="template_name">%TWITTER_SHARE%</span> - <?php _e('Displays button to share on Twitter.', 'icode-survey-master'); ?>
			</div>
			<?php do_action('icode_template_variable_list'); ?>
		</div>
		<div style="clear:both;"></div>
		<button id="save_landing_button" class="button-primary" onclick="javascript: document.mlw_survey_save_landing_form.submit();"><?php _e('Save Results Pages', 'icode-survey-master'); ?></button>
		<button id="new_landing_button" class="button" onclick="javascript: document.mlw_survey_add_landing_form.submit();"><?php _e('Add New Results Page', 'icode-survey-master'); ?></button>
		<form method="post" action="" name="mlw_survey_save_landing_form" style=" display:inline!important;">
		<table class="widefat">
			<thead>
				<tr>
					<th>ID</th>
					<th><?php _e('Score Greater Than Or Equal To', 'icode-survey-master'); ?></th>
					<th><?php _e('Score Less Than Or Equal To', 'icode-survey-master'); ?></th>
					<th><?php _e('Results Page Shown', 'icode-survey-master'); ?></th>
					<th><?php _e('Redirect URL (Beta)', 'icode-survey-master'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$mlw_each_count = 0;
				$alternate = "";
				foreach($mlw_message_after_array as $mlw_each)
				{
					if($alternate) $alternate = "";
					else $alternate = " class=\"alternate\"";
					$mlw_each_count += 1;
					if ($mlw_each[0] == 0 && $mlw_each[1] == 0)
					{
						echo "<tr{$alternate}>";
							echo "<td>";
								echo "Default";
							echo "</td>";
							echo "<td>";
								echo "<input type='hidden' id='message_after_begin_".$mlw_each_count."' name='message_after_begin_".$mlw_each_count."' value='0'/>-";
							echo "</td>";
							echo "<td>";
								echo "<input type='hidden' id='message_after_end_".$mlw_each_count."' name='message_after_end_".$mlw_each_count."' value='0'/>-";
							echo "</td>";
							echo "<td>";
								wp_editor( htmlspecialchars_decode($mlw_each[2], ENT_QUOTES), "message_after_".$mlw_each_count );
								//echo "<textarea cols='80' rows='15' id='message_after_".$mlw_each_count."' name='message_after_".$mlw_each_count."'>".$mlw_each[2]."</textarea>";
							echo "</td>";
							echo "<td>";
								echo "<input type='text' id='redirect_".$mlw_each_count."' name='redirect_".$mlw_each_count."' value='".esc_url($mlw_each["redirect_url"])."'/>";
							echo "</td>";
						echo "</tr>";
						break;
					}
					else
					{
						echo "<tr{$alternate}>";
							echo "<td>";
								echo $mlw_each_count."<div><span style='color:green;font-size:12px;'><a onclick=\"\$j('#trying_delete_".$mlw_each_count."').show();\">".__('Delete', 'icode-survey-master')."</a></span></div><div style=\"display: none;\" id='trying_delete_".$mlw_each_count."'>".__('Are you sure?', 'icode-survey-master')."<br /><a onclick=\"delete_landing(".$mlw_each_count.")\">".__('Yes', 'icode-survey-master')."</a>|<a onclick=\"\$j('#trying_delete_".$mlw_each_count."').hide();\">".__('No', 'icode-survey-master')."</a></div>";
							echo "</td>";
							echo "<td>";
								echo "<input type='text' id='message_after_begin_".$mlw_each_count."' name='message_after_begin_".$mlw_each_count."' title='What score must the user score better than to see this page' value='".$mlw_each[0]."'/>";
							echo "</td>";
							echo "<td>";
								echo "<input type='text' id='message_after_end_".$mlw_each_count."' name='message_after_end_".$mlw_each_count."' title='What score must the user score worse than to see this page' value='".$mlw_each[1]."' />";
							echo "</td>";
							echo "<td>";
								wp_editor( htmlspecialchars_decode($mlw_each[2], ENT_QUOTES), "message_after_".$mlw_each_count );
								//echo "<textarea cols='80' rows='15' id='message_after_".$mlw_each_count."' title='What text will the user see when reaching this page' name='message_after_".$mlw_each_count."'>".$mlw_each[2]."</textarea>";
							echo "</td>";
							echo "<td>";
								echo "<input type='text' id='redirect_".$mlw_each_count."' name='redirect_".$mlw_each_count."' value='".esc_url($mlw_each["redirect_url"])."'/>";
							echo "</td>";
						echo "</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th>ID</th>
					<th><?php _e('Score Greater Than Or Equal To', 'icode-survey-master'); ?></th>
					<th><?php _e('Score Less Than Or Equal To', 'icode-survey-master'); ?></th>
					<th><?php _e('Results Page Shown', 'icode-survey-master'); ?></th>
					<th><?php _e('Redirect URL (Beta)', 'icode-survey-master'); ?></th>
				</tr>
			</tfoot>
		</table>
		<input type='hidden' name='mlw_save_landing_pages' value='confirmation' />
		<input type='hidden' name='mlw_landing_survey_id' value='<?php echo $survey_id; ?>' />
		<input type='hidden' name='mlw_landing_page_total' value='<?php echo $mlw_each_count; ?>' />
		<button id="save_landing_button" class="button-primary" onclick="javascript: document.mlw_survey_save_landing_form.submit();"><?php _e('Save Results Pages', 'icode-survey-master'); ?></button>
		</form>
		<form method="post" action="" name="mlw_survey_add_landing_form" style=" display:inline!important;">
			<input type='hidden' name='mlw_add_landing_page' value='confirmation' />
			<input type='hidden' name='mlw_add_landing_survey_id' value='<?php echo $survey_id; ?>' />
			<button id="new_landing_button" class="button" onclick="javascript: document.mlw_survey_add_landing_form.submit();"><?php _e('Add New Results Page', 'icode-survey-master'); ?></button>
		</form>
	</div>
	<?php
}
?>
