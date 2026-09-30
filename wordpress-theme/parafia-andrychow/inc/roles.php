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

add_action(
	'after_switch_theme',
	function () {
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

		flush_rewrite_rules();
	}
);

/**
 * Redaktor nie widzi pól transmisji ani ustawień technicznych.
 */
add_action(
	'admin_head',
	function () {
		if ( current_user_can( 'manage_parafia_stream' ) ) {
			return;
		}
		echo '<style>#parafia-transmisja{display:none}</style>';
	}
);
