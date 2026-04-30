<?php
/**
 * Template Name: Home Page
 *
 * Full-bleed marketing page — no title, no container padding, no entry box.
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main sandbox-fullbleed-page">
	<?php while (have_posts()) : ?>
		<?php the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
