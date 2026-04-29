<?php
/**
 * Sandbox theme functions.
 *
 * @package Sandbox
 */

if (!defined('ABSPATH')) {
	exit;
}

define('SANDBOX_VERSION', '1.0.0');

function sandbox_setup()
{
	load_theme_textdomain('sandbox', get_template_directory() . '/languages');

	add_theme_support('automatic-feed-links');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('responsive-embeds');
	add_theme_support('custom-logo');
	add_theme_support('editor-styles');
	add_editor_style('style.css');
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'search-form',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary' => __('Primary Menu', 'sandbox'),
		)
	);
}
add_action('after_setup_theme', 'sandbox_setup');

require_once get_template_directory() . '/inc/blocks/hero.php';
require_once get_template_directory() . '/inc/blocks/features.php';
require_once get_template_directory() . '/inc/blocks/image-layout.php';
require_once get_template_directory() . '/inc/blocks/cta.php';

function sandbox_scripts()
{
	wp_enqueue_style('sandbox-style', get_stylesheet_uri(), array(), SANDBOX_VERSION);
	wp_enqueue_script('sandbox-script', get_template_directory_uri() . '/assets/js/theme.js', array(), SANDBOX_VERSION, true);

	if (is_page_template('page-home.php')) {
		$home_style_path = get_template_directory() . '/assets/css/pages/home.css';
		wp_enqueue_style(
			'sandbox-home-page',
			get_template_directory_uri() . '/assets/css/pages/home.css',
			array('sandbox-style'),
			file_exists($home_style_path) ? filemtime($home_style_path) : SANDBOX_VERSION
		);
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'sandbox_scripts');

function sandbox_widgets_init()
{
	register_sidebar(
		array(
			'name' => __('Footer', 'sandbox'),
			'id' => 'footer-1',
			'description' => __('Add footer widgets here.', 'sandbox'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'sandbox_widgets_init');
