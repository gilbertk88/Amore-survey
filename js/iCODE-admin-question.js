/**
 * iCODE Question Tab
 */

var iCODEQuestion;
(function ($) {
	iCODEQuestion = {
		question: Backbone.Model.extend({
			defaults: {
				id: null,
				surveyID: 1,
				type: '0',
				name: 'Your new question!',
				answerInfo: '',
				comments: '1',
				hint: '',
				category: '',
				required: 1,
				answers: [],
				page: 0
			}
		}),
		questions: null,
		questionCollection: null,
		categories: [],
		openQuestionBank: function( pageID ) {
			iCODEQuestion.loadQuestionBank();
			$( '#add-question-bank-page' ).val( pageID );
			MicroModal.show( 'modal-2' );
		},
		loadQuestionBank: function() {
			$( '#question-bank' ).empty();
			$( '#question-bank' ).append( '<div class="iCODE-spinner-loader"></div>' );
			$.ajax( {
				url: wpApiSettings.root + 'survey-survey-master/v1/questions',
				method: 'GET',
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', iCODEQuestionSettings.nonce );
				},
				data: {
					'surveyID' : 0
				},
				success: iCODEQuestion.questionBankLoadSuccess
			});
		},
		questionBankLoadSuccess: function( questions ) {
			$( '#question-bank' ).empty();
			for ( var i = 0; i < questions.length; i++) {
				iCODEQuestion.addQuestionToQuestionBank( questions[i] );
			}
		},
		addQuestionToQuestionBank: function( question ) {
			var questionText = iCODEQuestion.prepareQuestionText( question.name );
			var template = wp.template( 'single-question-bank-question' );
			$( '#question-bank' ).append( template( { id: question.id, question: questionText } ) );
		},
		addQuestionFromQuestionBank: function( questionID ) {
			MicroModal.close( 'modal-2' );
			iCODEQuestion.displayAlert( 'Adding question...', 'info' );
			var model = new iCODEQuestion.question( { id: questionID } );
			model.fetch({ 
				headers: { 'X-WP-Nonce': iCODEQuestionSettings.nonce },
				url: wpApiSettings.root + 'survey-survey-master/v1/questions/' + questionID,
				success: iCODEQuestion.questionBankSuccess,
				error: iCODEQuestion.displayError
			});	
		},
		questionBankSuccess: function( model ) {
			var page = parseInt( $( '#add-question-bank-page' ).val(), 10 );
			model.set( 'page', page );
			iCODEQuestion.displayAlert( 'Question added!', 'success' );
			iCODEQuestion.questions.add( model );
			iCODEQuestion.addQuestionToPage( model );
		},
		prepareCategories: function() {
			iCODEQuestion.categories = [];
			iCODEQuestion.questions.each(function( question ) {
				var category = question.get( 'category' );
				if ( 0 !== category.length && ! _.contains( iCODEQuestion.categories, category ) ) {
					iCODEQuestion.categories.push( category );
				}
			});
		},
		processCategories: function() {
			$( '.category' ).remove();
			_.each( iCODEQuestion.categories, function( category ) {
				iCODEQuestion.addCategory( category );
			});
		},
		addCategory: function( category ) {
			var template = wp.template( 'single-category' );
			$( '#categories' ).prepend( template( { category: category } ) );
		},
		loadQuestions: function() {
			iCODEQuestion.displayAlert( 'Loading questions...', 'info' );
			iCODEQuestion.questions.fetch({ 
				headers: { 'X-WP-Nonce': iCODEQuestionSettings.nonce },
				data: { surveyID: iCODEQuestionSettings.surveyID },
				success: iCODEQuestion.loadSuccess,
				error: iCODEQuestion.displayError
			});
		},
		loadSuccess: function() {
			iCODEQuestion.clearAlerts();
			var question;
			if ( iCODEQuestionSettings.pages.length > 0 ) {
				for ( var i = 0; i < iCODEQuestionSettings.pages.length; i++ ) {
					for ( var j = 0; j < iCODEQuestionSettings.pages[ i ].length; j++ ) {
						question = iCODEQuestion.questions.get( iCODEQuestionSettings.pages[ i ][ j ] );
						iCODEQuestion.addQuestionToPage( question );
					}
				}
			} else {
				iCODEQuestion.questions.each( iCODEQuestion.addQuestionToPage );
			}
		},
		savePages: function() {
			iCODEQuestion.displayAlert( 'Saving pages and questions...', 'info' );
			var pages = [];

			// Cycles through each page and add page + questions to pages variable
			_.each( jQuery( '.page' ), function( page ) {

				// If page is empty, do not add it.
				if( 0 == jQuery( page ).children( '.question' ).length ) {
					return;
				}
				var singlePage = [];
				// Cycle through each question and add to the page.
				_.each( jQuery( page ).children( '.question' ), function( question ){
					singlePage.push( jQuery( question ).data( 'question-id' ) )
				});
				pages.push( singlePage );
			});
			var data = {
				action: 'iCODE_save_pages',
				pages: pages,
				survey_id : iCODEQuestionSettings.surveyID
			};
	
			jQuery.ajax( ajaxurl, {
				data: data,
				method: 'POST',
				success: iCODEQuestion.savePagesSuccess,
				error: iCODEQuestion.displayjQueryError
			});
		},
		savePagesSuccess: function() {
			iCODEQuestion.displayAlert( 'Questions and pages were saved!', 'success' );
		},
		addNewPage: function() {
			var template = wp.template( 'page' );
			$( '.questions' ).append( template() );
			$( '.page' ).sortable({
				items: '.question',
				opacity: 70,
				cursor: 'move',
				placeholder: "ui-state-highlight",
				connectWith: '.page'
			});
			setTimeout( iCODEQuestion.removeNew, 250 );
		},
		addNewQuestion: function( model ) {
			iCODEQuestion.displayAlert( 'Question created!', 'success' );
			iCODEQuestion.addQuestionToPage( model );
			iCODEQuestion.openEditPopup( model.id );
		},
		addQuestionToPage: function( model ) {
			var page = model.get( 'page' ) + 1;
			var template = wp.template( 'question' );
			var page_exists = $( '.page:nth-child(' + page + ')' ).length;
			var count = 0;
			while ( ! page_exists ) {
				iCODEQuestion.addNewPage();
				page_exists = $( '.page:nth-child(' + page + ')' ).length;
				count++;
				if ( count > 5 ) {
					page_exists = true;
					console.log( 'count reached' );
				}
			}
			var questionName = iCODEQuestion.prepareQuestionText( model.get( 'name' ) );
			$( '.page:nth-child(' + page + ')' ).append( template( { id: model.id, category : model.get('category'), question: questionName } ) );
			setTimeout( iCODEQuestion.removeNew, 250 );
		},
		createQuestion: function( page ) {
			iCODEQuestion.displayAlert( 'Creating question...', 'info' );
			iCODEQuestion.questions.create( 
				{ 
					surveyID: iCODEQuestionSettings.surveyID,
					page: page
				},
				{
					headers: { 'X-WP-Nonce': iCODEQuestionSettings.nonce },
					success: iCODEQuestion.addNewQuestion,
					error: iCODEQuestion.displayError
				}
			);
		},
		duplicateQuestion: function( questionID ) {
			iCODEQuestion.displayAlert( 'Duplicating question...', 'info' );
			var model = iCODEQuestion.questions.get( questionID );
			var newModel = _.clone(model.attributes);
			newModel.id = null;
			iCODEQuestion.questions.create( 
				newModel,
				{
					headers: { 'X-WP-Nonce': iCODEQuestionSettings.nonce },
					success: iCODEQuestion.addNewQuestion,
					error: iCODEQuestion.displayError
				}
			);
		},
		saveQuestion: function( questionID ) {
			iCODEQuestion.displayAlert( 'Saving question...', 'info' );
			var model = iCODEQuestion.questions.get( questionID );
			var hint = $( '#hint' ).val();
			var name = wp.editor.getContent( 'question-text' );
			var answerInfo = $( '#correct_answer_info' ).val();
			var type = $( "#question_type" ).val();
			var comments = $( "#comments" ).val();
			var required = $( "#required" ).val();
			var category = $( ".category-radio:checked" ).val();
			if ( 'new_category' == category ) {
				category = $( '#new_category' ).val();
			}
			if ( ! category ) {
				category = '';
			}
			var answers = [];
			var $answers = jQuery( '.answers-single');
			_.each( $answers, function( answer ) {
				var $answer = jQuery( answer );
				var answer = $answer.find( '.answer-text' ).val();
				var points = $answer.find( '.answer-points' ).val();
				var correct = 0;
				if ( $answer.find( '.answer-correct' ).prop( 'checked' ) ) {
					correct = 1;
				}
				answers.push( [ answer, points, correct ] );
			});
			model.save( 
				{ 
					type: type,
					name: name,
					answerInfo: answerInfo,
					comments: comments,
					hint: hint,
					category: category,
					required: required,
					answers: answers,
				}, 
				{ 
					headers: { 'X-WP-Nonce': iCODEQuestionSettings.nonce },
					success: iCODEQuestion.saveSuccess,
					error: iCODEQuestion.displayError,
					type: 'POST'
				} 
			);
			MicroModal.close('modal-1');
		},
		saveSuccess: function( model ) {
			iCODEQuestion.displayAlert( 'Question was saved!', 'success' );
			var template = wp.template( 'question' );
			var page = model.get( 'page' ) + 1;
			$( '.question[data-question-id=' + model.id + ']' ).replaceWith( template( { id: model.id, type : model.get('type'), category : model.get('category'), question: model.get('name') } ) );
			setTimeout( iCODEQuestion.removeNew, 250 );
		},
		addNewAnswer: function( answer ) {
			var answerTemplate = wp.template( 'single-answer' );
			$( '#answers' ).append( answerTemplate( { answer: answer[0], points: answer[1], correct: answer[2] } ) );
		},
		openEditPopup: function( questionID ) {
			iCODEQuestion.prepareCategories();
			iCODEQuestion.processCategories();
			var question = iCODEQuestion.questions.get( questionID );
			var questionText = iCODEQuestion.prepareQuestionText( question.get( 'name' ) );
			$( '#edit_question_id' ).val( questionID );
			var question_editor = tinyMCE.get( 'question-text' );
			if ($('#wp-question-text-wrap').hasClass('html-active')) {
				jQuery( "#question-text" ).val( questionText );
			} else if ( question_editor ) {
				tinyMCE.get( 'question-text' ).setContent( questionText );
			} else {
				jQuery( "#question-text" ).val( questionText );
			}

			$( '#answers' ).empty();
			var answers = question.get( 'answers' );
			_.each( answers, function( answer ) {
				iCODEQuestion.addNewAnswer( answer );
			});
			$( '#hint' ).val( question.get( 'hint' ) );
			$( '#correct_answer_info' ).val( question.get( 'answerInfo' ) );
			$( "#question_type" ).val( question.get( 'type' ) );
			$( "#comments" ).val( question.get( 'comments' ) );
			$( "#required" ).val( question.get( 'required' ) );
			$( ".category-radio" ).removeAttr( 'checked' );
			if ( 0 !== question.get( 'category' ).length ) {
				$( ".category-radio" ).val( [question.get( 'category' )] );
			}
			MicroModal.show( 'modal-1' );
		},
		displayjQueryError: function( jqXHR, textStatus, errorThrown ) {
			iCODEQuestion.displayAlert( 'Error: ' + errorThrown + '! Please try again.', 'error' );
		},
		displayError: function( jqXHR, textStatus, errorThrown ) {
			iCODEQuestion.displayAlert( 'Error: ' + errorThrown.errorThrown + '! Please try again.', 'error' );
		},
		displayAlert: function( message, type ) {
			iCODEQuestion.clearAlerts();
			var template = wp.template( 'notice' );
			var data = {
				message: message,
				type: type
			};
			$( '.questions-messages' ).append( template( data ) );
		},
		clearAlerts: function() {
			$( '.questions-messages' ).empty();
		},
		removeNew: function() {
			$( '.page-new' ).removeClass( 'page-new' );
			$( '.question-new' ).removeClass( 'question-new' );
		},
		prepareQuestionText: function( question ) {
			return jQuery('<textarea />').html( question ).text();
		},
		prepareEditor: function() {
			var settings = {
				mediaButtons: true,
				tinymce:      {
      				forced_root_block : '',
					toolbar1: 'formatselect,bold,italic,bullist,numlist,link,blockquote,alignleft,aligncenter,alignright,strikethrough,hr,forecolor,pastetext,removeformat,codeformat,undo,redo'
				},
				quicktags:    true,
			};
			wp.editor.initialize( 'question-text', settings );
		}
	};

	$(function() {
		iCODEQuestion.questionCollection = Backbone.Collection.extend({
			url: wpApiSettings.root + 'survey-survey-master/v1/questions',
			model: iCODEQuestion.question
		});
		iCODEQuestion.questions = new iCODEQuestion.questionCollection();
		$( '.new-page-button' ).on( 'click', function( event ) {
			event.preventDefault();
			iCODEQuestion.addNewPage();
		});

		$( '.questions' ).on( 'click', '.new-question-button', function( event ) {
			event.preventDefault();
			iCODEQuestion.createQuestion( $( this ).parents( '.page' ).index() );
		});
		
		$( '.questions' ).on( 'click', '.add-question-bank-button', function( event ) {
			event.preventDefault();
			iCODEQuestion.openQuestionBank( $( this ).parents( '.page' ).index() );
		});

		$( '.questions' ).on( 'click', '.edit-question-button', function( event ) {
			event.preventDefault();
			iCODEQuestion.openEditPopup( $( this ).parents( '.question' ).data( 'question-id' ) );
		});

		$( '.questions' ).on( 'click', '.duplicate-question-button', function( event ) {
			event.preventDefault();
			iCODEQuestion.duplicateQuestion( $( this ).parents( '.question' ).data( 'question-id' ) );
		});
		$( '.questions' ).on( 'click', '.delete-question-button', function( event ) {
			event.preventDefault();
			$( this ).parents( '.question' ).remove();
		});
		$( '.questions' ).on( 'click', '.delete-page-button', function( event ) {
			event.preventDefault();
			$( this ).parents( '.page' ).remove();
		});
		$( '#answers' ).on( 'click', '.delete-answer-button', function( event ) {
			event.preventDefault();
			$( this ).parents( '.answers-single' ).remove();
		});
		$( '#save-popup-button' ).on( 'click', function( event ) {
			event.preventDefault();
			iCODEQuestion.saveQuestion( $( this ).parent().siblings( 'main' ).children( '#edit_question_id' ).val() );
		});
		$( '#new-answer-button' ).on( 'click', function( event ) {
			event.preventDefault();
			var answer = [ '', '', 0 ];
			iCODEQuestion.addNewAnswer( answer );
		});

		$( '.iCODE-popup-bank' ).on( 'click', '.import-button', function( event) {
			event.preventDefault();
			iCODEQuestion.addQuestionFromQuestionBank( $( this ).parents( '.question-bank-question' ).data( 'question-id' ) );
		});

		$( '.save-page-button' ).on( 'click', function( event ) {
			event.preventDefault();
			iCODEQuestion.savePages();
		});

		// Adds event handlers for searching questions
		$( '#question_search' ).on( 'keyup', function() {
			$( '.question' ).each(function() {
				if ( $(this).text().toLowerCase().indexOf( $( '#question_search' ).val().toLowerCase()) === -1 ) {
					$(this).hide();
				} else {
					$(this).show();
				}
			});
			$( '.page' ).each(function() {
				if ( 0 === $(this).children( '.question:visible' ).length ) {
					$(this).hide();
				} else {
					$(this).show();
				}
			});
			if ( 0 === $( '#question_search' ).val().length ) {
				$( '.page' ).show();
				$( '.question' ).show();
			}
		});

		$( '.questions' ).sortable({
			opacity: 70,
			cursor: 'move',
			placeholder: "ui-state-highlight"
		});
		$( '.page' ).sortable({
			items: '.question',
			opacity: 70,
			cursor: 'move',
			placeholder: "ui-state-highlight",
			connectWith: '.page'
		});
		iCODEQuestion.prepareEditor();
		iCODEQuestion.loadQuestions();
	});
}(jQuery));
