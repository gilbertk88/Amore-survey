<?php
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
$table_name = $wpdb->prefix . "mlw_results";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

$table_name = $wpdb->prefix . "mlw_surveyzes";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

$table_name = $wpdb->prefix . "mlw_questions";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

$table_name = $wpdb->prefix . "mlw_qm_audit_trail";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

// Taken from Easy Digital Downloads. Much better way of doing it than I was doing :)
// Cycle through custom post type array, retreive all posts, delete each one
$iCODE_post_types = array( 'survey', 'icode_log' );
foreach ( $iCODE_post_types as $post_type ) {
	$items = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );
	if ( $items ) {
		foreach ( $items as $item ) {
			wp_delete_post( $item, true);
		}
	}
}


delete_option( 'mlw_survey_version' );
delete_option( 'mlw_icode_review_notice' );
delete_option( 'mlw_advert_shows' );
delete_option( 'icode-settings' );
delete_option( 'icode-tracking-notice' );
?>
