<?php
/**
 * Search results template.
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main">
	<header class="page-header">
		<h1 class="page-title">
			<?php
			printf(
				esc_html__('Search results for: %s', 'sandbox'),
				'<span>' . esc_html(get_search_query()) . '</span>'
			);
			?>
		</h1>
	</header>

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<?php get_template_part('template-parts/content', 'search'); ?>
		<?php endwhile; ?>

		<?php the_posts_navigation(); ?>
	<?php else : ?>
		<?php get_template_part('template-parts/content', 'none'); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
