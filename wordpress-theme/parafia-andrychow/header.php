<?php
/**
 * Nagłówek serwisu — markup 1:1 z prototypu (sygnet + nazwa, menu, transmisja, burger).
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
			<img class="brand-mark" src="<?php echo esc_url( parafia_img( 'sygnet.png' ) ); ?>" alt="" width="525" height="240" />
			<span class="brand-text">
				<span class="brand-name"><?php echo esc_html( parafia_opt( 'nazwa' ) ); ?><?php if ( parafia_opt( 'wezwanie' ) ) : ?><span class="brand-long"><br /><?php echo esc_html( parafia_opt( 'wezwanie' ) ); ?></span><?php endif; ?></span>
				<span class="brand-sub"><?php echo esc_html( parafia_opt( 'miejscowosc' ) ); ?></span>
			</span>
		</a>

		<nav class="mainnav" aria-label="<?php esc_attr_e( 'Menu główne', 'parafia' ); ?>">
			<?php parafia_render_mainnav(); ?>
		</nav>

		<a class="nav-live" href="<?php echo esc_url( parafia_page_url( 'transmisja' ) ); ?>" aria-label="<?php esc_attr_e( 'Transmisja na żywo', 'parafia' ); ?>">
			<?php echo parafia_icon( 'camera' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="label-text"><?php esc_html_e( 'Transmisja', 'parafia' ); ?></span>
		</a>

		<button class="burger" type="button" aria-expanded="false" aria-controls="menu-mobilne" aria-label="<?php esc_attr_e( 'Otwórz menu', 'parafia' ); ?>">
			<?php echo parafia_icon( 'burger' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="label-text"><?php esc_html_e( 'Menu', 'parafia' ); ?></span>
		</button>
	</div>

	<nav class="mobilenav" id="menu-mobilne" aria-label="<?php esc_attr_e( 'Menu mobilne', 'parafia' ); ?>" hidden>
		<?php parafia_render_mobilenav(); ?>
	</nav>
</header>

<main id="tresc">
