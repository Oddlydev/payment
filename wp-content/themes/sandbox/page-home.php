<?php
/**
 * Template Name: Home Page
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main marketing-page-home">
	<?php while (have_posts()) : ?>
		<?php the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
