<?php
/**
 * Nagłówek serwisu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip" href="#tresc"><?php esc_html_e( 'Przejdź do treści', 'parafia' ); ?></a>
<?php parafia_demo_bar(); ?>

<header class="site-header">
	<div class="wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span>
				<span class="brand-name"><?php esc_html_e( 'Parafia św. Stanisława', 'parafia' ); ?><span class="brand-long"><br><?php esc_html_e( 'Biskupa i Męczennika', 'parafia' ); ?></span></span>
				<span class="brand-sub"><?php bloginfo( 'description' ); ?></span>
			</span>
		</a>

		<nav class="mainnav" aria-label="<?php esc_attr_e( 'Menu główne', 'parafia' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<a class="nav-live" href="<?php echo esc_url( home_url( '/transmisja/' ) ); ?>" aria-label="<?php esc_attr_e( 'Transmisja na żywo', 'parafia' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><path d="m16 13 5.2 3.1a1 1 0 0 0 1.5-.9V8.8a1 1 0 0 0-1.5-.9L16 11"></path><rect x="1.5" y="5.5" width="14.5" height="13" rx="2"></rect></svg>
			<span class="label-text"><?php esc_html_e( 'Transmisja', 'parafia' ); ?></span>
		</a>

		<button class="burger" type="button" aria-expanded="false" aria-controls="menu-mobilne" aria-label="<?php esc_attr_e( 'Otwórz menu', 'parafia' ); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18"></path></svg>
			<span class="label-text"><?php esc_html_e( 'Menu', 'parafia' ); ?></span>
		</button>
	</div>

	<nav class="mobilenav" id="menu-mobilne" hidden aria-label="<?php esc_attr_e( 'Menu mobilne', 'parafia' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
</header>

<main id="tresc">
