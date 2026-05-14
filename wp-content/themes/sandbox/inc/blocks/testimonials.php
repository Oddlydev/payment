<?php
/**
 * Testimonials Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_testimonials_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/testimonials.js';
	$style_path = get_template_directory() . '/assets/css/blocks/testimonials.css';

	wp_register_script(
		'sandbox-testimonials-block',
		get_template_directory_uri() . '/assets/js/blocks/testimonials.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-testimonials-block-style',
		get_template_directory_uri() . '/assets/css/blocks/testimonials.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/testimonials',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-testimonials-block',
			'style' => 'sandbox-testimonials-block-style',
			'editor_style' => 'sandbox-testimonials-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
			),
			'render_callback' => 'sandbox_render_testimonials_block',
			'attributes' => array(
				'backgroundColor' => array('type' => 'string', 'default' => ''),
				'textColor' => array('type' => 'string', 'default' => ''),
				'eyebrow' => array('type' => 'string', 'default' => 'Testimonials'),
				'title' => array('type' => 'string', 'default' => 'What customers are saying'),
				'testimonialCount' => array('type' => 'number', 'default' => 3),
				'layout' => array('type' => 'string', 'default' => 'grid'),
				'testimonials' => array('type' => 'array', 'default' => array()),
			),
		)
	);
}
add_action('init', 'sandbox_register_testimonials_block');

function sandbox_testimonials_default_items()
{
	return array(
		array('quote' => 'The page sections were easy to edit and gave us a polished launch page quickly.', 'name' => 'Amaya Perera', 'role' => 'Program Lead'),
		array('quote' => 'We could change the message, layout, and calls to action without touching code.', 'name' => 'Nuwan Silva', 'role' => 'Marketing Manager'),
		array('quote' => 'The blocks made every page feel consistent while still giving each section flexibility.', 'name' => 'Kavindi Fernando', 'role' => 'Course Creator'),
	);
}

function sandbox_render_testimonials_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$count = isset($attributes['testimonialCount']) ? (int) $attributes['testimonialCount'] : 3;
	$count = min(max($count, 1), 3);
	$layout = isset($attributes['layout']) ? sanitize_html_class($attributes['layout']) : 'grid';
	$testimonials = isset($attributes['testimonials']) && is_array($attributes['testimonials']) ? $attributes['testimonials'] : array();
	$defaults = sandbox_testimonials_default_items();

	$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '';
	$text_color = isset($attributes['textColor']) ? esc_attr($attributes['textColor']) : '';
	$inline_style = '';
	if ($background_color) { $inline_style .= 'background-color:' . $background_color . ';'; }
	if ($text_color) { $inline_style .= 'color:' . $text_color . ';'; }

	$wrapper_args = array('class' => 'sandbox-testimonials-block home-section');
	if ($inline_style) { $wrapper_args['style'] = $inline_style; }
	$wrapper_attributes = get_block_wrapper_attributes($wrapper_args);

	ob_start();
	?>
	<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="section-heading">
			<?php if ($eyebrow) : ?>
				<p class="marketing-eyebrow"><?php echo $eyebrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<?php if ($title) : ?>
				<h2><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php endif; ?>
		</div>
		<div class="sandbox-testimonials sandbox-testimonials--<?php echo esc_attr($layout); ?> sandbox-testimonials--count-<?php echo esc_attr($count); ?>">
			<?php for ($index = 0; $index < $count; $index++) : ?>
				<?php
				$item = isset($testimonials[$index]) && is_array($testimonials[$index]) ? $testimonials[$index] : array();
				$quote = isset($item['quote']) && '' !== $item['quote'] ? wp_kses_post($item['quote']) : $defaults[$index]['quote'];
				$name = isset($item['name']) && '' !== $item['name'] ? wp_kses_post($item['name']) : $defaults[$index]['name'];
				$role = isset($item['role']) && '' !== $item['role'] ? wp_kses_post($item['role']) : $defaults[$index]['role'];
				?>
				<figure class="sandbox-testimonial-card">
					<blockquote><?php echo $quote; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
					<figcaption>
						<strong><?php echo $name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						<span><?php echo $role; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</figcaption>
				</figure>
			<?php endfor; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
