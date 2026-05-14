<?php
/**
 * Card layout Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_card_layout_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/card-layout.js';
	$style_path = get_template_directory() . '/assets/css/blocks/card-layout.css';

	wp_register_script(
		'sandbox-card-layout-block',
		get_template_directory_uri() . '/assets/js/blocks/card-layout.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-card-layout-block-style',
		get_template_directory_uri() . '/assets/css/blocks/card-layout.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/card-layout',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-card-layout-block',
			'style' => 'sandbox-card-layout-block-style',
			'editor_style' => 'sandbox-card-layout-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
			),
			'render_callback' => 'sandbox_render_card_layout_block',
			'attributes' => array(
				'backgroundColor' => array('type' => 'string', 'default' => ''),
				'textColor' => array('type' => 'string', 'default' => ''),
				'eyebrow' => array('type' => 'string', 'default' => 'Featured'),
				'title' => array('type' => 'string', 'default' => 'Created Using Custom Gutenberg Blocks'),
				'cardCount' => array('type' => 'number', 'default' => 3),
				'layout' => array('type' => 'string', 'default' => 'equal'),
				'cards' => array('type' => 'array', 'default' => array()),
			),
		)
	);
}
add_action('init', 'sandbox_register_card_layout_block');

function sandbox_card_layout_default_cards()
{
	return array(
		array(
			'title' => 'Course-ready pages',
			'text' => 'Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow',
		),
		array(
			'title' => 'Course-ready pages 2',
			'text' => 'Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow',
		),
		array(
			'title' => 'Course-ready pages 3',
			'text' => 'Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow',
		),
		array(
			'title' => 'Course-ready pages 4',
			'text' => 'Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow',
		),
	);
}

function sandbox_render_card_layout_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$card_count = isset($attributes['cardCount']) ? (int) $attributes['cardCount'] : 3;
	$card_count = min(max($card_count, 2), 4);
	$layout = isset($attributes['layout']) ? sanitize_html_class($attributes['layout']) : 'equal';
	$cards = isset($attributes['cards']) && is_array($attributes['cards']) ? $attributes['cards'] : array();
	$default_cards = sandbox_card_layout_default_cards();

	if ('two-by-two' === $layout && 4 !== $card_count) {
		$layout = 'equal';
	}

	$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '';
	$text_color = isset($attributes['textColor']) ? esc_attr($attributes['textColor']) : '';
	$inline_style = '';
	if ($background_color) { $inline_style .= 'background-color:' . $background_color . ';'; }
	if ($text_color) { $inline_style .= 'color:' . $text_color . ';'; }

	$wrapper_args = array(
		'class' => 'sandbox-card-layout-block home-section',
	);
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

		<div class="sandbox-card-layout sandbox-card-layout--<?php echo esc_attr($layout); ?> sandbox-card-layout--count-<?php echo esc_attr($card_count); ?>">
			<?php for ($index = 0; $index < $card_count; $index++) : ?>
				<?php
				$card = isset($cards[$index]) && is_array($cards[$index]) ? $cards[$index] : array();
				$card_title = isset($card['title']) && '' !== $card['title'] ? wp_kses_post($card['title']) : $default_cards[$index]['title'];
				$card_text = isset($card['text']) && '' !== $card['text'] ? wp_kses_post($card['text']) : $default_cards[$index]['text'];
				?>
				<article class="sandbox-card-layout__card">
					<h3><?php echo $card_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
					<p><?php echo $card_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
