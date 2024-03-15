<?php 
function amoresurvey(){

	if (!isset($_POST['user_data_s_r1'])) { 

	?>

	<style>

		.a_results{
			background:#fff9;
		}
		#mc_embed_signup{clear:left; font:14px Helvetica,Arial,sans-serif; }
		body .sbody span, .container .sbody span{border:0px;}
		#mc_embed_signup_scroll{
				background: #fff9;
				padding: 20px;
				width: 90%;
			}
		#signup_submit{
				background: #333;
				color: #fff;
				border: 0px;
				padding: 10px 20px;
				border-radius: 30px;
		}
		.a_results{
				background: #fff9;
				padding: 20px;
		}
		.a_results_details, .custom_table_two{
				background: #03b903;
				padding: 25px;
				color: yellow;
				margin: 20px;
				border-radius: 10px;
				font-size: 20px;
		}
		td .td_custom_table_two{
			color: yellow;
			padding: 0px 15px;
		}

	</style>
	

<?php

}

	if ( isset( $_POST['user_data_s_r1'] ) ) {

		if ( isset( $_POST['user_data_s_r2'] ) && 0 < $_POST['user_data_s_r2'] ) {

			$data=array();//
			$data['value']=$_POST['user_data_s_r1'];

			global $wpdb;

			$table_name = $wpdb->prefix.'survey';

			// next line will insert the data
			$wpdb->insert($table_name, $data);
			// second person into database

			$data=array();//
			$data['value']=$_POST['user_data_s_r2'];
			
			global $wpdb;
			$table_name = $wpdb->prefix.'survey';

			// next line will insert the data
			$wpdb->insert($table_name, $data);

		$finalanswer = '
		</style>
			<div id="signup_form" style="padding:30% 5%;">
					
				<!-- Begin MailChimp Signup Form -->
				<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
				<div id="mc_embed_signup">

					<form action="//amorebeds.us11.list-manage.com/subscribe/post?u=b53d47510fa8715938c9fd197&amp;id=7cc439a2ee" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

						<div id="mc_embed_signup_scroll">
							<h2>Please enter your name and email to see your results</h2>
							<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
							<div class="mc-field-group">
								<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span></label>
								<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
							</div>
							<div class="mc-field-group">
								<label for="mce-FNAME">First Name <span class="asterisk">*</span></label>
								<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
							</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>

							<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
							<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_7193a1a716bba673239f3c66b_37c61a26ea" tabindex="-1" value=""></div>
							<div class="clear">			
								<input type="submit" value="Get Results" name="continue_to_results" id="signup_submit">
							</div>
						</div>

					</form>

				</div>

			<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script><script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";}(jQuery));var $mcj = jQuery.noConflict(true);</script>
			<!--End mc_embed_signup-->
					
					</div>
					<div id="actual_results" style="display:none;padding:30% 5%;"><br><br>
					<div class="a_results">
					<h5>Thank you for completing our survey.<br><br>Our recommendation would be to purchase an</h5><br>';

			$finalanswer .='
			<table class="custom_table_two">
				<tr style="height:;padding:5px;">
					<td class="td_custom_table_two"><h5 style="color: yellow; margin:0px;">(Left side)</h5></td>
					<td class="td_custom_table_two" ><h5 style="color: yellow; margin:0px;">(Right side)</h5></td>
				</tr>
				<tr>
					<td class="td_custom_table_two">
						<div style="color: yellow; padding-left:5px;float:left;">
						<h5 style="color: yellow;
						margin: 0px;
						line-height: 33px;
						background: #fff2;
						padding: 15px;
						margin: 10px;
						border-radius: 10px;">Amore  '.$_POST["ans1"].'<br>';
									
							if($_POST["Aans1"]==1){
								$finalanswer.='With Copper';
							}
							else{
								$finalanswer.='With Copper';
							}
									
							$finalanswer .= '
							</div>
						</div>
					</td>
					<td class="td_custom_table_two">
						<div style="padding-left:5px;float:left;">
						<h5 style="color: yellow;
						margin: 0px;
						line-height: 33px;
						background: #fff2;
						padding: 15px;
						margin: 10px;
						border-radius: 10px;">Amore '.$_POST["ans2"].' <br>';
						if($_POST["Bans1"]==1){
							$finalanswer.='With Copper';
						}
						else{
							$finalanswer.='With Copper';
						}
								$finalanswer.='</div></div>
					</td>
				</tr>
			</table>';

		$finalresponce = array("response"=> $finalanswer);

		wp_send_json( $finalresponce );

		}
		else{

			$data=array();//
			$data['value']=$_POST['user_data_s_r1'];

			global $wpdb;
			$table_name = $wpdb->prefix.'survey';

			// next line will insert the data
			$wpdb->insert($table_name, $data);
			$finalanswer='
			<div id="signup_form">
				<!-- Begin MailChimp Signup Form -->
				<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
				<style type="text/css">

					#mc_embed_signup{clear:left; font:14px Helvetica,Arial,sans-serif; }
					body .sbody span, .container .sbody span{border:0px;}
					
					/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
					We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */

				</style>

				<div id="mc_embed_signup">

					<form action="//amorebeds.us11.list-manage.com/subscribe/post?u=b53d47510fa8715938c9fd197&amp;id=7cc439a2ee" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<div id="mc_embed_signup_scroll">
							<h2>Please enter your name and email to see your results</h2>
							<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
							<div class="mc-field-group">
							<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span></label>
							<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
						</div>

						<div class="mc-field-group">
							<label for="mce-FNAME">First Name <span class="asterisk">*</span></label>
							<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
						</div>
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div>
						<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;" aria-hidden="true">
							<input type="text" name="b_7193a1a716bba673239f3c66b_37c61a26ea" tabindex="-1" value="">
						</div>
						<div class="clear">
							<input type="submit" value="Get Results" name="continue_to_results" id="signup_submit">
						</div>
						</div>
					</form>

				</div>

				<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script><script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";}(jQuery));var $mcj = jQuery.noConflict(true);</script>
				<!--End mc_embed_signup-->

			</div>
			<div id="actual_results" style="display:none;padding:30% 5%;">
				<div class="a_results" style=""><br><br>
					<h5>
						Thank you for completing our survey.<br><br>Our recommendation would be to purchase an<br> <div style="height:;padding:5px;">

						<div class="a_results_details" style=""> Amore '.$_POST["ans1"];
						
						if($_POST["bans1"]==1){
							$finalanswer.='With Copper';
						}
						else{ 
							$finalanswer.=' Without Copper'; 
						}
						
						$finalanswer.=' </div><div> 
						
					</h5>
			</div>';
				
				$finalresponce = array( "response" => $finalanswer );

				wp_send_json( $finalresponce );

		}
	}
else{ 
		
	ob_start();
		include dirname( __FILE__ ) . '/templates/survey_form.php';
	return ob_get_clean();

	}
}

?>