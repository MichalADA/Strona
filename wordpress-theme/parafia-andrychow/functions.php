<?php
/**
 * Parafia Andrychów — bootstrap motywu.
 *
 * Odpowiedzialności rozdzielone na pliki w katalogu inc/.
 * Ten plik nie zawiera logiki poza wczytaniem modułów.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

define( 'PARAFIA_VERSION', '1.0.0' );
define( 'PARAFIA_DIR', get_template_directory() );
define( 'PARAFIA_URI', get_template_directory_uri() );

require_once PARAFIA_DIR . '/inc/setup.php';        // supports, menus, image sizes
require_once PARAFIA_DIR . '/inc/assets.php';       // CSS/JS, fonty
require_once PARAFIA_DIR . '/inc/post-types.php';   // CPT: aktualnosc, intencja, ksiadz, galeria
require_once PARAFIA_DIR . '/inc/meta-fields.php';  // metaboxy CPT (bez wtyczek)
require_once PARAFIA_DIR . '/inc/settings.php';     // ekran „Parafia”: msze, kancelaria, kontakt, transmisja
require_once PARAFIA_DIR . '/inc/schedule.php';     // wyliczenie „dzisiejszych” godzin Mszy
require_once PARAFIA_DIR . '/inc/roles.php';        // rola „Redaktor parafialny”
require_once PARAFIA_DIR . '/inc/security.php';     // noindex demo, utwardzenie, nagłówki
require_once PARAFIA_DIR . '/inc/template-tags.php';// helpery szablonów
