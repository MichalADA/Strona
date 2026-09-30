<?php
/**
 * Helpery szablonów.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pasek trybu demonstracyjnego.
 */
function parafia_demo_bar() {
	if ( ! parafia_opt( 'tryb_demo' ) ) {
		return;
	}
	?>
	<div class="demobar" role="note">
		<strong><?php esc_html_e( 'Wersja demonstracyjna', 'parafia' ); ?></strong>
		— <?php esc_html_e( 'projekt nowej strony parafii. To nie jest oficjalny serwis parafialny.', 'parafia' ); ?>
	</div>
	<?php
}

/**
 * Lista księży posortowana polem kolejności.
 *
 * @param int $limit Maksymalna liczba.
 * @return WP_Post[]
 */
function parafia_get_ksieza( $limit = -1 ) {
	return get_posts(
		array(
			'post_type'      => 'ksiadz',
			'posts_per_page' => $limit,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
}

/**
 * Najnowszy opublikowany tydzień intencji.
 *
 * @return WP_Post|null
 */
function parafia_current_intencje() {
	$posts = get_posts( array( 'post_type' => 'intencja', 'posts_per_page' => 1 ) );
	return $posts ? $posts[0] : null;
}

/**
 * Bezpieczne osadzenie transmisji albo komunikat o niedostępności.
 */
function parafia_stream_embed() {
	$url = parafia_opt( 'transmisja_embed' );

	if ( ! $url ) {
		echo '<p class="stream-empty">' . esc_html__( 'Transmisja jest obecnie niedostępna.', 'parafia' ) . '</p>';
		return;
	}
	// Odtwarzacz zewnętrzny ładowany dopiero po kliknięciu — żadnych cookies bez zgody.
	printf(
		'<button type="button" class="stream-consent" data-embed="%1$s">%2$s</button>',
		esc_url( $url ),
		esc_html__( 'Włącz transmisję (odtwarzacz zewnętrzny)', 'parafia' )
	);
}
