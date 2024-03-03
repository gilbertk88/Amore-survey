/**
 * iCODE - surveyzes/Surveys Page
 */

var iCODEsurveyzesSurveys;
(function ($) {
  iCODEsurveyzesSurveys = {
    load: function() {
      if ( 0 !== iCODEsurveyObject.length ) {
        $.each( iCODEsurveyObject, function( i, val ) {
          iCODEsurveyzesSurveys.addsurveyRow( val );
        });
        $( '#the-list tr' ).filter( ':even' ).addClass( 'alternate' );
      } else {
        var template = wp.template( 'no-survey' );
        $( '.iCODE-surveyzes-page-content' ).hide();
        $( '#new_survey_button' ).parent().after( template() );
      }
    },
    addsurveyRow: function( surveyData ) {
      var template = wp.template( 'survey-row' );
      var values = {
        'id': surveyData.id,
        'name': surveyData.name,
        'link': surveyData.link,
        'postID': surveyData.postID,
        'views': surveyData.views,
        'taken': surveyData.taken,
        'lastActivity': surveyData.lastActivity
      };
      var row = $( template( values ) );
      $( '#the-list' ).append( row );
    },
    searchsurveyzes: function( query ) {
      $( ".iCODE-survey-row" ).each(function() {
        if ( -1 === $( this ).find( '.iCODE-survey-name' ).text().toLowerCase().indexOf( query.toLowerCase() ) ) {
          $( this ).hide();
        } else {
          $( this ).show();
        }
      });
    },
    deletesurvey: function( survey_id ) {
      $( '#delete_survey_id' ).val( survey_id );
      $.each( iCODEsurveyObject, function( i, val ) {
        if ( val.id == survey_id ) {
          $( '#delete_survey_name' ).val( val.name );
        }
      });
      MicroModal.show( 'modal-5' );
    },
    editsurveyName: function( survey_id ) {
      $( '#edit_survey_id' ).val( survey_id );
      $.each( iCODEsurveyObject, function( i, val ) {
        if ( val.id == survey_id ) {
          $( '#edit_survey_name' ).val( val.name );
        }
      });
      MicroModal.show( 'modal-3' );
    },
    duplicatesurvey: function( survey_id ) {
      $( '#duplicate_survey_id' ).val( survey_id );
      MicroModal.show( 'modal-4' );
    },
    /**
     * Opens the popup to reset survey stats
     *
     * @param int The ID of the survey
     */
    openResetPopup: function( survey_id ) {
      survey_id = parseInt( survey_id );
      $( '#reset_survey_id' ).val( survey_id );
      MicroModal.show( 'modal-1' );
    },
  };
  $(function() {
    $( '#new_survey_button, #new_survey_button_two' ).on( 'click', function( event ) {
      event.preventDefault();
      MicroModal.show( 'modal-2' );
    });
    $( '#survey_search' ).keyup( function() {
      iCODEsurveyzesSurveys.searchsurveyzes( $( this ).val() );
    });
    $( '#the-list' ).on( 'click', '.iCODE-action-link-delete', function( event ) {
      event.preventDefault();
      iCODEsurveyzesSurveys.deletesurvey( $( this ).parents( '.iCODE-survey-row' ).data( 'id' ) );
    });
    $( '#the-list' ).on( 'click', '.iCODE-action-link-duplicate', function( event ) {
      event.preventDefault();
      iCODEsurveyzesSurveys.duplicatesurvey( $( this ).parents( '.iCODE-survey-row' ).data( 'id' ) );
    });
    $( '#the-list' ).on( 'click', '.iCODE-edit-name', function( event ) {
      event.preventDefault();
      iCODEsurveyzesSurveys.editsurveyName( $( this ).parents( '.iCODE-survey-row' ).data( 'id' ) );
    });
    $( '#the-list' ).on( 'click', '.iCODE-action-link-reset', function( event ) {
      event.preventDefault();
      iCODEsurveyzesSurveys.openResetPopup( $( this ).parents( '.iCODE-survey-row' ).data( 'id' ) );
    });
    $( '#reset-stats-button' ).on( 'click', function( event ) {
      event.preventDefault();
      $( '#reset_survey_form' ).submit();
    });
    $( '#create-survey-button' ).on( 'click', function( event ) {
      event.preventDefault();
      $( '#new-survey-form' ).submit();
    });
    $( '#edit-name-button' ).on( 'click', function( event ) {
      event.preventDefault();
      $( '#edit-name-form' ).submit();
    });
    $( '#duplicate-survey-button' ).on( 'click', function( event ) {
      event.preventDefault();
      $( '#duplicate-survey-form' ).submit();
    });
    $( '#delete-survey-button' ).on( 'click', function( event ) {
      event.preventDefault();
      $( '#delete-survey-form' ).submit();
    });
    iCODEsurveyzesSurveys.load();
  });
}(jQuery));
