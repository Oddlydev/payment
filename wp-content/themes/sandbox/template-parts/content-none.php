<?php
/**
 * Empty content template part.
 *
 * @package Sandbox
 */
?>

<section class="entry no-results">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Nothing found', 'sandbox'); ?></h1>
	</header>

	<div class="entry-content">
		<?php if (is_search()) : ?>
			<p><?php esc_html_e('No results matched your search. Try different keywords.', 'sandbox'); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e('There is no content to show yet.', 'sandbox'); ?></p>
		<?php endif; ?>
	</div>
</section>
