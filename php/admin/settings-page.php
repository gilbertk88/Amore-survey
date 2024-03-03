<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generates The Settings Page For The Plugin
 *
 * @since 4.1.0
 */
class icodeGlobalSettingsPage {

	/**
	  * Main Construct Function
	  *
	  * Call functions within class
	  *
	  * @since 4.1.0
	  * @uses icodeGlobalSettingsPage::load_dependencies() Loads required filed
	  * @uses icodeGlobalSettingsPage::add_hooks() Adds actions to hooks and filters
	  * @return void
	  */
	function __construct() {
		$this->add_hooks();
	}

	/**
	  * Add Hooks
	  *
	  * Adds functions to relavent hooks and filters
	  *
	  * @since 4.1.0
	  * @return void
	  */
	private function add_hooks() {
		add_action( "admin_init", array( $this, 'init' ) );
	}

	/**
	 * Prepares Settings Fields And Sections
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function init() {
		register_setting( 'icode-settings-group', 'icode-settings' );
    	add_settings_section( 'icode-global-section', __( 'Main Settings', 'icode-survey-master' ), array( $this, 'global_section' ), 'icode_global_settings' );
		add_settings_field( 'ip-collection', __( 'Disable collecting and storing IP addresses?', 'icode-survey-master' ), array( $this, 'ip_collection_field' ), 'icode_global_settings', 'icode-global-section' );
		add_settings_field( 'cpt-search', __( 'Disable survey Posts From Being Searched?', 'icode-survey-master' ), array( $this, 'cpt_search_field' ), 'icode_global_settings', 'icode-global-section' );
		add_settings_field( 'cpt-archive', __( 'Disable survey Archive?', 'icode-survey-master' ), array( $this, 'cpt_archive_field' ), 'icode_global_settings', 'icode-global-section' );
		add_settings_field( 'cpt-slug', __( 'survey Url Slug', 'icode-survey-master' ), array( $this, 'cpt_slug_field' ), 'icode_global_settings', 'icode-global-section' );
		add_settings_field( 'plural-name', __( 'Post Type Plural Name (Shown in various places such as on archive pages)', 'icode-survey-master' ), array( $this, 'plural_name_field' ), 'icode_global_settings', 'icode-global-section' );
		add_settings_field( 'mailchimp-app-id', __( 'Mail Chimp App Id', 'icode-survey-master' ), array( $this, 'mailchimp_app_id' ), 'icode_global_settings', 'icode-global-section' );
		add_settings_field( 'results-details', __( 'Template For Admin Results Details', 'icode-survey-master' ), array( $this, 'results_details_template' ), 'icode_global_settings', 'icode-global-section' );
	}

	/**
	 * Generates Section Text
	 *
	 * Generates the section text. If page has been saved, flush rewrite rules for updated post type slug
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function global_section() {
		_e( 'These settings are applied to the entire plugin and all surveyzes.', 'icode-survey-master' );
		if ( isset( $_GET["settings-updated"] ) && $_GET["settings-updated"] ) {
			flush_rewrite_rules( true );
			echo "<span style='color:red;'>" . __( 'Settings have been updated!', 'icode-survey-master' ) . "</span>";
		}
	}

	/**
	 * Generates Setting Field For App Id
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function mailchimp_app_id() {
		$settings = (array) get_option( 'icode-settings' );
		$mailchimp_app_id = '483815031724529';
		if (isset($settings['mailchimp_app_id']))
		{
			$mailchimp_app_id = esc_attr( $settings['mailchimp_app_id'] );
		}
		echo "<input type='text' name='icode-settings[mailchimp_app_id]' id='icode-settings[mailchimp_app_id]' value='$mailchimp_app_id' />";
	}

	/**
	 * Generates Setting Field For Post Slug
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function cpt_slug_field() {
		$settings = (array) get_option( 'icode-settings' );
		$cpt_slug = 'survey';
		if ( isset( $settings['cpt_slug'] ) ) {
			$cpt_slug = esc_attr( $settings['cpt_slug'] );
		}
		echo "<input type='text' name='icode-settings[cpt_slug]' id='icode-settings[cpt_slug]' value='$cpt_slug' />";
	}

	/**
	 * Generates Setting Field For Plural name
	 *
	 * @since 5.3.0
	 * @return void
	 */
	public function plural_name_field() {
		$settings = (array) get_option( 'icode-settings' );
		$plural_name = __( 'iCode Surveys', 'icode-survey-master' );
		if ( isset( $settings['plural_name'] ) ) {
			$plural_name = esc_attr( $settings['plural_name'] );
		}
		echo "<input type='text' name='icode-settings[plural_name]' id='icode-settings[plural_name]' value='$plural_name' />";
	}

	/**
	 * Generates Setting Field For Exclude Search
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function cpt_search_field()
	{
		$settings = (array) get_option( 'icode-settings' );
		$cpt_search = '0';
		if (isset($settings['cpt_search']))
		{
			$cpt_search = esc_attr( $settings['cpt_search'] );
		}
		$checked = '';
		if ($cpt_search == '1')
		{
			$checked = " checked='checked'";
		}
		echo "<input type='checkbox' name='icode-settings[cpt_search]' id='icode-settings[cpt_search]' value='1'$checked />";
	}

	/**
	 * Generates Setting Field For Post Archive
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function cpt_archive_field()
	{
		$settings = (array) get_option( 'icode-settings' );
		$cpt_archive = '0';
		if (isset($settings['cpt_archive']))
		{
			$cpt_archive = esc_attr( $settings['cpt_archive'] );
		}
		$checked = '';
		if ($cpt_archive == '1')
		{
			$checked = " checked='checked'";
		}
		echo "<input type='checkbox' name='icode-settings[cpt_archive]' id='icode-settings[cpt_archive]' value='1'$checked />";
	}

	/**
	 * Generates Setting Field For Results Details Template
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function results_details_template()
	{
		$settings = (array) get_option( 'icode-settings' );
		if (isset($settings['results_details_template']))
		{
			$template = htmlspecialchars_decode($settings['results_details_template'], ENT_QUOTES);
		}
		else
		{
			$template = "<h2>survey Results for %survey_NAME%</h2>
			<p>%CONTACT_ALL%</p>
			<p>Name Provided: %USER_NAME%</p>
			<p>Business Provided: %USER_BUSINESS%</p>
			<p>Phone Provided: %USER_PHONE%</p>
			<p>Email Provided: %USER_EMAIL%</p>
			<p>Score Received: %AMOUNT_CORRECT%/%TOTAL_QUESTIONS% or %CORRECT_SCORE%% or %POINT_SCORE% points</p>
			<h2>Answers Provided:</h2>
			<p>The user took %TIMER% to complete survey.</p>
			<p>Comments entered were: %COMMENT_SECTION%</p>
			<p>The answers were as follows:</p>
			%QUESTIONS_ANSWERS%";
		}
		wp_editor( $template, 'results_template', array('textarea_name' => 'icode-settings[results_details_template]') );
	}

	/**
	 * Generates Setting Field For IP Collection
	 *
	 * @since 5.3.0
	 * @return void
	 */
	public function ip_collection_field() {
		$settings = (array) get_option( 'icode-settings' );
		$ip_collection = '0';
		if ( isset( $settings['ip_collection'] ) ) {
			$ip_collection = esc_attr( $settings['ip_collection'] );
		}
		$checked = '';
		if ( '1' == $ip_collection ) {
			$checked = " checked='checked'";
		}
		echo "<input type='checkbox' name='icode-settings[ip_collection]' id='icode-settings[ip_collection]' value='1'$checked />";
	}

	/**
	 * Generates Settings Page
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public static function display_page() {
		?>
		<div class="wrap">
        <h2><?php _e( 'Global Settings', 'icode-survey-master' ); ?></h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'icode-settings-group' ); ?>
            <?php do_settings_sections( 'icode_global_settings' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
		<?php
	}
}

$icodeGlobalSettingsPage = new icodeGlobalSettingsPage();
?>
