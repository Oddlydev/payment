<?php
/**
 * Theme footer.
 *
 * @package Sandbox
 */
?>
<footer class="site-footer">
	<div class="site-footer__inner">
		<?php if (is_active_sidebar('footer-1')) : ?>
			<?php dynamic_sidebar('footer-1'); ?>
		<?php endif; ?>

		<p>
			<?php
			printf(
				esc_html__('Copyright %1$s %2$s.', 'sandbox'),
				esc_html(date_i18n('Y')),
				esc_html(get_bloginfo('name'))
			);
			?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
