<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This function generates the admin side survey results page
*
* @return void
* @since 4.4.0
*/
function iCODE_generate_admin_results_page() {

	// Makes sure user has the right privledges
	if ( ! current_user_can('moderate_comments') ) {
		return;
	}

	// Retrieves the current stab and all registered tabs
	global $mlwiCodesurveyMaster;
	$active_tab = strtolower(str_replace( " ", "-", isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : __( 'survey Results', 'icode-survey-master' )));
	$tab_array = $mlwiCodesurveyMaster->pluginHelper->get_admin_results_tabs();
	global $wpdb;
	$mysurvey = $wpdb->get_results( $wpdb->prepare("SELECT `point_score` FROM `wp_mlw_results` WHERE `deleted`=0", ARRAY_N ));
	$results= $mysurvey;
	$Soft=$Medium=0;
	//count particular bracket.
	$results_no=count($results);
	//$results_no-1;
	for($i=0;$i<$results_no; $i++){
		if(1<=$results[$i]->point_score && $results[$i]->point_score<=1.9){
			$Soft++;
		}
		if(1.9<$results[$i]->point_score){
			$Medium++;
		}
	}
	$PSoft=$Soft/$results_no*100;
	$PMedium=$Medium/$results_no*100;
	?>

	<div class="wrap">
			<h2>Edit plugin</h2>
			<table class="widefat fixed" cellspacing="0">
		    <thead>
		    <tr>

		            <th id="cb" class="manage-column " scope="col"></th>
		            <th id="columnname" class="manage-column" scope="col">Luxury Medium</th>
		            <th id="columnname" class="manage-column" scope="col">Luxury Firm</th>
		            <th id="columnname" class="manage-column" scope="col">Surveys taken</th>

		    </tr>
		    </thead>
		    <tbody>
		    	 <tr class="alternate">
		            <th class="check-column" scope="row">Actual results</th>
		            <td class="column-columnname"><?php echo $Soft; ?></td>
		            <td class="column-columnname"><?php echo $Medium; ?></td>
		            <td class="column-columnname"><?php echo $results_no; ?></td>
		        </tr>
		        <tr>
		            <th class="check-column" scope="row">Percentage</th>
		            <td class="column-columnname"><?php echo substr($PSoft,0,4); ?>%</td>
		            <td class="column-columnname"><?php echo substr($PMedium,0,4); ?>%</td>
		            <td class="column-columnname">100%</td>
		        </tr>
		        
		    </tbody>
		</table>
		<h2><?php _e('survey Results', 'icode-survey-master'); ?></h2>
		<?php $mlwiCodesurveyMaster->alertManager->showAlerts(); ?>
		<h2 class="nav-tab-wrapper">
			<?php
			// Cycles through the tabs and creates the navigation
			foreach( $tab_array as $tab ) {
				$active_class = '';
				if ( $active_tab == $tab['slug'] ) {
					$active_class = 'nav-tab-active';
				}
				echo "<a href=\"?page=mlw_survey_results&tab={$tab['slug']}\" class=\"nav-tab $active_class\">{$tab['title']}</a>";
			}
			?>
		</h2>
		<div>
		<?php
			// Locates the active tab and calls its content function
			foreach( $tab_array as $tab ) {
				if ( $active_tab == $tab['slug'] ) {
					call_user_func( $tab['function'] );
				}
			}
		?>
		</div>
	</div>

	<?php

}

/**
 * Adds Overview Tab To Admin Results Page
 *
 * @since 5.0.0
 * @return void
 */
function iCODE_results_overview_tab() {
	global $mlwiCodesurveyMaster;
	$mlwiCodesurveyMaster->pluginHelper->register_admin_results_tab( __( "survey Results", 'icode-survey-master' ), "iCODE_results_overview_tab_content" );
}
add_action( 'plugins_loaded', 'iCODE_results_overview_tab' );

/**
 * Generates HTML For Overview Tab
 *
 * @since 5.0.0
 * @return void
 */
function iCODE_results_overview_tab_content() {

	global $wpdb;
	global $mlwiCodesurveyMaster;

	// If nonce is correct, delete results
  if ( isset( $_POST["delete_results_nonce"] ) && wp_verify_nonce( $_POST['delete_results_nonce'], 'delete_results') ) {

		// Variables from delete result form
		$mlw_delete_results_id = intval( $_POST["result_id"] );
		$mlw_delete_results_name = sanitize_text_field( $_POST["delete_survey_name"] );

		// Update table to mark results as deleted
		$results = $wpdb->update(
			$wpdb->prefix . "mlw_results",
			array(
				'deleted' => 1
			),
			array( 'result_id' => $mlw_delete_results_id ),
			array(
				'%d'
			),
			array( '%d' )
		);

		if ( false === $results ) {
			$error = $wpdb->last_error;
			if ( empty( $error ) ) {
				$error = __( 'Unknown error', 'icode-survey-master' );
			}
			$mlwiCodesurveyMaster->alertManager->newAlert( sprintf( __( 'There was an error when deleting this result. Error from WordPress: %s', 'icode-survey-master' ), $error ), 'error' );
			$mlwiCodesurveyMaster->log_manager->add( 'Error deleting result', "Tried {$wpdb->last_query} but got $error.", 0, 'error' );
		} else {
			$mlwiCodesurveyMaster->alertManager->newAlert( __('Your results has been deleted successfully.','icode-survey-master'), 'success');
			$mlwiCodesurveyMaster->audit_manager->new_audit( "Results Has Been Deleted From: $mlw_delete_results_name" );
			
		}
	}

	// Check if bulk delete has been selected. If so, verify nonce.
	if ( isset( $_POST["bulk_delete"] ) && wp_verify_nonce( $_POST['bulk_delete_nonce'], 'bulk_delete') ) {

		// Ensure the POST variable is an array
		if ( is_array( $_POST["delete_results"] ) ) {

			// Cycle through the POST array which should be an array of the result ids of the results the user wishes to delete
			foreach( $_POST["delete_results"] as $result ) {

				// Santize by ensuring the value is an int
				$result_id = intval( $result );
				$wpdb->update(
					$wpdb->prefix."mlw_results",
					array(
						'deleted' => 1,
					),
					array( 'result_id' => $result_id ),
					array(
						'%d'
					),
					array( '%d' )
				);
			}

			$mlwiCodesurveyMaster->audit_manager->new_audit( "Results Have Been Bulk Deleted" );
		}
	}

	// Prepares the SQL to retrieve the results
	$mlw_icode_table_limit = 40;
	$search_phrase_sql = '';
	$order_by_sql = 'ORDER BY result_id DESC';
	if ( isset( $_GET["icode_search_phrase"] ) && !empty( $_GET["icode_search_phrase"] ) ) {
		$search_phrase = $_GET["icode_search_phrase"];
		$mlw_icode_results_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(result_id) FROM {$wpdb->prefix}mlw_results WHERE deleted='0' AND (survey_name LIKE %s OR name LIKE %s OR business LIKE %s OR email LIKE %s OR phone LIKE %s)", '%' . $wpdb->esc_like($search_phrase) . '%', '%' . $wpdb->esc_like($search_phrase) . '%', '%' . $wpdb->esc_like($search_phrase) . '%', '%' . $wpdb->esc_like($search_phrase) . '%', '%' . $wpdb->esc_like($search_phrase) . '%' ) );
		$search_phrase_sql = " AND (survey_name LIKE '%$search_phrase%' OR name LIKE '%$search_phrase%' OR business LIKE '%$search_phrase%' OR email LIKE '%$search_phrase%' OR phone LIKE '%$search_phrase%')";
	} else {
		$mlw_icode_results_count = $wpdb->get_var( "SELECT COUNT(result_id) FROM " . $wpdb->prefix . "mlw_results WHERE deleted='0'" );
	}
	if ( isset( $_GET["icode_order_by"] ) ) {
		 switch ( $_GET["icode_order_by"] )
		 {
			 case 'survey_name':
				 $order_by_sql = " ORDER BY survey_name DESC";
				 break;
			 case 'name':
				 $order_by_sql = " ORDER BY name DESC";
				 break;
			 case 'point_score':
				 $order_by_sql = " ORDER BY point_score DESC";
				 break;
			 case 'correct_score':
				 $order_by_sql = " ORDER BY correct_score DESC";
				 break;
			 default:
				 $order_by_sql = " ORDER BY result_id DESC";
		 }
	}


	if( isset( $_GET['mlw_result_page'] ) ) {
	   $mlw_icode_result_page = intval( $_GET['mlw_result_page'] ) + 1;
	   $mlw_icode_result_begin = $mlw_icode_table_limit * $mlw_icode_result_page ;
	} else {
	   $mlw_icode_result_page = 0;
	   $mlw_icode_result_begin = 0;
	}
	$mlw_icode_result_left = $mlw_icode_results_count - ($mlw_icode_result_page * $mlw_icode_table_limit);
	if ( isset( $_GET["survey_id"] ) && $_GET["survey_id"] != "" ) {
		$survey_id = intval( $_GET["survey_id"] );
		$mlw_survey_data = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "mlw_results WHERE deleted='0' AND survey_id=$survey_id $search_phrase_sql $order_by_sql LIMIT $mlw_icode_result_begin, $mlw_icode_table_limit" );
	} else {
		$mlw_survey_data = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "mlw_results WHERE deleted='0'$search_phrase_sql $order_by_sql LIMIT $mlw_icode_result_begin, $mlw_icode_table_limit" );
	}

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'icode_admin_js', plugins_url( '../../js/admin.js', __FILE__ ) );
	wp_enqueue_style( 'icode_jquery_redmond_theme', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css' );
	?>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
		function deleteResults(id,surveyName){
			$j("#delete_dialog").dialog({
				autoOpen: false,
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#delete_dialog").dialog('open');
			var idHidden = document.getElementById("result_id");
			var idHiddenName = document.getElementById("delete_survey_name");
			idHidden.value = id;
			idHiddenName.value = surveyName;
		};
	</script>
	<div class="tablenav top">
		<div class="alignleft actions bulkactions">
			<a href="javascript: document.bulk_delete_form.submit();" class="button action">Bulk Delete</a>
		</div>
		<div class="tablenav-pages">
			<span class="displaying-num"><?php echo sprintf(_n('One result', '%s results', $mlw_icode_results_count, 'icode-survey-master'), number_format_i18n($mlw_icode_results_count)); ?></span>
			<span class="pagination-links">
				<?php
				$mlw_icode_previous_page = 0;
				$mlw_current_page = $mlw_icode_result_page+1;
				$mlw_total_pages = ceil($mlw_icode_results_count/$mlw_icode_table_limit);

				$url_query_string = '';
				if ( isset( $_GET["survey_id"] ) && $_GET["survey_id"] != "" ) {
					$url_query_string .= '&&survey_id='.intval( $_GET["survey_id"] );
				}

				if ( isset( $_GET["icode_search_phrase"] ) && !empty( $_GET["icode_search_phrase"] ) ) {
					$url_query_string .= '&&icode_search_phrase='.$_GET["icode_search_phrase"];
				}

				if ( isset( $_GET["icode_order_by"] ) && !empty( $_GET["icode_order_by"] ) ) {
					$url_query_string .= '&&icode_order_by='.$_GET["icode_order_by"];
				}

				if( $mlw_icode_result_page > 0 )
				{
						$mlw_icode_previous_page = $mlw_icode_result_page - 2;
						echo "<a class=\"prev-page\" title=\"Go to the previous page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_previous_page$url_query_string\"><</a>";
						echo "<span class=\"paging-input\">$mlw_current_page of $mlw_total_pages</span>";
						if( $mlw_icode_result_left > $mlw_icode_table_limit )
						{
							echo "<a class=\"next-page\" title=\"Go to the next page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_result_page$url_query_string\">></a>";
						}
					else
					{
						echo "<a class=\"next-page disabled\" title=\"Go to the next page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_result_page$url_query_string\">></a>";
					}
				}
				else if( $mlw_icode_result_page == 0 )
				{
					if( $mlw_icode_result_left > $mlw_icode_table_limit )
					{
						echo "<a class=\"prev-page disabled\" title=\"Go to the previous page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_previous_page$url_query_string\"><</a>";
						echo "<span class=\"paging-input\">$mlw_current_page of $mlw_total_pages</span>";
						echo "<a class=\"next-page\" title=\"Go to the next page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_result_page$url_query_string\">></a>";
					}
				}
				else if( $mlw_icode_result_left < $mlw_icode_table_limit )
				{
					$mlw_icode_previous_page = $mlw_icode_result_page - 2;
					echo "<a class=\"prev-page\" title=\"Go to the previous page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_previous_page$url_query_string\"><</a>";
					echo "<span class=\"paging-input\">$mlw_current_page of $mlw_total_pages</span>";
					echo "<a class=\"next-page disabled\" title=\"Go to the next page\" href=\"?page=mlw_survey_results&&mlw_result_page=$mlw_icode_result_page$url_query_string\">></a>";
				}
				?>
			</span>
			<br class="clear">
		</div>
	</div>
	<form action='' method="get">
		<?php
		if ( isset( $_GET["survey_id"] ) ) {
			?>
			<input type="hidden" name="survey_id" value="<?php echo $_GET["survey_id"]; ?>" />
			<?php
		}
		?>
		<input type="hidden" name="page" value="mlw_survey_results">
		<p class="search-box">
			<label for="icode_search_phrase"><?php _e( 'Search Results', 'icode-survey-master' ); ?></label>
			<input type="search" id="icode_search_phrase" name="icode_search_phrase" value="">
			<label for="icode_order_by"><?php _e( 'Order By', 'icode-survey-master' ); ?></label>
			<select id="icode_order_by" name="icode_order_by">
				<option value="survey_name"><?php _e( 'survey Name', 'icode-survey-master' ); ?></option>
				<option value="name"><?php _e( 'User Name', 'icode-survey-master' ); ?></option>
				<option value="point_score"><?php _e( 'Points', 'icode-survey-master' ); ?></option>
				<option value="correct_score"><?php _e( 'Correct Percent', 'icode-survey-master' ); ?></option>
				<option value="default"><?php _e( 'Default (Time)', 'icode-survey-master' ); ?></option>
			</select>
			<button class="button"><?php _e( 'Search Results', 'icode-survey-master' ); ?></button>
		</p>
	</form>
	<form action="" method="post" name="bulk_delete_form">
		<input type="hidden" name="bulk_delete" value="confirmation" />
		<?php wp_nonce_field('bulk_delete','bulk_delete_nonce'); ?>
		<table class=widefat>
			<thead>
				<tr>
					<th><input type="checkbox" id="icode_check_all" /></th>
					<th><?php _e('Actions','icode-survey-master'); ?></th>
					<th><?php _e('survey Name','icode-survey-master'); ?></th>
					<th><?php _e('Score 1','icode-survey-master'); ?></th>
					<th><?php _e('Score 2','icode-survey-master'); ?></th>
					<th><?php _e('Name','icode-survey-master'); ?></th>
					<th><?php _e('Email','icode-survey-master'); ?></th>
					<th><?php _e('User','icode-survey-master'); ?></th>
					<th><?php _e('Time Taken','icode-survey-master'); ?></th>
				</tr>
			</thead>
			<?php
			$quotes_list = "";
			$display = "";
			$alternate = "";
			foreach($mlw_survey_data as $mlw_survey_info) {
				if($alternate) $alternate = "";
				else $alternate = " class=\"alternate\"";
				$mlw_complete_time = '';
				$mlw_icode_results_array = @unserialize($mlw_survey_info->survey_results);
				$check=false;
				$display='';
				$display1='';
				$display2='';
				$point_score_left=0;
				$point_score_right=0;
				foreach($mlw_icode_results_array as $values) {
					foreach ($values as $value) {
						if($value["category"]=='right-people'){
							$check=true;
						}
					}
				}
				if($mlw_survey_info->survey_name=='Two Person'){
					foreach($mlw_icode_results_array as $values) {
						foreach ($values as $value) {
							if($value["category"]=='right-people'){
								$point_score_right+=$value["points"];
							}
							if($value["category"]=='left-people'){
								$point_score_left+=$value["points"];
							}
						}
					}
					$point_score_right=substr($point_score_right/6,0,4);
					$point_score_left=substr($point_score_left/6,0,4);
					$quotes_list .= "<tr{$alternate}>";
					$quotes_list .= "<td><input type='checkbox' class='icode_delete_checkbox' name='delete_results[]' value='".$mlw_survey_info->result_id. "' /></td>";
					$quotes_list .= "<td><span style='color:green;font-size:16px;'><a href='admin.php?page=iCODE_survey_result_details&&result_id=".$mlw_survey_info->result_id."'>View</a>|<a onclick=\"deleteResults('".$mlw_survey_info->result_id."','".esc_js($mlw_survey_info->survey_name)."')\" href='#'>Delete</a></span></td>";
					$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->survey_name . "</span></td>";
					if ($mlw_survey_info->survey_system == 0)
					{
						$quotes_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_survey_info->correct ." out of ".$mlw_survey_info->total." or ".$mlw_survey_info->correct_score."%</span></td>";
					}
					if ($mlw_survey_info->survey_system == 1)
					{
						$quotes_list .= "<td><span style='font-size:16px;'>" . $point_score_right . " Points</span></td>";
						$quotes_list .= "<td><span style='font-size:16px;'>" . $point_score_left . " Points</span></td>";
					}
					if ($mlw_survey_info->survey_system == 2)
					{
						$quotes_list .= "<td><span style='font-size:16px;'>".__('Not Graded','icode-survey-master')."</span></td>";
					}
					$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->name ."</span></td>";
					$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->email ."</span></td>";
					if ( 0 == $mlw_survey_info->user ) {
						$quotes_list .= "<td><span style='font-size:16px;'>Visitor</span></td>";
					} else {
						$quotes_list .= "<td><span style='font-size:16px;'><a href='user-edit.php?user_id=" . $mlw_survey_info->user ."'>" . $mlw_survey_info->user ."</a></span></td>";
					}
					$date = date_i18n( get_option( 'date_format' ), strtotime( $mlw_survey_info->time_taken ) );
					$time = date( "h:i:s A", strtotime( $mlw_survey_info->time_taken ) );
					$quotes_list .= "<td><span style='font-size:16px;'>$date $time</span></td>";
					$quotes_list .= "</tr>";
				}else{
					$quotes_list .= "<tr{$alternate}>";
					$quotes_list .= "<td><input type='checkbox' class='icode_delete_checkbox' name='delete_results[]' value='".$mlw_survey_info->result_id. "' /></td>";
					$quotes_list .= "<td><span style='color:green;font-size:16px;'><a href='admin.php?page=iCODE_survey_result_details&&result_id=".$mlw_survey_info->result_id."'>View</a>|<a onclick=\"deleteResults('".$mlw_survey_info->result_id."','".esc_js($mlw_survey_info->survey_name)."')\" href='#'>Delete</a></span></td>";
					$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->survey_name . "</span></td>";
					if ($mlw_survey_info->survey_system == 0)
					{
						$quotes_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_survey_info->correct ." out of ".$mlw_survey_info->total." or ".$mlw_survey_info->correct_score."%</span></td>";
					}
					if ($mlw_survey_info->survey_system == 1)
					{
						$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->point_score . " Points</span></td>";
						$quotes_list .= "<td><span style='font-size:16px;'></span></td>";
					}
					if ($mlw_survey_info->survey_system == 2)
					{
						$quotes_list .= "<td><span style='font-size:16px;'>".__('Not Graded','icode-survey-master')."</span></td>";
					}
					$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->name ."</span></td>";
					$quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_survey_info->email ."</span></td>";
					if ( 0 == $mlw_survey_info->user ) {
						$quotes_list .= "<td><span style='font-size:16px;'>Visitor</span></td>";
					} else {
						$quotes_list .= "<td><span style='font-size:16px;'><a href='user-edit.php?user_id=" . $mlw_survey_info->user ."'>" . $mlw_survey_info->user ."</a></span></td>";
					}
					$date = date_i18n( get_option( 'date_format' ), strtotime( $mlw_survey_info->time_taken ) );
					$time = date( "h:i:s A", strtotime( $mlw_survey_info->time_taken ) );
					$quotes_list .= "<td><span style='font-size:16px;'>$date $time</span></td>";
					$quotes_list .= "</tr>";
				}
			}
			$display .= "<tbody id=\"the-list\">{$quotes_list}</tbody>";
			echo $display;
			?>
		</table>
	</form>

	<div id="delete_dialog" title="Delete Results?" style="display:none;">
		<h3><b><?php _e('Are you sure you want to delete these results?','icode-survey-master'); ?></b></h3>
		<form action='' method='post'>
			<?php wp_nonce_field( 'delete_results','delete_results_nonce' ); ?>
			<input type='hidden' id='result_id' name='result_id' value='' />
			<input type='hidden' id='delete_survey_name' name='delete_survey_name' value='' />
			<p class='submit'><input type='submit' class='button-primary' value='<?php _e('Delete Results','icode-survey-master'); ?>' /></p>
		</form>
	</div>
<?php } ?>