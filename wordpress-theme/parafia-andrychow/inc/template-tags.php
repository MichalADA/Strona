<?php
/**
 * Helpery szablonów.
 *
 * Markup generowany tutaj odwzorowuje 1:1 prototyp „Parafia św. Stanisława - zieleń.dc.html”
 * (te same klasy, ta sama kolejność elementów), bo cała warstwa wizualna
 * (assets/css/theme.css) jest kopią arkuszy prototypu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------ Ogólne */

/**
 * Pasek trybu demonstracyjnego.
 */
function parafia_demo_bar() {
	if ( ! parafia_opt( 'tryb_demo' ) ) {
		return;
	}
	?>
	<div class="demobar" role="note"><strong><?php esc_html_e( 'Wersja demonstracyjna', 'parafia' ); ?></strong> — <?php esc_html_e( 'projekt nowej strony parafii. To nie jest oficjalny serwis parafialny.', 'parafia' ); ?></div>
	<?php
}

/**
 * Adres pliku z katalogu assets/img motywu.
 *
 * @param string $file Nazwa pliku.
 * @return string
 */
function parafia_img( $file ) {
	return PARAFIA_URI . '/assets/img/' . $file;
}

/**
 * Adres strony WordPressa po ścieżce (np. „sakramenty/chrzest”).
 * Gdy strona jeszcze nie istnieje — przewidywany adres, żeby odnośniki nie znikały.
 *
 * @param string $path Ścieżka strony.
 * @return string
 */
function parafia_page_url( $path ) {
	static $cache = array();
	if ( isset( $cache[ $path ] ) ) {
		return $cache[ $path ];
	}

	$page = get_page_by_path( $path );
	if ( $page && 'publish' === $page->post_status ) {
		return $cache[ $path ] = get_permalink( $page );
	}

	// Transmisja: strona może mieć inny adres, ale zawsze ma przypisany szablon.
	if ( 'transmisja' === $path ) {
		$pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'template-transmisja.php',
				'number'     => 1,
			)
		);
		if ( $pages ) {
			return $cache[ $path ] = get_permalink( $pages[0] );
		}
	}

	if ( 'polityka-prywatnosci' === $path && get_privacy_policy_url() ) {
		return $cache[ $path ] = get_privacy_policy_url();
	}

	return $cache[ $path ] = home_url( user_trailingslashit( $path ) );
}

/**
 * Adres archiwum typu treści z bezpiecznym zapasem.
 *
 * @param string $post_type Typ treści.
 * @param string $fallback  Ścieżka zapasowa.
 * @return string
 */
function parafia_archive_url( $post_type, $fallback ) {
	$url = get_post_type_archive_link( $post_type );
	return $url ? $url : home_url( user_trailingslashit( $fallback ) );
}

/**
 * Pierwsza linia adresu (np. „ul. Starowiejska 30”).
 *
 * @return string
 */
function parafia_address_first_line() {
	$lines = preg_split( '/\r\n|\r|\n/', (string) parafia_opt( 'adres' ) );
	return trim( (string) ( $lines[0] ?? '' ) );
}

/**
 * Link tel: z numeru zapisanego w ustawieniach.
 *
 * @param string $tel Numer.
 * @return string
 */
function parafia_tel_href( $tel ) {
	$digits = preg_replace( '/[^0-9+]/', '', (string) $tel );
	if ( '' === $digits ) {
		return '';
	}
	if ( '+' !== $digits[0] && 9 === strlen( $digits ) ) {
		$digits = '+48' . $digits;
	}
	return 'tel:' . $digits;
}

/**
 * Telefon parafii jako odnośnik (z prefiksem „tel.”), albo pusty ciąg.
 */
function parafia_the_phone() {
	$tel = parafia_opt( 'telefon' );
	if ( ! $tel ) {
		return;
	}
	printf(
		'%1$s <a href="%2$s">%3$s</a>',
		esc_html__( 'tel.', 'parafia' ),
		esc_url( parafia_tel_href( $tel ), array( 'tel' ) ),
		esc_html( $tel )
	);
}

/**
 * Adres parafii jako HTML: linie rozdzielone <br />.
 */
function parafia_the_address_lines() {
	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) parafia_opt( 'adres' ) ) ), 'strlen' );
	echo implode( '<br />', array_map( 'esc_html', $lines ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- każda linia escapowana.
}

/**
 * Nazwa miesiąca w dopełniaczu i dnia tygodnia — bez zależności od pakietu językowego.
 *
 * @param string $ymd Data Y-m-d.
 * @return string np. „Poniedziałek, 31 sierpnia”.
 */
function parafia_format_day( $ymd ) {
	$ts = strtotime( (string) $ymd );
	if ( ! $ts ) {
		return (string) $ymd;
	}
	$dni  = array( 'Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota' );
	$mies = array( 1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia' );
	return sprintf( '%s, %d %s', $dni[ (int) gmdate( 'w', $ts ) ], (int) gmdate( 'j', $ts ), $mies[ (int) gmdate( 'n', $ts ) ] );
}

/**
 * Data wpisu w formacie prototypu: „30 sierpnia 2026”.
 *
 * @param int|WP_Post|null $post Wpis.
 * @return string
 */
function parafia_post_date( $post = null ) {
	$mies = array( 1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia' );
	$d    = get_post_datetime( $post );
	if ( ! $d ) {
		return '';
	}
	return sprintf( '%d %s %d', (int) $d->format( 'j' ), $mies[ (int) $d->format( 'n' ) ], (int) $d->format( 'Y' ) );
}

/**
 * Ikony SVG z prototypu.
 *
 * @param string $name Nazwa ikony.
 * @return string
 */
function parafia_icon( $name ) {
	$icons = array(
		'chevron' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"></path></svg>',
		'camera'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><path d="m16 13 5.2 3.1a1 1 0 0 0 1.5-.9V8.8a1 1 0 0 0-1.5-.9L16 11"></path><rect x="1.5" y="5.5" width="14.5" height="13" rx="2"></rect></svg>',
		'burger'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18"></path></svg>',
		'live'    => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke-width="1.6" aria-hidden="true" focusable="false"><path d="m16 13 5.2 3.1a1 1 0 0 0 1.5-.9V8.8a1 1 0 0 0-1.5-.9L16 11"></path><rect x="1.5" y="5.5" width="14.5" height="13" rx="2"></rect></svg>',
		'arrow'   => '<svg class="live-arrow" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="1.6" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>',
		'clock'   => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="1.6" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>',
		'calendar'=> '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="1.6" aria-hidden="true" focusable="false"><rect x="3" y="4.5" width="18" height="16" rx="2"></rect><path d="M3 9.5h18M8 2.5v4M16 2.5v4"></path></svg>',
		'megaphone' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="1.6" aria-hidden="true" focusable="false"><path d="M4 8h3l9-4.5v17L7 16H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"></path><path d="M19 9.5a4 4 0 0 1 0 5"></path></svg>',
		'phone'   => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="1.6" aria-hidden="true" focusable="false"><path d="M15.5 21a13 13 0 0 1-12.5-12.5 2 2 0 0 1 2-2.5h2.5a1 1 0 0 1 1 .9c.15 1.1.4 2.1.8 3a1 1 0 0 1-.3 1.2l-1.1.9a12 12 0 0 0 5 5l.9-1.1a1 1 0 0 1 1.2-.3c.9.4 1.9.65 3 .8a1 1 0 0 1 .9 1V19a2 2 0 0 1-2.4 2Z"></path></svg>',
		'play'    => '<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" style="margin:0 auto 14px" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10.5"></circle><path d="M10 8.5l6 3.5-6 3.5z"></path></svg>',
	);
	return $icons[ $name ] ?? '';
}

/* ------------------------------------------------------------ Nawigacja */

/**
 * Czy adres wskazuje bieżącą stronę (porównanie ścieżek).
 *
 * @param string $url Adres.
 * @return bool
 */
function parafia_is_current_url( $url ) {
	static $current = null;
	if ( null === $current ) {
		$uri     = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$current = untrailingslashit( (string) wp_parse_url( $uri, PHP_URL_PATH ) );
	}
	$path = untrailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	return $path === $current;
}

/**
 * Domyślne menu — dokładnie te pozycje, które ma prototyp. Używane, dopóki
 * parafia nie przypisze własnego menu w Wygląd → Menu.
 *
 * @param string $location Lokalizacja menu.
 * @return array
 */
function parafia_default_menu( $location ) {
	$sakramenty = array(
		array( 'title' => __( 'Chrzest', 'parafia' ), 'url' => parafia_page_url( 'sakramenty/chrzest' ) ),
		array( 'title' => __( 'Małżeństwo', 'parafia' ), 'url' => parafia_page_url( 'sakramenty/malzenstwo' ) ),
		array( 'title' => __( 'Odwiedziny chorych', 'parafia' ), 'url' => parafia_page_url( 'sakramenty/odwiedziny-chorych' ) ),
		array( 'title' => __( 'Pogrzeb', 'parafia' ), 'url' => parafia_page_url( 'sakramenty/pogrzeb' ) ),
	);
	$parafia    = array(
		array( 'title' => __( 'O parafii', 'parafia' ), 'url' => parafia_page_url( 'o-parafii' ) ),
		array( 'title' => __( 'Historia', 'parafia' ), 'url' => parafia_page_url( 'historia' ) ),
		array( 'title' => __( 'Nasi księża', 'parafia' ), 'url' => parafia_archive_url( 'ksiadz', 'ksieza' ) ),
		array( 'title' => __( 'Grupy parafialne', 'parafia' ), 'url' => parafia_page_url( 'grupy-parafialne' ) ),
		array( 'title' => __( 'Galeria', 'parafia' ), 'url' => parafia_archive_url( 'galeria', 'galeria' ) ),
	);

	switch ( $location ) {
		case 'primary':
			return array(
				array( 'title' => __( 'Ogłoszenia', 'parafia' ), 'url' => parafia_archive_url( 'aktualnosc', 'aktualnosci' ) ),
				array( 'title' => __( 'Intencje', 'parafia' ), 'url' => parafia_archive_url( 'intencja', 'intencje' ) ),
				array( 'title' => __( 'Sakramenty', 'parafia' ), 'url' => $sakramenty[0]['url'], 'children' => $sakramenty ),
				array( 'title' => __( 'Parafia', 'parafia' ), 'url' => $parafia[0]['url'], 'children' => $parafia ),
				array( 'title' => __( 'Kontakt', 'parafia' ), 'url' => parafia_page_url( 'kontakt' ) ),
			);

		case 'mobile':
			$items = array(
				array( 'title' => __( 'Strona główna', 'parafia' ), 'url' => home_url( '/' ) ),
				array( 'title' => __( 'Ogłoszenia parafialne', 'parafia' ), 'url' => parafia_archive_url( 'aktualnosc', 'aktualnosci' ) ),
				array( 'title' => __( 'Intencje mszalne', 'parafia' ), 'url' => parafia_archive_url( 'intencja', 'intencje' ) ),
				array( 'title' => __( 'Porządek Mszy Świętych', 'parafia' ), 'url' => parafia_page_url( 'msze' ) ),
				array( 'title' => __( 'Transmisja na żywo', 'parafia' ), 'url' => parafia_page_url( 'transmisja' ) ),
			);
			foreach ( array_merge( $sakramenty, $parafia ) as $sub ) {
				$items[] = $sub + array( 'sub' => true );
			}
			$items[] = array( 'title' => __( 'Kontakt', 'parafia' ), 'url' => parafia_page_url( 'kontakt' ) );
			return $items;

		case 'footer':
			return array(
				array( 'title' => __( 'Msze Święte', 'parafia' ), 'url' => parafia_page_url( 'msze' ) ),
				array( 'title' => __( 'Intencje mszalne', 'parafia' ), 'url' => parafia_archive_url( 'intencja', 'intencje' ) ),
				array( 'title' => __( 'Ogłoszenia', 'parafia' ), 'url' => parafia_archive_url( 'aktualnosc', 'aktualnosci' ) ),
				array( 'title' => __( 'Transmisja na żywo', 'parafia' ), 'url' => parafia_page_url( 'transmisja' ) ),
			);

		case 'footer_info':
			return array(
				array( 'title' => __( 'O parafii', 'parafia' ), 'url' => parafia_page_url( 'o-parafii' ) ),
				array( 'title' => __( 'Sakramenty', 'parafia' ), 'url' => $sakramenty[0]['url'] ),
				array( 'title' => __( 'Kontakt', 'parafia' ), 'url' => parafia_page_url( 'kontakt' ) ),
				array( 'title' => __( 'Polityka prywatności', 'parafia' ), 'url' => parafia_page_url( 'polityka-prywatnosci' ) ),
			);
	}
	return array();
}

/**
 * Drzewo menu (dwa poziomy) z przypisanego menu WordPressa albo null.
 *
 * @param string $location Lokalizacja menu.
 * @return array|null
 */
function parafia_assigned_menu( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return null;
	}
	$items = wp_get_nav_menu_items( $locations[ $location ], array( 'update_post_term_cache' => false ) );
	if ( ! $items ) {
		return null;
	}

	$tree = array();
	$refs = array();
	foreach ( $items as $item ) {
		$node = array(
			'title'    => $item->title,
			'url'      => $item->url,
			'children' => array(),
		);
		if ( ! $item->menu_item_parent ) {
			$tree[]                = $node;
			$refs[ (int) $item->ID ] = count( $tree ) - 1;
		} elseif ( isset( $refs[ (int) $item->menu_item_parent ] ) ) {
			$tree[ $refs[ (int) $item->menu_item_parent ] ]['children'][] = $node;
		}
	}
	return $tree;
}

/**
 * Pozycje menu dla lokalizacji: przypisane menu albo domyślne z prototypu.
 *
 * @param string $location Lokalizacja.
 * @return array
 */
function parafia_menu( $location ) {
	$assigned = parafia_assigned_menu( $location );
	if ( null !== $assigned ) {
		return $assigned;
	}
	// Menu mobilne bez własnego przypisania: spłaszczone menu główne, jeśli istnieje.
	if ( 'mobile' === $location ) {
		$primary = parafia_assigned_menu( 'primary' );
		if ( null !== $primary ) {
			return $primary;
		}
	}
	return parafia_default_menu( $location );
}

/**
 * Atrybut aria-current dla bieżącej strony.
 *
 * @param string $url Adres.
 * @return string
 */
function parafia_current_attr( $url ) {
	return parafia_is_current_url( $url ) ? ' aria-current="page"' : '';
}

/**
 * Menu główne (desktop) — markup prototypu: .navlink, .has-sub, .submenu.
 */
function parafia_render_mainnav() {
	echo "<ul>\n";
	foreach ( parafia_menu( 'primary' ) as $item ) {
		if ( ! empty( $item['children'] ) ) {
			printf(
				'<li class="has-sub"><a class="navlink" href="%1$s"%2$s>%3$s %4$s</a><div class="submenu">',
				esc_url( $item['url'] ),
				parafia_current_attr( $item['url'] ), // phpcs:ignore WordPress.Security.EscapeOutput
				esc_html( $item['title'] ),
				parafia_icon( 'chevron' ) // phpcs:ignore WordPress.Security.EscapeOutput
			);
			foreach ( $item['children'] as $child ) {
				printf( '<a class="navlink" href="%1$s"%2$s>%3$s</a>', esc_url( $child['url'] ), parafia_current_attr( $child['url'] ), esc_html( $child['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			echo "</div></li>\n";
		} else {
			printf( '<li><a class="navlink" href="%1$s"%2$s>%3$s</a></li>' . "\n", esc_url( $item['url'] ), parafia_current_attr( $item['url'] ), esc_html( $item['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
	echo '</ul>';
}

/**
 * Menu mobilne — lista płaska, podstrony z klasą .sub.
 */
function parafia_render_mobilenav() {
	echo "<ul>\n";
	foreach ( parafia_menu( 'mobile' ) as $item ) {
		printf(
			'<li%1$s><a href="%2$s"%3$s>%4$s</a></li>' . "\n",
			empty( $item['sub'] ) ? '' : ' class="sub"',
			esc_url( $item['url'] ),
			parafia_current_attr( $item['url'] ), // phpcs:ignore WordPress.Security.EscapeOutput
			esc_html( $item['title'] )
		);
		foreach ( $item['children'] ?? array() as $child ) {
			printf( '<li class="sub"><a href="%1$s"%2$s>%3$s</a></li>' . "\n", esc_url( $child['url'] ), parafia_current_attr( $child['url'] ), esc_html( $child['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
	echo '</ul>';
}

/**
 * Kolumna odnośników w stopce.
 *
 * @param string $location Lokalizacja menu.
 */
function parafia_render_footer_nav( $location ) {
	echo '<ul style="list-style:none;margin:0;padding:0;line-height:2.4">' . "\n";
	foreach ( parafia_menu( $location ) as $item ) {
		printf( '<li><a href="%1$s">%2$s</a></li>' . "\n", esc_url( $item['url'] ), esc_html( $item['title'] ) );
	}
	echo '</ul>';
}

/* ------------------------------------------------------------ Treści */

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
 * Bieżący tydzień intencji: najnowszy opublikowany wpis.
 *
 * @return WP_Post|null
 */
function parafia_current_intencje() {
	$posts = get_posts( array( 'post_type' => 'intencja', 'posts_per_page' => 1 ) );
	return $posts ? $posts[0] : null;
}

/**
 * Tydzień intencji w układzie prototypu (nagłówek dnia + tabela godzin).
 *
 * @param WP_Post $post Wpis intencji.
 */
function parafia_render_intencje_week( $post ) {
	$rows = get_post_meta( $post->ID, '_parafia_intencje', true );
	$rows = is_array( $rows ) ? $rows : array();

	if ( ! $rows ) {
		echo '<p>' . esc_html__( 'Intencje na ten okres nie zostały jeszcze opublikowane.', 'parafia' ) . '</p>';
		return;
	}

	$grouped = array();
	foreach ( $rows as $row ) {
		$grouped[ $row['dzien'] ?? '' ][] = $row;
	}
	?>
	<div style="display:grid;gap:var(--space-6);max-width:900px">
		<?php foreach ( $grouped as $dzien => $items ) : ?>
			<section>
				<h2 style="font-size:24px;margin:0 0 var(--space-2);padding-bottom:8px;border-bottom:1px solid var(--color-accent-300)"><?php echo esc_html( $dzien ? parafia_format_day( $dzien ) : __( 'Bez daty', 'parafia' ) ); ?></h2>
				<table class="sched">
					<tbody>
					<?php foreach ( $items as $item ) : ?>
						<tr><th scope="row" style="width:110px" class="tnum"><?php echo esc_html( preg_replace( '/^0(\d)/', '$1', (string) $item['godzina'] ) ); ?></th><td><?php echo esc_html( $item['tresc'] ); ?></td></tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</section>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Godziny kancelarii jako wiersze [etykieta, godziny] — z linii „Etykieta: godziny”.
 *
 * @return array<int,array{0:string,1:string}>
 */
function parafia_kancelaria_rows() {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) parafia_opt( 'kancelaria_godziny' ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts  = preg_split( '/:\s+/', $line, 2 );
		$rows[] = array( trim( $parts[0] ), trim( $parts[1] ?? '' ) );
	}
	return $rows;
}

/**
 * Zdanie „Kancelaria nieczynna …” z listy okoliczności (jedna w wierszu).
 *
 * @return string
 */
function parafia_kancelaria_closed_sentence() {
	$items = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) parafia_opt( 'kancelaria_nieczynna' ) ) ), 'strlen' ) );
	if ( ! $items ) {
		return '';
	}
	$last = array_pop( $items );
	$list = $items ? implode( ', ', $items ) . ' ' . __( 'oraz', 'parafia' ) . ' ' . $last : $last;
	return sprintf( __( 'Kancelaria nieczynna %s.', 'parafia' ), rtrim( $list, '.' ) );
}

/**
 * Bezpieczne osadzenie transmisji albo komunikat o niedostępności —
 * wnętrze odtwarzacza jak w prototypie.
 */
function parafia_stream_embed() {
	$url = parafia_opt( 'transmisja_embed' );
	?>
	<div data-consent-box style="text-align:center;color:#cfc8c2;padding:24px;max-width:560px">
		<?php echo parafia_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php if ( ! $url ) : ?>
			<p style="margin:0;font-size:18px"><?php echo esc_html( parafia_opt( 'transmisja_komunikat' ) ); ?></p>
		<?php else : ?>
			<p style="margin:0 0 18px;font-size:18px"><?php esc_html_e( 'Odtwarzacz pochodzi z serwisu zewnętrznego i ładuje się dopiero po naciśnięciu przycisku.', 'parafia' ); ?></p>
			<button type="button" class="stream-consent" data-embed="<?php echo esc_url( $url ); ?>" data-title="<?php esc_attr_e( 'Transmisja na żywo', 'parafia' ); ?>"><?php esc_html_e( 'Włącz transmisję', 'parafia' ); ?></button>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Nadtytuł strony: pole „Nadtytuł”, a gdy puste — tytuł strony nadrzędnej.
 *
 * @param WP_Post $post Strona.
 * @return string
 */
function parafia_page_kicker( $post ) {
	$kicker = (string) get_post_meta( $post->ID, '_parafia_kicker', true );
	if ( '' === $kicker && $post->post_parent ) {
		$kicker = get_the_title( $post->post_parent );
	}
	return $kicker;
}

/**
 * Identyfikatory zdjęć z treści galerii: blok Galeria, pojedyncze obrazki, a na końcu obrazek wyróżniający.
 *
 * @param WP_Post $post  Wpis galerii.
 * @param int     $limit Maksymalna liczba zdjęć.
 * @return int[]
 */
function parafia_gallery_image_ids( $post, $limit = 6 ) {
	$ids = array();
	foreach ( parse_blocks( $post->post_content ) as $block ) {
		parafia_collect_image_ids( $block, $ids );
	}
	if ( ! $ids && has_post_thumbnail( $post ) ) {
		$ids[] = (int) get_post_thumbnail_id( $post );
	}
	return array_slice( array_values( array_unique( array_filter( $ids ) ) ), 0, $limit );
}

/**
 * Rekurencyjnie zbiera ID obrazków z bloków core/image (także wewnątrz core/gallery).
 *
 * @param array $block Blok.
 * @param int[] $ids   Zebrane ID (przez referencję).
 */
function parafia_collect_image_ids( $block, &$ids ) {
	if ( 'core/image' === ( $block['blockName'] ?? '' ) && ! empty( $block['attrs']['id'] ) ) {
		$ids[] = (int) $block['attrs']['id'];
	}
	if ( 'core/gallery' === ( $block['blockName'] ?? '' ) && ! empty( $block['attrs']['ids'] ) ) {
		$ids = array_merge( $ids, array_map( 'intval', (array) $block['attrs']['ids'] ) );
	}
	foreach ( $block['innerBlocks'] ?? array() as $inner ) {
		parafia_collect_image_ids( $inner, $ids );
	}
}

/**
 * Adres osadzenia mapy: z ustawień, a gdy puste — wyszukiwanie adresu parafii w Mapach Google.
 *
 * @return string
 */
function parafia_map_url() {
	$url = parafia_opt( 'mapa_url' );
	if ( $url ) {
		return $url;
	}
	$adres = trim( preg_replace( '/\s+/', ' ', (string) parafia_opt( 'adres' ) ) );
	return $adres ? 'https://www.google.com/maps?output=embed&q=' . rawurlencode( $adres ) : '';
}
