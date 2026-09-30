<?php
/**
 * Ekran „Parafia” — ustawienia globalne: msze, kancelaria, kontakt, transmisja.
 * Jedna opcja tablicowa, Settings API, pełna sanityzacja.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

const PARAFIA_OPTION = 'parafia_ustawienia';

/**
 * Domyślne wartości. Godziny Mszy zgodne z informacjami przekazanymi przez parafię.
 *
 * @return array
 */
function parafia_defaults() {
	return array(
		'msze_niedziela'         => '6:30, 8:30, 10:00, 11:30, 13:00, 18:00',
		'msze_niedziela_lato'    => '6:30, 8:30, 10:00, 11:30, 20:00',
		'msze_swieta_zniesione'  => '6:30, 8:30, 16:30, 18:00',
		'msze_powszednie'        => '6:30, 7:00, 18:00',
		'msze_adwent'            => '6:30, 18:00',
		'msze_powszednie_lato'   => '6:30, 18:00',
		'kancelaria_godziny'     => "Poniedziałek – sobota: 8:00 – 9:00\nWtorek, środa, czwartek: 16:00 – 17:30",
		'kancelaria_nieczynna'   => "w pierwszy czwartek miesiąca\nw niedziele i święta przypadające w tygodniu\nw dniu spowiedzi parafialnej",
		'adres'                  => "ul. Starowiejska 30\n34-120 Andrychów",
		'telefon'                => '33 875 33 77',
		'email'                  => '',
		'mapa_url'               => '',
		'transmisja_embed'       => '',
		'transmisja_opis'        => 'Transmisja Mszy Świętej z kościoła parafialnego.',
		'tryb_demo'              => 1,
	);
}

/**
 * Odczyt pojedynczego ustawienia.
 *
 * @param string $key Klucz.
 * @return mixed
 */
function parafia_opt( $key ) {
	$all = wp_parse_args( (array) get_option( PARAFIA_OPTION, array() ), parafia_defaults() );
	return $all[ $key ] ?? '';
}

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Parafia', 'parafia' ),
			__( 'Parafia', 'parafia' ),
			'manage_parafia',
			'parafia',
			'parafia_settings_page',
			'dashicons-bank',
			3
		);
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'parafia',
			PARAFIA_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'parafia_sanitize_settings',
				'default'           => parafia_defaults(),
			)
		);
	}
);

/**
 * Sanityzacja wszystkich ustawień.
 *
 * @param array $input Dane z formularza.
 * @return array
 */
function parafia_sanitize_settings( $input ) {
	$input = (array) $input;
	$out   = parafia_defaults();

	foreach ( array( 'msze_niedziela', 'msze_niedziela_lato', 'msze_swieta_zniesione', 'msze_powszednie', 'msze_adwent', 'msze_powszednie_lato', 'telefon', 'transmisja_opis' ) as $k ) {
		$out[ $k ] = sanitize_text_field( $input[ $k ] ?? $out[ $k ] );
	}
	foreach ( array( 'kancelaria_godziny', 'kancelaria_nieczynna', 'adres' ) as $k ) {
		$out[ $k ] = sanitize_textarea_field( $input[ $k ] ?? $out[ $k ] );
	}
	$out['email']    = sanitize_email( $input['email'] ?? '' );
	$out['mapa_url'] = esc_url_raw( $input['mapa_url'] ?? '' );

	// Transmisja: przyjmujemy wyłącznie publiczny URL osadzenia z listy dozwolonych hostów.
	$out['transmisja_embed'] = parafia_sanitize_embed( $input['transmisja_embed'] ?? '' );
	$out['tryb_demo']        = empty( $input['tryb_demo'] ) ? 0 : 1;

	return $out;
}

/**
 * Dopuszczamy tylko publiczne adresy osadzenia znanych dostawców — nigdy dowolny HTML,
 * nigdy tokeny/klucze prywatne.
 *
 * @param string $url Adres podany przez administratora.
 * @return string
 */
function parafia_sanitize_embed( $url ) {
	$url = esc_url_raw( trim( (string) $url ) );
	if ( '' === $url ) {
		return '';
	}
	$host    = wp_parse_url( $url, PHP_URL_HOST );
	$allowed = apply_filters(
		'parafia_allowed_stream_hosts',
		array( 'www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'youtube-nocookie.com', 'player.vimeo.com' )
	);
	if ( ! $host || ! in_array( strtolower( $host ), $allowed, true ) ) {
		add_settings_error( PARAFIA_OPTION, 'embed', __( 'Adres transmisji musi pochodzić z dozwolonego dostawcy (YouTube lub Vimeo).', 'parafia' ) );
		return '';
	}
	if ( 'https' !== wp_parse_url( $url, PHP_URL_SCHEME ) ) {
		return '';
	}
	return $url;
}

/**
 * Ekran ustawień.
 */
function parafia_settings_page() {
	if ( ! current_user_can( 'manage_parafia' ) ) {
		wp_die( esc_html__( 'Brak uprawnień.', 'parafia' ) );
	}
	$o = wp_parse_args( (array) get_option( PARAFIA_OPTION, array() ), parafia_defaults() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ustawienia parafii', 'parafia' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'parafia' ); ?>

			<h2><?php esc_html_e( 'Porządek Mszy Świętych', 'parafia' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Godziny oddzielaj przecinkami, np. 6:30, 7:00, 18:00.', 'parafia' ); ?></p>
			<table class="form-table" role="presentation">
				<?php
				$msze = array(
					'msze_niedziela'        => __( 'Niedziele', 'parafia' ),
					'msze_niedziela_lato'   => __( 'Niedziele — lipiec i sierpień', 'parafia' ),
					'msze_swieta_zniesione' => __( 'Święta zniesione', 'parafia' ),
					'msze_powszednie'       => __( 'Dni powszednie', 'parafia' ),
					'msze_adwent'           => __( 'Adwent', 'parafia' ),
					'msze_powszednie_lato'  => __( 'Dni powszednie — lipiec i sierpień', 'parafia' ),
				);
				foreach ( $msze as $key => $label ) :
					?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
						<td><input class="regular-text" type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $o[ $key ] ); ?>"></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h2><?php esc_html_e( 'Kancelaria parafialna', 'parafia' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="kancelaria_godziny"><?php esc_html_e( 'Godziny otwarcia', 'parafia' ); ?></label></th>
					<td><textarea id="kancelaria_godziny" rows="4" class="large-text" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[kancelaria_godziny]"><?php echo esc_textarea( $o['kancelaria_godziny'] ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Jedna pozycja w wierszu.', 'parafia' ); ?></p></td>
				</tr>
				<tr>
					<th scope="row"><label for="kancelaria_nieczynna"><?php esc_html_e( 'Kancelaria nieczynna', 'parafia' ); ?></label></th>
					<td><textarea id="kancelaria_nieczynna" rows="4" class="large-text" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[kancelaria_nieczynna]"><?php echo esc_textarea( $o['kancelaria_nieczynna'] ); ?></textarea></td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'Kontakt', 'parafia' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><label for="adres"><?php esc_html_e( 'Adres', 'parafia' ); ?></label></th>
					<td><textarea id="adres" rows="3" class="large-text" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[adres]"><?php echo esc_textarea( $o['adres'] ); ?></textarea></td></tr>
				<tr><th scope="row"><label for="telefon"><?php esc_html_e( 'Telefon', 'parafia' ); ?></label></th>
					<td><input class="regular-text" type="text" id="telefon" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[telefon]" value="<?php echo esc_attr( $o['telefon'] ); ?>"></td></tr>
				<tr><th scope="row"><label for="email"><?php esc_html_e( 'E-mail', 'parafia' ); ?></label></th>
					<td><input class="regular-text" type="email" id="email" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[email]" value="<?php echo esc_attr( $o['email'] ); ?>"></td></tr>
				<tr><th scope="row"><label for="mapa_url"><?php esc_html_e( 'Adres mapy (osadzenie)', 'parafia' ); ?></label></th>
					<td><input class="large-text" type="url" id="mapa_url" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[mapa_url]" value="<?php echo esc_attr( $o['mapa_url'] ); ?>">
					<p class="description"><?php esc_html_e( 'Mapa ładuje się dopiero po kliknięciu użytkownika (RODO / cookies).', 'parafia' ); ?></p></td></tr>
			</table>

			<h2><?php esc_html_e( 'Transmisja na żywo', 'parafia' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><label for="transmisja_embed"><?php esc_html_e( 'Publiczny adres osadzenia', 'parafia' ); ?></label></th>
					<td><input class="large-text" type="url" id="transmisja_embed" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[transmisja_embed]" value="<?php echo esc_attr( $o['transmisja_embed'] ); ?>" placeholder="https://www.youtube-nocookie.com/embed/...">
					<p class="description"><?php esc_html_e( 'Wyłącznie publiczny adres osadzenia (YouTube / Vimeo). Nie wpisuj tu kluczy transmisji ani danych logowania. Puste pole = komunikat „Transmisja jest obecnie niedostępna”.', 'parafia' ); ?></p></td></tr>
				<tr><th scope="row"><label for="transmisja_opis"><?php esc_html_e( 'Zdanie pod nagłówkiem', 'parafia' ); ?></label></th>
					<td><input class="large-text" type="text" id="transmisja_opis" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[transmisja_opis]" value="<?php echo esc_attr( $o['transmisja_opis'] ); ?>"></td></tr>
			</table>

			<h2><?php esc_html_e( 'Tryb demonstracyjny', 'parafia' ); ?></h2>
			<label><input type="checkbox" name="<?php echo esc_attr( PARAFIA_OPTION ); ?>[tryb_demo]" value="1" <?php checked( $o['tryb_demo'], 1 ); ?>>
			<?php esc_html_e( 'Pokaż pasek „Wersja demonstracyjna” i wymuś noindex/nofollow.', 'parafia' ); ?></label>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
