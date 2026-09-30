<?php
/**
 * Zasoby front-endu.
 *
 * theme.css — kopia 1:1 arkuszy prototypu (styles.css + parish-zielen.css),
 * wp.css    — wyłącznie dopasowania do markupu generowanego przez WordPressa,
 * parafia.js — menu mobilne i zgoda na treści zewnętrzne.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'parafia-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Lora:wght@400;600&display=swap', array(), null );
		wp_enqueue_style( 'parafia', PARAFIA_URI . '/assets/css/theme.css', array( 'parafia-fonts' ), PARAFIA_VERSION );
		wp_enqueue_style( 'parafia-wp', PARAFIA_URI . '/assets/css/wp.css', array( 'parafia' ), PARAFIA_VERSION );

		// Jedyny skrypt front-endu: przełącznik menu mobilnego i zgoda na treści zewnętrzne.
		wp_enqueue_script( 'parafia', PARAFIA_URI . '/assets/js/parafia.js', array(), PARAFIA_VERSION, true );
	}
);

// Style bloków ładowane tylko dla bloków faktycznie użytych na stronie.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

// Emoji WordPressa — zbędne żądania i skrypt.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Preload fotografii na stronie głównej (LCP).
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		$id  = (int) get_theme_mod( 'parafia_hero_image' );
		$src = $id ? wp_get_attachment_image_url( $id, 'parafia-hero' ) : '';
		if ( ! $src ) {
			$src = parafia_img( 'kosciol-dzien.jpg' );
		}
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $src ) );
	},
	2
);
