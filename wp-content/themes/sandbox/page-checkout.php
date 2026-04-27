<?php
/**
 * Checkout page template.
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main checkout-page">
	<section class="checkout-layout">
		<div class="checkout-copy">
			<p class="marketing-eyebrow"><?php esc_html_e('Secure checkout', 'sandbox'); ?></p>
			<h1><?php esc_html_e('Complete your enrollment.', 'sandbox'); ?></h1>
			<p><?php esc_html_e('Enter your details and continue to WEBXPAY to finish the payment securely.', 'sandbox'); ?></p>
		</div>

		<div class="checkout-card">
			<?php
			if (shortcode_exists('custom_checkout')) {
				echo do_shortcode('[custom_checkout]');
			} else {
				echo '<p>' . esc_html__('The WEBXPAY checkout plugin is not active.', 'sandbox') . '</p>';
			}
			?>
		</div>
	</section>
</main>

<?php
get_footer();
