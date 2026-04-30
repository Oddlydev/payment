<?php
/**
 * Page template.
 *
 * @package Sandbox
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php while (have_posts()) : ?>
		<?php the_post(); ?>
		<?php the_content(); ?>

		<?php
		if (comments_open() || get_comments_number()) {
			comments_template();
		}
		?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
