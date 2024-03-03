<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This function adds a widget to the dashboard in wordpress. 
* 
* @return void
* @since 4.4.0
*/
function icode_add_dashboard_widget()
{
	if ( current_user_can( 'publish_posts' ) )
	{
		wp_add_dashboard_widget(
			'icode_snapshot_widget',
			__('iCode Survey Master Snapshot', 'icode-survey-master'),
			'icode_snapshot_dashboard_widget'
		);
	}
}

add_action( 'wp_dashboard_setup', 'icode_add_dashboard_widget' );

/**
* This function creates the actual widget that is added to the dashboard. 
* 
* This widget adds things like the most popular/least popular survey. How many people have taken the survey etc. 
* @param type description
* @return type description
* @since 4.4.0
*/
function icode_snapshot_dashboard_widget()
{
	global $wpdb;
	$mlw_icode_today_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '" . date( "Y-m-d", current_time( 'timestamp' ) )." 00:00:00' AND '" . date( "Y-m-d", current_time( 'timestamp' ) )." 23:59:59') AND deleted=0");
	$mlw_last_week =  mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
	$mlw_last_week = date("Y-m-d", $mlw_last_week);
	$mlw_icode_last_weekday_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_week." 00:00:00' AND '".$mlw_last_week." 23:59:59') AND deleted=0");
	if ($mlw_icode_last_weekday_taken != 0)
	{
		$mlw_icode_analyze_today = round((($mlw_icode_today_taken - $mlw_icode_last_weekday_taken) / $mlw_icode_last_weekday_taken) * 100, 2);
	}
	else
	{
		$mlw_icode_analyze_today = $mlw_icode_today_taken * 100;
	}

	$mlw_this_week =  mktime(0, 0, 0, date("m")  , date("d")-6, date("Y"));
	$mlw_this_week = date("Y-m-d", $mlw_this_week);
	$mlw_icode_this_week_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_week." 00:00:00' AND '".date("Y-m-d")." 23:59:59') AND deleted=0");

	$mlw_last_week_start =  mktime(0, 0, 0, date("m")  , date("d")-13, date("Y"));
	$mlw_last_week_start = date("Y-m-d", $mlw_last_week_start);
	$mlw_last_week_end =  mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
	$mlw_last_week_end = date("Y-m-d", $mlw_last_week_end);
	$mlw_icode_last_week_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_week_start." 00:00:00' AND '".$mlw_last_week_end." 23:59:59') AND deleted=0");

	if ($mlw_icode_last_week_taken != 0)
	{
		$mlw_icode_analyze_week = round((($mlw_icode_this_week_taken - $mlw_icode_last_week_taken) / $mlw_icode_last_week_taken) * 100, 2);
	}
	else
	{
		$mlw_icode_analyze_week = $mlw_icode_this_week_taken * 100;
	}

	$mlw_this_month =  mktime(0, 0, 0, date("m")  , date("d")-29, date("Y"));
	$mlw_this_month = date("Y-m-d", $mlw_this_month);
	$mlw_icode_this_month_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_month." 00:00:00' AND '".date("Y-m-d")." 23:59:59') AND deleted=0");

	$mlw_last_month_start =  mktime(0, 0, 0, date("m")  , date("d")-59, date("Y"));
	$mlw_last_month_start = date("Y-m-d", $mlw_last_month_start);
	$mlw_last_month_end =  mktime(0, 0, 0, date("m")  , date("d")-30, date("Y"));
	$mlw_last_month_end = date("Y-m-d", $mlw_last_month_end);
	$mlw_icode_last_month_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_month_start." 00:00:00' AND '".$mlw_last_month_end." 23:59:59') AND deleted=0");

	if ($mlw_icode_last_month_taken != 0)
	{
		$mlw_icode_analyze_month = round((($mlw_icode_this_month_taken - $mlw_icode_last_month_taken) / $mlw_icode_last_month_taken) * 100, 2);
	}
	else
	{
		$mlw_icode_analyze_month = $mlw_icode_this_month_taken * 100;
	}

	$mlw_this_quater =  mktime(0, 0, 0, date("m")  , date("d")-89, date("Y"));
	$mlw_this_quater = date("Y-m-d", $mlw_this_quater);
	$mlw_icode_this_quater_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_this_quater." 00:00:00' AND '".date("Y-m-d")." 23:59:59') AND deleted=0");

	$mlw_last_quater_start =  mktime(0, 0, 0, date("m")  , date("d")-179, date("Y"));
	$mlw_last_quater_start = date("Y-m-d", $mlw_last_quater_start);
	$mlw_last_quater_end =  mktime(0, 0, 0, date("m")  , date("d")-90, date("Y"));
	$mlw_last_quater_end = date("Y-m-d", $mlw_last_quater_end);
	$mlw_icode_last_quater_taken = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "mlw_results WHERE (time_taken_real BETWEEN '".$mlw_last_quater_start." 00:00:00' AND '".$mlw_last_quater_end." 23:59:59') AND deleted=0");

	if ($mlw_icode_last_quater_taken != 0)
	{
		$mlw_icode_analyze_quater = round((($mlw_icode_this_quater_taken - $mlw_icode_last_quater_taken) / $mlw_icode_last_quater_taken) * 100, 2);
	}
	else
	{
		$mlw_icode_analyze_quater = $mlw_icode_this_quater_taken * 100;
	}

	$mlw_stat_total_active_survey = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."mlw_surveyzes WHERE deleted=0 LIMIT 1" );
	$mlw_stat_total_questions = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."mlw_questions WHERE deleted=0 LIMIT 1" );

	$mlw_stat_most_popular_survey = $wpdb->get_row( "SELECT survey_name FROM ".$wpdb->prefix."mlw_surveyzes WHERE deleted=0 ORDER BY survey_taken Desc LIMIT 1" );
	$mlw_stat_least_popular_survey = $wpdb->get_row( "SELECT survey_name FROM ".$wpdb->prefix."mlw_surveyzes WHERE deleted=0 ORDER BY survey_taken ASC LIMIT 1" );
	?>
	<style>
		.icode_dashboard_list
		{
			overflow: hidden;
			margin: 0;
		}
		.icode_dashboard_list li:first-child
		{
			border-top: 0;
		}
		.icode_full_width
		{
			width: 100%;
		}
		.icode_half_width
		{
			width: 50%;
		}
		.icode_dashboard_element
		{
			float: left;
			padding: 0;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			margin: 0;
			border-top: 1px solid #ececec;
			color: #aaa;
		}
		.icode_dashboard_inside
		{
			display: block;
			color: #aaa;
			padding: 9px 12px;
			-webkit-transition: all ease .5s;
			position: relative;
			font-size: 12px;
		}
		.icode_dashboard_inside strong
		{
			font-size: 18px;
			line-height: 1.2em;
			font-weight: 400;
			display: block;
			color: #21759b;
		}
		.icode_dashboard_graph
		{
			width: 25%;
			height: 10px;
			display: block;
			float: right;
			position: absolute;
			right: 0;
			top: 50%;
			margin-right: 12px;
			margin-top: -1.25em;
			font-size: 18px
		}
		.icode_dashboard_graph img
		{
			width: 15px;
			height: 15px;
		}
	</style>
	<ul class="icode_dashboard_list">
		<li class="icode_dashboard_element icode_full_width">
			<div class="icode_dashboard_inside">
				<strong><?php echo $mlw_icode_today_taken; ?></strong>
				<?php _e('surveyzes taken today', 'icode-survey-master'); ?>
				<span class="icode_dashboard_graph">
					<?php
					echo $mlw_icode_analyze_today."% ";
					if ($mlw_icode_analyze_today >= 0)
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/green_triangle.png'/>";
					}
					else
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/red_triangle.png'/>";
					}
					?>
				</span>
			</div>
		</li>
		<li class="icode_dashboard_element icode_full_width">
			<div class="icode_dashboard_inside">
				<strong><?php echo $mlw_icode_this_week_taken; ?></strong>
				<?php _e('surveyzes taken last 7 days', 'icode-survey-master'); ?>
				<span class="icode_dashboard_graph">
					<?php
					echo $mlw_icode_analyze_week."% ";
					if ($mlw_icode_analyze_week >= 0)
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/green_triangle.png'/>";
					}
					else
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/red_triangle.png'/>";
					}
					?>
				</span>
			</div>
		</li>
		<li class="icode_dashboard_element icode_full_width">
			<div class="icode_dashboard_inside">
				<strong><?php echo $mlw_icode_this_month_taken; ?></strong>
				<?php _e('surveyzes taken last 30 days', 'icode-survey-master'); ?>
				<span class="icode_dashboard_graph">
					<?php
					echo $mlw_icode_analyze_month."% ";
					if ($mlw_icode_analyze_month >= 0)
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/green_triangle.png'/>";
					}
					else
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/red_triangle.png'/>";
					}
					?>
				</span>
			</div>
		</li>
		<li class="icode_dashboard_element icode_full_width">
			<div class="icode_dashboard_inside">
				<strong><?php echo $mlw_icode_this_quater_taken; ?></strong>
				<?php _e('surveyzes taken last 120 days', 'icode-survey-master'); ?>
				<span class="icode_dashboard_graph">
					<?php
					echo $mlw_icode_analyze_quater."% ";
					if ($mlw_icode_analyze_quater >= 0)
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/green_triangle.png'/>";
					}
					else
					{
						echo "<img src='".plugin_dir_url( __FILE__ )."../images/red_triangle.png'/>";
					}
					?>
				</span>
			</div>
		</li>
		<li class="icode_dashboard_element icode_half_width">
			<div class="icode_dashboard_inside">
				<strong><?php echo $mlw_stat_total_active_survey; ?></strong>
				<?php _e('total active surveyzes', 'icode-survey-master'); ?>
			</div>
		</li>
		<li class="icode_dashboard_element icode_half_width">
			<div class="icode_dashboard_inside">
				<strong><?php echo $mlw_stat_total_questions; ?></strong>
				<?php _e('total active questions', 'icode-survey-master'); ?>
			</div>
		</li>
		<li class="icode_dashboard_element icode_half_width">
			<div class="icode_dashboard_inside">
				<strong><?php if (!is_null($mlw_stat_most_popular_survey)) { echo $mlw_stat_most_popular_survey->survey_name; } ?></strong>
				<?php _e('most popular survey', 'icode-survey-master'); ?>
			</div>
		</li>
		<li class="icode_dashboard_element icode_half_width">
			<div class="icode_dashboard_inside">
				<strong><?php if (!is_null($mlw_stat_least_popular_survey)) { echo $mlw_stat_least_popular_survey->survey_name; } ?></strong>
				<?php _e('least popular survey', 'icode-survey-master'); ?>
			</div>
		</li>
	</ul>
	<?php
}
?>