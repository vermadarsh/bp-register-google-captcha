<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/vermadarsh
 * @since             1.0.0
 * @package           Bp_Register_Google_Captcha
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyPress Register Google Captcha
 * Plugin URI:        https://github.com/vermadarsh
 * Description:       This plugin adds Google Captcha on BuddyPress Registration page.
 * Version:           1.0.0
 * Author:            Adarsh Verma
 * Author URI:        https://github.com/vermadarsh
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-register-google-captcha
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BPRGC_PLUGIN_VERSION', '1.0.0' );

if ( ! defined( 'BPRGC_PLUGIN_PATH' ) ) {
	define( 'BPRGC_PLUGIN_PATH', plugin_dir_path(__FILE__) );
}

if ( ! defined( 'BPRGC_TEXT_DOMAIN' ) ) {
	define( 'BPRGC_TEXT_DOMAIN', 'bp-register-google-captcha' );
}

if ( ! defined( 'BPRGC_PLUGIN_URL' ) ) {
	define( 'BPRGC_PLUGIN_URL', plugin_dir_url(__FILE__) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bp-register-google-captcha-activator.php
 */
function activate_bp_register_google_captcha() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-register-google-captcha-activator.php';
	Bp_Register_Google_Captcha_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bp-register-google-captcha-deactivator.php
 */
function deactivate_bp_register_google_captcha() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-register-google-captcha-deactivator.php';
	Bp_Register_Google_Captcha_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bp_register_google_captcha' );
register_deactivation_hook( __FILE__, 'deactivate_bp_register_google_captcha' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_register_google_captcha() {

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-bp-register-google-captcha.php';
	$plugin = new Bp_Register_Google_Captcha();
	$plugin->run();

}

/**
 * Check plugin requirement on plugins loaded, this plugin requires BuddyPress to be installed and active.
 */
add_action('plugins_loaded', 'bprgc_plugin_init');
function bprgc_plugin_init() {
	$bp_active = in_array( 'buddypress/bp-loader.php', get_option( 'active_plugins' ) );
	if ( current_user_can('activate_plugins') && $bp_active !== true ) {
		add_action('admin_notices', 'bprgc_plugin_admin_notice');
	} else {
		run_bp_register_google_captcha();
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bprgc_plugin_links' );
	}
}

/**
 * Show admin notice in case of BuddyPress plugin is missing.
 */
function bprgc_plugin_admin_notice() {
	$bprgc_plugin = 'BuddyPress Register Google Captcha';
	$bp_plugin = 'BuddyPress';

	echo '<div class="error"><p>'
	. sprintf(__('%1$s is ineffective as it requires %2$s to be installed and active.', BPRGC_TEXT_DOMAIN), '<strong>' . esc_html( $bprgc_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>')
	. '</p></div>';
	if ( isset($_GET['activate'] ) ) unset( $_GET['activate'] );
}

/**
 * Settings link on plugin listing page
 */
function bprgc_plugin_links( $links ) {
	$bprgc_links = array(
		'<a href="'.admin_url('options-general.php?page=bp-register-google-captcha').'">'.__( 'Settings', BPRGC_TEXT_DOMAIN ).'</a>'
	);
	return array_merge( $links, $bprgc_links );
}

if( !  function_exists( 'debug' ) ) {
	function debug( $params ) {
		echo '<pre>';
		print_r( $params );
		echo '</pre>';
	}
}