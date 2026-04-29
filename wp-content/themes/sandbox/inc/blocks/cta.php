<?php
/**
 * CTA Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_cta_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/cta.js';
	$style_path = get_template_directory() . '/assets/css/blocks/cta.css';

	wp_register_script(
		'sandbox-cta-block',
		get_template_directory_uri() . '/assets/js/blocks/cta.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-cta-block-style',
		get_template_directory_uri() . '/assets/css/blocks/cta.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/cta',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-cta-block',
			'style' => 'sandbox-cta-block-style',
			'editor_style' => 'sandbox-cta-block-style',
			'render_callback' => 'sandbox_render_cta_block',
			'attributes' => array(
				'eyebrow' => array('type' => 'string', 'default' => __('Ready to launch?', 'sandbox')),
				'title' => array('type' => 'string', 'default' => __('Build your marketing website with Sandbox today.', 'sandbox')),
				'text' => array('type' => 'string', 'default' => __('Start with this template, then replace each section using custom Gutenberg blocks tailored to your brand.', 'sandbox')),
				'buttonText' => array('type' => 'string', 'default' => __('Go to checkout', 'sandbox')),
				'buttonUrl' => array('type' => 'string', 'default' => home_url('/checkout/')),
			),
		)
	);
}
add_action('init', 'sandbox_register_cta_block');

function sandbox_render_cta_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$text = isset($attributes['text']) ? wp_kses_post($attributes['text']) : '';
	$button_text = isset($attributes['buttonText']) ? sanitize_text_field($attributes['buttonText']) : '';
	$button_url = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';

	$wrapper_attributes = get_block_wrapper_attributes(array('class' => 'sandbox-cta-block home-cta'));

	ob_start();
	?>
	<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div>
			<?php if ($eyebrow) : ?>
				<p class="marketing-eyebrow"><?php echo $eyebrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<?php if ($title) : ?>
				<h2><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php endif; ?>
			<?php if ($text) : ?>
				<p><?php echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div>
		<?php if ($button_text && $button_url) : ?>
			<a class="button button-primary" href="<?php echo esc_url($button_url); ?>">
				<?php echo esc_html($button_text); ?>
			</a>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}
