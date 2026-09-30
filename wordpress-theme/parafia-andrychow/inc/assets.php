<?php
/**
 * Zasoby front-endu. Jeden arkusz CSS, jeden mikroskrypt menu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'parafia-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Lora:wght@400;600&display=swap', array(), null );
		wp_enqueue_style( 'parafia', PARAFIA_URI . '/assets/css/theme.css', array( 'parafia-fonts' ), PARAFIA_VERSION );

		// Jedyny skrypt front-endu: przełącznik menu mobilnego i zgoda na treści zewnętrzne.
		wp_enqueue_script( 'parafia', PARAFIA_URI . '/assets/js/parafia.js', array(), PARAFIA_VERSION, true );
	}
);

// Bloki Gutenberga wczytują domyślnie osobny arkusz; łączymy, by ograniczyć liczbę żądań.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

// Emoji WordPressa — zbędne żądania i skrypt.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Preload fotografii nagłówkowej strony głównej (LCP).
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		$id = (int) get_theme_mod( 'parafia_hero_image' );
		if ( ! $id ) {
			return;
		}
		$src = wp_get_attachment_image_url( $id, 'parafia-hero' );
		if ( $src ) {
			printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $src ) );
		}
	},
	2
);
