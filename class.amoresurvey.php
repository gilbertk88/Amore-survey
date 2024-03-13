<?php 
function amoresurvey(){
if (!isset($_POST['user_data_s_r1'])) { ?>

<style type="text/style">

</style>
<?php  }
if (isset($_POST['user_data_s_r1'])) {
if (isset($_POST['user_data_s_r2']) && 0<$_POST['user_data_s_r2']) {
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
 $finalanswer='<div id="signup_form" style="padding:30% 5%;">
		
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
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_7193a1a716bba673239f3c66b_37c61a26ea" tabindex="-1" value=""></div>
    <div class="clear">			
			<input type="submit" value="Get Results" name="continue_to_results" id="signup_submit">
	</div>
    </div>
</form>
</div>
<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script><script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
		
		
		</div> <div id="actual_results" style="display:none;padding:30% 5%;"><br><br><h4>Thank you for completing our survey.<br><br>Our recommendation would be to purchase an<br> ';
$finalanswer .='<table class="custom_table_two">
<tr style="height:;padding:5px;">
	<td><h5 style="margin:0px;">(Left side)</h5></td>
	<td><h5 style="margin:0px;">(Right side)</h5></td>
</tr>
<tr>
	<td>
		<div style="padding-left:5px;float:left;"><h5 style="margin:0px; line-height:33px;">Amore  '.$_POST["ans1"].'<br>';
if($_POST["Aans1"]==1){
	$finalanswer.='With Copper';
}
else{
	$finalanswer.='With Copper';
}
		
		$finalanswer.='</div></div></td>	<td>
		<div style="padding-left:5px;float:left;"><h5 style="line-height:33px; margin:0px;">Amore '.$_POST["ans2"].' <br>';
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
$finalresponce=array("response"=> $finalanswer);
	wp_send_json($finalresponce);
}
else{
	$data=array();//
	$data['value']=$_POST['user_data_s_r1'];
	global $wpdb;
	$table_name = $wpdb->prefix.'survey';
	// next line will insert the data
	$wpdb->insert($table_name, $data);
	 $finalanswer='<div id="signup_form">
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
	<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
</label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
<div class="mc-field-group">
	<label for="mce-FNAME">First Name <span class="asterisk">*</span></label>
	<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
</div>

	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
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
<div id="actual_results" style="display:none;padding:30% 5%;"><div class="" style=""><br><br><h4>Thank you for completing our survey.<br><br>Our recommendation would be to purchase an<br> <div style="height:;padding:5px;">   <div style="padding-left:3px; text-align: left; line-height: 1.3;"> Amore '.$_POST["ans1"].'<br>';
	 
	 if($_POST["bans1"]==1){
		$finalanswer.='With Copper';
	 }
	 else{ 
		$finalanswer.=' Without Copper'; 
	 }
	 
	$finalanswer.='</div><div></h4></div>';
	
	$finalresponce=array("response"=> $finalanswer);
	wp_send_json($finalresponce);
}
}
else{ ?>
<center>
<h1>Amore Survey</h1>
<div id="ppselector">How many people will be sleeping in the bed?</div>
<?php 

echo '<form method="POST" id="main_form">';
 // wp_nonce_field('user_data_s');
  ?>

  <input type="hidden" name="user_data_s_r1" id="user_data_s_r1">
  <input type="hidden" name="ans1" id="ans1">

  <input type="hidden" name="user_data_s_r2" id="user_data_s_r2">
  <input type="hidden" name="ans2" id="ans2">
<?php
  //submit_button('Send Data');
  echo '</form>';?>

<div class="sbody">
<div id="personNo">

<div><div class="p1"><span id="p1">One Person</span></div><div class="p1"><span  id="p2">Two People</span></div></div>

</div>
<div class="dbody">
	<div id="test_pod">
	<div class="1person">
		<table>
		<tr class="personid"><td>One Person</td>
		</tr>
		<tr><td  id="q1">
			I typically like my mattress to be:<br><div id="q1e"></div>
			<div class="answer_span"><span id="q1s">Soft</span><span  id="q1m">Medium</span><span  id="q1f">Firm</span><div class="dontknow"><span style="width:100% !important;" id="q1null">I Don't Know</span></div></div>
			</td>
		</tr>
		<tr><td id="q2">
			My preferred sleeping position is:<br><div id="q2e"></div>
			<div class="answer_span"><span id="q2b">Back</span><span  id="q2s">Stomach</span><span  id="q2si">Side</span><span  id="q2a">All Over the Place</span></div>
			</td>
		</tr>
		<tr>
		<td id="q3">
			My body frame type is:<br><div id="q3e"></div>
			<div class="answer_span"><span id="q3s">Small</span><span  id="q3a">Average</span><span  id="q3l">Large</span></div>
			</td>
		</tr>
		<tr>
		<td id="q4">
			My age is:<br><div id="q4e"></div>
			<div class="answer_span"><span id="q4s">Up to 30</span><span  id="q4a">31-55</span><span  id="q4l">56+</span></div>
			</td>
		</tr>
		<tr>
		<td id="q5">
			I suffer from back / joint pain:<br><div id="q5e"></div>
			<div class="answer_span"><span id="q5s">Never</span><span  id="q5a">Sometimes</span><span  id="q5l">Often</span></div>
			</td>
		</tr>
		<tr>
		<td id="q6">
			I prefer a healthier sleep environment:<br><div id="q6e"></div>
			<div class="answer_span"><span id="q6s">Yes</span><span  id="q6a">No</span></div>
			</td>
		</tr>
		</table>	
	</div>

<div class="2person" >
	<div class="person">
		<table>
			<tr class="personid"><td>PERSON 1/ Left side</td>
			</tr>
			<tr><td  id="Aq1">
				<div class="persondef">Left Person</div> <div class="personhiphen"> - </div> I typically like my mattress to be:<div id="Aq1e"></div>
				<div class="answer_span"><span id="Aq1s">Soft</span><span  id="Aq1m">Medium</span><span  id="Aq1f">Firm</span>
<div class="dontknow"><span style="width:100% !important;" id="Aq1null">I Don't Know</span></div></div>
				</td>
			</tr>
			<tr><td id="Aq2">
				My preferred sleeping position is:<br><div id="Aq2e"></div>
				<div class="answer_span"><span id="Aq2b">Back</span><span  id="Aq2s">Stomach</span><span  id="Aq2si">Side</span><div id="padda"><span  id="Aq2a">All Over the Place</span></div></div>
				</td>
			</tr>
			<tr>
			<td id="Aq3">
				My body frame type is:<br><div id="Aq3e"></div>
				<div class="answer_span"><span id="Aq3s">Small</span><span  id="Aq3a">Average</span><span  id="Aq3l">Large</span></div>
				</td>
			</tr>
			<tr>
			<td id="Aq4">
				My age is:<br><div id="Aq4e"></div>
				<div class="answer_span"><span id="Aq4s">Up to 30</span><span  id="Aq4a">31-55</span><span  id="Aq4l">56+</span></div>
				</td>
			</tr>
			<tr>
			<td id="Aq5">
				I suffer from back / joint pain:<br><div id="Aq5e"></div>
				<div class="answer_span"><span id="Aq5s">Never</span><span  id="Aq5a">Sometimes</span><span  id="Aq5l">Often</span></div>
				</td>
			</tr>
			<tr>
			<td id="Aq6">
				I prefer a healthier sleep<br>environment:<br><div id="Aq6e"></div>
				<div class="answer_span"><span id="Aq6s">Yes</span><span  id="Aq6a">No</span></div>
				</td>
			</tr>
		</table>
	</div>
	<div class="person">
		<table>
			<tr class="personid"><td>PERSON 2/ Right side</td>
			</tr>
			<tr><td  id="Bq1">
				<div style="">
<div class="persondef">Right Person</div> <div class="personhiphen">-</div> I typically like my mattress to be:<div id="Bq1e"></div></div>
				<div class="answer_span"><span id="Bq1s">Soft</span><span  id="Bq1m">Medium</span><span  id="Bq1f">Firm</span>
<div class="dontknow"><span style="width:100% !important;" id="Bq1null">I Don't Know</span></div></div>
				</td>
			</tr>
			<tr><td id="Bq2">
				My preferred sleeping position is:<br><div id="Bq2e"></div>
				<div class="answer_span"><span id="Bq2b">Back</span><span  id="Bq2s">Stomach</span><span  id="Bq2si">Side</span><div id="padda"><span  id="Bq2a">All Over the Place</span></div></div>
				</td>
			</tr>
			<tr>
			<td id="Bq3">
				My body frame type is:<br><div id="Bq3e"></div>
				<div class="answer_span"><span id="Bq3s">Small</span><span  id="Bq3a">Average</span><span  id="Bq3l">Large</span></div>
				</td>
			</tr>
			<tr>
			<td id="Bq4">
				My age is:<br><div id="Bq4e"></div>
				<div class="answer_span"><span id="Bq4s">Up to 30</span><span  id="Bq4a">31-55</span><span  id="Bq4l">56+</span></div>
				</td>
			</tr>
			<tr>
			<td id="Bq5">
				I suffer from back / joint pain:<br><div id="Bq5e"></div>
				<div class="answer_span"><span id="Bq5s">Never</span><span  id="Bq5a">Sometimes</span><span  id="Bq5l">Often</span></div>
				</td>
			</tr>
			<tr>
			<td id="Bq6">
				I prefer a healthier sleep<br>environment:<br><div id="Bq6e"></div>
				<div class="answer_span"><span id="Bq6s">Yes</span><span  id="Bq6a">No</span></div>
				</td>
			</tr>
		</table>
	</div>	
	</div>	
</div><br>
<div id="result_pod"></div>
</div>
<div class="submit1">
<input type="button" value="SUBMIT" id="submit1"></div>
</div>
<script type="text/javascript">jQuery(document).on('click', '#signup_submit', function(){jQuery("#signup_form").hide("slow");
				jQuery("#actual_results").show("slow");});
			</script>
<?php
wp_enqueue_script('amoresurvey-form','/wp-content/plugins/amoresurvey/bedsurvey.js',array('jquery'),'',true);
}
}