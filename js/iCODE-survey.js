/**************************
 * Survey Master 
 *************************/

/**************************
 * This object contains the newer functions. All global functions under are slowly 
 * being deprecated and replaced with rewritten newer functions
 **************************/

var iCODE;
(function ($) {
	iCODE = {
		/**
		 * Initializes all surveyzes or surveys on the page
		 */
		init: function() {
			// Makes sure we have surveyzes on this page
			if ( typeof icode_survey_data != 'undefined' && icode_survey_data) {
				// Cycle through all surveyzes
				_.each( icode_survey_data, function( survey ) {
					surveyID = parseInt( survey.survey_id );
					iCODE.initPagination( surveyID );
					if ( survey.hasOwnProperty( 'timer_limit' ) && 0 != survey.timer_limit ) {
						iCODE.initTimer( surveyID );
					}				
				});
			}
		},

		/**
		 * Sets up timer for a survey
		 * 
		 * @param int surveyID The ID of the survey
		 */
		initTimer: function( surveyID ) {

			// Gets our form
			var $surveyForm = iCODE.getsurveyForm( surveyID );

			// Creates timer status key.
			icode_survey_data[ surveyID ].timerStatus = false;

			// If we are using the newer pagination system...
			if ( 0 < $surveyForm.children( '.iCODE-page' ).length ) {
				// If there is a first page...
				if ( icode_survey_data[surveyID].hasOwnProperty('first_page') && icode_survey_data[surveyID].first_page ) {
					// ... attach an event handler to the click event to activate the timer.
					$( '#surveyForm' + surveyID ).closest( '.icode_survey_container' ).find( '.mlw_next' ).on( 'click', function(event) {
						event.preventDefault();
						if ( ! icode_survey_data[ surveyID ].timerStatus && icodeValidatePage( 'surveyForm' + surveyID ) ) {
							iCODE.activateTimer( surveyID );
						}
					});
				// ...else, activate the timer on page load.
				} else {
					iCODE.activateTimer( surveyID );
				}
			// ...else, we must be using the questions per page option.
			} else {
				if ( icode_survey_data[surveyID].hasOwnProperty('pagination') && icode_survey_data[surveyID].first_page ) {
					$( '#surveyForm' + surveyID ).closest( '.icode_survey_container' ).find( '.mlw_next' ).on( 'click', function(event) {
						event.preventDefault();
						if ( ! icode_survey_data[ surveyID ].timerStatus && ( 0 == $( '.survey_begin:visible' ).length || ( 1 == $( '.survey_begin:visible' ).length && icodeValidatePage( 'surveyForm' + surveyID ) ) ) ) {
							iCODE.activateTimer( surveyID );
						}
					});
				} else {
					iCODE.activateTimer( surveyID );
				}
			}
		},
		/**
		 * Starts the timer for the survey.
		 *
		 * @param int surveyID The ID of the survey.
		 */
		activateTimer: function( surveyID ) {
			
			// Gets our form.
			var $timer = iCODE.getTimer( surveyID );

			// Sets up our variables.
			icode_survey_data[ surveyID ].timerStatus = true;
			var seconds = 0;

			// Calculates starting time.
			var timerStarted = localStorage.getItem( 'mlw_started_survey' + surveyID );
			var timerRemaning = localStorage.getItem( 'mlw_time_survey' + surveyID );
			if ( 'yes' == timerStarted && 0 < timerRemaning ) {
				seconds = parseInt( timerRemaning );
			} else {
				seconds = parseFloat( icode_survey_data[ surveyID ].timer_limit ) * 60;
			}
			icode_survey_data[ surveyID ].timerRemaning = seconds;

			// Makes the timer appear.
			$timer.show();
			$timer.text( iCODE.secondsToTimer( seconds ) );

			// Sets up timer interval.
			icode_survey_data[ surveyID ].timerInterval = setInterval( iCODE.timer, 1000, surveyID );
		},
		/**
		 * Reduces the timer by one second and checks if timer is 0
		 *
		 * @param int surveyID The ID of the survey.
		 */
		timer: function( surveyID ) {
			icode_survey_data[ surveyID ].timerRemaning -= 1;
			if ( 0 > icode_survey_data[ surveyID ].timerRemaning ) {
				icode_survey_data[ surveyID ].timerRemaning = 0;
			}
			var secondsRemaining = icode_survey_data[ surveyID ].timerRemaning;
			var display = iCODE.secondsToTimer( secondsRemaining );

			// Sets our local storage values for the timer being started and current timer value.
			localStorage.setItem( 'mlw_time_survey' + surveyID, secondsRemaining );
			localStorage.setItem( 'mlw_started_survey' + surveyID, "yes" );

			// Updates timer element and title on browser tab.
			var $timer = iCODE.getTimer( surveyID );
			$timer.text( display );
			document.title = display + ' ' + iCODETitleText;

			// If timer is run out, disable fields.
			if ( 0 >= secondsRemaining ) {
				clearInterval( icode_survey_data[ surveyID ].timerInterval );
				$( ".mlw_icode_survey input:radio" ).attr( 'disabled', true );
				$( ".mlw_icode_survey input:checkbox" ).attr( 'disabled', true );
				$( ".mlw_icode_survey select" ).attr( 'disabled', true );
				$( ".mlw_icode_question_comment" ).attr( 'disabled', true );
				$( ".mlw_answer_open_text" ).attr( 'disabled', true );
				$( ".mlw_answer_number" ).attr( 'disabled', true );

				var $surveyForm = iCODE.getsurveyForm( surveyID );
				$surveyForm.closest( '.icode_survey_container' ).addClass( 'iCODE_timer_ended' );
				//document.surveyForm.submit();
				return;
			}
		},
		/**
		 * Clears timer interval
		 *
		 * @param int surveyID The ID of the survey
		 */
		endTimer: function( surveyID ) {
			localStorage.setItem( 'mlw_time_survey' + surveyID, 'completed' );
			localStorage.setItem( 'mlw_started_survey' + surveyID, 'no' );
			document.title = iCODETitleText;
			if ( typeof icode_survey_data[ surveyID ].timerInterval != 'undefined' ) {
				clearInterval( icode_survey_data[ surveyID ].timerInterval );
			}
		},
		/**
		 * Converts seconds to 00:00:00 format
		 *
		 * @param int seconds The number of seconds
		 * @return string A string in H:M:S format
		 */
		secondsToTimer: function( seconds ) {
			var formattedTime = '';
			seconds = parseInt( seconds );

			// Prepares the hours part.
			var hours = Math.floor( seconds / 3600 );
			if ( 0 === hours) {
				formattedTime = '00:';
			} else if ( 10 > hours ) {
				formattedTime = '0' + hours + ':';
			} else {
				formattedTime = hours + ':';
			}

			// Prepares the minutes part.
			var minutes = Math.floor( ( seconds % 3600 ) / 60 );
			if ( 0 === minutes) {
				formattedTime = formattedTime + '00:';
			} else if ( 10 > minutes ) {
				formattedTime = formattedTime + '0' + minutes + ':';
			} else {
				formattedTime = formattedTime + minutes + ':';
			}

			// Prepares the seconds part.
			var remainder = Math.floor( ( seconds % 60 ) );
			if ( 0 === remainder) {
				formattedTime = formattedTime + '00';
			} else if ( 10 > remainder ) {
				formattedTime = formattedTime + '0' + remainder;
			} else {
				formattedTime = formattedTime + remainder;
			}
			return formattedTime;
		},
		/**
		 * Gets the jQuery object for the timer
		 */
		getTimer: function( surveyID ) {
			var $surveyForm = iCODE.getsurveyForm( surveyID );
			return $surveyForm.children( '.mlw_icode_timer' );
		},
		/**
		 * Sets up pagination for a survey
		 * 
		 * @param int surveyID The ID of the survey.
		 */
		initPagination: function( surveyID ) {
			var $surveyForm = iCODE.getsurveyForm( surveyID );
			if ( 0 < $surveyForm.children( '.iCODE-page' ).length ) {
				$surveyForm.children( '.iCODE-page' ).hide();
				template = wp.template( 'iCODE-pagination' );
				$surveyForm.append( template() );
				if ( '1' == icode_survey_data[ surveyID ].progress_bar ) {
					$( '#iCODE-progress-bar' ).show();
					icode_survey_data[ surveyID ].bar = new ProgressBar.Line('#iCODE-progress-bar', {
						strokeWidth: 2,
						easing: 'easeInOut',
						duration: 1400,
						color: '#3498db',
						trailColor: '#eee',
						trailWidth: 1,
						svgStyle: {width: '100%', height: '100%'},
						text: {
						  style: {
							// color: '#999',
							position: 'absolute',
							padding: 0,
							margin: 0,
							top: 0,
							right: '10px',
							'font-size': '13px',
							'font-weight': 'bold',
							transform: null
						  },
						  autoStyleContainer: false
						},
						from: {color: '#3498db'},
						to: {color: '#ED6A5A'},
						step: function(state, bar) {
						  bar.setText(Math.round(bar.value() * 100) + ' %');
						}
					});
				}
				iCODE.goToPage( surveyID, 1 );
				$surveyForm.find( '.iCODE-pagination .iCODE-next' ).on( 'click', function( event ) {
					event.preventDefault();
					iCODE.nextPage( surveyID );
				});
				$surveyForm.find( '.iCODE-pagination .iCODE-previous' ).on( 'click', function( event ) {
					event.preventDefault();
					iCODE.prevPage( surveyID );
				});
			}			
		},
		/**
		 * Navigates survey to specific page
		 *
		 * @param int pageNumber The number of the page
		 */
		goToPage: function( surveyID, pageNumber ) {
			var $surveyForm = iCODE.getsurveyForm( surveyID );
			var $pages = $surveyForm.children( '.iCODE-page' );
			$pages.hide();
			$surveyForm.children( '.iCODE-page:nth-of-type(' + pageNumber + ')' ).show();
			$surveyForm.find( '.iCODE-previous' ).hide();
			$surveyForm.find( '.iCODE-next' ).hide();
			$surveyForm.find( '.iCODE-submit-btn' ).hide();
			if ( pageNumber < $pages.length ) {
				$surveyForm.find( '.iCODE-next' ).show();
			} else {
				$surveyForm.find( '.iCODE-submit-btn' ).show();
			}
			if ( 1 < pageNumber ) {
				$surveyForm.find( '.iCODE-previous' ).show();
			}
			if ( '1' == icode_survey_data[ surveyID ].progress_bar ) {
				icode_survey_data[ surveyID ].bar.animate( pageNumber / $pages.length );
			}
			iCODE.savePage( surveyID, pageNumber );
		},
		/**
		 * Moves forward or backwards through the pages
		 *
		 * @param int surveyID The ID of the survey
		 * @param int difference The number of pages to forward or back
		 */
		changePage: function( surveyID, difference ) {
			var page = iCODE.getPage( surveyID );
			page += difference;
			iCODE.goToPage( surveyID, page );
		},
		nextPage: function( surveyID ) {
			if ( icodeValidatePage( 'surveyForm' + surveyID ) ) {
				iCODE.changePage( surveyID, 1 );
			}			
		},
		prevPage: function( surveyID ) {
			iCODE.changePage( surveyID, -1 );
		},
		savePage: function( surveyID, pageNumber ) {
			sessionStorage.setItem( 'survey' + surveyID + 'page', pageNumber );
		},
		getPage: function( surveyID ) {
			pageNumber = parseInt( sessionStorage.getItem( 'survey' + surveyID + 'page' ) );
			if ( isNaN( pageNumber ) || null == pageNumber ) {
				pageNumber = 1;
			}
			return pageNumber;
		},
		/**
		 * Scrolls to the top of supplied element
		 *
		 * @param jQueryObject The jQuery version of an element. i.e. $('#surveyForm3')
		 */
		scrollTo: function( $element ) {
			jQuery( 'html, body' ).animate( 
				{ 
					scrollTop: $element.offset().top - 150
				}, 
			1000 );
		},
		/**
		 * Gets the jQuery object of the survey form
		 */
		getsurveyForm: function( surveyID ) {
			return $( '#surveyForm' + surveyID );
		}
	};

	// On load code
	$(function() {

		// Legacy init.
		icodeInit();

		// Call main initialization.
		iCODE.init();
	});
}(jQuery));

// Global Variables
var iCODETitleText = document.title;

function icodeTimeTakenTimer() {
	var x = +jQuery( '#timer' ).val();
	if ( NaN === x ) {
		x = 0;
	}
	x = x + 1;
	jQuery( '#timer' ).val( x );
}

function iCODEEndTimeTakenTimer() {
	clearInterval( iCODETimerInterval );
}

function icodeClearField( field ) {
	if ( field.defaultValue == field.value ) field.value = '';
}

// function iCODEScrollTo( $element ) {
// 	$( 'html, body' ).animate( { scrollTop: $element.offset().top - 150 }, 1000 );
// }

function icodeDisplayError( message, field, survey_form_id ) {
	jQuery( '#' + survey_form_id + ' .icode_error_message_section' ).addClass( 'icode_error_message' );
	jQuery( '#' + survey_form_id + ' .icode_error_message' ).text( message );
	field.closest( '.survey_section' ).addClass( 'icode_error' );
}

function icodeResetError( survey_form_id ) {
	jQuery( '#' + survey_form_id + ' .icode_error_message' ).text( '' );
	jQuery( '#' + survey_form_id + ' .icode_error_message_section' ).removeClass( 'icode_error_message' );
	jQuery( '#' + survey_form_id + ' .survey_section' ).removeClass( 'icode_error' );
}

function icodeValidation( element, survey_form_id ) {
	var result = true;
	var survey_id = +jQuery( '#' + survey_form_id ).find( '.icode_survey_id' ).val();
	var email_error = icode_survey_data[ survey_id ].error_messages.email;
	var number_error = icode_survey_data[ survey_id ].error_messages.number;
	var empty_error = icode_survey_data[ survey_id ].error_messages.empty;
	var incorrect_error = icode_survey_data[ survey_id ].error_messages.incorrect;
	icodeResetError( survey_form_id );
	jQuery( element ).each(function(){
		if ( jQuery( this ).attr( 'class' )) {
			if( jQuery( this ).attr( 'class' ).indexOf( 'mlwEmail' ) > -1 && this.value !== "" ) {
				var x = this.value;
				var atpos = x.indexOf('@');
				var dotpos = x.lastIndexOf( '.' );
				if ( atpos < 1 || dotpos < atpos + 2 || dotpos + 2>= x.length ) {
					icodeDisplayError( email_error, jQuery( this ), survey_form_id );
					result = false;
				}
			}
			if ( localStorage.getItem( 'mlw_time_survey' + survey_id ) === null || localStorage.getItem( 'mlw_time_survey'+survey_id ) > 0.08 ) {

				if( jQuery( this ).attr( 'class' ).indexOf( 'mlwRequiredNumber' ) > -1 && this.value === "" && +this.value != NaN ) {
					icodeDisplayError( number_error, jQuery( this ), survey_form_id );
					result =  false;
				}
				if( jQuery( this ).attr( 'class' ).indexOf( 'mlwRequiredText' ) > -1 && this.value === "" ) {
					icodeDisplayError( empty_error, jQuery( this ), survey_form_id );
					result =  false;
				}
				if( jQuery( this ).attr( 'class' ).indexOf( 'mlwRequiredCaptcha' ) > -1 && this.value != mlw_code ) {
					icodeDisplayError( incorrect_error, jQuery( this ), survey_form_id );
					result =  false;
				}
				if( jQuery( this ).attr( 'class' ).indexOf( 'mlwRequiredAccept' ) > -1 && ! jQuery( this ).prop( 'checked' ) ) {
					icodeDisplayError( empty_error, jQuery( this ), survey_form_id );
					result =  false;
				}
				if( jQuery( this ).attr( 'class' ).indexOf( 'mlwRequiredRadio' ) > -1 ) {
					check_val = jQuery( this ).find( 'input:checked' ).val();
					if ( check_val == "No Answer Provided" ) {
						icodeDisplayError( empty_error, jQuery( this ), survey_form_id );
						result =  false;
					}
				}
				if( jQuery( this ).attr( 'class' ).indexOf( 'iCODERequiredSelect' ) > -1 ) {
					check_val = jQuery( this ).val();
					if ( check_val == "No Answer Provided" ) {
						icodeDisplayError( empty_error, jQuery( this ), survey_form_id );
						result =  false;
					}
				}
				if( jQuery( this ).attr( 'class' ).indexOf( 'mlwRequiredCheck' ) > -1 ) {
					if ( ! jQuery( this ).find( 'input:checked' ).length ) {
						icodeDisplayError( empty_error, jQuery( this ), survey_form_id );
						result =  false;
					}
				}
			}
		}
	});
	return result;
}

function icodeFormSubmit( survey_form_id ) {
	var survey_id = +jQuery( '#' + survey_form_id ).find( '.icode_survey_id' ).val();
	var $container = jQuery( '#' + survey_form_id ).closest( '.icode_survey_container' );
	var result = icodeValidation( '#' + survey_form_id + ' *', survey_form_id );

	if ( ! result ) { return result; }

	jQuery( '.mlw_icode_survey input:radio' ).attr( 'disabled', false );
	jQuery( '.mlw_icode_survey input:checkbox' ).attr( 'disabled', false );
	jQuery( '.mlw_icode_survey select' ).attr( 'disabled', false );
	jQuery( '.mlw_icode_question_comment' ).attr( 'disabled', false );
	jQuery( '.mlw_answer_open_text' ).attr( 'disabled', false );

	var data = {
		action: 'icode_process_survey',
		surveyData: jQuery( '#' + survey_form_id ).serialize()
	};

	iCODEEndTimeTakenTimer();

	if ( icode_survey_data[survey_id].hasOwnProperty( 'timer_limit' ) ) {
		iCODE.endTimer( survey_id );
	}

	jQuery( '#' + survey_form_id + ' input[type=submit]' ).attr( 'disabled', 'disabled' );
	iCODEDisplayLoading( $container );

	jQuery.post( icode_ajax_object.ajaxurl, data, function( response ) {
		icodeDisplayResults( JSON.parse( response ), survey_form_id, $container );
	});

	return false;
}

function iCODEDisplayLoading( $container ) {
	$container.empty();
	$container.append( '<div class="iCODE-spinner-loader">Loadding...</div>' );
	// iCODEScrollTo( $container );
}

function icodeDisplayResults( results, survey_form_id, $container ) {
	$container.empty();
	if ( results.redirect ) {
		window.location.replace( results.redirect_url );
	} else {
		$container.append( '<div class="icode_results_page"></div>' );
		$container.find( '.icode_results_page' ).html( results.display );
		// iCODEScrollTo( $container );
		
	}
}

function icodeInit() {
	if ( typeof icode_survey_data != 'undefined' && icode_survey_data ) {
		for ( var key in icode_survey_data ) {
			if ( icode_survey_data[key].ajax_show_correct === '1' ) {
				jQuery( '#surveyForm' + icode_survey_data[key].survey_id + ' .icode_survey_radio').change(function() {
					var chosen_answer = jQuery(this).val();
					var question_id = jQuery(this).attr('name').replace(/question/i,'');
					var chosen_id = jQuery(this).attr('id');
					jQuery.each( icode_survey_data[key].question_list, function( i, value ) {
						if ( question_id == value.question_id ) {
							jQuery.each( value.answers, function(j, answer ) {
								if ( answer[0] === chosen_answer ) {
									if ( answer[2] !== 1) {
										jQuery( '#'+chosen_id ).parent().addClass( "icode_incorrect_answer" );
									}
								}
								if ( answer[2] === 1) {
									jQuery( ':radio[name=question'+question_id+'][value="'+answer[0]+'"]' ).parent().addClass( "icode_correct_answer" );
								}
							});
						}
					});
				});
			}

			if ( icode_survey_data[key].disable_answer === '1' ) {
				jQuery( '#surveyForm' + icode_survey_data[key].survey_id + ' .icode_survey_radio').change(function() {
					var radio_group = jQuery(this).attr('name');
					jQuery('input[type=radio][name='+radio_group+']').prop('disabled',true);
				});
			}

			if ( icode_survey_data[key].hasOwnProperty('pagination') ) {
		    icodeInitPagination( icode_survey_data[key].survey_id );
			}
		}
	}
}

//Function to validate the answers provided in survey
function icodeValidatePage( survey_form_id ) {
	var result = icodeValidation( '#' + survey_form_id + ' .survey_section:visible *', survey_form_id );
	return result;
}

//Function to advance survey to next page
function icodeNextSlide( pagination, go_to_top, survey_form_id ) {
	var survey_id = +jQuery( survey_form_id ).find( '.icode_survey_id' ).val();
	var $container = jQuery( survey_form_id ).closest( '.icode_survey_container' );
	var slide_number = +$container.find( '.slide_number_hidden' ).val();
	var previous = +$container.find( '.previous_amount_hidden' ).val();
	var section_totals = +$container.find( '.total_sections_hidden' ).val();

	jQuery( survey_form_id + " .survey_section" ).hide();
	for ( var i = 0; i < pagination; i++ ) {
		if (i === 0 && previous === 1 && slide_number > 1) {
			slide_number = slide_number + pagination;
		} else {
			slide_number++;
		}
		if (slide_number < 1) {
			slide_number = 1;
		}
		$container.find( ".mlw_icode_survey_link.mlw_previous" ).hide();

    if ( icode_survey_data[ survey_id ].first_page ) {
      if (slide_number > 1) {
				$container.find( ".mlw_icode_survey_link.mlw_previous" ).show();
      }
    } else {
			if (slide_number > pagination) {
				$container.find( ".mlw_icode_survey_link.mlw_previous" ).show();
			}
    }
		if (slide_number == section_totals) {
			$container.find( ".mlw_icode_survey_link.mlw_next" ).hide();
		}
		if (slide_number < section_totals) {
			$container.find( ".mlw_icode_survey_link.mlw_next" ).show();
		}
		jQuery( survey_form_id + " .survey_section.slide" + slide_number ).show();
	}

	jQuery( survey_form_id ).closest( '.icode_survey_container' ).find( '.slide_number_hidden' ).val( slide_number );
	jQuery( survey_form_id ).closest( '.icode_survey_container' ).find( '.previous_amount_hidden' ).val( 0 );

	icodeUpdatePageNumber( 1, survey_form_id );

	if (go_to_top == 1) {
		// iCODEScrollTo( $container );
	}
}

function icodePrevSlide( pagination, go_to_top, survey_form_id ) {
	var survey_id = +jQuery( survey_form_id ).find( '.icode_survey_id' ).val();
	var $container = jQuery( survey_form_id ).closest( '.icode_survey_container' );
	var slide_number = +$container.find( '.slide_number_hidden' ).val();
	var previous = +$container.find( '.previous_amount_hidden' ).val();
	var section_totals = +$container.find( '.total_sections_hidden' ).val();

	jQuery( survey_form_id + " .survey_section" ).hide();
	for (var i = 0; i < pagination; i++) {
		if (i === 0 && previous === 0)	{
			slide_number = slide_number - pagination;
		} else {
			slide_number--;
		}
		if (slide_number < 1) {
			slide_number = 1;
		}

		$container.find( ".mlw_icode_survey_link.mlw_previous" ).hide();

		if ( icode_survey_data[ survey_id ].first_page ) {
			if (slide_number > 1) {
				$container.find( ".mlw_icode_survey_link.mlw_previous" ).show();
			}
		} else {
			if (slide_number > pagination) {
				$container.find( ".mlw_icode_survey_link.mlw_previous" ).show();
			}
		}
		if (slide_number == section_totals) {
			$container.find( ".mlw_icode_survey_link.mlw_next" ).hide();
		}
		if (slide_number < section_totals) {
			$container.find( ".mlw_icode_survey_link.mlw_next" ).show();
		}
		jQuery( survey_form_id + " .survey_section.slide" + slide_number ).show();
	}

	icodeUpdatePageNumber( -1, survey_form_id );

	jQuery( survey_form_id ).closest( '.icode_survey_container' ).find( '.slide_number_hidden' ).val( slide_number );
	jQuery( survey_form_id ).closest( '.icode_survey_container' ).find( '.previous_amount_hidden' ).val( 0 );

	if (go_to_top == 1) {
		// iCODEScrollTo( $container );
	}
}

function icodeUpdatePageNumber( amount, survey_form_id ) {
	var current_page = +jQuery( survey_form_id ).closest( '.icode_survey_container' ).find( '.current_page_hidden' ).val();
	var total_pages = jQuery( survey_form_id ).closest( '.icode_survey_container' ).find( '.total_pages_hidden' ).val();
	current_page += amount;
	//jQuery( survey_form_id ).siblings( '.icode_pagination' ).find( " .icode_page_counter_message" ).text( current_page + "/" + total_pages );
}

function icodeInitPagination( survey_id ) {

	var icode_section_total = +icode_survey_data[survey_id].pagination.total_questions + 1;
	if ( icode_survey_data[survey_id].pagination.section_comments === '0' ) {
		icode_section_total += 1;
	}
	var icode_total_pages = Math.ceil( icode_section_total / +icode_survey_data[survey_id].pagination.amount );
	if ( icode_survey_data[survey_id].first_page ) {
		icode_total_pages += 1;
		icode_section_total += 1;
	}

	jQuery( '#surveyForm' + survey_id + ' .survey_section' ).hide();
	jQuery( '#surveyForm' + survey_id + ' .survey_section' ).append( "<br />" );
	jQuery( '#surveyForm' + survey_id ).closest( '.icode_survey_container' ).append( '<div class="icode_pagination border margin-bottom"></div>' );
	jQuery( '#surveyForm' + survey_id ).closest( '.icode_survey_container' ).find( '.icode_pagination' ).append( '<input type="hidden" value="0" name="slide_number" class="slide_number_hidden" />')
		.append( '<input type="hidden" value="0" name="current_page" class="current_page_hidden" />')
		.append( '<input type="hidden" value="' + icode_total_pages + '" name="total_pages" class="total_pages_hidden" />')
		.append( '<input type="hidden" value="' + icode_section_total + '" name="total_sections" class="total_sections_hidden" />')
		.append( '<input type="hidden" value="0" name="previous_amount" class="previous_amount_hidden" />')
		.append( '<a class="icode_btn mlw_icode_survey_link mlw_previous" href="#">' + icode_survey_data[survey_id].pagination.previous_text + '</a>' )
		.append( '<span class="icode_page_message"></span>' )
		.append( '<div class="icode_page_counter_message"></div>' )
		.append( '<a class="icode_btn mlw_icode_survey_link mlw_next" href="#">' + icode_survey_data[survey_id].pagination.next_text + '</a>' );

	jQuery(".mlw_next").click(function(event) {
		event.preventDefault();
		var survey_id = +jQuery( this ).closest( '.icode_survey_container' ).find( '.icode_survey_id' ).val();
		if ( icodeValidatePage( 'surveyForm' + survey_id ) ) {
			icodeNextSlide( icode_survey_data[survey_id].pagination.amount, 1, '#surveyForm' + survey_id );
		}
	});
	
	jQuery(".mlw_previous").click(function(event) {
		event.preventDefault();
		var survey_id = +jQuery( this ).closest( '.icode_survey_container' ).find( '.icode_survey_id' ).val();
		icodePrevSlide( icode_survey_data[survey_id].pagination.amount, 1, '#surveyForm' + survey_id );
	});
	
	if ( icode_survey_data[survey_id].first_page ) {
		icodeNextSlide( 1, 0, '#surveyForm' + survey_id );
	} else {
		icodeNextSlide( icode_survey_data[survey_id].pagination.amount, 0, '#surveyForm' + survey_id );
	}
}

function icodeSocialShare( network, mlw_icode_social_text, mlw_icode_title, facebook_id ) {
	var sTop = window.screen.height / 2 - ( 218 );
	var sLeft = window.screen.width / 2 - ( 313 );
	var sqShareOptions = "height=400,width=580,toolbar=0,status=0,location=0,menubar=0,directories=0,scrollbars=0,top=" + sTop + ",left=" + sLeft;
	var pageUrl = window.location.href;
	var pageUrlEncoded = encodeURIComponent( pageUrl );
	var url = '';
	if ( network == 'facebook' ) {
		url = "https://www.facebook.com/dialog/feed?"	+ "display=popup&" + "app_id="+facebook_id +
			"&" + "link=" + pageUrlEncoded + "&" + "name=" + encodeURIComponent(mlw_icode_social_text) +
			"&" + "description=  &" + "redirect_uri=http://www.mylocalwebstop.com/mlw_icode_close.html";
	}
	if ( network == 'twitter' )	{
		url = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(mlw_icode_social_text);
	}
	window.open( url, "Share", sqShareOptions );
	return false;
}

jQuery(function() {	
	jQuery( '.icode_survey_container' ).tooltip();
	
	jQuery( '.icode_survey_container input' ).on( 'keypress', function ( e ) {
		if ( e.which === 13 ) {
			e.preventDefault();
		}
	});
	
	jQuery( '.icode_survey_form' ).on( "submit", function( event ) {
	  event.preventDefault();
		icodeFormSubmit( this.id );
	});
});

var iCODETimerInterval = setInterval( icodeTimeTakenTimer, 1000 );


// edit JS



jQuery( document ).ready(function() {
    if (jQuery('.icode_results_page').length) {
	  	jQuery('.w-tabs-list').addClass('active');
	}
});

