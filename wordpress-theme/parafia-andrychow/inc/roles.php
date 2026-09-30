<?php
/**
 * Role i uprawnienia — zasada najmniejszych uprawnień.
 *
 * Administrator: motyw, wtyczki, użytkownicy, konfiguracja transmisji.
 * Redaktor parafialny: aktualności, intencje, księża, galerie, strony.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Rola „Redaktor parafialny” i uprawnienia do ekranu „Parafia”.
 * Wywoływane przy aktywacji motywu oraz przy aktualizacji motywu (inc/starter-content.php).
 */
function parafia_setup_roles() {
	$editor = get_role( 'editor' );
	$caps   = $editor ? $editor->capabilities : array();
	unset( $caps['unfiltered_html'], $caps['edit_theme_options'], $caps['manage_categories'] );

	remove_role( 'parafia_redaktor' );
	add_role( 'parafia_redaktor', __( 'Redaktor parafialny', 'parafia' ), $caps );

	// Ekran „Parafia” (msze, kancelaria, kontakt) — redaktor tak, transmisja tylko admin.
	foreach ( array( 'administrator', 'parafia_redaktor' ) as $slug ) {
		$role = get_role( $slug );
		if ( $role ) {
			$role->add_cap( 'manage_parafia' );
		}
	}
	$admin = get_role( 'administrator' );
	if ( $admin ) {
		$admin->add_cap( 'manage_parafia_stream' );
	}
}
