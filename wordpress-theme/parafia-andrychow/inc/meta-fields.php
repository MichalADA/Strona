<?php
/**
 * Pola dodatkowe — natywne metaboxy.
 *
 * DLACZEGO BEZ ACF: potrzebne pola to kilkanaście prostych wartości
 * (telefon, e-mail, wiersze intencji, URL transmisji). Natywne metaboxy
 * i Settings API nie dodają zależności, nie wygasają licencyjnie i nie
 * blokują migracji. Jeśli parafia w przyszłości zechce rozbudowanego
 * modelu treści, ACF można dołożyć bez przepisywania motywu — pola
 * zapisywane są w standardowym post_meta o czytelnych kluczach.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------------- Księża */

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'parafia-ksiadz', __( 'Dane kontaktowe', 'parafia' ), 'parafia_ksiadz_box', 'ksiadz', 'normal', 'high' );
		add_meta_box( 'parafia-intencje', __( 'Intencje w tym tygodniu', 'parafia' ), 'parafia_intencje_box', 'intencja', 'normal', 'high' );
		add_meta_box( 'parafia-strona', __( 'Układ strony', 'parafia' ), 'parafia_strona_box', 'page', 'side', 'default' );
	}
);

/**
 * Metabox strony: nadtytuł i odnośnik do kancelarii pod treścią.
 *
 * @param WP_Post $post Edytowana strona.
 */
function parafia_strona_box( $post ) {
	wp_nonce_field( 'parafia_strona', 'parafia_strona_nonce' );
	?>
	<p><label for="parafia_kicker"><strong><?php esc_html_e( 'Nadtytuł', 'parafia' ); ?></strong></label><br>
	<input type="text" class="widefat" id="parafia_kicker" name="parafia_kicker" value="<?php echo esc_attr( get_post_meta( $post->ID, '_parafia_kicker', true ) ); ?>" placeholder="<?php esc_attr_e( 'np. Sakramenty', 'parafia' ); ?>"></p>
	<p class="description"><?php esc_html_e( 'Mały napis nad tytułem. Puste = tytuł strony nadrzędnej.', 'parafia' ); ?></p>
	<p><label><input type="checkbox" name="parafia_bez_kancelarii" value="1" <?php checked( get_post_meta( $post->ID, '_parafia_bez_kancelarii', true ), '1' ); ?>>
	<?php esc_html_e( 'Ukryj odnośnik „Sprawy formalne … kancelaria” pod treścią', 'parafia' ); ?></label></p>
	<p class="description"><?php esc_html_e( 'Zajawka strony (panel „Zajawka”) wyświetla się jako większy akapit wprowadzający pod tytułem.', 'parafia' ); ?></p>
	<?php
}

/**
 * Metabox księdza.
 *
 * @param WP_Post $post Edytowany wpis.
 */
function parafia_ksiadz_box( $post ) {
	wp_nonce_field( 'parafia_ksiadz', 'parafia_ksiadz_nonce' );
	$rola     = get_post_meta( $post->ID, '_parafia_rola', true );
	$telefon  = get_post_meta( $post->ID, '_parafia_telefon', true );
	$email    = get_post_meta( $post->ID, '_parafia_email', true );
	?>
	<p><label for="parafia_rola"><strong><?php esc_html_e( 'Funkcja', 'parafia' ); ?></strong></label><br>
	<input type="text" class="widefat" id="parafia_rola" name="parafia_rola" value="<?php echo esc_attr( $rola ); ?>" placeholder="<?php esc_attr_e( 'np. Proboszcz', 'parafia' ); ?>"></p>

	<p><label for="parafia_telefon"><strong><?php esc_html_e( 'Telefon', 'parafia' ); ?></strong></label><br>
	<input type="text" class="widefat" id="parafia_telefon" name="parafia_telefon" value="<?php echo esc_attr( $telefon ); ?>"></p>

	<p><label for="parafia_email"><strong><?php esc_html_e( 'E-mail', 'parafia' ); ?></strong></label><br>
	<input type="email" class="widefat" id="parafia_email" name="parafia_email" value="<?php echo esc_attr( $email ); ?>"></p>

	<hr>

	<p><label for="parafia_rodzic"><input type="checkbox" id="parafia_rodzic" name="parafia_rodzic" value="rodak" <?php checked( get_post_meta( $post->ID, '_parafia_rodzic', true ), 'rodak' ); ?>>
	<strong><?php esc_html_e( 'Kapłan pochodzący z naszej parafii', 'parafia' ); ?></strong></label><br>
	<span class="description"><?php esc_html_e( 'Zaznacz, jeśli ksiądz nie posługuje obecnie w parafii, lecz z niej pochodzi. Trafi wtedy na listę chronologiczną, a nie do kart duszpasterzy.', 'parafia' ); ?></span></p>

	<p><label for="parafia_rok_swiecen"><strong><?php esc_html_e( 'Rok święceń kapłańskich', 'parafia' ); ?></strong></label><br>
	<input type="number" min="1900" max="2100" step="1" id="parafia_rok_swiecen" name="parafia_rok_swiecen" value="<?php echo esc_attr( get_post_meta( $post->ID, '_parafia_rok_swiecen', true ) ); ?>"></p>

	<p class="description"><?php esc_html_e( 'Kolejność kart duszpasterzy ustawia się polem „Kolejność” w bloku Atrybuty strony. Kapłani pochodzący z parafii sortowani są rokiem święceń.', 'parafia' ); ?></p>
	<?php
}

/**
 * Metabox tygodnia intencji — proste pola powtarzalne (bez JS frameworków).
 *
 * @param WP_Post $post Edytowany wpis.
 */
function parafia_intencje_box( $post ) {
	wp_nonce_field( 'parafia_intencje', 'parafia_intencje_nonce' );
	$rows = get_post_meta( $post->ID, '_parafia_intencje', true );
	$rows = is_array( $rows ) ? $rows : array();
	$rows[] = array( 'dzien' => '', 'godzina' => '', 'tresc' => '' ); // zawsze jeden pusty wiersz
	?>
	<table class="widefat striped">
		<thead><tr>
			<th style="width:160px"><?php esc_html_e( 'Dzień', 'parafia' ); ?></th>
			<th style="width:110px"><?php esc_html_e( 'Godzina', 'parafia' ); ?></th>
			<th><?php esc_html_e( 'Treść intencji', 'parafia' ); ?></th>
		</tr></thead>
		<tbody>
		<?php foreach ( $rows as $i => $row ) : ?>
			<tr>
				<td><input type="date" name="parafia_intencje[<?php echo (int) $i; ?>][dzien]" value="<?php echo esc_attr( $row['dzien'] ?? '' ); ?>"></td>
				<td><input type="time" name="parafia_intencje[<?php echo (int) $i; ?>][godzina]" value="<?php echo esc_attr( $row['godzina'] ?? '' ); ?>"></td>
				<td><input type="text" class="widefat" name="parafia_intencje[<?php echo (int) $i; ?>][tresc]" value="<?php echo esc_attr( $row['tresc'] ?? '' ); ?>"></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p class="description"><?php esc_html_e( 'Po zapisaniu pojawi się kolejny pusty wiersz. Wiersz z pustą treścią zostaje usunięty.', 'parafia' ); ?></p>
	<?php
}

/**
 * Zapis metaboxów: nonce, uprawnienia, sanityzacja.
 *
 * @param int $post_id ID wpisu.
 */
add_action(
	'save_post',
	function ( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['parafia_ksiadz_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['parafia_ksiadz_nonce'] ) ), 'parafia_ksiadz' ) ) {
			update_post_meta( $post_id, '_parafia_rola', sanitize_text_field( wp_unslash( $_POST['parafia_rola'] ?? '' ) ) );
			update_post_meta( $post_id, '_parafia_telefon', sanitize_text_field( wp_unslash( $_POST['parafia_telefon'] ?? '' ) ) );
			update_post_meta( $post_id, '_parafia_email', sanitize_email( wp_unslash( $_POST['parafia_email'] ?? '' ) ) );
			update_post_meta( $post_id, '_parafia_rodzic', ( 'rodak' === ( $_POST['parafia_rodzic'] ?? '' ) ) ? 'rodak' : '' );
			update_post_meta( $post_id, '_parafia_rok_swiecen', absint( $_POST['parafia_rok_swiecen'] ?? 0 ) ?: '' );
		}

		if ( isset( $_POST['parafia_strona_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['parafia_strona_nonce'] ) ), 'parafia_strona' ) ) {
			update_post_meta( $post_id, '_parafia_kicker', sanitize_text_field( wp_unslash( $_POST['parafia_kicker'] ?? '' ) ) );
			update_post_meta( $post_id, '_parafia_bez_kancelarii', empty( $_POST['parafia_bez_kancelarii'] ) ? '' : '1' );
		}

		if ( isset( $_POST['parafia_intencje_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['parafia_intencje_nonce'] ) ), 'parafia_intencje' ) ) {
			$raw   = isset( $_POST['parafia_intencje'] ) ? (array) wp_unslash( $_POST['parafia_intencje'] ) : array();
			$clean = array();
			foreach ( $raw as $row ) {
				$tresc = sanitize_text_field( $row['tresc'] ?? '' );
				if ( '' === $tresc ) {
					continue;
				}
				$clean[] = array(
					'dzien'   => sanitize_text_field( $row['dzien'] ?? '' ),
					'godzina' => sanitize_text_field( $row['godzina'] ?? '' ),
					'tresc'   => $tresc,
				);
			}
			usort(
				$clean,
				function ( $a, $b ) {
					return strcmp( $a['dzien'] . $a['godzina'], $b['dzien'] . $b['godzina'] );
				}
			);
			update_post_meta( $post_id, '_parafia_intencje', $clean );
		}
	}
);
