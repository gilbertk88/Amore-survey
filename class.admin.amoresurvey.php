<?php 
function  admin_amoresurvey(){ ?>

<br><br>

<div class=�wrap�>

<h2>Amore Mattress Survey Results</h2>

<p>On this page, you see the results of Amore Mattress Surveys taken.</p><br>
	
	<style>
	.Surveystat {
		padding:8px;
		float:left;
		border:1px solid gray;
		border-radius:5px;
		min-width:210px;
		text-align:center;
		color:gray;
		font-size:24px;
	}
	.resultbody{
	background:#fff;
	border:1px solid #ddd;
	border-radius:3px;
	max-width:960px;
	padding:30px;
	
	}
	.brown{
		color:brown;
	}
	.green{
		color:green;
	}
	.full{
		width:73%;
	}
	</style>
	
	<?php
	global $wpdb;
	$mysurvey = $wpdb->get_col( $wpdb->prepare("SELECT * FROM wp_survey where value>0", ARRAY_N ));
	
	//print_r($mysurvey);
	$results= $mysurvey;
	$Soft=$Medium=$Firm=0;
	//count particular bracket.
	$results_no=count($results);
	//$results_no-1;
	for($i=0;$i<$results_no; $i++){
		if(0<$results[$i] && $results[$i]<=1.25){
			$Soft++;
		}
		elseif(1.25<$results[$i] && $results[$i]<=2.5){
			$Medium++;
		}
		elseif(2.5<$results[$i] && $results[$i]<=3){
			$Firm++;
		}
	}
	
	$PSoft=$Soft/$results_no*100;
	$PMedium=$Medium/$results_no*100;
	$PFirm=$Firm/$results_no*100;
	?>
	<hr><br>
	<div class="resultbody">
	<table>
	<tr>
		<td id="totalentries"></td>
	<tr>
	<tr id="eachentry">
		<td class="Surveystat brown">Mattress type</td>
		<td class="Surveystat green">Soft</td>
		<td class="Surveystat green">Medium</td>
		<td class="Surveystat green">Firm</td>
	</tr>
	<tr id="eachentry">
		<td class="Surveystat brown">Actual results</td>
		<td class="Surveystat"><?php echo $Soft; ?></td>
		<td class="Surveystat"><?php echo $Medium; ?></td>
		<td class="Surveystat"><?php echo $Firm; ?></td>
	</tr>
	<tr id="percentage">
		<td class="Surveystat brown">Percentage</td>
		<td class="Surveystat"><?php echo $PSoft; ?> %</td>
		<td class="Surveystat"><?php echo $PMedium; ?> %</td>
		<td class="Surveystat"><?php echo $PFirm; ?> %</td>
	</tr>
	<tr id="eachentry">
		<td class="Surveystat brown">Surveys taken</td>
		<td class="Surveystat full green" ><?php echo $results_no; ?></td>
	</tr>
	</table>
	</div>
	</div>
<?php
}