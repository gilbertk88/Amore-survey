<?php
function amoresurvey(){
?>
<style> 
span, #fselect{
padding:5px;
border:1px #b3b3b3 solid ;
border-radius:5px;
margin-left:2px;
cursor:pointer;
}
a{
color:gray;
text-decoration:none !important;
}
#fselect{
color:gray;
}
.person{
float:left;
padding:5px;
display :none;
max-width:50%;
min-width:250px;
}
.submit1{
width:100%;
border:0px solid;
}
td{
	border:0px solid;
}
#submit1{
padding:10px;
background:#545366;
border:0px solid ;
border-radius:5px;
color:#fff;
font-size:18px;
margin-top:2px;
width:80%;
}
.2person, .1person{
min-height:1500px;
float:left;
}
.dbody{
min-height:300px;
}
.personid td{
font-size:20px;
background-color:#545366;
color:#fff;
padding:0px 20px;
margin-right:20px;
border:0px solid #c5c5d8;
border-radius:5px;
}
#padda{
	padding-top:5px;
}
#ppselector{
	font-size:25px;
}
table{
	border:0px;
}
#personNo{
	padding-top:40px;
}
@media screen and (min-width: 501px) {
.p1{
	width:50%;
	text-align:center;
	float:left;
	font-size:20px;
	margin-bottom:50px;
	margin-top:20px;
}
.sbody{
max-width:900px;
background:url('wp-content/plugins/amoresurvey/8868.png');
background-size: 100% 100%;
background-repeat: no-repeat;
padding-left:30px;
padding-right:20px;
padding-bottom:20px;
}
}
@media screen and (max-width: 500px) {
.sbody{
max-width:900px;
background:url('wp-content/plugins/amoresurvey/88681.png');
background-size: 100% 100%;
background-repeat: no-repeat;
padding-left:30px;
padding-right:20px;
padding-bottom:20px;
}
.person{
float:left;
padding:0px;
display :none;
max-width:50%;
min-width:250px;
}
.1person{
	padding-top:100px;
}

.p1{
	width:50%;
	text-align:center;
	float:left;	
	font-size:20px;
	margin-bottom:30px;
}
}
</style>
<?php 
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
 $finalanswer='<div class="sbody" style="padding:35px;"><br><br><h4>Thank you for completing our survey.<br><br>Our recommendation would be to purchase an<br> ';
$finalanswer .='<table>
<tr style="height:;padding:5px;">
	<td><h5 style="margin:0px;">(Left side)</h5></td>
	<td><h5 style="margin:0px;">(Right side)</h5></td>
</tr>
<tr>
	<td>
		<div style="float:left; padding:0px 0px;"><img align="middle" width="50px" src="'.get_site_url() .'/wp-content/plugins/amoresurvey/amore.png"><sup>TM</sup></div> <div style="padding-left:5px;float:left;"><h5 style="margin:0px; line-height:33px;"> '.$_POST["ans1"].'</div></div>
	</td>
	<td>
		<div style="float:left; padding:0px 0px;vertical-align: top !important;"><img align="middle" width="50px" src="'.get_site_url() .'/wp-content/plugins/amoresurvey/amore.png"><sup>TM</sup></div>  <div style="padding-left:5px;float:left;"><h5 style="line-height:33px; margin:0px;"> '.$_POST["ans2"].'</div></div>
	</td>
</tr>
</table>';
echo $finalanswer;
}
else{
	$data=array();//
	$data['value']=$_POST['user_data_s_r1'];
	global $wpdb;
	$table_name = $wpdb->prefix.'survey';
	// next line will insert the data
	$wpdb->insert($table_name, $data);
	echo '<div class="sbody" style="padding:35px;"><br><br><h4>Thank you for completing our survey.<br><br>Our recommendation would be to purchase an<br> <div style="height:;padding:5px;">   <div style="padding-left:3px;float:left;"> <div style="float:left; padding:0px 10px;"><img align="middle" width="50px" src="'.get_site_url() .'/wp-content/plugins/amoresurvey/amore.png"><sup>TM</sup></div>  '.$_POST["ans1"].'</div><div></h4>';
}
}
else{ ?>
<center>
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

<div><div class="p1"><span id="p1">1 PERSON</span></div><div class="p1"><span  id="p2">2 PEOPLE</span></div></div>

</div>
<div class="dbody">
	<div class="1person">
		<table>
		<tr class="personid"><td>One Person</td>
		</tr>
		<tr><td  id="q1">
			I typically like my mattress to be:<br><div id="q1e"></div>
			<div><span id="q1s">SOFT</span><span  id="q1m">MEDIUM</span><span  id="q1f">FIRM</span></div><br>
			</td>
		</tr>
		<tr><td id="q2">
			My preferred sleeping position is:<br><div id="q2e"></div>
			<div><span id="q2b">BACK</span><span  id="q2s">STOMACH</span><span  id="q2si">SIDE</span><span  id="q2a">ALL OVER THE PLACE</span></div><br>
			</td>
		</tr>
		<tr>
		<td id="q3">
			My body frame type is:<br><div id="q3e"></div>
			<div><span id="q3s">SMALL</span><span  id="q3a">AVERAGE</span><span  id="q3l">LARGE</span></div><br>
			</td>
		</tr>
		</table>	
	</div>

<div class="2person" >
	<div class="person">
		<table>
			<tr class="personid"><td>PERSON 1/ Left side</td>
			</tr><br>
			<tr><td  id="Aq1"><br>
				I typically like my mattress to be:<br><div id="Aq1e"></div>
				<div><span id="Aq1s">SOFT</span><span  id="Aq1m">MEDIUM</span><span  id="Aq1f">FIRM</span></div><br>
				</td>
			</tr>
			<tr><td id="Aq2">
				My preferred sleeping position is:<br><div id="Aq2e"></div>
				<div><span id="Aq2b">BACK</span><span  id="Aq2s">STOMACH</span><span  id="Aq2si">SIDE</span><div id="padda"><span  id="Aq2a">ALL OVER THE PLACE</span></div></div><br>
				</td>
			</tr>
			<tr>
			<td id="Aq3">
				My body frame type is:<br><div id="Aq3e"></div>
				<div><span id="Aq3s">SMALL</span><span  id="Aq3a">AVERAGE</span><span  id="Aq3l">LARGE</span></div><br>
				</td>
			</tr>
		</table>
	</div>
	<div class="person">
		<table>
			<tr class="personid"><td>PERSON 2/ Right side</td>
			</tr><br>
			<tr><td  id="Bq1"><br>
				<div style="">I typically like my mattress to be:<br><div id="Bq1e"></div></div>
				<div ><span id="Bq1s">SOFT</span><span  id="Bq1m">MEDIUM</span><span  id="Bq1f">FIRM</span></div><br>
				</td>
			</tr>
			<tr><td id="Bq2">
				My preferred sleeping position is:<br><div id="Bq2e"></div>
				<div><span id="Bq2b">BACK</span><span  id="Bq2s">STOMACH</span><span  id="Bq2si">SIDE</span><div id="padda"><span  id="Bq2a">ALL OVER THE PLACE</span></div></div><br>
				</td>
			</tr>
			<tr>
			<td id="Bq3">
				My body frame type is:<br><div id="Bq3e"></div>
				<div><span id="Bq3s">SMALL</span><span  id="Bq3a">AVERAGE</span><span  id="Bq3l">LARGE</span></div><br>
				</td>
			</tr>
		</table>
	</div>
</div><br>
</div>
<div class="submit1">
<input type="button" value="SUBMIT" id="submit1"></div><br>
</div>

<?php
wp_enqueue_script('amoresurvey-form','/wp-content/plugins/amoresurvey/bedsurvey.js',array('jquery'),'',true);
}
}