<?php
/**
 * Template Name: Home Page
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main marketing-page-home">
	<section class="home-hero" aria-labelledby="home-hero-title">
		<div class="home-hero__content">
			<p class="marketing-eyebrow"><?php esc_html_e('Modern learning platform', 'sandbox'); ?></p>
			<h1 id="home-hero-title"><?php esc_html_e('Launch and scale your training business in days.', 'sandbox'); ?>
			</h1>
			<p class="home-hero__lead">
				<?php esc_html_e('Create engaging learning experiences with clean course pages, simple checkout flows, and analytics-ready user journeys that help your team grow faster.', 'sandbox'); ?>
			</p>
			<div class="marketing-actions">
				<a class="button button-primary" href="<?php echo esc_url(home_url('/checkout/')); ?>">
					<?php esc_html_e('Start now', 'sandbox'); ?>
				</a>
				<a class="button button-secondary" href="#home-features">
					<?php esc_html_e('Explore features', 'sandbox'); ?>
				</a>
			</div>
		</div>
		<div class="home-hero__stats" aria-label="<?php esc_attr_e('Business highlights', 'sandbox'); ?>">
			<div>
				<span><?php esc_html_e('35%', 'sandbox'); ?></span>
				<p><?php esc_html_e('Average conversion lift', 'sandbox'); ?></p>
			</div>
			<div>
				<span><?php esc_html_e('2x', 'sandbox'); ?></span>
				<p><?php esc_html_e('Faster launch time', 'sandbox'); ?></p>
			</div>
			<div>
				<span><?php esc_html_e('24/7', 'sandbox'); ?></span>
				<p><?php esc_html_e('Instant course access', 'sandbox'); ?></p>
			</div>
		</div>
	</section>

	<section id="home-features" class="home-section">
		<div class="section-heading">
			<p class="marketing-eyebrow"><?php esc_html_e('Platform features', 'sandbox'); ?></p>
			<h2><?php esc_html_e('Everything needed to run a strong marketing website.', 'sandbox'); ?></h2>
		</div>
		<div class="home-feature-grid">
			<article class="feature-card">
				<h3><?php esc_html_e('Conversion-focused hero blocks', 'sandbox'); ?></h3>
				<p><?php esc_html_e('Use editable hero sections with call-to-action controls built directly in Gutenberg.', 'sandbox'); ?>
				</p>
			</article>
			<article class="feature-card">
				<h3><?php esc_html_e('Flexible page building', 'sandbox'); ?></h3>
				<p><?php esc_html_e('Combine reusable sections and rich content blocks to design custom campaign pages quickly.', 'sandbox'); ?>
				</p>
			</article>
			<article class="feature-card">
				<h3><?php esc_html_e('Checkout-ready flow', 'sandbox'); ?></h3>
				<p><?php esc_html_e('Guide visitors from product discovery to payment with clear actions and minimal friction.', 'sandbox'); ?>
				</p>
			</article>
		</div>
	</section>

	<section class="home-section">
		<div class="section-heading">
			<p class="marketing-eyebrow"><?php esc_html_e('Image layout', 'sandbox'); ?></p>
			<h2><?php esc_html_e('Show your product experience visually.', 'sandbox'); ?></h2>
		</div>
		<div class="home-image-layout">
			<figure class="home-image-layout__item home-image-layout__item--large">
				<img src="https://picsum.photos/900/620?random=11"
					alt="<?php esc_attr_e('Students watching an online session', 'sandbox'); ?>">
				<figcaption><?php esc_html_e('Live cohort sessions and guided lessons.', 'sandbox'); ?></figcaption>
			</figure>
			<figure class="home-image-layout__item">
				<img src="https://picsum.photos/620/420?random=12"
					alt="<?php esc_attr_e('Instructor dashboard', 'sandbox'); ?>">
				<figcaption><?php esc_html_e('Simple instructor dashboard to manage modules.', 'sandbox'); ?>
				</figcaption>
			</figure>
			<figure class="home-image-layout__item">
				<img src="https://picsum.photos/620/420?random=13"
					alt="<?php esc_attr_e('Mobile learning screen', 'sandbox'); ?>">
				<figcaption><?php esc_html_e('Learner-friendly experience across devices.', 'sandbox'); ?></figcaption>
			</figure>
		</div>
	</section>

	<section class="home-cta" aria-labelledby="home-cta-title">
		<div>
			<p class="marketing-eyebrow"><?php esc_html_e('Ready to launch?', 'sandbox'); ?></p>
			<h2 id="home-cta-title"><?php esc_html_e('Build your marketing website with Sandbox today.', 'sandbox'); ?>
			</h2>
			<p><?php esc_html_e('Start with this template, then replace each section using custom Gutenberg blocks tailored to your brand.', 'sandbox'); ?>
			</p>
		</div>
		<a class="button button-primary" href="<?php echo esc_url(home_url('/checkout/')); ?>">
			<?php esc_html_e('Go to checkout', 'sandbox'); ?>
		</a>
	</section>
</main>

<?php
get_footer();
