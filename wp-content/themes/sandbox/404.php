<?php
/**
 * 404 template.
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="entry">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e('Page not found', 'sandbox'); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php esc_html_e('The page you are looking for does not exist or has moved.', 'sandbox'); ?></p>
			<?php get_search_form(); ?>
		</div>
	</section>
</main>

<?php
get_footer();
