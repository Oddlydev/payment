<?php
/**
 * Image layout Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_image_layout_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/image-layout.js';
	$style_path = get_template_directory() . '/assets/css/blocks/image-layout.css';

	wp_register_script(
		'sandbox-image-layout-block',
		get_template_directory_uri() . '/assets/js/blocks/image-layout.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-image-layout-block-style',
		get_template_directory_uri() . '/assets/css/blocks/image-layout.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/image-layout',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-image-layout-block',
			'style' => 'sandbox-image-layout-block-style',
			'editor_style' => 'sandbox-image-layout-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
			),
			'render_callback' => 'sandbox_render_image_layout_block',
			'attributes' => array(
				'backgroundColor' => array('type' => 'string', 'default' => ''),
				'eyebrow' => array('type' => 'string', 'default' => ''),
				'title' => array('type' => 'string', 'default' => ''),
				'imageOneUrl' => array('type' => 'string', 'default' => ''),
				'imageOneAlt' => array('type' => 'string', 'default' => ''),
				'imageOneCaption' => array('type' => 'string', 'default' => ''),
				'imageTwoUrl' => array('type' => 'string', 'default' => ''),
				'imageTwoAlt' => array('type' => 'string', 'default' => ''),
				'imageTwoCaption' => array('type' => 'string', 'default' => ''),
				'imageThreeUrl' => array('type' => 'string', 'default' => ''),
				'imageThreeAlt' => array('type' => 'string', 'default' => ''),
				'imageThreeCaption' => array('type' => 'string', 'default' => ''),
			),
		)
	);
}
add_action('init', 'sandbox_register_image_layout_block');

function sandbox_render_image_layout_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$image_one_url = isset($attributes['imageOneUrl']) ? esc_url($attributes['imageOneUrl']) : '';
	$image_one_alt = isset($attributes['imageOneAlt']) ? sanitize_text_field($attributes['imageOneAlt']) : '';
	$image_one_caption = isset($attributes['imageOneCaption']) ? sanitize_text_field($attributes['imageOneCaption']) : '';
	$image_two_url = isset($attributes['imageTwoUrl']) ? esc_url($attributes['imageTwoUrl']) : '';
	$image_two_alt = isset($attributes['imageTwoAlt']) ? sanitize_text_field($attributes['imageTwoAlt']) : '';
	$image_two_caption = isset($attributes['imageTwoCaption']) ? sanitize_text_field($attributes['imageTwoCaption']) : '';
	$image_three_url = isset($attributes['imageThreeUrl']) ? esc_url($attributes['imageThreeUrl']) : '';
	$image_three_alt = isset($attributes['imageThreeAlt']) ? sanitize_text_field($attributes['imageThreeAlt']) : '';
	$image_three_caption = isset($attributes['imageThreeCaption']) ? sanitize_text_field($attributes['imageThreeCaption']) : '';

	$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '';
	$wrapper_args = array('class' => 'sandbox-image-layout-block home-section');
	if ($background_color) { $wrapper_args['style'] = 'background-color:' . $background_color . ';'; }
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

		<div class="home-image-layout">
			<figure class="home-image-layout__item home-image-layout__item--large">
				<?php if ($image_one_url) : ?>
					<img src="<?php echo esc_url($image_one_url); ?>" alt="<?php echo esc_attr($image_one_alt); ?>">
				<?php endif; ?>
				<?php if ($image_one_caption) : ?>
					<figcaption><?php echo esc_html($image_one_caption); ?></figcaption>
				<?php endif; ?>
			</figure>

			<figure class="home-image-layout__item">
				<?php if ($image_two_url) : ?>
					<img src="<?php echo esc_url($image_two_url); ?>" alt="<?php echo esc_attr($image_two_alt); ?>">
				<?php endif; ?>
				<?php if ($image_two_caption) : ?>
					<figcaption><?php echo esc_html($image_two_caption); ?></figcaption>
				<?php endif; ?>
			</figure>

			<figure class="home-image-layout__item">
				<?php if ($image_three_url) : ?>
					<img src="<?php echo esc_url($image_three_url); ?>" alt="<?php echo esc_attr($image_three_alt); ?>">
				<?php endif; ?>
				<?php if ($image_three_caption) : ?>
					<figcaption><?php echo esc_html($image_three_caption); ?></figcaption>
				<?php endif; ?>
			</figure>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
