<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/vermadarsh
 * @since      1.0.0
 *
 * @package    Bp_Register_Google_Captcha
 * @subpackage Bp_Register_Google_Captcha/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bp_Register_Google_Captcha
 * @subpackage Bp_Register_Google_Captcha/public
 * @author     Adarsh Verma <adarsh.verma@multidots.com>
 */
class Bp_Register_Google_Captcha_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function bprgc_enqueue_styles() {

		if( stripos( $_SERVER['REQUEST_URI'], 'register' ) !== false ) {
			wp_enqueue_style( $this->plugin_name, BPRGC_PLUGIN_URL . 'public/css/bp-register-google-captcha-public.css' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function bprgc_enqueue_scripts() {

		if( stripos( $_SERVER['REQUEST_URI'], 'register' ) !== false ) {
			global $bp_register_google_captcha;
			$general_settings = array();
			if( ! empty( $bp_register_google_captcha->plugin_admin_settings['general_settings'] ) ) {
				$general_settings = $bp_register_google_captcha->plugin_admin_settings['general_settings'];
			}
			$site_key = ( ! empty( $general_settings['recaptcha_site_key'] ) ) ? $general_settings['recaptcha_site_key'] : '';
			$secret_key = ( ! empty( $general_settings['recaptcha_secret_key'] ) ) ? $general_settings['recaptcha_secret_key'] : '';

			wp_enqueue_script( $this->plugin_name . '-google-recaptcha', 'https://www.google.com/recaptcha/api.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name, BPRGC_PLUGIN_URL . 'public/js/bp-register-google-captcha-public.js', array( 'jquery' ) );

			wp_localize_script(
				$this->plugin_name,
				'BPRGC_Public_JS_Obj',
				array(
					'ajaxurl'				=>	admin_url( 'admin-ajax.php' ),
					'site_key'				=>	$site_key,
					'secret_key'			=>	$secret_key,
				)
			);
		}

	}

	/**
	 * Add Google reCaptcha before submit button on register page
	 *
	 * @since    1.0.0
	 */
	public function bprgc_add_google_recaptcha_before_submit() {

		global $bp_register_google_captcha, $bp;
		$general_settings = array();
		if( ! empty( $bp_register_google_captcha->plugin_admin_settings['general_settings'] ) ) {
			$general_settings = $bp_register_google_captcha->plugin_admin_settings['general_settings'];
		}
		$site_key = ( ! empty( $general_settings['recaptcha_site_key'] ) ) ? $general_settings['recaptcha_site_key'] : '';?>
		<div class="register-section" id="security-section" style="clear:both; float:right; margin-top:30px;">
			<div class="editfield">
				<?php if ( ! empty( $bp->signup->errors['recaptcha_response_field'] ) ) : ?>
					<div class="error"><?php echo $bp->signup->errors['recaptcha_response_field']; ?></div>
				<?php endif; ?>
 
				<div class="g-recaptcha" data-sitekey="<?php echo $site_key;?>"></div>
			</div>
		</div>
		<?php

	}

	/**
	 * Validate the google reCaptcha on form submission.
	 *
	 * @since    1.0.0
	 */
	public function bprgc_validate_google_recaptcha() {

		global $bp_register_google_captcha, $bp;
		$general_settings = array();
		if( ! empty( $bp_register_google_captcha->plugin_admin_settings['general_settings'] ) ) {
			$general_settings = $bp_register_google_captcha->plugin_admin_settings['general_settings'];
		}
		$secret_key = ( ! empty( $general_settings['recaptcha_secret_key'] ) ) ? $general_settings['recaptcha_secret_key'] : '';

		$query = array(
			'secret'	=>	$secret_key,
			'response'	=>	$_POST['g-recaptcha-response'],
			'remoteip'	=>	$_SERVER['REMOTE_ADDR']
		);

		$url 			=	'https://www.google.com/recaptcha/api/siteverify';
		$request 		=	new WP_Http;
		$result 		=	$request->request( $url, array( 'method' => 'POST', 'body' => $query ) );
		$response 		=	$result['response'];
		$body 			=	json_decode( $result['body'] );

		if ($response['message'] != 'OK' || $body->success != true) {
			foreach ($body->{'error-codes'} as $error_code) {
				if ($error_code == 'missing-input-response') {
					$error_string = 'You must prove you are human. ';
				} else {
					$error_string = 'There was an error (' . $error_code . ') in reCaptcha. ';
				}	
			}
			$bp->signup->errors['recaptcha_response_field'] = $error_string;
		}

	}

}
