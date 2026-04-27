<?php
/**
 * Payment success page template.
 *
 * @package Sandbox
 */

get_header();

$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
?>

<main id="primary" class="site-main status-page">
	<section class="status-card status-card--success">
		<p class="marketing-eyebrow"><?php esc_html_e('Payment status', 'sandbox'); ?></p>
		<h1><?php esc_html_e('Payment received.', 'sandbox'); ?></h1>
		<p><?php esc_html_e('Thank you. Your order has been sent for confirmation and will be marked paid once the gateway response is verified.', 'sandbox'); ?></p>

		<?php if ($order_id) : ?>
			<p class="status-reference">
				<?php
				printf(
					esc_html__('Order reference: #%d', 'sandbox'),
					$order_id
				);
				?>
			</p>
		<?php endif; ?>

		<a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'sandbox'); ?></a>
	</section>
</main>

<?php
get_footer();
