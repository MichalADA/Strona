<?php
/**
 * Utwardzenie i tryb demonstracyjny.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Demo nie może trafić do wyszukiwarek. Nie polegamy wyłącznie na robots.txt —
 * wysyłamy meta robots ORAZ nagłówek HTTP X-Robots-Tag.
 */
add_action(
	'wp_head',
	function () {
		if ( parafia_opt( 'tryb_demo' ) ) {
			echo '<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">' . "\n";
		}
	},
	1
);

add_action(
	'send_headers',
	function () {
		if ( parafia_opt( 'tryb_demo' ) ) {
			header( 'X-Robots-Tag: noindex, nofollow, noarchive', true );
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	}
);

// Wersja WordPressa w źródle strony — zbędna informacja dla skanerów.
remove_action( 'wp_head', 'wp_generator' );

// XML-RPC nie jest w tym serwisie używany.
add_filter( 'xmlrpc_enabled', '__return_false' );

// Wyliczanie loginów przez ?author=1.
add_action(
	'template_redirect',
	function () {
		if ( is_author() ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);

// Komunikat logowania nie zdradza, czy login istnieje.
add_filter(
	'login_errors',
	function () {
		return __( 'Nieprawidłowe dane logowania.', 'parafia' );
	}
);
