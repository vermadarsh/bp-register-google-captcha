<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Register_Google_Captcha
 * @subpackage Bp_Register_Google_Captcha/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Register_Google_Captcha
 * @subpackage Bp_Register_Google_Captcha/admin
 * @author     Adarsh Verma <adarsh.verma@multidots.com>
 */
class Bp_Register_Google_Captcha_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		/**
		 * Save the admin settings for the plugin
		 */
		if ( isset( $_POST[ 'bprgc-save-general-settings' ] ) && wp_verify_nonce( $_POST[ 'bprgc-bp-register-google-captcha-nonce' ], 'bprgc-bp-register-google-captcha' ) ) {
			$this->bprgc_save_general_settings();
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function bprgc_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, BPRGC_PLUGIN_URL . 'admin/css/bp-register-google-captcha-admin.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function bprgc_enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, BPRGC_PLUGIN_URL . 'admin/js/bp-register-google-captcha-admin.js', array( 'jquery' ) );

	}

	/**
	 * Register a menu page to handle plugin settings - under Settings
	 *
	 * @since    1.0.0
	 */
	public function bprgc_settings_page() {
		add_options_page( __( 'BuddyPress Register Google Captcha Settings', BPRGC_TEXT_DOMAIN ), __( 'BP Register Google Captcha', BPRGC_TEXT_DOMAIN ), 'manage_options', $this->plugin_name, array( $this, 'bprgc_admin_settings_page' ) );
	}

	/**
	 * Function called to create settings page under Users page
	 *
	 * @since    1.0.0
	 */
	public function bprgc_admin_settings_page() {
		$tab = isset($_GET['tab']) ? $_GET['tab'] : $this->plugin_name;
		?>
		<div class="wrap">
			<h2><?php _e( 'BuddyPress Register Google Captcha', BPRGC_TEXT_DOMAIN );?></h2>
			<?php $this->bprgc_plugin_settings_tabs();
			do_settings_sections( $tab );?>
		</div>
		<?php
	}

	/**
	 * Function called to create settings tabs
	 *
	 * @since    1.0.0
	 */
	public function bprgc_plugin_settings_tabs() {
		$current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->plugin_name;
		echo '<h2 class="nav-tab-wrapper">';
		foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" id="' . $tab_key . '-tab" href="?page=' . $this->plugin_name . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * Function called to create settings page content
	 *
	 * @since    1.0.0
	 */
	public function bprgc_plugin_settings_content() {
		
		//General Settings Tab
		$this->plugin_settings_tabs[ $this->plugin_name ] = __( 'General', BPRGC_TEXT_DOMAIN );
		register_setting( $this->plugin_name, $this->plugin_name );
		add_settings_section( $this->plugin_name . '-section', ' ', array(&$this, 'bprgc_general_settings'), $this->plugin_name );

	}

	/**
	 * Function called to manage admin settings
	 *
	 * @since    1.0.0
	 */
	public function bprgc_general_settings() {

		$file = BPRGC_PLUGIN_PATH . 'admin/includes/bp-register-google-captcha-settings.php';
		if ( file_exists( $file ) ) {
			require_once( $file );
		}

	}

	/**
	 * This function will save the admin settings for the plugin.
	 *
	 * @author     Adarsh Verma <adarsh.verma@multidots.com>
	 * @since      1.0.0
	 */
	public function bprgc_save_general_settings() {

		$settings['general_settings'] = array(
			'recaptcha_site_key'	=>	sanitize_text_field( $_POST['bprgc-recaptcha-site-key'] ),
			'recaptcha_secret_key'	=>	sanitize_text_field( $_POST['bprgc-recaptcha-secret-key'] )
		);
		
		update_option( 'bp_register_google_captcha_settings', $settings );
		$success_msg = "<div class='notice updated' id='message'>";
		$success_msg .= "<p>" . __( 'Settings Updated.', BPRGC_TEXT_DOMAIN ) . "</p>";
		$success_msg .= "</div>";
		echo $success_msg;

	}

}
