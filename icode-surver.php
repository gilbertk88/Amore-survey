<?php
/**
 * Plugin Name: iCode Survey Master
 * Description: Easily add surveys to your website.
 * Version: 1.0.1
 * Author: iCode Network
 * Author URI: http://icodedesigns.net
 * Plugin URI: http://icodedesigns.net
 * Text Domain: icode-survey-master
 *
 * @author iCode Network
 * @version 1.0.1
 * @package iCODE
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'iCODE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
/**
 * This class is the main class of the plugin
 *
 * When loaded, it loads the included plugin files and add functions to hooks or filters. The class also handles the admin menu
 *
 * @since 3.6.1
 */
class MLWiCodesurveyMaster {

	/**
	 * iCODE Version Number
	 *
	 * @var string
	 * @since 4.0.0
	 */
	public $version = '1.0.1';

	/**
	 * iCODE Alert Manager Object
	 *
	 * @var object
	 * @since 3.7.1
	 */
	public $alertManager;

	/**
	 * iCODE Plugin Helper Object
	 *
	 * @var object
	 * @since 4.0.0
	 */
	public $pluginHelper;

	/**
	 * iCODE survey Creator Object
	 *
	 * @var object
	 * @since 3.7.1
	 */
	public $surveyCreator;

	/**
	 * iCODE Log Manager Object
	 *
	 * @var object
	 * @since 4.5.0
	 */
	public $log_manager;

	/**
	 * iCODE Audit Manager Object
	 *
	 * @var object
	 * @since 4.7.1
	 */
	public $audit_manager;

	/**
	 * iCODE Settings Object
	 *
	 * @var object
	 * @since 5.0.0
	 */
	public $survey_settings;

	/**
	 * Main Construct Function
	 *
	 * Call functions within class
	 *
	 * @since 3.6.1
	 * @uses MLWiCodesurveyMaster::load_dependencies() Loads required filed
	 * @uses MLWiCodesurveyMaster::add_hooks() Adds actions to hooks and filters
	 * @return void
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->add_hooks();
	}

	/**
	 * Load File Dependencies
	 *
	 * @since 3.6.1
	 * @return void
	 */
	private function load_dependencies() {

		include 'php/classes/class-ism-install.php';
		include 'php/classes/class-ism-fields.php';

		include 'php/classes/class-icode-log-manager.php';
		$this->log_manager = new icode_Log_Manager();

		include 'php/classes/class-ism-audit.php';
		$this->audit_manager = new iCODE_Audit();

		if ( is_admin() ) {
			include 'php/admin/stats-page.php';
			include 'php/admin/surveyzes-page.php';
			include 'php/admin/survey-options-page.php';
			include 'php/admin/admin-results-page.php';
			include 'php/admin/admin-results-details-page.php';
			include 'php/classes/class-ism-changelog-generator.php';
			include 'php/admin/about-page.php';
			include 'php/admin/dashboard-widgets.php';
			include 'php/admin/options-page-questions-tab.php';
			include 'php/admin/options-page-contact-tab.php';
			include 'php/admin/options-page-text-tab.php';
			include 'php/admin/options-page-option-tab.php';
			include 'php/admin/options-page-email-tab.php';
			include 'php/admin/options-page-results-page-tab.php';
			include 'php/admin/options-page-style-tab.php';
			include 'php/admin/options-page-preview-tab.php';
			include 'php/admin/settings-page.php';
			include 'php/classes/class-ism-review-message.php';
			include 'php/gdpr.php';
		}
		include 'php/classes/class-ism-questions.php';
		include 'php/classes/class-ism-contact-manager.php';
		include 'php/classes/class-ism-survey-manager.php';

		include 'php/template-variables.php';
		include 'php/question-types.php';
		include 'php/default-templates.php';
		include 'php/shortcodes.php';

		include 'php/classes/class-ism-alert-manager.php';
		$this->alertManager = new MlwicodeAlertManager();

		include 'php/classes/class-ism-survey-creator.php';
		$this->surveyCreator = new icodesurveyCreator();

		include 'php/classes/class-ism-plugin-helper.php';
		$this->pluginHelper = new icodePluginHelper();

		include 'php/classes/class-ism-settings.php';
		$this->survey_settings = new iCODE_survey_Settings();

		include 'php/rest-api.php';
	}

	/**
	 * Add Hooks
	 *
	 * Adds functions to relavent hooks and filters
	 *
	 * @since 3.6.1
	 * @return void
	 */
	private function add_hooks() {
		add_action( 'admin_menu', array( $this, 'setup_admin_menu' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 900 );
		add_action( 'init', array( $this, 'register_survey_post_types' ) );
	}

	/**
	 * Creates Custom survey Post Type
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function register_survey_post_types() {

		// Checks settings to see if we need to alter the defaults.
		$has_archive    = true;
		$exclude_search = false;
		$cpt_slug       = 'survey';
		$settings       = (array) get_option( 'icode-settings' );
		$plural_name    = __( 'iCode Surveys', 'icode-survey-master' );

		// Checks if admin turned off archive.
		if ( isset( $settings['cpt_archive'] ) && '1' == $settings['cpt_archive'] ) {
			$has_archive = false;
		}

		// Checks if admin turned off search.
		if ( isset( $settings['cpt_search'] ) && '1' == $settings['cpt_search'] ) {
			$exclude_search = true;
		}

		// Checks if admin changed slug.
		if ( isset( $settings['cpt_slug'] ) ) {
			$cpt_slug = trim( strtolower( str_replace( ' ', '-', $settings['cpt_slug'] ) ) );
		}

		// Checks if admin changed plural name.
		if ( isset( $settings['plural_name'] ) ) {
			$plural_name = trim( $settings['plural_name'] );
		}

		// Prepares labels.
		$survey_labels = array(
			'name'               => $plural_name,
			'singular_name'      => __( 'survey', 'icode-survey-master' ),
			'menu_name'          => __( 'survey', 'icode-survey-master' ),
			'name_admin_bar'     => __( 'survey', 'icode-survey-master' ),
			'add_new'            => __( 'Add New', 'icode-survey-master' ),
			'add_new_item'       => __( 'Add New survey', 'icode-survey-master' ),
			'new_item'           => __( 'New survey', 'icode-survey-master' ),
			'edit_item'          => __( 'Edit survey', 'icode-survey-master' ),
			'view_item'          => __( 'View survey', 'icode-survey-master' ),
			'all_items'          => __( 'All surveyzes', 'icode-survey-master' ),
			'search_items'       => __( 'Search surveyzes', 'icode-survey-master' ),
			'parent_item_colon'  => __( 'Parent survey:', 'icode-survey-master' ),
			'not_found'          => __( 'No survey Found', 'icode-survey-master' ),
			'not_found_in_trash' => __( 'No survey Found In Trash', 'icode-survey-master' ),
		);

		// Prepares post type array.
		$survey_args = array(
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => true,
			'labels'              => $survey_labels,
			'publicly_queryable'  => true,
			'exclude_from_search' => $exclude_search,
			'label'               => $plural_name,
			'rewrite'             => array( 'slug' => $cpt_slug ),
			'has_archive'         => $has_archive,
			'supports'            => array( 'title', 'author', 'comments' )
		);

		// Registers post type.
		register_post_type( 'survey', $survey_args );
	}

	/**
	 * Setup Admin Menu
	 *
	 * Creates the admin menu and pages for the plugin and attaches functions to them
	 *
	 * @since 3.6.1
	 * @return void
	 */
	public function setup_admin_menu() {
		if ( function_exists( 'add_menu_page' ) ) {
			add_menu_page( 'iCode Survey Master', __( 'iCode Survey Master', 'icode-survey-master' ), 'moderate_comments', __FILE__, 'iCODE_generate_surveyzes_surveys_page', 'dashicons-feedback' );
			add_submenu_page( __FILE__, __( 'Settings', 'icode-survey-master' ), __( 'Settings', 'icode-survey-master' ), 'moderate_comments', 'mlw_survey_options', 'iCODE_generate_survey_options' );
			add_submenu_page( __FILE__, __( 'Results', 'icode-survey-master' ), __( 'Results', 'icode-survey-master' ), 'moderate_comments', 'mlw_survey_results', 'iCODE_generate_admin_results_page' );
			add_submenu_page( __FILE__, __( 'Result Details', 'icode-survey-master' ), __( 'Result Details', 'icode-survey-master' ), 'moderate_comments', 'iCODE_survey_result_details', 'iCODE_generate_result_details' );
			add_submenu_page( __FILE__, __( 'Settings', 'icode-survey-master' ), __( 'Settings', 'icode-survey-master' ), 'manage_options', 'icode_global_settings', array( 'icodeGlobalSettingsPage', 'display_page' ) );
			add_dashboard_page(
				__( 'iCode About', 'icode-survey-master' ),
				__( 'iCode About', 'icode-survey-master' ),
				'manage_options',
				'iCode_about',
				'mlw_generate_about_page'
			);
		}
	}

	/**
	 * Removes Unnecessary Admin Page
	 *
	 * Removes the update, survey settings, and survey results pages from the survey Menu
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'iCode_about' );
		remove_submenu_page( 'icode-survey-master/icode-surver.php', 'mlw_survey_options' );
		remove_submenu_page( 'icode-survey-master/icode-surver.php', 'iCODE_survey_result_details' );
	}
}

global $mlwiCodesurveyMaster;
$mlwiCodesurveyMaster = new MLWiCodesurveyMaster();
register_activation_hook( __FILE__, array( 'iCODE_Install', 'install' ) );
?>