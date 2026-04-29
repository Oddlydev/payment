<?php
/**
 * Payment failed page template.
 *
 * @package Sandbox
 */

get_header();

$order_ref = isset($_GET['order_id']) ? sanitize_text_field(wp_unslash($_GET['order_id'])) : '';
?>

<main id="primary" class="site-main status-page">
	<section class="status-card status-card--failed">
		<p class="marketing-eyebrow"><?php esc_html_e('Payment status', 'sandbox'); ?></p>
		<h1><?php esc_html_e('Payment was not completed.', 'sandbox'); ?></h1>
		<p><?php esc_html_e('The payment failed, was cancelled, or could not be verified. You can return to checkout and try again.', 'sandbox'); ?></p>

		<?php if ($order_ref !== '') : ?>
			<p class="status-reference">
				<?php
				printf(
					esc_html__('Order reference: #%s', 'sandbox'),
					esc_html($order_ref)
				);
				?>
			</p>
		<?php endif; ?>

		<a class="button button-primary" href="<?php echo esc_url(home_url('/checkout/')); ?>"><?php esc_html_e('Try again', 'sandbox'); ?></a>
	</section>
</main>

<?php
get_footer();
