jQuery( function ( $ ) {

	var survey_vars = function() {

		var q1 = 'x' ;
		var q2 = 'x' ;
		var q3 = 'x' ;
		var q4 = 'x' ;
		var q5 = 'x' ;

		var Aq1 = 'x';
		var Aq2 = 'x';
		var Aq3 = 'x';
		var Aq4 = 'x';
		var Aq5 = 'x';

		var Bq1 = 'x';
		var Bq2 = 'x';
		var Bq3 = 'x';
		var Bq4 = 'x';
		var Bq5 = 'x';

		var Bq8 = 'x';
	}

	survey_vars();

	function survey_switcher(){
		
		q1 = q2 = q3 = q4 = q5 = 'x' ;

		Aq1 = Aq2 = Aq3 = Aq4 = Aq5 = 'x';

		Bq1 = Bq2 = Bq3 = Bq4 = Bq5 = Bq8 = 'x'; 

		$('.b_span').css({"border":"1px solid #fff9"});

	}

	function start_survey(e){

		survey_switcher();
		//q6=1;
		p1 = 1;
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
	} );

	survey_reset();
		
	$('#p1').click(function(){

		survey_switcher();

		$('#p1').css( {
			border:'1px solid #04bb04',
			background: '#fff9',
			color: '#333',
		} );
		$('#p2').css({
			border:'2px solid #fff',
			background: '#fff9',
			color: '#333',
		});
		$('#pns').css( {
			border:'2px solid #fff',
			background: '#fff9',
			color: '#333',
		} );

		$('.1person').show();
		$('.person').hide();
		p1=1

	});

	$('#p2').click(function(){

		survey_switcher();

		$('#p2').css({
			border:'2px solid #04bb04',
			background: '#fff9',
			color: '#333',
		});
		$('#p1').css({		
			border:'2px solid #fff',
			background: '#fff9',
			color: '#333',
		});
		$('#pns').css( {
			border:'2px solid #fff',
			background: '#fff9',
			color: '#333',
		} );

		$('.person').show();
		$('.1person').hide();

		p1=2;

		});

	//change display & allocate value to hidden field

	//------------------------------------------------------------form for 1 person---------------------------------------------------------------------------
	function oneperson() {
	// question 1
		//color
		$('#q1s').click(function(){ // soft
			$('#q1s').css({border:'2px solid #04bb04'});
			$('#q1m').css({border:'2px solid #fff'});
			$('#q1f').css({border:'2px solid #fff'});
			$('#q1null').css({border:'2px solid #fff'});
			q1 = $(this).data('selection') ;
		});
		$('#q1m').click(function(){ // medium
			$('#q1m').css({border:'2px solid #04bb04'});
			$('#q1s').css({border:'2px solid #fff'});
			$('#q1f').css({border:'2px solid #fff'});
			$('#q1null').css({border:'2px solid #fff'});
			q1 = $(this).data('selection');
		});
		$('#q1null').click(function(){ 
			$('#q1null').css({border:'2px solid #04bb04'});
			$('#q1s').css({border:'2px solid #fff'});
			$('#q1f').css({border:'2px solid #fff'});
			$('#q1m').css({border:'2px solid #fff'});
			q1 = $(this).data('selection');
		});
		$('#q1f').click(function(){ // firm
			$('#q1f').css({border:'2px solid #04bb04'});
			$('#q1m').css({border:'2px solid #fff'});
			$('#q1s').css({border:'2px solid #fff'});
			$('#q1null').css({border:'2px solid #fff'});
			q1 = $(this).data('selection');
		});
		//value

		// question 2
			//color
			//value
		$('#q2b').click(function(){ // back
			$('#q2b').css({border:'2px solid #04bb04'});
			$('#q2s').css({border:'2px solid #fff'});
			$('#q2si').css({border:'2px solid #fff'});
			$('#q2a').css({border:'2px solid #fff'});
			q2=$(this).data('selection');
		});
		$('#q2s').click(function(){ 
			$('#q2s').css({border:'2px solid #04bb04'});
			$('#q2b').css({border:'2px solid #fff'});
			$('#q2si').css({border:'2px solid #fff'});
			$('#q2a').css({border:'2px solid #fff'});
			q2=$(this).data('selection');
		});
		$('#q2si').click(function(){ 
			$('#q2si').css({border:'2px solid #04bb04'});
			$('#q2s').css({border:'2px solid #fff'});
			$('#q2b').css({border:'2px solid #fff'});
			$('#q2a').css({border:'2px solid #fff'});
			q2=$(this).data('selection');
		});
		$('#q2a').click(function(){ 
			$('#q2a').css({border:'2px solid #04bb04'});
			$('#q2s').css({border:'2px solid #fff'});
			$('#q2si').css({border:'2px solid #fff'});
			$('#q2b').css({border:'2px solid #fff'});
			q2=$(this).data('selection');
		});

	// question 3
		//color
		//value
		$('#q3s').click(function(){ 
			$('#q3s').css({border:'2px solid #04bb04'});
			$('#q3a').css({border:'2px solid #fff'});
			$('#q3l').css({border:'2px solid #fff'});
			q3=$(this).data('selection');
		});
		$('#q3a').click(function(){ 
			$('#q3a').css({border:'2px solid #04bb04'});
			$('#q3s').css({border:'2px solid #fff'});
			$('#q3l').css({border:'2px solid #fff'});
			q3=$(this).data('selection');
		});
		$('#q3l').click(function(){ 
			$('#q3l').css({border:'2px solid #04bb04'});
			$('#q3a').css({border:'2px solid #fff'});
			$('#q3s').css({border:'2px solid #fff'});
			q3=$(this).data('selection');
		});
		
	// question 4
		//color
		//value
		$('#q4s').click(function(){ 
			$('#q4s').css({border:'2px solid #04bb04'});
			$('#q4a').css({border:'2px solid #fff'});
			$('#q4l').css({border:'2px solid #fff'});
			q4=$(this).data('selection');
		});
		$('#q4a').click(function(){ 
			$('#q4a').css({border:'2px solid #04bb04'});
			$('#q4s').css({border:'2px solid #fff'});
			$('#q4l').css({border:'2px solid #fff'});
			q4=$(this).data('selection');
		});
		$('#q4l').click(function(){ 
			$('#q4l').css({border:'2px solid #04bb04'});
			$('#q4a').css({border:'2px solid #fff'});
			$('#q4s').css({border:'2px solid #fff'});
			q4=$(this).data('selection');
		});
		
	// question 5
		//color
		//value
		$('#q5s').click(function(){ 
			$('#q5s').css({border:'2px solid #04bb04'});
			$('#q5a').css({border:'2px solid #fff'});
			$('#q5l').css({border:'2px solid #fff'});
			q5=$(this).data('selection');
		});
		$('#q5a').click(function(){ 
			$('#q5a').css({border:'2px solid #04bb04'});
			$('#q5s').css({border:'2px solid #fff'});
			$('#q5l').css({border:'2px solid #fff'});
			q5=$(this).data('selection');
		});
		$('#q5l').click(function(){ 
			$('#q5l').css({border:'2px solid #04bb04'});
			$('#q5a').css({border:'2px solid #fff'});
			$('#q5s').css({border:'2px solid #fff'});
			q5=$(this).data('selection');
		});

	}

	//----------------------------------------------------------------------end of form for 1 person-----------------------------------------------------------------------------------------------------------------------
		

	//------------------------------------------------------------form for 2 people---------------------------------------------------------------------------
	function twopeople() {
	//-------------------------------------------section A(person 1 left side)=====================================================
	// question 1
		//color
		$('#Aq1s').click(function(){
			$('#Aq1s').css({border:'2px solid #04bb04'});
			$('#Aq1m').css({border:'2px solid #fff'});
			$('#Aq1f').css({border:'2px solid #fff'});
			$('#Aq1null').css({border:'2px solid #fff'});
			Aq1=$(this).data('selection');
		});
		$('#Aq1m').click(function(){ 
			$('#Aq1m').css({border:'2px solid #04bb04'});
			$('#Aq1s').css({border:'2px solid #fff'});
			$('#Aq1f').css({border:'2px solid #fff'});
			$('#Aq1null').css({border:'2px solid #fff'});
			Aq1=$(this).data('selection');
		});
		$('#Aq1null').click(function(){ 
			$('#Aq1null').css({border:'2px solid #04bb04'});
			$('#Aq1s').css({border:'2px solid #fff'});
			$('#Aq1f').css({border:'2px solid #fff'});
			$('#Aq1m').css({border:'2px solid #fff'});
			Aq1=$(this).data('selection');
		});
		$('#Aq1f').click(function(){ 
			$('#Aq1f').css({border:'2px solid #04bb04'});
			$('#Aq1m').css({border:'2px solid #fff'});
			$('#Aq1s').css({border:'2px solid #fff'});
			$('#Aq1null').css({border:'2px solid #fff'});
			Aq1=$(this).data('selection');
		});
		//value

		$('#p1').click( function(e) {
			$('.td_area').css( {'border':'0px'} );
			$('.error_message').html('');
		} )

		$('#p2').click( function(e) {
			$('.td_area').css( {'border':'0px'} );
			$('.error_message').html('');
		} )

	// question 2
		//color
		//value
		$('#Aq2b').click(function(){ 
			$('#Aq2b').css({border:'2px solid #04bb04'});
			$('#Aq2s').css({border:'2px solid #fff'});
			$('#Aq2si').css({border:'2px solid #fff'});
			$('#Aq2a').css({border:'2px solid #fff'});
			$('#Aq2null').css({border:'2px solid #fff'});
			Aq2=$(this).data('selection');
		});	
		$('#Aq2s').click(function(){ 
			$('#Aq2s').css({border:'2px solid #04bb04'});
			$('#Aq2b').css({border:'2px solid #fff'});
			$('#Aq2si').css({border:'2px solid #fff'});
			$('#Aq2a').css({border:'2px solid #fff'});
			$('#Aq2null').css({border:'2px solid #fff'});
			Aq2=$(this).data('selection');
		});
		$('#Aq2si').click(function(){ 
			$('#Aq2si').css({border:'2px solid #04bb04'});
			$('#Aq2s').css({border:'2px solid #fff'});
			$('#Aq2b').css({border:'2px solid #fff'});
			$('#Aq2a').css({border:'2px solid #fff'});
			$('#Aq2null').css({border:'2px solid #fff'});
			Aq2=$(this).data('selection');
		});
		$('#Aq2a').click(function(){
			$('#Aq2a').css({border:'2px solid #04bb04'});
			$('#Aq2s').css({border:'2px solid #fff'});
			$('#Aq2si').css({border:'2px solid #fff'});
			$('#Aq2b').css({border:'2px solid #fff'});
			$('#Aq2null').css({border:'2px solid #fff'});
			Aq2=$(this).data('selection');
		});

		$('#Aq2null').click(function(){
			$('#Aq2null').css({border:'2px solid #04bb04'});
			$('#Aq2b').css({border:'2px solid #fff'});
			$('#Aq2s').css({border:'2px solid #fff'});
			$('#Aq2si').css({border:'2px solid #fff'});
			$('#Aq2a').css({border:'2px solid #fff'});
			Aq2=$(this).data('selection');
		});	

		
		
	// question 3
		//color
		//value
		$('#Aq3s').click(function(){ 
			$('#Aq3s').css({border:'2px solid #04bb04'});
			$('#Aq3a').css({border:'2px solid #fff'});
			$('#Aq3l').css({border:'2px solid #fff'});
			Aq3=$(this).data('selection');
		});
		$('#Aq3a').click(function(){ 
			$('#Aq3a').css({border:'2px solid #04bb04'});
			$('#Aq3s').css({border:'2px solid #fff'});
			$('#Aq3l').css({border:'2px solid #fff'});
			Aq3=$(this).data('selection');
		});
		$('#Aq3l').click(function(){ 
			$('#Aq3l').css({border:'2px solid #04bb04'});
			$('#Aq3a').css({border:'2px solid #fff'});
			$('#Aq3s').css({border:'2px solid #fff'});
			Aq3=$(this).data('selection');
		});
		
	// question 4
		//color
		//value
		$('#Aq4s').click(function(){

			$('#Aq4s').css({border:'2px solid #04bb04'});
			$('#Aq4a').css({border:'2px solid #fff'});
			$('#Aq4l').css({border:'2px solid #fff'});

			Aq4=$(this).data('selection');

		});
		$('#Aq4a').click(function(){

			$('#Aq4a').css({border:'2px solid #04bb04'});
			$('#Aq4s').css({border:'2px solid #fff'});
			$('#Aq4l').css({border:'2px solid #fff'});

			Aq4=$(this).data('selection');

		});
		$('#Aq4l').click(function(){ 

			$('#Aq4l').css({border:'2px solid #04bb04'});
			$('#Aq4a').css({border:'2px solid #fff'});
			$('#Aq4s').css({border:'2px solid #fff'});

			Aq4=$(this).data('selection');

		});
	// question 5
		//color
		//value
		$('#Aq5s').click(function(){
			$('#Aq5s').css({border:'2px solid #04bb04'});
			$('#Aq5a').css({border:'2px solid #fff'});
			$('#Aq5l').css({border:'2px solid #fff'});
			Aq5=$(this).data('selection');
		});
		$('#Aq5a').click(function(){ 
			$('#Aq5a').css({border:'2px solid #04bb04'});
			$('#Aq5s').css({border:'2px solid #fff'});
			$('#Aq5l').css({border:'2px solid #fff'});
			Aq5=$(this).data('selection');
		});
		$('#Aq5l').click(function(){ 
			$('#Aq5l').css({border:'2px solid #04bb04'});
			$('#Aq5a').css({border:'2px solid #fff'});
			$('#Aq5s').css({border:'2px solid #fff'});
			Aq5=$(this).data('selection');
		});		
		
	//---------------------------------------------------------------------section B (person 2/ right side)-----------------------------------------------------------------------------------------------------------------
	// question 1
		//color
		$('#Bq1s').click(function(){ 
			$('#Bq1s').css({border:'2px solid #04bb04'});
			$('#Bq1m').css({border:'2px solid #fff'});
			$('#Bq1f').css({border:'2px solid #fff'});
			$('#Bq1null').css({border:'2px solid #fff'});
			Bq1=$(this).data('selection');
		});
		$('#Bq1m').click(function(){ 
			$('#Bq1m').css({border:'2px solid #04bb04'});
			$('#Bq1s').css({border:'2px solid #fff'});
			$('#Bq1f').css({border:'2px solid #fff'});
			$('#Bq1null').css({border:'2px solid #fff'});
			Bq1=$(this).data('selection');
		});
		$('#Bq1null').click(function(){ 
			$('#Bq1null').css({border:'2px solid #04bb04'});
			$('#Bq1s').css({border:'2px solid #fff'});
			$('#Bq1f').css({border:'2px solid #fff'});
			$('#Bq1m').css({border:'2px solid #fff'});
			Bq1=$(this).data('selection');
		});
		$('#Bq1f').click(function(){ 
			$('#Bq1f').css({border:'2px solid #04bb04'});
			$('#Bq1m').css({border:'2px solid #fff'});
			$('#Bq1s').css({border:'2px solid #fff'});
			$('#Bq1null').css({border:'2px solid #fff'});
			Bq1=$(this).data('selection');
		});
		//value
	// question 2
		//color
		//value
		$('#Bq2b').click(function(){ 
			$('#Bq2b').css({border:'2px solid #04bb04'});
			$('#Bq2s').css({border:'2px solid #fff'});
			$('#Bq2si').css({border:'2px solid #fff'});
			$('#Bq2a').css({border:'2px solid #fff'});
			Bq2=$(this).data('selection');
		});	
		$('#Bq2s').click(function(){ 
			$('#Bq2s').css({border:'2px solid #04bb04'});
			$('#Bq2b').css({border:'2px solid #fff'});
			$('#Bq2si').css({border:'2px solid #fff'});
			$('#Bq2a').css({border:'2px solid #fff'});
			Bq2=$(this).data('selection');
		});
		$('#Bq2si').click(function(){ 
			$('#Bq2si').css({border:'2px solid #04bb04'});
			$('#Bq2s').css({border:'2px solid #fff'});
			$('#Bq2b').css({border:'2px solid #fff'});
			$('#Bq2a').css({border:'2px solid #fff'});
			Bq2=$(this).data('selection');
		});
		$('#Bq2a').click(function(){ 
			$('#Bq2a').css({border:'2px solid #04bb04'});
			$('#Bq2s').css({border:'2px solid #fff'});
			$('#Bq2si').css({border:'2px solid #fff'});
			$('#Bq2b').css({border:'2px solid #fff'});
			Bq2=$(this).data('selection');
		});
	// question 3
		//color
		//value
		$('#Bq3s').click(function(){ 
			$('#Bq3s').css({border:'2px solid #04bb04'});
			$('#Bq3a').css({border:'2px solid #fff'});
			$('#Bq3l').css({border:'2px solid #fff'});
			Bq3=$(this).data('selection');
		});
		$('#Bq3a').click(function(){ 
			$('#Bq3a').css({border:'2px solid #04bb04'});
			$('#Bq3s').css({border:'2px solid #fff'});
			$('#Bq3l').css({border:'2px solid #fff'});
			Bq3=$(this).data('selection');
		});
		$('#Bq3l').click(function(){ 
			$('#Bq3l').css({border:'2px solid #04bb04'});
			$('#Bq3a').css({border:'2px solid #fff'});
			$('#Bq3s').css({border:'2px solid #fff'});
			Bq3=$(this).data('selection');
		});
	// question 4
		//color
		//value
		$('#Bq4s').click(function(){ 
			$('#Bq4s').css({border:'2px solid #04bb04'});
			$('#Bq4a').css({border:'2px solid #fff'});
			$('#Bq4l').css({border:'2px solid #fff'});
			Bq4=$(this).data('selection');
		});
		$('#Bq4a').click(function(){ 
			$('#Bq4a').css({border:'2px solid #04bb04'});
			$('#Bq4s').css({border:'2px solid #fff'});
			$('#Bq4l').css({border:'2px solid #fff'});
			Bq4=$(this).data('selection');
		});
		$('#Bq4l').click(function(){ 
			$('#Bq4l').css({border:'2px solid #04bb04'});
			$('#Bq4a').css({border:'2px solid #fff'});
			$('#Bq4s').css({border:'2px solid #fff'});
			Bq4=$(this).data('selection');
		});
	// question 5
		//color
		//value
		$('#Bq5s').click(function(){ 
			$('#Bq5s').css({border:'2px solid #04bb04'});
			$('#Bq5a').css({border:'2px solid #fff'});
			$('#Bq5l').css({border:'2px solid #fff'});
			Bq5=$(this).data('selection');
		});
		$('#Bq5a').click(function(){ 
			$('#Bq5a').css({border:'2px solid #04bb04'});
			$('#Bq5s').css({border:'2px solid #fff'});
			$('#Bq5l').css({border:'2px solid #fff'});
			Bq5=$(this).data('selection');
		});
		$('#Bq5l').click(function(){ 
			$('#Bq5l').css({border:'2px solid #04bb04'});
			$('#Bq5a').css({border:'2px solid #fff'});
			$('#Bq5s').css({border:'2px solid #fff'});
			Bq5=$(this).data('selection');
		});

	// question 8
		$("#q8y").click( function(){
			
			$('#q8y').css({border:'2px solid #04bb04'});
			$('#q8ns').css({border:'2px solid #fff'});
			$('#q8n').css({border:'2px solid #fff'});
			
			Bq8=$(this).data('selection');

		} );

		$("#q8ns").click( function(){

			$('#q8ns').css({border:'2px solid #04bb04'});
			$('#q8y').css({border:'2px solid #fff'});
			$('#q8n').css({border:'2px solid #fff'});

			Bq8=$(this).data('selection');

		} );

		$("#q8n").click( function(){

			$('#q8n').css({border:'2px solid #04bb04'});
			$('#q8ns').css({border:'2px solid #fff'});
			$('#q8y').css({border:'2px solid #fff'});

			Bq8=$(this).data('selection');

		} );
		
	}

	//----------------------------------------------------------------------end of form for 2 people-----------------------------------------------------------------------------------------------------------------------
	function openpersonsubmit(){

		console.log( 'q1' );
		console.log( q1 );

		console.log( 'q2' );
		console.log( q2 );

		console.log( 'q3' );
		console.log( q3 );

		console.log( 'q4' );
		console.log( q4 );

		console.log( 'q5' );
		console.log( q5 );

		console.log( 'Bq8' );
		console.log( Bq8 );

		//e.preventDefault();
		if( q1 !== 'x' && 'x' !== q2 && 'x' !== q3 &&'x' !== q4 && 'x' !== q5 && 'x' !== Bq8 ){

			// if all are set 
			a = q1 + q2 + q3 + q4 + q5 ;
			a = a / 5;

			ans = '';
			
			if( Bq8 == 'yes' ){
				if( 0 < a && a <= 1.49 ){
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable</a>';
				} else if(1.5<=a && a<=2.24){
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Medium</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Med</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/luxury-pillows/">2-Sided Natural Hybrid Pillowtop</a>';
				} else if(2.25<a && a<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-natural-mattress/">2-Sided Natural Hybrid Original</a>';
				} else if(2.51<a && a<=3){ 
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-natural-mattress/">2-Sided Natural Hybrid Original</a>';
				}
			}
			if( Bq8 == 'no' ){
				if( 0 < a && a <= 1.49 ){
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hypbrid Foam/Coil SOFT;</a>  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable<a>';
				} else if(1.5<=a && a<=2.24){
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a> <br><a class="gil_recommend_link"  href="https://www.amorebeds.com/product/bed/">12" 2-Sided Copper Foam Flippable</a></br>;  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Med</a>';
				} else if(2.25<a && a<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a>';
				} else if(2.51<a && a<=3){ 
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Firm</a>';
				}
			}
			if( Bq8 == 'not_sure' ){
				if( 0 < a && a <= 1.49 ){
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hypbrid Foam/Coil SOFT</a>';
				} else if(1.5<=a && a<=2.24){
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Hybrid Latex Lux Medium</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Med</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Natural Hybrid Pillowtop</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a><br>  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Med</a>';
				} else if(2.25<a && a<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Copper Hybrid Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Natural Hybrid Original</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a><br>  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Firm</a>';
				} else if(2.51<a && a<=3){ 
					ans='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href=" https://www.amorebeds.com/product/amore-1-sided-natural-mattress/">1-Sided Natural Hybrid Original</a>';
				}
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

			$.ajax( {
				//url:"http://localhost/wordpress/wp-content/plugins/amoresurvey/68.php",
				success: function( data ){ // console.log(data); // datan=JSON.parse(data);

					$('#result_pod').show();
					$('#test_pod').hide();
					$('#result_pod').html( data.response );
					
					$( document ).on('click', '#signup_submit', function(){
						$("#signup_form").hide("slow");
						$("#actual_results").show("slow");
					});

					$("input#mc-embedded-subscribe").click( function(){
						$("#signup_submit").css({display:'block'});
					} );

				},

				//dataType:"JSON",
				data:{ans1:ans,user_data_s_r1:a,bans1:Bq8},
				method:"POST",

			} ) ;
			
		}
		else{
				
			if( q1=='x' ){
				$('#q1e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
				$('#q1').css({border:'1px solid #ff0000a3', 'padding':'20px'});
				//$('#q1').css({border-radius:'5px'});
			}

			if( q2=='x' ){
				$('#q2e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
				$('#q2').css({border:'1px solid #ff0000a3', 'padding':'20px'});
			}

			if( q3=='x' ){
				$('#q3e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
				$('#q3').css({border:'1px solid #ff0000a3', 'padding':'20px'});
			}

			if( q4=='x' ){
				$('#q4e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
				$('#q4').css({border:'1px solid #ff0000a3', 'padding':'20px'});
			}

			if( q5=='x' ){
				$('#q5e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
				$('#q5').css({border:'1px solid #ff0000a3', 'padding':'20px'});
			}

			if( Bq8=='x' ){
				$('#q8e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
				$('#q8').css({border:'1px solid #ff0000a3', 'padding':'20px'});
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
		if( 'x' !== Aq1 && 'x' !== Aq2 && 'x' !== Aq3 && 'x' !== Aq4 && 'x' !== Aq5 && 'x' !== Bq1 && 'x' !== Bq2 && 'x' !== Bq3 && 'x' !== Bq4 && 'x' !== Bq5 && 'x' !== Bq8 ){
			// if all are set 
			Aa = Aq1 + Aq2 + Aq3 + Aq4 + Aq5 ;
			Ba = Bq1 + Bq2 + Bq3 + Bq4 + Bq5 ;

			Aa = Aa/5;
			Ba = Ba/5;
			
			if( Bq8 == 'yes' ){
				if( 0 < Aa && Aa <= 1.49 ){
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable</a>';
				} else if(1.5<=Aa && Aa<=2.24){
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Medium</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Med</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/luxury-pillows/">2-Sided Natural Hybrid Pillowtop</a>';
				} else if(2.25<Aa && Aa<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Firm</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-natural-mattress/">2-Sided Natural Hybrid Original</a>';
				} else if(2.51<Aa && Aa<=3){ 
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-natural-mattress/">2-Sided Natural Hybrid Original</a>';
				}
			}
			if( Bq8 == 'no' ){
				if( 0 < Aa && Aa <= 1.49 ){
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hypbrid Foam/Coil SOFT;</a>  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable<a>';
				} else if(1.5<=Aa && Aa<=2.24){
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a> <br><a class="gil_recommend_link"  href="https://www.amorebeds.com/product/bed/">12" 2-Sided Copper Foam Flippable</a></br>;  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Med</a>';
				} else if(2.25<Aa && Aa<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a>';
				} else if(2.51<Aa && Aa<=3){ 
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Firm</a>';
				}
			}
			if( Bq8 == 'not_sure' ){
				if( 0 < Aa && Aa <= 1.49 ){
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hypbrid Foam/Coil SOFT</a>';
				} else if(1.5<=Aa && Aa<=2.24){
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Hybrid Latex Lux Medium</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Med</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Natural Hybrid Pillowtop</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a><br>  <a class="gil_recommend_link" class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Med</a>';
				} else if(2.25<Aa && Aa<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Copper Hybrid Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Natural Hybrid Original</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a><br>  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Firm</a>';
				} else if(2.51<Aa && Aa<=3){ 
					ans1='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href=" https://www.amorebeds.com/product/amore-1-sided-natural-mattress/">1-Sided Natural Hybrid Original</a>';
				}
			}
			
			if( Bq8 == 'yes' ){
				if( 0 < Ba && Ba <= 1.49 ){
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable</a>';
				} else if(1.5<=Ba && Ba<=2.24){
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Medium</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Med</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/luxury-pillows/">2-Sided Natural Hybrid Pillowtop</a>';
				} else if(2.25<Ba && Ba<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Firm</a>;  <br>	<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-natural-mattress/">2-Sided Natural Hybrid Original</a>';
				} else if(2.51<Ba && Ba<=3){ 
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-natural-mattress/">2-Sided Natural Hybrid Original</a>';
				}
			}
			if( Bq8 == 'no' ){
				if( 0 < Ba && Ba <= 1.49 ){
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hypbrid Foam/Coil SOFT;</a>  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable<a>';
				} else if(1.5<=Ba && Ba<=2.24){
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a> <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" 2-Sided Copper Foam Flippable</a></br>;  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Med</a>';
				} else if(2.25<Ba && Ba<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a>';
				} else if(2.51<Ba && Ba<=3){ 
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Firm</a>';
				}
			}
			if( Bq8 == 'not_sure' ){
				if( 0 < Ba && Ba <= 1.49 ){
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">12" 2-Sided Copper Foam Flippable</a>; <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hypbrid Foam/Coil SOFT</a>';
				} else if(1.5<=Ba && Ba<=2.24){
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Hybrid Latex Lux Medium</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-2-sided-hybrid-copper-flippable/">2-Sided Copper Hybrid Lux Med</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Natural Hybrid Pillowtop</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a><br>  <a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Med</a>';
				} else if(2.25<Ba && Ba<=2.5){ // 2.25 - 2.5 = 
					//replace with the recommendation
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Copper Hybrid Lux Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">2-Sided Natural Hybrid Original</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Medium</a><br>  <a href="https://www.amorebeds.com/product/bed/">Luxury 1-Sided Hybrid Firm</a>';
				} else if(2.51<Ba && Ba<=3){ 
					ans2='<a class="gil_recommend_link" href="https://www.amorebeds.com/product/bed/">12" Hybrid Foam/Coil Firm</a>;  <br><a class="gil_recommend_link" href="https://www.amorebeds.com/product/amore-luxury-hybrid-2-sided-latex-mattress/">2-Sided Luxury Hybrid Latex Lux Firm</a>;  <br><a class="gil_recommend_link" href=" https://www.amorebeds.com/product/amore-1-sided-natural-mattress/">1-Sided Natural Hybrid Original</a>';
				}
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
					ans1:ans1,Aans1:Bq8,
					user_data_s_r2:Ba,
					ans2:ans2,Bans1:Bq8,
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
		if( Aq1 == 'x' ){
			$('#Aq1e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
			$('#Aq1').css({border:'1px solid #ff0000a3', 'padding':'20px'}); //$('#q1').css({border-radius:'5px'});
		}

		if(Aq2 == 'x'){
			$('#Aq2e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
			$('#Aq2').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		if(Aq3 == 'x'){
			$('#Aq3e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
			$('#Aq3').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		if(Aq4 == 'x'){
			$('#Aq4e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
			$('#Aq4').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		if(Aq5 == 'x'){
			$('#Aq5e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
			$('#Aq5').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		//==================From B 2 people===========================================================
		if(Bq1 == 'x'){
		$('#Bq1e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
		$('#Bq1').css({border:'1px solid #ff0000a3','padding':'20px'});
		//$('#q1').css({border-radius:'5px'});
		}

		if(Bq2 == 'x'){
		$('#Bq2e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
		$('#Bq2').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		if(Bq3 == 'x'){
		$('#Bq3e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
		$('#Bq3').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		if(Bq4 == 'x'){
		$('#Bq4e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
		$('#Bq4').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		if(Bq5 == 'x'){
		$('#Bq5e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
		$('#Bq5').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}
		
		if( Bq8=='x' ){
			$('#q8e').html('<br><div style="color:#ff0000a3; font-size:14px;">Please select a value below</div>');
			$('#q8').css({border:'1px solid #ff0000a3', 'padding':'20px'});
		}

		}

	}
		
	$('#p1').click();
	$('#submit1').click(function (){

		$('.td_area').css( {'border':'0px'} );
		$('.error_message').html('');

		// see if there is value missing- if there is any missing show error 
		if( p1==1 ){

			openpersonsubmit();

		}
		else if( p1==2 ){

			twopeoplesubmit();

		}

		a = null;

	});

	oneperson();

	twopeople();

});