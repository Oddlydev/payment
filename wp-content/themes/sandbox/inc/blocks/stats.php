<?php
/**
 * Stats Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_stats_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/stats.js';
	$style_path = get_template_directory() . '/assets/css/blocks/stats.css';

	wp_register_script(
		'sandbox-stats-block',
		get_template_directory_uri() . '/assets/js/blocks/stats.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-stats-block-style',
		get_template_directory_uri() . '/assets/css/blocks/stats.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/stats',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-stats-block',
			'style' => 'sandbox-stats-block-style',
			'editor_style' => 'sandbox-stats-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
			),
			'render_callback' => 'sandbox_render_stats_block',
			'attributes' => array(
				'backgroundColor' => array('type' => 'string', 'default' => ''),
				'textColor' => array('type' => 'string', 'default' => ''),
				'eyebrow' => array('type' => 'string', 'default' => 'Results'),
				'title' => array('type' => 'string', 'default' => 'Numbers that show the impact'),
				'statCount' => array('type' => 'number', 'default' => 3),
				'stats' => array('type' => 'array', 'default' => array()),
			),
		)
	);
}
add_action('init', 'sandbox_register_stats_block');

function sandbox_stats_default_items()
{
	return array(
		array('value' => '98%', 'label' => 'Customer satisfaction'),
		array('value' => '42k+', 'label' => 'Learners enrolled'),
		array('value' => '120+', 'label' => 'Reusable page sections'),
		array('value' => '24/7', 'label' => 'Access to resources'),
	);
}

function sandbox_render_stats_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$stat_count = isset($attributes['statCount']) ? (int) $attributes['statCount'] : 3;
	$stat_count = min(max($stat_count, 2), 4);
	$stats = isset($attributes['stats']) && is_array($attributes['stats']) ? $attributes['stats'] : array();
	$default_stats = sandbox_stats_default_items();

	$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '';
	$text_color = isset($attributes['textColor']) ? esc_attr($attributes['textColor']) : '';
	$inline_style = '';
	if ($background_color) { $inline_style .= 'background-color:' . $background_color . ';'; }
	if ($text_color) { $inline_style .= 'color:' . $text_color . ';'; }

	$wrapper_args = array('class' => 'sandbox-stats-block home-section');
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
		<div class="sandbox-stats-grid sandbox-stats-grid--count-<?php echo esc_attr($stat_count); ?>">
			<?php for ($index = 0; $index < $stat_count; $index++) : ?>
				<?php
				$stat = isset($stats[$index]) && is_array($stats[$index]) ? $stats[$index] : array();
				$value = isset($stat['value']) && '' !== $stat['value'] ? wp_kses_post($stat['value']) : $default_stats[$index]['value'];
				$label = isset($stat['label']) && '' !== $stat['label'] ? wp_kses_post($stat['label']) : $default_stats[$index]['label'];
				?>
				<div class="sandbox-stat-card">
					<strong><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					<span><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
			<?php endfor; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
