<?php
/**
 * Treści startowe — żeby motyw zaraz po aktywacji wyglądał jak prototyp.
 *
 * Tworzy WYŁĄCZNIE brakujące elementy (nic nie nadpisuje):
 * - strony: Msze, Transmisja, Kontakt, Sakramenty (+4 podstrony), O parafii,
 *   Historia, Grupy parafialne, Ofiara, Polityka prywatności — z treścią z prototypu,
 * - w trybie demonstracyjnym i tylko gdy dany typ treści jest pusty: przykładowe
 *   ogłoszenia, tydzień intencji i księży (oznaczone jako treść demonstracyjna).
 *
 * Uruchamia się przy aktywacji motywu oraz raz po aktualizacji motywu
 * (zmiana PARAFIA_SETUP_VERSION) przy pierwszym wejściu administratora do panelu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

const PARAFIA_SETUP_VERSION = '3';

add_action( 'after_switch_theme', 'parafia_run_setup' );

add_action(
	'admin_init',
	function () {
		if ( get_option( 'parafia_setup_version' ) !== PARAFIA_SETUP_VERSION && current_user_can( 'manage_options' ) ) {
			parafia_run_setup();
		}
	}
);

/**
 * Konfiguracja motywu: role, strony, treści przykładowe, przepisywanie adresów.
 */
function parafia_run_setup() {
	update_option( 'parafia_setup_version', PARAFIA_SETUP_VERSION );

	parafia_setup_roles();
	parafia_create_pages();

	if ( parafia_opt( 'tryb_demo' ) ) {
		parafia_create_demo_posts();
	}

	flush_rewrite_rules();
}

/* ------------------------------------------------------------ Bloki */

/**
 * Akapit bloku.
 *
 * @param string $text  Tekst (bez HTML).
 * @param string $class Klasa CSS.
 * @return string
 */
function parafia_block_p( $text, $class = '' ) {
	if ( $class ) {
		return '<!-- wp:paragraph {"className":"' . $class . '"} --><p class="' . esc_attr( $class ) . '">' . esc_html( $text ) . "</p><!-- /wp:paragraph -->\n\n";
	}
	return '<!-- wp:paragraph --><p>' . esc_html( $text ) . "</p><!-- /wp:paragraph -->\n\n";
}

/**
 * Nagłówek H2 bloku.
 *
 * @param string $text Tekst.
 * @return string
 */
function parafia_block_h2( $text ) {
	return '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html( $text ) . "</h2><!-- /wp:heading -->\n\n";
}

/**
 * Lista bloku.
 *
 * @param string[] $items Pozycje.
 * @return string
 */
function parafia_block_list( $items ) {
	$out = '<!-- wp:list --><ul class="wp-block-list">';
	foreach ( $items as $item ) {
		$out .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->';
	}
	return $out . "</ul><!-- /wp:list -->\n\n";
}

/**
 * Treść artykułu z sekcji [nagłówek, akapity, lista] — jak ARTICLES w prototypie.
 *
 * @param array  $sections Sekcje.
 * @param string $note     Notka demonstracyjna na początku.
 * @return string
 */
function parafia_article_content( $sections, $note = 'Treść demonstracyjna. Rzeczywiste zasady i wymagania podaje parafia.' ) {
	$out = $note ? parafia_block_p( $note, 'demo-note' ) : '';
	foreach ( $sections as $section ) {
		$out .= parafia_block_h2( $section[0] );
		foreach ( $section[1] as $para ) {
			$out .= parafia_block_p( $para );
		}
		if ( ! empty( $section[2] ) ) {
			$out .= parafia_block_list( $section[2] );
		}
	}
	return trim( $out );
}

/* ------------------------------------------------------------ Strony */

/**
 * Definicje stron startowych (treść z prototypu).
 *
 * @return array<string,array>
 */
function parafia_starter_pages() {
	return array(
		'msze'                          => array(
			'title'   => 'Msze Święte',
			// Zajawka strony Msze = dopisek pod dzisiejszą datą (np. wspomnienie dnia).
			'excerpt' => parafia_opt( 'tryb_demo' ) ? 'Wspomnienie dnia — treść demonstracyjna' : '',
		),
		'transmisja'                    => array(
			'title'    => 'Transmisja na żywo',
			'template' => 'template-transmisja.php',
		),
		'kontakt'                       => array(
			'title'   => 'Kontakt i kancelaria',
			'kicker'  => 'Kontakt',
			'content' => parafia_block_p( 'Kościół znajduje się przy ul. Starowiejskiej, w pobliżu centrum Andrychowa.', 'text-muted' )
				. parafia_block_p( 'Formularz kontaktowy jest w wersji demonstracyjnej wyłączony — wiadomości nie są wysyłane ani zapisywane.', 'demo-note' ),
		),
		'sakramenty'                    => array(
			'title'   => 'Sakramenty',
			'kicker'  => 'Sakramenty',
			'excerpt' => 'Sakramenty zgłaszamy w kancelarii parafialnej. Wybierz sakrament, aby zobaczyć wymagane dokumenty i terminy.',
			'content' => parafia_block_list( array( 'Chrzest', 'Małżeństwo', 'Odwiedziny chorych', 'Pogrzeb' ) ),
		),
		'sakramenty/chrzest'            => array(
			'title'   => 'Chrzest',
			'parent'  => 'sakramenty',
			'order'   => 1,
			'excerpt' => 'Chrzest zgłaszamy w kancelarii parafialnej — poniżej praktyczne informacje o dokumentach i przygotowaniu.',
			'content' => parafia_article_content(
				array(
					array( 'Wymagane dokumenty', array(), array( 'akt urodzenia dziecka z USC (do wglądu)', 'dane rodziców chrzestnych: imię, nazwisko, wiek, adres', 'zaświadczenie z parafii chrzestnych, że mogą pełnić tę funkcję' ) ),
					array( 'Kiedy zgłosić', array( 'Zgłoszenia przyjmujemy w godzinach pracy kancelarii, najlepiej na dwa tygodnie przed planowaną datą.' ) ),
					array( 'Przygotowanie', array( 'Przed chrztem odbywa się spotkanie dla rodziców i chrzestnych. Termin spotkania podawany jest przy zgłoszeniu.' ) ),
				)
			),
		),
		'sakramenty/malzenstwo'         => array(
			'title'   => 'Małżeństwo',
			'parent'  => 'sakramenty',
			'order'   => 2,
			'excerpt' => 'Formalności związane z zawarciem małżeństwa rozpoczynamy w kancelarii parafialnej z odpowiednim wyprzedzeniem.',
			'content' => parafia_article_content(
				array(
					array( 'Wymagane dokumenty', array(), array( 'aktualne świadectwa chrztu (wydane nie wcześniej niż 3 miesiące przed ślubem)', 'dowody osobiste', 'zaświadczenie o ukończeniu nauk przedmałżeńskich', 'zaświadczenie z USC (przy ślubie konkordatowym)' ) ),
					array( 'Kiedy zgłosić', array( 'Termin ślubu rezerwujemy z wyprzedzeniem; protokół przedmałżeński spisujemy zwykle na około trzy miesiące przed ceremonią.' ) ),
					array( 'Przygotowanie', array( 'Narzeczeni odbywają nauki przedmałżeńskie oraz spotkania w poradni rodzinnej. Terminy podaje kancelaria.' ) ),
				)
			),
		),
		'sakramenty/odwiedziny-chorych' => array(
			'title'   => 'Odwiedziny chorych',
			'parent'  => 'sakramenty',
			'order'   => 3,
			'excerpt' => 'Kapłani odwiedzają chorych z posługą sakramentalną regularnie oraz na każde wezwanie.',
			'content' => parafia_article_content(
				array(
					array( 'Odwiedziny stałe', array( 'Chorych zgłoszonych w kancelarii odwiedzamy w wyznaczone dni miesiąca.' ) ),
					array( 'Wezwanie do chorego', array( 'W nagłych przypadkach prosimy o telefon do kancelarii parafialnej — kapłan przyjeżdża o każdej porze.' ) ),
					array( 'Przygotowanie mieszkania', array(), array( 'stół nakryty białym obrusem', 'krzyż i zapalona świeca', 'szklanka wody' ) ),
				)
			),
		),
		'sakramenty/pogrzeb'            => array(
			'title'   => 'Pogrzeb',
			'parent'  => 'sakramenty',
			'order'   => 4,
			'excerpt' => 'Pogrzeb zgłasza rodzina w kancelarii parafialnej; termin ustalamy wspólnie z zakładem pogrzebowym.',
			'content' => parafia_article_content(
				array(
					array( 'Wymagane dokumenty', array(), array( 'akt zgonu z USC', 'zaświadczenie o przyjęciu sakramentów (jeśli zgon nastąpił w szpitalu lub hospicjum)', 'zgoda parafii zamieszkania, jeśli zmarły należał do innej parafii' ) ),
					array( 'Zgłoszenie', array( 'Zgłoszenia przyjmujemy w kancelarii; w sprawach pilnych prosimy o kontakt telefoniczny.' ) ),
					array( 'Modlitwa za zmarłych', array( 'Rodzina może zamówić Mszę Świętą w intencji zmarłego — intencje przyjmuje kancelaria.' ) ),
				)
			),
		),
		'o-parafii'                     => array(
			'title'   => 'O parafii',
			'kicker'  => 'Parafia',
			'image'   => array( 'kosciol-zachod.jpg', 'Kościół św. Stanisława w Andrychowie z lotu ptaka o zachodzie słońca — wieża, trójkątny szczyt z witrażami i plac przed wejściem' ),
			'excerpt' => 'Parafia św. Stanisława Biskupa i Męczennika w Andrychowie należy do diecezji bielsko-żywieckiej, dekanat andrychowski.',
			'content' => parafia_article_content(
				array(
					array( 'Kościół parafialny', array( 'Kościół znajduje się przy ul. Starowiejskiej 30 w Andrychowie. Opis architektury i wyposażenia uzupełni parafia.' ) ),
					array( 'Życie parafialne', array( 'Msze Święte, nabożeństwa, grupy duszpasterskie i dzieła charytatywne — szczegóły w pozostałych działach serwisu.' ) ),
				)
			),
		),
		'historia'                      => array(
			'title'   => 'Historia parafii',
			'kicker'  => 'Parafia',
			'excerpt' => 'Rys historyczny parafii zostanie uzupełniony treścią przekazaną przez parafię.',
			'content' => parafia_article_content(
				array(
					array( 'Kalendarium', array( 'W tym miejscu prezentowane będzie kalendarium: erygowanie parafii, budowa i konsekracja kościoła, ważne wydarzenia i wizytacje.' ) ),
					array( 'Materiały archiwalne', array( 'Kroniki, fotografie i dokumenty można publikować jako galerie oraz pliki do pobrania.' ) ),
				)
			),
		),
		'grupy-parafialne'              => array(
			'title'   => 'Grupy parafialne',
			'kicker'  => 'Parafia',
			'excerpt' => 'Wykaz wspólnot działających przy parafii — treść demonstracyjna, do uzupełnienia przez parafię.',
			'content' => parafia_article_content(
				array(
					array( 'Przykładowe wspólnoty', array(), array( 'Liturgiczna Służba Ołtarza', 'Schola i chór parafialny', 'Żywy Różaniec', 'Caritas parafialna', 'Grupa młodzieżowa' ) ),
					array( 'Jak dołączyć', array( 'Przy każdej wspólnocie można podać opiekuna, miejsce i godzinę spotkań oraz kontakt.' ) ),
				)
			),
		),
		'ofiara'                        => array(
			'title'          => 'Wsparcie parafii',
			'kicker'         => 'Ofiara na kościół',
			'bez_kancelarii' => true,
			'content'        => parafia_block_p( 'Funkcja demonstracyjna. Płatności nie są obsługiwane, a formularz nie przyjmuje danych.', 'demo-note' )
				. "<!-- wp:html -->\n" . '<div class="card" style="margin-top:var(--space-8);gap:var(--space-4);opacity:0.75"><h2 class="card-title" style="font-size:24px">Ofiara online — podgląd</h2><div class="seg" role="group" aria-label="Kwota (demonstracja)"><label class="seg-opt"><input type="radio" name="kwota" disabled="disabled" />50 zł</label><label class="seg-opt"><input type="radio" name="kwota" disabled="disabled" />100 zł</label><label class="seg-opt"><input type="radio" name="kwota" disabled="disabled" />200 zł</label></div><button class="btn btn-primary" type="button" disabled="disabled">Przekaż ofiarę (nieaktywne)</button></div>' . "\n<!-- /wp:html -->\n\n"
				. parafia_block_h2( 'Przelew tradycyjny' )
				. parafia_block_p( 'Numer rachunku parafii nie został podany do wersji demonstracyjnej.' ),
		),
		'polityka-prywatnosci'          => array(
			'title'          => 'Polityka prywatności i pliki cookie',
			'kicker'         => 'Informacje',
			'bez_kancelarii' => true,
			'content'        => parafia_block_p( 'Dokument demonstracyjny — wersja produkcyjna wymaga treści zatwierdzonej przez parafię i inspektora ochrony danych diecezji.', 'demo-note' )
				. parafia_block_h2( 'Zakres' )
				. parafia_block_p( 'Serwis demonstracyjny nie zbiera danych osobowych, nie posiada formularzy przyjmujących zgłoszenia ani systemu płatności. Nie korzysta z narzędzi analitycznych ani reklamowych.' )
				. parafia_block_h2( 'Treści zewnętrzne' )
				. parafia_block_p( 'Mapa oraz odtwarzacz transmisji pochodzą z serwisów zewnętrznych i są ładowane wyłącznie po świadomym kliknięciu użytkownika. Do tego momentu żadne zapytania do tych dostawców nie są wysyłane.' ),
		),
	);
}

/**
 * Tworzy brakujące strony.
 */
function parafia_create_pages() {
	foreach ( parafia_starter_pages() as $path => $def ) {
		if ( get_page_by_path( $path ) ) {
			continue;
		}
		// Transmisja może już istnieć pod innym adresem — wtedy jej nie dublujemy.
		if ( ! empty( $def['template'] ) && get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => $def['template'], 'number' => 1 ) ) ) {
			continue;
		}

		$parent_id = 0;
		if ( ! empty( $def['parent'] ) ) {
			$parent = get_page_by_path( $def['parent'] );
			if ( ! $parent ) {
				continue;
			}
			$parent_id = $parent->ID;
		}

		$slug = basename( $path );
		$id   = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $def['title'],
				'post_name'    => $slug,
				'post_parent'  => $parent_id,
				'menu_order'   => $def['order'] ?? 0,
				'post_excerpt' => $def['excerpt'] ?? '',
				'post_content' => $def['content'] ?? '',
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}

		if ( ! empty( $def['template'] ) ) {
			update_post_meta( $id, '_wp_page_template', $def['template'] );
		}
		if ( ! empty( $def['kicker'] ) ) {
			update_post_meta( $id, '_parafia_kicker', $def['kicker'] );
		}
		if ( ! empty( $def['bez_kancelarii'] ) ) {
			update_post_meta( $id, '_parafia_bez_kancelarii', '1' );
		}
		if ( ! empty( $def['image'] ) ) {
			$att = parafia_import_theme_image( $def['image'][0], $def['image'][1], $id );
			if ( $att ) {
				set_post_thumbnail( $id, $att );
			}
		}
	}
}

/**
 * Kopiuje zdjęcie z katalogu motywu do biblioteki mediów.
 *
 * @param string $file   Nazwa pliku w assets/img.
 * @param string $alt    Tekst alternatywny.
 * @param int    $parent ID wpisu nadrzędnego.
 * @return int ID załącznika albo 0.
 */
function parafia_import_theme_image( $file, $alt, $parent = 0 ) {
	$src = PARAFIA_DIR . '/assets/img/' . $file;
	if ( ! is_readable( $src ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $file );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), $parent, $alt );
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	return (int) $id;
}

/* ------------------------------------------------------------ Treści demonstracyjne */

/**
 * Czy typ treści ma już jakiekolwiek wpisy (w dowolnym stanie).
 *
 * @param string $type Typ treści.
 * @return bool
 */
function parafia_has_posts( $type ) {
	return (bool) get_posts( array( 'post_type' => $type, 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
}

/**
 * Przykładowe ogłoszenia, tydzień intencji i księża — tylko gdy dany typ jest pusty.
 */
function parafia_create_demo_posts() {
	$now = current_datetime();

	if ( ! parafia_has_posts( 'aktualnosc' ) ) {
		$news = array(
			array( 'Przykładowy tytuł ogłoszenia parafialnego', 'Krótki zajawkowy fragment ogłoszenia parafialnego — treść demonstracyjna.', 0 ),
			array( 'Drugi przykładowy wpis', 'Treść demonstracyjna.', 6 ),
		);
		foreach ( $news as $n ) {
			wp_insert_post(
				array(
					'post_type'    => 'aktualnosc',
					'post_status'  => 'publish',
					'post_title'   => $n[0],
					'post_excerpt' => $n[1],
					'post_content' => parafia_block_p( $n[1] ) . parafia_block_p( 'Treść demonstracyjna.', 'demo-note' ),
					'post_date'    => $now->modify( '-' . $n[2] . ' days' )->format( 'Y-m-d H:i:s' ),
				)
			);
		}
	}

	if ( ! parafia_has_posts( 'intencja' ) ) {
		$monday = $now->modify( 'monday this week' );
		$sunday = $monday->modify( '+6 days' );
		$mies   = array( 1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia' );
		$title  = sprintf( '%d %s – %d %s %d', (int) $monday->format( 'j' ), $mies[ (int) $monday->format( 'n' ) ], (int) $sunday->format( 'j' ), $mies[ (int) $sunday->format( 'n' ) ], (int) $sunday->format( 'Y' ) );
		$text   = 'Intencja demonstracyjna — treść przykładowa';
		$rows   = array();
		foreach ( array( 0 => array( '06:30', '07:00', '18:00' ), 1 => array( '06:30', '07:00', '18:00' ), 6 => array( '06:30', '08:30', '10:00', '11:30', '13:00', '18:00' ) ) as $offset => $times ) {
			foreach ( $times as $time ) {
				$rows[] = array( 'dzien' => $monday->modify( '+' . $offset . ' days' )->format( 'Y-m-d' ), 'godzina' => $time, 'tresc' => $text );
			}
		}
		$id = wp_insert_post( array( 'post_type' => 'intencja', 'post_status' => 'publish', 'post_title' => $title ) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_parafia_intencje', $rows );
		}
	}

	if ( ! parafia_has_posts( 'galeria' ) ) {
		// Galerie bez zdjęć — archiwum pokazuje w ich miejscu zastępniki, jak prototyp.
		foreach ( array( array( 'Remont kościoła · przykładowa galeria', 3, 1 ), array( 'Odpust parafialny · przykładowa galeria', 6, 0 ) ) as $g ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'galeria',
					'post_status'  => 'publish',
					'post_title'   => $g[0],
					'post_content' => parafia_block_p( 'Treść demonstracyjna — zdjęcia dodaje się blokiem Galeria.', 'demo-note' ),
					'post_date'    => $now->modify( '-' . $g[2] . ' days' )->format( 'Y-m-d H:i:s' ),
				)
			);
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_parafia_demo_miejsca', $g[1] );
			}
		}
	}

	if ( ! parafia_has_posts( 'ksiadz' ) ) {
		foreach ( array( 'Proboszcz', 'Wikariusz', 'Wikariusz', 'Rezydent', 'Rezydent', 'Wikariusz' ) as $i => $rola ) {
			$id = wp_insert_post( array( 'post_type' => 'ksiadz', 'post_status' => 'publish', 'post_title' => 'Imię i nazwisko', 'post_content' => 'Dane demonstracyjne', 'menu_order' => $i ) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_parafia_rola', $rola );
			}
		}
		foreach ( array( 1974, 1981, 1987, 1996, 2004, 2015 ) as $rok ) {
			$id = wp_insert_post( array( 'post_type' => 'ksiadz', 'post_status' => 'publish', 'post_title' => 'Imię i nazwisko' ) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_parafia_rodzic', 'rodak' );
				update_post_meta( $id, '_parafia_rok_swiecen', $rok );
			}
		}
	}
}
