<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This function shows the about page. It also shows the changelog information.
 *
 * @return void
 * @since 4.4.0
 */
function mlw_generate_about_page() {

	global $mlwiCodesurveyMaster;
	$version = $mlwiCodesurveyMaster->version;
	wp_enqueue_style( 'iCODE_admin_style', plugins_url( '../../css/iCODE-admin.css' , __FILE__ ), array(),$version);
	wp_enqueue_script( 'iCODE_admin_js', plugins_url( '../../js/admin.js' , __FILE__ ), array( 'jquery' ),$version);
	?>
	<div class="wrap about-wrap">
		<h1><?php _e( 'Welcome To iCode Survey Master', 'icode-survey-master' ); ?></h1>
		<div class="about-text"><?php _e( 'Thank you for updating!', 'icode-survey-master' ); ?></div>
		<h2 class="nav-tab-wrapper">
			<a href="#" data-tab='1' class="nav-tab nav-tab-active iCODE-tab">
				<?php _e( "What's New!", 'icode-survey-master' ); ?></a>
		</h2>
		<div class="iCODE-tab-content tab-1">
			<div class="feature">
				<h2 class="feature-headline">Welcome to iCODE!</h2>
			</div>
			<div class="feature">
				<h2 class="feature-headline">New PHP Minimum</h2>
				<p class="feature-text">As planned, all versions of iCode Survey Master starting with this version will require a minimum of PHP 5.4.</p>
			</div>
		</div>
	</div>
<?php
}
?>
