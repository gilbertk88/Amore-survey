
var iCODEAdmin;
(function ($) {

  iCODEAdmin = {
    selectTab: function( tab ) {
      $( '.iCODE-tab' ).removeClass( 'nav-tab-active' );
      $( '.iCODE-tab-content' ).hide();
      tab.addClass( 'nav-tab-active' );
      tabID = tab.data( 'tab' );
      $( '.tab-' + tabID ).show();
    }
  };
  $(function() {
    $( '.iCODE-tab' ).on( 'click', function( event ) {
      event.preventDefault();
      iCODEAdmin.selectTab( $( this ) );
    });
  });
}(jQuery));

jQuery("#icode_check_all").change( function() {
	jQuery('.icode_delete_checkbox').prop( 'checked', jQuery('#icode_check_all').prop('checked') );
});
