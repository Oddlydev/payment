<?php
/**
 * Hero Gutenberg block registration and rendering.
 *
 * The Hero is a CONTAINER block: it owns the layout (max-width, padding,
 * background/text color, full/wide alignment) and renders whatever inner
 * blocks the user composes inside it. Every inner block (heading, paragraph,
 * buttons, group, columns, etc.) keeps its own native toolbar so the user can
 * change alignment, text color, background color, typography, etc. per element.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register sandbox hero block assets and block type.
 */
function sandbox_register_hero_block()
{
	$hero_script_path = get_template_directory() . '/assets/js/blocks/hero.js';
	$hero_style_path = get_template_directory() . '/assets/css/blocks/hero.css';

	wp_register_script(
		'sandbox-hero-block',
		get_template_directory_uri() . '/assets/js/blocks/hero.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($hero_script_path) ? filemtime($hero_script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-hero-block-style',
		get_template_directory_uri() . '/assets/css/blocks/hero.css',
		array(),
		file_exists($hero_style_path) ? filemtime($hero_style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/hero',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-hero-block',
			'style' => 'sandbox-hero-block-style',
			'editor_style' => 'sandbox-hero-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
				'color' => array(
					'background' => true,
					'text' => true,
					'gradients' => true,
					'link' => true,
				),
				'spacing' => array(
					'padding' => true,
					'margin' => array('top', 'bottom'),
					'blockGap' => true,
				),
				'typography' => array(
					'fontSize' => true,
					'lineHeight' => true,
				),
				'__experimentalLayout' => true,
			),
			'attributes' => array(
				'maxWidth' => array(
					'type' => 'number',
					'default' => 1120,
				),
			),
			'render_callback' => 'sandbox_render_hero_block',
		)
	);
}
add_action('init', 'sandbox_register_hero_block');

/**
 * Render callback for sandbox/hero block.
 *
 * Outputs a wrapper with layout styles and prints the inner-block content
 * exactly as authored. WordPress already injects color/spacing classes and
 * inline styles via get_block_wrapper_attributes() based on `supports`.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Inner blocks HTML.
 * @return string
 */
function sandbox_render_hero_block($attributes, $content)
{
	$max_width = isset($attributes['maxWidth']) ? (int) $attributes['maxWidth'] : 1120;
	$max_width = min(max($max_width, 720), 1600);

	$style = sprintf('--sandbox-hero-max-width:%dpx;', $max_width);

	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'sandbox-hero-block',
			'style' => $style,
		)
	);

	return sprintf(
		'<section %1$s><div class="sandbox-hero-block__inner">%2$s</div></section>',
		$wrapper_attributes,
		$content // Already-rendered inner blocks; safe.
	);
}
