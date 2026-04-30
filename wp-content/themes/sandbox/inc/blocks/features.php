<?php
/**
 * Features Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_features_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/features.js';
	$style_path = get_template_directory() . '/assets/css/blocks/features.css';

	wp_register_script(
		'sandbox-features-block',
		get_template_directory_uri() . '/assets/js/blocks/features.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-features-block-style',
		get_template_directory_uri() . '/assets/css/blocks/features.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/features',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-features-block',
			'style' => 'sandbox-features-block-style',
			'editor_style' => 'sandbox-features-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
			),
			'render_callback' => 'sandbox_render_features_block',
			'attributes' => array(
				'backgroundColor' => array('type' => 'string', 'default' => ''),
				'textColor' => array('type' => 'string', 'default' => ''),
				'eyebrow' => array('type' => 'string', 'default' => ''),
				'title' => array('type' => 'string', 'default' => ''),
				'featureOneTitle' => array('type' => 'string', 'default' => ''),
				'featureOneText' => array('type' => 'string', 'default' => ''),
				'featureTwoTitle' => array('type' => 'string', 'default' => ''),
				'featureTwoText' => array('type' => 'string', 'default' => ''),
				'featureThreeTitle' => array('type' => 'string', 'default' => ''),
				'featureThreeText' => array('type' => 'string', 'default' => ''),
			),
		)
	);
}
add_action('init', 'sandbox_register_features_block');

function sandbox_render_features_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$feature_one_title = isset($attributes['featureOneTitle']) ? sanitize_text_field($attributes['featureOneTitle']) : '';
	$feature_one_text = isset($attributes['featureOneText']) ? sanitize_text_field($attributes['featureOneText']) : '';
	$feature_two_title = isset($attributes['featureTwoTitle']) ? sanitize_text_field($attributes['featureTwoTitle']) : '';
	$feature_two_text = isset($attributes['featureTwoText']) ? sanitize_text_field($attributes['featureTwoText']) : '';
	$feature_three_title = isset($attributes['featureThreeTitle']) ? sanitize_text_field($attributes['featureThreeTitle']) : '';
	$feature_three_text = isset($attributes['featureThreeText']) ? sanitize_text_field($attributes['featureThreeText']) : '';

	$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '';
	$text_color = isset($attributes['textColor']) ? esc_attr($attributes['textColor']) : '';
	$inline_style = '';
	if ($background_color) { $inline_style .= 'background-color:' . $background_color . ';'; }
	if ($text_color) { $inline_style .= 'color:' . $text_color . ';'; }

	$wrapper_args = array('class' => 'sandbox-features-block home-section');
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
		<div class="home-feature-grid">
			<article class="feature-card">
				<h3><?php echo esc_html($feature_one_title); ?></h3>
				<p><?php echo esc_html($feature_one_text); ?></p>
			</article>
			<article class="feature-card">
				<h3><?php echo esc_html($feature_two_title); ?></h3>
				<p><?php echo esc_html($feature_two_text); ?></p>
			</article>
			<article class="feature-card">
				<h3><?php echo esc_html($feature_three_title); ?></h3>
				<p><?php echo esc_html($feature_three_text); ?></p>
			</article>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
