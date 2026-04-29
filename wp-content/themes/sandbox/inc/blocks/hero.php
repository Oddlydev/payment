<?php
/**
 * Hero Gutenberg block registration and rendering.
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
			'render_callback' => 'sandbox_render_hero_block',
			'attributes' => array(
				'eyebrow' => array(
					'type' => 'string',
					'default' => __('Professional training made simple', 'sandbox'),
				),
				'title' => array(
					'type' => 'string',
					'default' => __('Build skills faster with focused online training.', 'sandbox'),
				),
				'intro' => array(
					'type' => 'string',
					'default' => __('A clean learning experience for teams and individuals who want practical lessons, clear progress, and direct access to paid course enrollment.', 'sandbox'),
				),
				'buttonText' => array(
					'type' => 'string',
					'default' => __('Enroll now', 'sandbox'),
				),
				'buttonUrl' => array(
					'type' => 'string',
					'default' => home_url('/checkout/'),
				),
				'secondaryButtonText' => array(
					'type' => 'string',
					'default' => __('View features', 'sandbox'),
				),
				'secondaryButtonUrl' => array(
					'type' => 'string',
					'default' => '#',
				),
				'statOneNumber' => array(
					'type' => 'string',
					'default' => __('8 weeks', 'sandbox'),
				),
				'statOneLabel' => array(
					'type' => 'string',
					'default' => __('Structured learning path', 'sandbox'),
				),
				'statTwoNumber' => array(
					'type' => 'string',
					'default' => __('24/7', 'sandbox'),
				),
				'statTwoLabel' => array(
					'type' => 'string',
					'default' => __('Access to course materials', 'sandbox'),
				),
				'statThreeNumber' => array(
					'type' => 'string',
					'default' => __('Certificate', 'sandbox'),
				),
				'statThreeLabel' => array(
					'type' => 'string',
					'default' => __('Issued after completion', 'sandbox'),
				),
				'maxWidth' => array(
					'type' => 'number',
					'default' => 1120,
				),
				'topPadding' => array(
					'type' => 'number',
					'default' => 72,
				),
				'bottomPadding' => array(
					'type' => 'number',
					'default' => 72,
				),
			),
		)
	);
}
add_action('init', 'sandbox_register_hero_block');

/**
 * Render callback for sandbox/hero block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function sandbox_render_hero_block($attributes)
{
	$eyebrow = isset($attributes['eyebrow']) ? wp_kses_post($attributes['eyebrow']) : '';
	$title = isset($attributes['title']) ? wp_kses_post($attributes['title']) : '';
	$intro = isset($attributes['intro']) ? wp_kses_post($attributes['intro']) : '';
	$button_text = isset($attributes['buttonText']) ? sanitize_text_field($attributes['buttonText']) : '';
	$button_url = isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '';
	$secondary_button_text = isset($attributes['secondaryButtonText']) ? sanitize_text_field($attributes['secondaryButtonText']) : '';
	$secondary_button_url = isset($attributes['secondaryButtonUrl']) ? esc_url($attributes['secondaryButtonUrl']) : '';

	$stat_one_number = isset($attributes['statOneNumber']) ? sanitize_text_field($attributes['statOneNumber']) : '';
	$stat_one_label = isset($attributes['statOneLabel']) ? sanitize_text_field($attributes['statOneLabel']) : '';
	$stat_two_number = isset($attributes['statTwoNumber']) ? sanitize_text_field($attributes['statTwoNumber']) : '';
	$stat_two_label = isset($attributes['statTwoLabel']) ? sanitize_text_field($attributes['statTwoLabel']) : '';
	$stat_three_number = isset($attributes['statThreeNumber']) ? sanitize_text_field($attributes['statThreeNumber']) : '';
	$stat_three_label = isset($attributes['statThreeLabel']) ? sanitize_text_field($attributes['statThreeLabel']) : '';

	$max_width = isset($attributes['maxWidth']) ? (int) $attributes['maxWidth'] : 1120;
	$top_padding = isset($attributes['topPadding']) ? (int) $attributes['topPadding'] : 72;
	$bottom_padding = isset($attributes['bottomPadding']) ? (int) $attributes['bottomPadding'] : 72;

	$max_width = min(max($max_width, 720), 1440);
	$top_padding = min(max($top_padding, 24), 160);
	$bottom_padding = min(max($bottom_padding, 24), 160);

	$style = sprintf(
		'--sandbox-hero-max-width:%dpx;--sandbox-hero-padding-top:%dpx;--sandbox-hero-padding-bottom:%dpx;',
		$max_width,
		$top_padding,
		$bottom_padding
	);

	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'sandbox-hero-block',
			'style' => $style,
		)
	);

	ob_start();
	?>
	<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sandbox-hero-block__inner">
			<div class="sandbox-hero-block__content">
				<?php if ($eyebrow) : ?>
					<p class="marketing-eyebrow"><?php echo $eyebrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>

				<?php if ($title) : ?>
					<h2 class="sandbox-hero-block__title"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>

				<?php if ($intro) : ?>
					<p class="sandbox-hero-block__intro"><?php echo $intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>

				<div class="marketing-actions">
					<?php if ($button_text && $button_url) : ?>
						<a class="button button-primary" href="<?php echo esc_url($button_url); ?>">
							<?php echo esc_html($button_text); ?>
						</a>
					<?php endif; ?>

					<?php if ($secondary_button_text && $secondary_button_url) : ?>
						<a class="button button-secondary" href="<?php echo esc_url($secondary_button_url); ?>">
							<?php echo esc_html($secondary_button_text); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="sandbox-hero-block__panel" aria-label="<?php esc_attr_e('Hero statistics', 'sandbox'); ?>">
				<div>
					<span><?php echo esc_html($stat_one_number); ?></span>
					<p><?php echo esc_html($stat_one_label); ?></p>
				</div>
				<div>
					<span><?php echo esc_html($stat_two_number); ?></span>
					<p><?php echo esc_html($stat_two_label); ?></p>
				</div>
				<div>
					<span><?php echo esc_html($stat_three_number); ?></span>
					<p><?php echo esc_html($stat_three_label); ?></p>
				</div>
			</div>
		</div>
	</section>
	<?php

	return ob_get_clean();
}
