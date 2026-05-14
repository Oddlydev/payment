<?php
/**
 * FAQ Gutenberg block registration and rendering.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

function sandbox_register_faq_block()
{
	$script_path = get_template_directory() . '/assets/js/blocks/faq.js';
	$style_path = get_template_directory() . '/assets/css/blocks/faq.css';

	wp_register_script(
		'sandbox-faq-block',
		get_template_directory_uri() . '/assets/js/blocks/faq.js',
		array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components'),
		file_exists($script_path) ? filemtime($script_path) : SANDBOX_VERSION,
		true
	);

	wp_register_style(
		'sandbox-faq-block-style',
		get_template_directory_uri() . '/assets/css/blocks/faq.css',
		array(),
		file_exists($style_path) ? filemtime($style_path) : SANDBOX_VERSION
	);

	register_block_type(
		'sandbox/faq',
		array(
			'api_version' => 2,
			'editor_script' => 'sandbox-faq-block',
			'style' => 'sandbox-faq-block-style',
			'editor_style' => 'sandbox-faq-block-style',
			'supports' => array(
				'align' => array('wide', 'full'),
				'anchor' => true,
				'className' => true,
			),
			'render_callback' => 'sandbox_render_faq_block',
			'attributes' => array(
				'backgroundColor' => array('type' => 'string', 'default' => ''),
				'textColor' => array('type' => 'string', 'default' => ''),
				'eyebrow' => array('type' => 'string', 'default' => 'FAQ'),
				'title' => array('type' => 'string', 'default' => 'Frequently asked questions'),
				'faqCount' => array('type' => 'number', 'default' => 4),
				'layout' => array('type' => 'string', 'default' => 'single'),
				'items' => array('type' => 'array', 'default' => array()),
			),
		)
	);
}
add_action('init', 'sandbox_register_faq_block');

function sandbox_faq_default_items()
{
	return array(
		array('question' => 'Can I edit the section content?', 'answer' => 'Yes. Each question and answer is editable directly in the block preview.'),
		array('question' => 'Can I change how many FAQs are shown?', 'answer' => 'Yes. The block supports two to six FAQ items from the sidebar settings.'),
		array('question' => 'Will this render on the frontend?', 'answer' => 'Yes. The saved page is rendered by PHP using the same attributes from the editor.'),
		array('question' => 'Can I use it on full-width pages?', 'answer' => 'Yes. The block supports wide and full alignment like the other custom blocks.'),
		array('question' => 'Can I change colors?', 'answer' => 'Yes. Background and text colors are available in the block inspector.'),
		array('question' => 'Does it need extra JavaScript?', 'answer' => 'No. The frontend output uses native details and summary elements.'),
	);
}

function sandbox_render_faq_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$count = isset($attributes['faqCount']) ? (int) $attributes['faqCount'] : 4;
	$count = min(max($count, 2), 6);
	$layout = isset($attributes['layout']) ? sanitize_html_class($attributes['layout']) : 'single';
	$items = isset($attributes['items']) && is_array($attributes['items']) ? $attributes['items'] : array();
	$defaults = sandbox_faq_default_items();

	$background_color = isset($attributes['backgroundColor']) ? esc_attr($attributes['backgroundColor']) : '';
	$text_color = isset($attributes['textColor']) ? esc_attr($attributes['textColor']) : '';
	$inline_style = '';
	if ($background_color) { $inline_style .= 'background-color:' . $background_color . ';'; }
	if ($text_color) { $inline_style .= 'color:' . $text_color . ';'; }

	$wrapper_args = array('class' => 'sandbox-faq-block home-section');
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
		<div class="sandbox-faq-list sandbox-faq-list--<?php echo esc_attr($layout); ?>">
			<?php for ($index = 0; $index < $count; $index++) : ?>
				<?php
				$item = isset($items[$index]) && is_array($items[$index]) ? $items[$index] : array();
				$question = isset($item['question']) && '' !== $item['question'] ? wp_kses_post($item['question']) : $defaults[$index]['question'];
				$answer = isset($item['answer']) && '' !== $item['answer'] ? wp_kses_post($item['answer']) : $defaults[$index]['answer'];
				?>
				<details class="sandbox-faq-item">
					<summary><?php echo $question; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></summary>
					<div><?php echo $answer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</details>
			<?php endfor; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
