<?php
/**
 * Single content template part.
 *
 * @package Sandbox
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
		<div class="entry-meta">
			<?php echo esc_html(get_the_date()); ?>
		</div>
	</header>

	<?php if (has_post_thumbnail()) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail('large'); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'sandbox'),
				'after' => '</div>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php the_tags('', ', '); ?>
	</footer>
</article>
