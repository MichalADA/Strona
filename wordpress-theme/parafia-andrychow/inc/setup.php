<?php
/**
 * Konfiguracja motywu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'parafia', PARAFIA_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/theme.css' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
		);

		register_nav_menus(
			array(
				'primary' => __( 'Menu główne', 'parafia' ),
				'footer'  => __( 'Menu w stopce', 'parafia' ),
			)
		);

		// Rozmiary dopasowane do realnych kontenerów szablonu — bez marnowania miejsca na dysku.
		add_image_size( 'parafia-card', 720, 480, true );      // karty aktualności
		add_image_size( 'parafia-portrait', 480, 600, true );  // portrety księży
		add_image_size( 'parafia-hero', 2000, 900, true );     // fotografia nagłówkowa
	}
);

// Komentarze są w serwisie parafialnym zbędne — wyłączone globalnie.
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/**
 * Rozmiary obrazów podpowiadane przeglądarce (sizes) dla kart.
 */
add_filter(
	'wp_calculate_image_sizes',
	function ( $sizes, $size ) {
		if ( is_array( $size ) && isset( $size[0] ) && 720 === (int) $size[0] ) {
			return '(max-width: 700px) 100vw, (max-width: 1100px) 50vw, 360px';
		}
		return $sizes;
	},
	10,
	2
);
