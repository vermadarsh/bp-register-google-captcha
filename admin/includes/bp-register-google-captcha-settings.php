<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit, if accessed directly

global $bp_register_google_captcha;
$general_settings = array();
if( ! empty( $bp_register_google_captcha->plugin_admin_settings['general_settings'] ) ) {
	$general_settings = $bp_register_google_captcha->plugin_admin_settings['general_settings'];
}
?>
<form class="bprgc-general-settings" action="" method="POST">

	<h4><?php echo sprintf( __( 'If you don\'t already have your Google reCaptcha API private and public keys, %1$s.', BPRGC_TEXT_DOMAIN ), '<a href="https://www.google.com/recaptcha/admin" target="_blank">click here to get them</a>' ); ?></h4>

	<table class="form-table">
		<tbody>
			<!-- RE-CAPTCHA SITE KEY -->
			<tr>
				<th scope="row"><label for="bprgc-recaptcha-site-key"><?php _e( 'reCaptcha Site Key', BPRGC_TEXT_DOMAIN ); ?></label></th>
				<td>
					<input type="text" name="bprgc-recaptcha-site-key" class="regular-text" placeholder="<?php _e( 'Site Key', BPRGC_TEXT_DOMAIN );?>" value="<?php echo ( ! empty( $general_settings['recaptcha_site_key'] ) ) ? $general_settings['recaptcha_site_key'] : '';?>" required>
					<p class="description"><?php _e( 'reCaptcha Site Key.', BPRGC_TEXT_DOMAIN ); ?></p>
				</td>
			</tr>

			<!-- RE-CAPTCHA SECRET KEY -->
			<tr>
				<th scope="row"><label for="bprgc-recaptcha-secret-key"><?php _e( 'reCaptcha Secret Key', BPRGC_TEXT_DOMAIN ); ?></label></th>
				<td>
					<input type="text" name="bprgc-recaptcha-secret-key" class="regular-text" placeholder="<?php _e( 'Secret Key', BPRGC_TEXT_DOMAIN );?>" value="<?php echo ( ! empty( $general_settings['recaptcha_secret_key'] ) ) ? $general_settings['recaptcha_secret_key'] : '';?>" required>
					<p class="description"><?php _e( 'reCaptcha Secret Key.', BPRGC_TEXT_DOMAIN ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<?php wp_nonce_field( 'bprgc-bp-register-google-captcha', 'bprgc-bp-register-google-captcha-nonce' );?>
		<input type="submit" name="bprgc-save-general-settings" class="button button-primary" value="<?php _e( 'Save Changes', BPRGC_TEXT_DOMAIN ); ?>">
	</p>
</form>