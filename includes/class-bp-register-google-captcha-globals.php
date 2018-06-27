<?php

/**
 * The file that defines the global variable of the plugin
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Flag_Members
 * @subpackage Bp_Flag_Members/includes
 * @author     Wbcom Designs <adarsh.verma@multidots.com>
 */
class Bp_Register_Google_Captcha_Globals {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin Settings
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin_admin_settings
	 */
	public $plugin_admin_settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $plugin_version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $plugin_version;
		$this->setup_plugin_global();
	}

	/**
	 * Include the following files that make up the plugin:
	 *
	 * - Bp_Flag_Members_Globals.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function setup_plugin_global() {
		global $bp_register_google_captcha;
		$plugin_admin_settings = get_option( 'bp_register_google_captcha_settings' );

		$this->plugin_admin_settings = array();
		if( isset( $plugin_admin_settings ) && ! empty( $plugin_admin_settings ) ) {
			$this->plugin_admin_settings = $plugin_admin_settings;
		}

	}
}