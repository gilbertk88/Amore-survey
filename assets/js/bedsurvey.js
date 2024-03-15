jQuery( function ( $ ) {

function start_survey(e){
	
	q1 = q2 = q3 = q4 = q5 = 0 ;
	q6=1;
	p1=1;
	a=null;

	// allocate variables
	Aa = '';
	Ba = '';
	ans2 = '';
	ans1 = '';

	$("#user_data_s_r1").val('') ;
	$("#ans1").val('') ;
	$("#user_data_s_r2").val('') ;
	$("#ans2").val('') ;

}

$('#survey_close').click(function(){
	$('#survey-popup').toggle();
	survey_reset();
	});
	
$('#p1').click(function(){ 
	$('#p1').css({border:'1px solid #323232'});
	$('#p2').css({border:'1px solid #fff'});
	$('.1person').show();
	$('.person').hide();
	p1=1
	});
$('#p2').click(function(){ 
	$('#p2').css({border:'1px solid #323232'});
	$('#p1').css({border:'1px solid #fff'});
	$('.person').show();
	$('.1person').hide();
	p1=2;
	Aq1=Aq2=Aq3=Aq4=Aq5=Bq1=Bq2=Bq3=Bq4=Bq5=0;
	Aq6=Bq6=1;
	});

//change display & allocate value to hidden field

//------------------------------------------------------------form for 1 person---------------------------------------------------------------------------
function oneperson() {	
// question 1
	//color
	$('#q1s').click(function(){
	$('#q1s').css({border:'1px solid #323232'});
	$('#q1m').css({border:'1px solid #fff'});
	$('#q1f').css({border:'1px solid #fff'});
	$('#q1null').css({border:'1px solid #fff'});
	q1=2;
	});
	$('#q1m').click(function(){ 
	$('#q1m').css({border:'1px solid #323232'});
	$('#q1s').css({border:'1px solid #fff'});
	$('#q1f').css({border:'1px solid #fff'});
	$('#q1null').css({border:'1px solid #fff'});
	q1=4;
	});
	$('#q1null').click(function(){ 
	$('#q1null').css({border:'1px solid #323232'});
	$('#q1s').css({border:'1px solid #fff'});
	$('#q1f').css({border:'1px solid #fff'});
	$('#q1m').css({border:'1px solid #fff'});
	q1=4;
	});
	$('#q1f').click(function(){ 
	$('#q1f').css({border:'1px solid #323232'});
	$('#q1m').css({border:'1px solid #fff'});
	$('#q1s').css({border:'1px solid #fff'});
	$('#q1null').css({border:'1px solid #fff'});
	q1=6;
	});
	//value

// question 2
	//color
	//value
$('#q2b').click(function(){ 
	$('#q2b').css({border:'1px solid #323232'});
	$('#q2s').css({border:'1px solid #fff'});
	$('#q2si').css({border:'1px solid #fff'});
	$('#q2a').css({border:'1px solid #fff'});
	q2=2;
	});	
$('#q2s').click(function(){ 
	$('#q2s').css({border:'1px solid #323232'});
	$('#q2b').css({border:'1px solid #fff'});
	$('#q2si').css({border:'1px solid #fff'});
	$('#q2a').css({border:'1px solid #fff'});
	q2=3;
	});
$('#q2si').click(function(){ 
	$('#q2si').css({border:'1px solid #323232'});
	$('#q2s').css({border:'1px solid #fff'});
	$('#q2b').css({border:'1px solid #fff'});
	$('#q2a').css({border:'1px solid #fff'});
	q2=1;
	});
$('#q2a').click(function(){ 
	$('#q2a').css({border:'1px solid #000000'});
	$('#q2s').css({border:'1px solid #fff'});
	$('#q2si').css({border:'1px solid #fff'});
	$('#q2b').css({border:'1px solid #fff'});
	q2=2;
	});


// question 3
	//color
	//value
	$('#q3s').click(function(){ 
	$('#q3s').css({border:'1px solid #323232'});
	$('#q3a').css({border:'1px solid #fff'});
	$('#q3l').css({border:'1px solid #fff'});
	q3=1;
	});
		$('#q3a').click(function(){ 
	$('#q3a').css({border:'1px solid #323232'});
	$('#q3s').css({border:'1px solid #fff'});
	$('#q3l').css({border:'1px solid #fff'});
	q3=2;
	});
	$('#q3l').click(function(){ 
	$('#q3l').css({border:'1px solid #323232'});
	$('#q3a').css({border:'1px solid #fff'});
	$('#q3s').css({border:'1px solid #fff'});
	q3=3;
	});
	
// question 4
	//color
	//value
	$('#q4s').click(function(){ 
	$('#q4s').css({border:'1px solid #323232'});
	$('#q4a').css({border:'1px solid #fff'});
	$('#q4l').css({border:'1px solid #fff'});
	q4=1;
	});
	$('#q4a').click(function(){ 
	$('#q4a').css({border:'1px solid #323232'});
	$('#q4s').css({border:'1px solid #fff'});
	$('#q4l').css({border:'1px solid #fff'});
	q4=2;
	});
	$('#q4l').click(function(){ 
	$('#q4l').css({border:'1px solid #323232'});
	$('#q4a').css({border:'1px solid #fff'});
	$('#q4s').css({border:'1px solid #fff'});
	q4=3;
	});
	
// question 5
	//color
	//value
	$('#q5s').click(function(){ 
	$('#q5s').css({border:'1px solid #323232'});
	$('#q5a').css({border:'1px solid #fff'});
	$('#q5l').css({border:'1px solid #fff'});
	q5=1;
	});
	$('#q5a').click(function(){ 
	$('#q5a').css({border:'1px solid #323232'});
	$('#q5s').css({border:'1px solid #fff'});
	$('#q5l').css({border:'1px solid #fff'});
	q5=2;
	});
	$('#q5l').click(function(){ 
	$('#q5l').css({border:'1px solid #323232'});
	$('#q5a').css({border:'1px solid #fff'});
	$('#q5s').css({border:'1px solid #fff'});
	q5=3;
	});
// question 6
	//color
	//value
	$('#q6s').click(function(){ 
	$('#q6s').css({border:'1px solid #323232'});
	$('#q6a').css({border:'1px solid #fff'});
	$('#q6l').css({border:'1px solid #fff'});
	q6=1;
	});
	$('#q6a').click(function(){ 
	$('#q6a').css({border:'1px solid #323232'});
	$('#q6s').css({border:'1px solid #fff'});
	$('#q6l').css({border:'1px solid #fff'});
	q6=2;
	});	
}
//----------------------------------------------------------------------end of form for 1 person-----------------------------------------------------------------------------------------------------------------------
	

//------------------------------------------------------------form for 2 people---------------------------------------------------------------------------
function twopeople() {
//-------------------------------------------section A(person 1 left side)=====================================================
// question 1
	//color
	$('#Aq1s').click(function(){ 
	$('#Aq1s').css({border:'1px solid #323232'});
	$('#Aq1m').css({border:'1px solid #fff'});
	$('#Aq1f').css({border:'1px solid #fff'});
	$('#Aq1null').css({border:'1px solid #fff'});
	Aq1=1*2;
	});
	$('#Aq1m').click(function(){ 
	$('#Aq1m').css({border:'1px solid #323232'});
	$('#Aq1s').css({border:'1px solid #fff'});
	$('#Aq1f').css({border:'1px solid #fff'});
	$('#Aq1null').css({border:'1px solid #fff'});
	Aq1=2*2;
	});
	$('#Aq1null').click(function(){ 
	$('#Aq1null').css({border:'1px solid #323232'});
	$('#Aq1s').css({border:'1px solid #fff'});
	$('#Aq1f').css({border:'1px solid #fff'});
	$('#Aq1m').css({border:'1px solid #fff'});
	Aq1=2*2;
	});
	$('#Aq1f').click(function(){ 
	$('#Aq1f').css({border:'1px solid #323232'});
	$('#Aq1m').css({border:'1px solid #fff'});
	$('#Aq1s').css({border:'1px solid #fff'});
	$('#Aq1null').css({border:'1px solid #fff'});
	Aq1=3*2;
	});
	//value
	
// question 2
	//color
	//value
$('#Aq2b').click(function(){ 
	$('#Aq2b').css({border:'1px solid #323232'});
	$('#Aq2s').css({border:'1px solid #fff'});
	$('#Aq2si').css({border:'1px solid #fff'});
	$('#Aq2a').css({border:'1px solid #fff'});
	Aq2=2;
	});	
$('#Aq2s').click(function(){ 
	$('#Aq2s').css({border:'1px solid #323232'});
	$('#Aq2b').css({border:'1px solid #fff'});
	$('#Aq2si').css({border:'1px solid #fff'});
	$('#Aq2a').css({border:'1px solid #fff'});
	Aq2=3;
	});
$('#Aq2si').click(function(){ 
	$('#Aq2si').css({border:'1px solid #323232'});
	$('#Aq2s').css({border:'1px solid #fff'});
	$('#Aq2b').css({border:'1px solid #fff'});
	$('#Aq2a').css({border:'1px solid #fff'});
	Aq2=1;
	});
$('#Aq2a').click(function(){ 
	$('#Aq2a').css({border:'1px solid #323232'});
	$('#Aq2s').css({border:'1px solid #fff'});
	$('#Aq2si').css({border:'1px solid #fff'});
	$('#Aq2b').css({border:'1px solid #fff'});
	Aq2=2;
	});
	
	
// question 3
	//color
	//value
	$('#Aq3s').click(function(){ 
	$('#Aq3s').css({border:'1px solid #323232'});
	$('#Aq3a').css({border:'1px solid #fff'});
	$('#Aq3l').css({border:'1px solid #fff'});
	Aq3=1;
	});
	$('#Aq3a').click(function(){ 
	$('#Aq3a').css({border:'1px solid #323232'});
	$('#Aq3s').css({border:'1px solid #fff'});
	$('#Aq3l').css({border:'1px solid #fff'});
	Aq3=2;
	});
	$('#Aq3l').click(function(){ 
	$('#Aq3l').css({border:'1px solid #323232'});
	$('#Aq3a').css({border:'1px solid #fff'});
	$('#Aq3s').css({border:'1px solid #fff'});
	Aq3=3;
	});
	
// question 4
	//color
	//value
	$('#Aq4s').click(function(){ 
	$('#Aq4s').css({border:'1px solid #323232'});
	$('#Aq4a').css({border:'1px solid #fff'});
	$('#Aq4l').css({border:'1px solid #fff'});
	Aq4=1;
	});
	$('#Aq4a').click(function(){ 
	$('#Aq4a').css({border:'1px solid #323232'});
	$('#Aq4s').css({border:'1px solid #fff'});
	$('#Aq4l').css({border:'1px solid #fff'});
	Aq4=2;
	});
	$('#Aq4l').click(function(){ 
	$('#Aq4l').css({border:'1px solid #323232'});
	$('#Aq4a').css({border:'1px solid #fff'});
	$('#Aq4s').css({border:'1px solid #fff'});
	Aq4=3;
	});
// question 5
	//color
	//value
	$('#Aq5s').click(function(){ 
	$('#Aq5s').css({border:'1px solid #323232'});
	$('#Aq5a').css({border:'1px solid #fff'});
	$('#Aq5l').css({border:'1px solid #fff'});
	Aq5=1;
	});
	$('#Aq5a').click(function(){ 
	$('#Aq5a').css({border:'1px solid #323232'});
	$('#Aq5s').css({border:'1px solid #fff'});
	$('#Aq5l').css({border:'1px solid #fff'});
	Aq5=2;
	});
	$('#Aq5l').click(function(){ 
	$('#Aq5l').css({border:'1px solid #323232'});
	$('#Aq5a').css({border:'1px solid #fff'});
	$('#Aq5s').css({border:'1px solid #fff'});
	Aq5=3;
	});
// question 6
	//color
	//value
	$('#Aq6s').click(function(){ 
	$('#Aq6s').css({border:'1px solid #323232'});
	$('#Aq6a').css({border:'1px solid #fff'});
	$('#Aq6l').css({border:'1px solid #fff'});
	Aq6=1;
	});
	$('#Aq6a').click(function(){ 
	$('#Aq6a').css({border:'1px solid #323232'});
	$('#Aq6s').css({border:'1px solid #fff'});
	$('#Aq6l').css({border:'1px solid #fff'});
	Aq6=2;
	});
	
	
//---------------------------------------------------------------------section B (person 2/ right side)-----------------------------------------------------------------------------------------------------------------
// question 1
	//color
	$('#Bq1s').click(function(){ 
	$('#Bq1s').css({border:'1px solid #323232'});
	$('#Bq1m').css({border:'1px solid #fff'});
	$('#Bq1f').css({border:'1px solid #fff'});
	$('#Bq1null').css({border:'1px solid #fff'});
	Bq1=1*2;
	});
	$('#Bq1m').click(function(){ 
	$('#Bq1m').css({border:'1px solid #323232'});
	$('#Bq1s').css({border:'1px solid #fff'});
	$('#Bq1f').css({border:'1px solid #fff'});
	$('#Bq1null').css({border:'1px solid #fff'});
	Bq1=2*2;
	});
	$('#Bq1null').click(function(){ 
	$('#Bq1null').css({border:'1px solid #323232'});
	$('#Bq1s').css({border:'1px solid #fff'});
	$('#Bq1f').css({border:'1px solid #fff'});
	$('#Bq1m').css({border:'1px solid #fff'});
	Bq1=2*2;
	});
	$('#Bq1f').click(function(){ 
	$('#Bq1f').css({border:'1px solid #323232'});
	$('#Bq1m').css({border:'1px solid #fff'});
	$('#Bq1s').css({border:'1px solid #fff'});
	$('#Bq1null').css({border:'1px solid #fff'});
	Bq1=3*2;
	});
	//value
// question 2
	//color
	//value
$('#Bq2b').click(function(){ 
	$('#Bq2b').css({border:'1px solid #323232'});
	$('#Bq2s').css({border:'1px solid #fff'});
	$('#Bq2si').css({border:'1px solid #fff'});
	$('#Bq2a').css({border:'1px solid #fff'});
	Bq2=2;
	});	
$('#Bq2s').click(function(){ 
	$('#Bq2s').css({border:'1px solid #323232'});
	$('#Bq2b').css({border:'1px solid #fff'});
	$('#Bq2si').css({border:'1px solid #fff'});
	$('#Bq2a').css({border:'1px solid #fff'});
	Bq2=3;
	});
$('#Bq2si').click(function(){ 
	$('#Bq2si').css({border:'1px solid #323232'});
	$('#Bq2s').css({border:'1px solid #fff'});
	$('#Bq2b').css({border:'1px solid #fff'});
	$('#Bq2a').css({border:'1px solid #fff'});
	Bq2=1;
	});
$('#Bq2a').click(function(){ 
	$('#Bq2a').css({border:'1px solid #323232'});
	$('#Bq2s').css({border:'1px solid #fff'});
	$('#Bq2si').css({border:'1px solid #fff'});
	$('#Bq2b').css({border:'1px solid #fff'});
	Bq2=2;
	});
// question 3
	//color
	//value
	$('#Bq3s').click(function(){ 
	$('#Bq3s').css({border:'1px solid #323232'});
	$('#Bq3a').css({border:'1px solid #fff'});
	$('#Bq3l').css({border:'1px solid #fff'});
	Bq3=1;
	});
	$('#Bq3a').click(function(){ 
	$('#Bq3a').css({border:'1px solid #323232'});
	$('#Bq3s').css({border:'1px solid #fff'});
	$('#Bq3l').css({border:'1px solid #fff'});
	Bq3=2;
	});
	$('#Bq3l').click(function(){ 
	$('#Bq3l').css({border:'1px solid #323232'});
	$('#Bq3a').css({border:'1px solid #fff'});
	$('#Bq3s').css({border:'1px solid #fff'});
	Bq3=3;
	});
// question 4
	//color
	//value
	$('#Bq4s').click(function(){ 
	$('#Bq4s').css({border:'1px solid #323232'});
	$('#Bq4a').css({border:'1px solid #fff'});
	$('#Bq4l').css({border:'1px solid #fff'});
	Bq4=1;
	});
	$('#Bq4a').click(function(){ 
	$('#Bq4a').css({border:'1px solid #323232'});
	$('#Bq4s').css({border:'1px solid #fff'});
	$('#Bq4l').css({border:'1px solid #fff'});
	Bq4=2;
	});
	$('#Bq4l').click(function(){ 
	$('#Bq4l').css({border:'1px solid #323232'});
	$('#Bq4a').css({border:'1px solid #fff'});
	$('#Bq4s').css({border:'1px solid #fff'});
	Bq4=3;
	});
// question 5
	//color
	//value
	$('#Bq5s').click(function(){ 
	$('#Bq5s').css({border:'1px solid #323232'});
	$('#Bq5a').css({border:'1px solid #fff'});
	$('#Bq5l').css({border:'1px solid #fff'});
	Bq5=1;
	});
	$('#Bq5a').click(function(){ 
	$('#Bq5a').css({border:'1px solid #323232'});
	$('#Bq5s').css({border:'1px solid #fff'});
	$('#Bq5l').css({border:'1px solid #fff'});
	Bq5=2;
	});
	$('#Bq5l').click(function(){ 
	$('#Bq5l').css({border:'1px solid #323232'});
	$('#Bq5a').css({border:'1px solid #fff'});
	$('#Bq5s').css({border:'1px solid #fff'});
	Bq5=3;
	});
// question 6
	//color
	//value
	$('#Bq6s').click(function(){ 
	$('#Bq6s').css({border:'1px solid #323232'});
	$('#Bq6a').css({border:'1px solid #fff'});
	$('#Bq6l').css({border:'1px solid #fff'});
	Bq6=1;
	});
	$('#Bq6a').click(function(){ 
	$('#Bq6a').css({border:'1px solid #323232'});
	$('#Bq6s').css({border:'1px solid #fff'});
	$('#Bq6l').css({border:'1px solid #fff'});
	Bq6=2;
	});
}
//----------------------------------------------------------------------end of form for 2 people-----------------------------------------------------------------------------------------------------------------------
function openpersonsubmit(){
	//e.preventDefault();
if(0<q1 && 0<q2 && 0<q3 && 0<q4 && 0<q5){
	// if all are set 
	a=q1+q2+q3+q4+q5;
	a=a/6;
	
	if(0<a && a<=1.49){
	//replace with the recommendation
	ans='Soft';
	} else if(1.5<=a && a<=2.5){
	//replace with the recommendation
	ans='Medium';
	} else if(2.51<a && a<=3){
	//replace with the recommendation
	ans='Firm';
	}
	// disappear current content
	
	$('.1person').hide('slow');
	$('.person').hide('slow');
	$('.submit1').hide('slow');
	$('#personNo').hide('slow');
	$('#ppselector').hide('slow');
	$("#user_data_s_r1").val(a);
	//$("#ans1").val(ans);
	//$("#main_form").submit();
	$('#result_pod').show();
	$('#test_pod').hide();
	$('#result_pod').html('<br><br><br><br><br><div>Computing... </div>');
	//$('.dbody').html('<br><br><br><br><div>Computing... </div>');
	$.ajax({
		//url:"http://localhost/wordpress/wp-content/plugins/amoresurvey/68.php",
		success: function(data){	
			console.log(data);
		  //  datan=JSON.parse(data);		  
			$('#result_pod').show();
			$('#test_pod').hide();
			$('#result_pod').html(data.response);
			$(document).on('click', '#signup_submit', function(){$("#signup_form").hide("slow");
				$("#actual_results").show("slow");});
			
				
				$("input#mc-embedded-subscribe").click( function(){ 
					$("#signup_submit").css({display:'block'});
					});
		},
		//dataType:"JSON",
		data:{ans1:ans,user_data_s_r1:a,bans1:q6},
		method:"POST",	
		});
	
}
else{
if(q1===0){
$('#q1e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#q1').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
//$('#q1').css({border-radius:'5px'});
}

if(q2===0){
$('#q2e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#q2').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(q3===0){
$('#q3e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#q3').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(q4===0){
$('#q4e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#q4').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(q5===0){
$('#q5e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#q5').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

}
}

function survey_reset(){
		start_survey();
		//	make the results hidden
		$("#result_pod").hide();
		// make the original view visible
		$("#test_pod").show();
		$("#personNo").show();
		$(".submit1").show();
		$(".dbody").show();
		$(".1person").show();
	}

function twopeoplesubmit(){
 $('#submit_mailchip_details').on('click',function(){
	$('.survey_results').show();
	$('.survey_mailchimp').hide();
	});
	//event.preventDefault();
if(0<Aq1 && 0<Aq2 && 0<Aq3 && 0<Bq1 && 0<Bq2 && 0<Bq3){
	// if all are set 
	Aa=Aq1+Aq2+Aq3+Aq4+Aq5;
	Ba=Bq1+Bq2+Bq3+Bq4+Bq5;
	Aa=Aa/6;
	Ba=Ba/6;

	if(0<Aa && Aa<=1.49){
	//replace with the recommendation
	ans1='Soft';
	} else if(1.5<=Aa && Aa<=2.4){
	//replace with the recommendation
	ans1='Medium';
	} else if(2.4<Aa && Aa<=3){
	//replace with the recommendation
	ans1='Firm';
	}
	
	if(0<Ba && Ba<=1.49){
	//replace with the recommendation
	ans2='Soft';
	} else if(1.5<=Ba && Ba<=2.4){
	//replace with the recommendation
	ans2='Medium';
	} else if(2.4<Ba && Ba<=3){
	//replace with the recommendation
	ans2='Firm';
	}
	// disappear current content
	$('.1person').hide('slow');
	$('.person').hide('slow');
	$('.submit1').hide('slow');
	$('#personNo').hide('slow');
	$('#ppselector').hide('slow');
	// allocate variables
	$("#user_data_s_r1").val(Aa);
	$("#ans1").val(ans1);
	
	$("#user_data_s_r2").val(Ba);
	$("#ans2").val(ans2);	
	$.ajax({
		//url:"http://localhost/wordpress/wp-content/plugins/amoresurvey/68.php",
		success: function(data){
			console.log(data);
		  //  datan=JSON.parse(data);	
			
			$('#result_pod').show();
			$('#test_pod').hide();
			$('#result_pod').html(data.response);
			$(document).on('click', '#signup_submit', function(){$("#signup_form").hide("slow");
				$("#actual_results").show("slow");});
			
				$("input#mc-embedded-subscribe").click( function(){ 
					$("#signup_submit").css({display:'block'});
					});         
			},
		//dataType:"JSON",
		data:{
			user_data_s_r1:Aa,
			ans1:ans1,Aans1:Aq6,
			user_data_s_r2:Ba,
			ans2:ans2,Bans1:Bq6,
			},
		method:"POST",	
		});
	//$("#main_form").submit();
	$('#result_pod').show();
	$('#test_pod').hide();
	$('#result_pod').html('<br><br><br><br><br><div>Computing... </div>');
}
else{
//==================From A person 1===========================================================
if(Aq1===0){
$('#Aq1e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Aq1').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
//$('#q1').css({border-radius:'5px'});
}

if(Aq2===0){
$('#Aq2e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Aq2').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(Aq3===0){
$('#Aq3e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Aq3').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(Aq4===0){
$('#Aq4e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Aq4').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(Aq5===0){
$('#Aq5e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Aq5').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

//==================From B 2 people===========================================================
if(Bq1===0){
$('#Bq1e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Bq1').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
//$('#q1').css({border-radius:'5px'});
}

if(Bq2===0){
$('#Bq2e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Bq2').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(Bq3===0){
$('#Bq3e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Bq3').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(Bq4===0){
$('#Bq4e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Bq4').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

if(Bq5===0){
$('#Bq5e').html('<br><div style="color:red; font-size:14px;">Please select a value below</div>');
$('#Bq5').css({border:'1px solid red','border-radius':'5px', 'padding':'5px'});
}

}
}
	
$('#p1').click();
$('#submit1').click(function (){

// see if there is value missing- if there is any missing show error 
if(p1===1){
openpersonsubmit();}
else if(p1===2){
twopeoplesubmit();}

a=null;
});
oneperson();
twopeople();
});