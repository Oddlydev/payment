<?php
/**
 * Plugin Name: WebXPay Redirect Integration
 * Description: Redirect checkout for WEBXPAY (staging/live). Registers [custom_checkout] for the Sandbox theme.
 * Version: 1.0.0
 * Author: Payment site
 * License: GPL v2 or later
 * Text Domain: webxpay-redirect
 */

if (!defined('ABSPATH')) {
	exit;
}

define('WEBXPAY_REDIRECT_VERSION', '1.0.0');
define('WEBXPAY_REDIRECT_OPTION', 'webxpay_redirect_settings');

define('WEBXPAY_BILLING_LIVE', 'https://webxpay.com/index.php?route=checkout/billing');
define('WEBXPAY_BILLING_STAGING', 'https://stagingxpay.info/index.php?route=checkout/billing');

/**
 * Default public key from WEBXPAY PHP response sample (verify signatures).
 * Replace with your key from Dashboard > Settings > Integration if WEBXPAY instructs otherwise.
 */
function webxpay_redirect_default_public_key_pem() {
	return "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9l2HykxDIDVZeyDPJU4pA0imf
3nWsvyJgb3zTsnN8B0mFX6u5squ5NQcnQ03L8uQ56b4/isHBgiyKwfMr4cpEpCTY
/t1WSdJ5EokCI/F7hCM7aSSSY85S7IYOiC6pKR4WbaOYMvAMKn5gCobEPtosmPLz
gh8Lo3b8UsjPq2W26QIDAQAB
-----END PUBLIC KEY-----";
}

function webxpay_redirect_get_settings() {
	$defaults = array(
		'mode'              => 'staging',
		'secret_key'        => '',
		'public_key_pem'    => '',
		'default_currency'  => 'LKR',
		'default_amount'    => '1.00',
		'success_path'      => '/payment-success/',
		'failure_path'      => '/payment-failed/',
	);
	$saved = get_option(WEBXPAY_REDIRECT_OPTION, array());
	return wp_parse_args(is_array($saved) ? $saved : array(), $defaults);
}

function webxpay_redirect_billing_url($mode) {
	return ($mode === 'live') ? WEBXPAY_BILLING_LIVE : WEBXPAY_BILLING_STAGING;
}

/**
 * PEM for encrypting the payment parameter (Dashboard public key).
 */
function webxpay_redirect_request_public_key_pem() {
	$opts = webxpay_redirect_get_settings();
	$pem = isset($opts['public_key_pem']) ? trim($opts['public_key_pem']) : '';
	if ($pem === '') {
		return webxpay_redirect_default_public_key_pem();
	}
	return $pem;
}

/**
 * PEM for verifying response signature (usually same as integration public key).
 */
function webxpay_redirect_response_public_key_pem() {
	return webxpay_redirect_request_public_key_pem();
}

/**
 * Build encrypted payment per WEBXPAY guide: unique_order_id|total_amount -> openssl_public_encrypt -> base64_encode.
 *
 * @param string $order_id Unique order id.
 * @param string $amount   Decimal amount as string.
 * @return string|WP_Error Base64 ciphertext or error.
 */
function webxpay_redirect_encrypt_payment($order_id, $amount) {
	$plaintext = $order_id . '|' . $amount;
	$pub_pem   = webxpay_redirect_request_public_key_pem();
	$key = openssl_pkey_get_public($pub_pem);
	if (!$key) {
		return new WP_Error('webxpay_bad_key', __('Invalid public key PEM. Paste your key from WEBXPAY Dashboard > Settings > Integration.', 'webxpay-redirect'));
	}
	$encrypted = '';
	$ok        = openssl_public_encrypt($plaintext, $encrypted, $key, OPENSSL_PKCS1_PADDING);
	if (!$ok || $encrypted === '') {
		return new WP_Error('webxpay_encrypt', __('Could not encrypt payment payload. Check public key and OpenSSL.', 'webxpay-redirect'));
	}
	return base64_encode($encrypted);
}

/**
 * Sanitize contact per guide: digits and +, length 9–20.
 */
function webxpay_redirect_sanitize_contact($raw) {
	$s = preg_replace('/[^0-9+]/', '', (string) $raw);
	if (strlen($s) < 9) {
		$s = str_pad($s, 9, '0', STR_PAD_RIGHT);
	}
	if (strlen($s) > 20) {
		$s = substr($s, 0, 20);
	}
	return $s;
}

/**
 * Return URL for merchant dashboard (GET triggers handler; POST from gateway).
 */
function webxpay_redirect_callback_url() {
	return add_query_arg('webxpay_return', '1', home_url('/'));
}

/**
 * Handle POST back from WEBXPAY (redirect.html §2.6, php-response.txt).
 */
function webxpay_redirect_handle_return() {
	if (!isset($_GET['webxpay_return']) || (string) $_GET['webxpay_return'] !== '1') {
		return;
	}
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['payment']) || empty($_POST['signature'])) {
		return;
	}

	$payment_b64   = wp_unslash($_POST['payment']);
	$signature_b64 = wp_unslash($_POST['signature']);

	$payment   = base64_decode($payment_b64, true);
	$signature = base64_decode($signature_b64, true);
	if ($payment === false || $signature === false) {
		webxpay_redirect_redirect_outcome(false, '');
		exit;
	}

	$publickey = webxpay_redirect_response_public_key_pem();
	$pkey = openssl_pkey_get_public($publickey);
	if (!$pkey) {
		webxpay_redirect_redirect_outcome(false, '');
		exit;
	}

	$value = '';
	// Match WEBXPAY php-response.txt (padding default).
	openssl_public_decrypt($signature, $value, $pkey);

	$signature_status = ($value !== '' && hash_equals($payment, $value));

	// Format after decode: order_id|reference|datetime|status_code|comment|gateway (see guide example).
	$parts = array_map('trim', explode('|', $payment));
	$order_ref = isset($parts[0]) ? $parts[0] : '';
	$status    = isset($parts[3]) ? $parts[3] : '';

	$approved = $signature_status && in_array($status, array('0', '00'), true);

	webxpay_redirect_redirect_outcome($approved, $order_ref);
	exit;
}
add_action('init', 'webxpay_redirect_handle_return', 1);

/**
 * @param bool   $success
 * @param string $order_ref Order id segment from gateway response (pipe-delimited payment field).
 */
function webxpay_redirect_redirect_outcome($success, $order_ref) {
	$opts = webxpay_redirect_get_settings();
	$path = $success ? $opts['success_path'] : $opts['failure_path'];
	$url  = home_url($path);
	if ($order_ref !== '') {
		$url = add_query_arg('order_id', $order_ref, $url);
	}
	wp_safe_redirect($url);
	exit;
}

/**
 * Shortcode: checkout form -> POST to WEBXPAY billing.
 */
function webxpay_redirect_shortcode_checkout() {
	$opts = webxpay_redirect_get_settings();
	$out   = '';

	if (empty($opts['secret_key'])) {
		return '<p class="webxpay-notice">' . esc_html__('WebXPay is not configured. Add your secret key under Settings → WebXPay Redirect.', 'webxpay-redirect') . '</p>';
	}

	if (isset($_POST['webxpay_checkout_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['webxpay_checkout_nonce'])), 'webxpay_checkout')) {
		$first = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
		$last  = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
		$email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
		$phone = isset($_POST['contact_number']) ? webxpay_redirect_sanitize_contact(wp_unslash($_POST['contact_number'])) : '';
		$addr1 = isset($_POST['address_line_one']) ? sanitize_text_field(wp_unslash($_POST['address_line_one'])) : '';
		$addr2 = isset($_POST['address_line_two']) ? sanitize_text_field(wp_unslash($_POST['address_line_two'])) : '';
		$city  = isset($_POST['city']) ? sanitize_text_field(wp_unslash($_POST['city'])) : '';
		$state = isset($_POST['state']) ? sanitize_text_field(wp_unslash($_POST['state'])) : '';
		$zip   = isset($_POST['postal_code']) ? sanitize_text_field(wp_unslash($_POST['postal_code'])) : '';
		$country = isset($_POST['country']) ? sanitize_text_field(wp_unslash($_POST['country'])) : '';
		$currency = isset($_POST['process_currency']) ? strtoupper(sanitize_text_field(wp_unslash($_POST['process_currency']))) : $opts['default_currency'];
		$amount   = isset($_POST['amount']) ? sanitize_text_field(wp_unslash($_POST['amount'])) : $opts['default_amount'];
		$gateway_id = isset($_POST['payment_gateway_id']) ? sanitize_text_field(wp_unslash($_POST['payment_gateway_id'])) : '';

		if ($first === '' || $last === '' || !is_email($email) || $phone === '' || $addr1 === '') {
			$out .= '<p class="webxpay-error">' . esc_html__('Please fill all required fields.', 'webxpay-redirect') . '</p>';
		} else {
			$order_id = 'WP' . gmdate('YmdHis') . wp_rand(100, 999);
			$enc      = webxpay_redirect_encrypt_payment($order_id, $amount);
			if (is_wp_error($enc)) {
				$out .= '<p class="webxpay-error">' . esc_html($enc->get_error_message()) . '</p>';
			} else {
				$custom_raw = 'wordpress|' . $order_id;
				$fields     = array(
					'first_name'       => $first,
					'last_name'        => $last,
					'email'            => $email,
					'contact_number'   => $phone,
					'address_line_one' => $addr1,
					'secret_key'       => $opts['secret_key'],
					'payment'          => $enc,
					'cms'              => 'WordPress',
					'process_currency' => in_array($currency, array('LKR', 'USD', 'GBP', 'AUD'), true) ? $currency : 'LKR',
				);
				if ($addr2 !== '') {
					$fields['address_line_two'] = $addr2;
				}
				if ($city !== '') {
					$fields['city'] = $city;
				}
				if ($state !== '') {
					$fields['state'] = $state;
				}
				if ($zip !== '') {
					$fields['postal_code'] = $zip;
				}
				if ($country !== '') {
					$fields['country'] = $country;
				}
				$fields['custom_fields'] = base64_encode($custom_raw);
				if ($gateway_id !== '') {
					$fields['payment_gateway_id'] = $gateway_id;
				}

				$action = webxpay_redirect_billing_url($opts['mode']);
				$out   .= webxpay_redirect_auto_post_form($action, $fields);
				return $out;
			}
		}
	}

	ob_start();
	$def_currency = esc_attr($opts['default_currency']);
	$def_amount   = esc_attr($opts['default_amount']);
	?>
	<form class="webxpay-checkout-form" method="post" action="">
		<?php wp_nonce_field('webxpay_checkout', 'webxpay_checkout_nonce'); ?>
		<p>
			<label><?php esc_html_e('First name', 'webxpay-redirect'); ?> *</label><br />
			<input type="text" name="first_name" required maxlength="30" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Last name', 'webxpay-redirect'); ?> *</label><br />
			<input type="text" name="last_name" required maxlength="30" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Email', 'webxpay-redirect'); ?> *</label><br />
			<input type="email" name="email" required class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Contact number', 'webxpay-redirect'); ?> *</label><br />
			<input type="text" name="contact_number" required minlength="9" maxlength="20" class="widefat" placeholder="+94771234567" />
		</p>
		<p>
			<label><?php esc_html_e('Address line 1', 'webxpay-redirect'); ?> *</label><br />
			<input type="text" name="address_line_one" required class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Address line 2', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="address_line_two" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('City', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="city" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('State', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="state" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Postal code', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="postal_code" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Country', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="country" class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Amount', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="amount" value="<?php echo $def_amount; ?>" required class="widefat" />
		</p>
		<p>
			<label><?php esc_html_e('Currency', 'webxpay-redirect'); ?></label><br />
			<select name="process_currency">
				<option value="LKR" <?php selected($def_currency, 'LKR'); ?>>LKR</option>
				<option value="USD" <?php selected($def_currency, 'USD'); ?>>USD</option>
				<option value="GBP" <?php selected($def_currency, 'GBP'); ?>>GBP</option>
				<option value="AUD" <?php selected($def_currency, 'AUD'); ?>>AUD</option>
			</select>
		</p>
		<p>
			<label><?php esc_html_e('Payment gateway ID (optional)', 'webxpay-redirect'); ?></label><br />
			<input type="text" name="payment_gateway_id" class="widefat" placeholder="<?php esc_attr_e('e.g. 42 — skips gateway picker when set', 'webxpay-redirect'); ?>" />
		</p>
		<p><button type="submit" class="button button-primary"><?php esc_html_e('Continue to WEBXPAY', 'webxpay-redirect'); ?></button></p>
	</form>
	<?php
	return $out . ob_get_clean();
}
add_shortcode('custom_checkout', 'webxpay_redirect_shortcode_checkout');

/**
 * @param string               $action Gateway URL.
 * @param array<string,string> $fields POST body fields.
 */
function webxpay_redirect_auto_post_form($action, $fields) {
	ob_start();
	?>
	<p><?php esc_html_e('Redirecting you to WEBXPAY…', 'webxpay-redirect'); ?></p>
	<form id="webxpay-billing-form" method="post" action="<?php echo esc_url($action); ?>">
		<?php foreach ($fields as $name => $value) : ?>
			<input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" />
		<?php endforeach; ?>
	</form>
	<script>document.getElementById("webxpay-billing-form").submit();</script>
	<noscript><button type="submit" form="webxpay-billing-form"><?php esc_html_e('Continue', 'webxpay-redirect'); ?></button></noscript>
	<?php
	return ob_get_clean();
}

function webxpay_redirect_admin_menu() {
	add_options_page(
		__('WebXPay Redirect', 'webxpay-redirect'),
		__('WebXPay Redirect', 'webxpay-redirect'),
		'manage_options',
		'webxpay-redirect',
		'webxpay_redirect_render_settings'
	);
}
add_action('admin_menu', 'webxpay_redirect_admin_menu');

function webxpay_redirect_register_setting() {
	register_setting(
		'webxpay_redirect',
		WEBXPAY_REDIRECT_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'webxpay_redirect_sanitize_settings',
		)
	);
}
add_action('admin_init', 'webxpay_redirect_register_setting');

function webxpay_redirect_sanitize_settings($input) {
	$prev  = webxpay_redirect_get_settings();
	$clean = $prev;
	if (!is_array($input)) {
		return $clean;
	}
	if (isset($input['mode']) && in_array($input['mode'], array('staging', 'live'), true)) {
		$clean['mode'] = $input['mode'];
	}
	if (isset($input['secret_key'])) {
		$clean['secret_key'] = sanitize_text_field($input['secret_key']);
	}
	if (isset($input['public_key_pem'])) {
		$clean['public_key_pem'] = sanitize_textarea_field($input['public_key_pem']);
	}
	if (isset($input['default_currency']) && in_array(strtoupper($input['default_currency']), array('LKR', 'USD', 'GBP', 'AUD'), true)) {
		$clean['default_currency'] = strtoupper($input['default_currency']);
	}
	if (isset($input['default_amount'])) {
		$clean['default_amount'] = sanitize_text_field($input['default_amount']);
	}
	foreach (array('success_path', 'failure_path') as $key) {
		if (isset($input[ $key ])) {
			$path = trim(sanitize_text_field($input[ $key ]));
			if ($path !== '' && $path[0] === '/') {
				$clean[ $key ] = $path;
			}
		}
	}
	return $clean;
}

function webxpay_redirect_render_settings() {
	if (!current_user_can('manage_options')) {
		return;
	}
	$opts = webxpay_redirect_get_settings();
	$cb   = webxpay_redirect_callback_url();
	?>
	<div class="wrap">
		<h1><?php esc_html_e('WebXPay Redirect', 'webxpay-redirect'); ?></h1>
		<p><?php esc_html_e('Configure credentials from your WEBXPAY merchant dashboard (Settings → Integration). Use staging until you go live.', 'webxpay-redirect'); ?></p>
		<p><strong><?php esc_html_e('Return URL to register at WEBXPAY', 'webxpay-redirect'); ?></strong><br />
			<code style="user-select:all"><?php echo esc_html($cb); ?></code></p>
		<p class="description"><?php esc_html_e('Dashboard: Settings → Website Integration → Add Return URL (per WEBXPAY redirect guide). The gateway POSTs payment, signature, and custom_fields to this URL.', 'webxpay-redirect'); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields('webxpay_redirect'); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e('Environment', 'webxpay-redirect'); ?></th>
					<td>
						<select name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[mode]">
							<option value="staging" <?php selected($opts['mode'], 'staging'); ?>><?php esc_html_e('Staging (stagingxpay.info)', 'webxpay-redirect'); ?></option>
							<option value="live" <?php selected($opts['mode'], 'live'); ?>><?php esc_html_e('Live (webxpay.com)', 'webxpay-redirect'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="webxpay_secret"><?php esc_html_e('Secret key', 'webxpay-redirect'); ?></label></th>
					<td>
						<input type="password" class="large-text" id="webxpay_secret" name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[secret_key]" value="<?php echo esc_attr($opts['secret_key']); ?>" autocomplete="off" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="webxpay_pub"><?php esc_html_e('Public key (PEM)', 'webxpay-redirect'); ?></label></th>
					<td>
						<textarea class="large-text code" rows="8" id="webxpay_pub" name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[public_key_pem]" placeholder="<?php esc_attr_e('Paste full PEM from Dashboard → Generate keys', 'webxpay-redirect'); ?>"><?php echo esc_textarea($opts['public_key_pem']); ?></textarea>
						<p class="description"><?php esc_html_e('Used to encrypt the payment parameter on checkout and to verify the response signature. Leave empty to use the sample key from WEBXPAY developer docs (may only work for limited testing).', 'webxpay-redirect'); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Default checkout amount', 'webxpay-redirect'); ?></th>
					<td><input type="text" name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[default_amount]" value="<?php echo esc_attr($opts['default_amount']); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Default currency', 'webxpay-redirect'); ?></th>
					<td>
						<select name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[default_currency]">
							<?php foreach (array('LKR', 'USD', 'GBP', 'AUD') as $c) : ?>
								<option value="<?php echo esc_attr($c); ?>" <?php selected($opts['default_currency'], $c); ?>><?php echo esc_html($c); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Success page path', 'webxpay-redirect'); ?></th>
					<td><input type="text" class="large-text" name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[success_path]" value="<?php echo esc_attr($opts['success_path']); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e('Failure page path', 'webxpay-redirect'); ?></th>
					<td><input type="text" class="large-text" name="<?php echo esc_attr(WEBXPAY_REDIRECT_OPTION); ?>[failure_path]" value="<?php echo esc_attr($opts['failure_path']); ?>" /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
