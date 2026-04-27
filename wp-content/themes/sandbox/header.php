<?php
/**
 * Theme header.
 *
 * @package Sandbox
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'sandbox'); ?></a>

<header class="site-header">
	<div class="site-header__inner">
		<div class="site-branding">
			<?php
			if (has_custom_logo()) {
				the_custom_logo();
			}

			if (is_front_page() && is_home()) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
			<?php endif; ?>

			<?php if (get_bloginfo('description')) : ?>
				<p class="site-description"><?php bloginfo('description'); ?></p>
			<?php endif; ?>
		</div>

		<nav class="site-navigation" aria-label="<?php esc_attr_e('Primary menu', 'sandbox'); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class' => 'primary-menu',
					'container' => false,
					'fallback_cb' => false,
				)
			);
			?>
		</nav>
	</div>
</header>
