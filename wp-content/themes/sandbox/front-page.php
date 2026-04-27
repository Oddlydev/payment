<?php
/**
 * Front page marketing template.
 *
 * @package Sandbox
 */

get_header();

$checkout_url = home_url('/checkout/');
?>

<main id="primary" class="site-main marketing-home">
	<section class="marketing-hero" aria-labelledby="marketing-title">
		<div class="marketing-hero__content">
			<p class="marketing-eyebrow"><?php esc_html_e('Professional training made simple', 'sandbox'); ?></p>
			<h1 id="marketing-title"><?php esc_html_e('Build skills faster with focused online training.', 'sandbox'); ?></h1>
			<p class="marketing-intro">
				<?php esc_html_e('A clean learning experience for teams and individuals who want practical lessons, clear progress, and direct access to paid course enrollment.', 'sandbox'); ?>
			</p>
			<div class="marketing-actions">
				<a class="button button-primary" href="<?php echo esc_url($checkout_url); ?>">
					<?php esc_html_e('Enroll now', 'sandbox'); ?>
				</a>
				<a class="button button-secondary" href="#features">
					<?php esc_html_e('View features', 'sandbox'); ?>
				</a>
			</div>
		</div>

		<div class="marketing-hero__panel" aria-label="<?php esc_attr_e('Course highlights', 'sandbox'); ?>">
			<div>
				<span><?php esc_html_e('8 weeks', 'sandbox'); ?></span>
				<p><?php esc_html_e('Structured learning path', 'sandbox'); ?></p>
			</div>
			<div>
				<span><?php esc_html_e('24/7', 'sandbox'); ?></span>
				<p><?php esc_html_e('Access to course materials', 'sandbox'); ?></p>
			</div>
			<div>
				<span><?php esc_html_e('Certificate', 'sandbox'); ?></span>
				<p><?php esc_html_e('Issued after completion', 'sandbox'); ?></p>
			</div>
		</div>
	</section>

	<section id="features" class="marketing-section">
		<div class="section-heading">
			<p class="marketing-eyebrow"><?php esc_html_e('What you get', 'sandbox'); ?></p>
			<h2><?php esc_html_e('Everything needed for a focused training website.', 'sandbox'); ?></h2>
		</div>

		<div class="feature-grid">
			<article class="feature-card">
				<h3><?php esc_html_e('Course-ready pages', 'sandbox'); ?></h3>
				<p><?php esc_html_e('Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow.', 'sandbox'); ?></p>
			</article>
			<article class="feature-card">
				<h3><?php esc_html_e('Clear payment action', 'sandbox'); ?></h3>
				<p><?php esc_html_e('The main call to action routes visitors through a redirect handler prepared for your real gateway URL.', 'sandbox'); ?></p>
			</article>
			<article class="feature-card">
				<h3><?php esc_html_e('Lightweight theme', 'sandbox'); ?></h3>
				<p><?php esc_html_e('No plugin dependencies or build tools are required for this testing theme to render.', 'sandbox'); ?></p>
			</article>
		</div>
	</section>

	<section class="pricing-band" aria-labelledby="pricing-title">
		<div>
			<p class="marketing-eyebrow"><?php esc_html_e('Starter enrollment', 'sandbox'); ?></p>
			<h2 id="pricing-title"><?php esc_html_e('Start with one complete course package.', 'sandbox'); ?></h2>
			<p><?php esc_html_e('Use this area for your price, payment terms, and short guarantee message.', 'sandbox'); ?></p>
		</div>
		<a class="button button-primary" href="<?php echo esc_url($checkout_url); ?>">
			<?php esc_html_e('Continue to payment', 'sandbox'); ?>
		</a>
	</section>
</main>

<?php
get_footer();
