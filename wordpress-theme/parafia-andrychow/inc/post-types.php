<?php
/**
 * Typy treści.
 *
 * Zasada: CPT tylko tam, gdzie redaktor realnie potrzebuje osobnej listy
 * i osobnych pól. Podstrony informacyjne (sakramenty, historia, o parafii)
 * to zwykłe strony WordPressa — nie tworzymy dla nich CPT.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		register_post_type(
			'aktualnosc',
			array(
				// Jeden typ treści dla ogłoszeń duszpasterskich i wiadomości z życia
				// parafii. Publicznie nazywamy je konsekwentnie „Ogłoszeniami”.
				// Rozdzielenie w przyszłości = dodanie taksonomii, bez migracji wpisów.
				'labels'        => array(
					'name'          => __( 'Ogłoszenia', 'parafia' ),
					'singular_name' => __( 'Ogłoszenie', 'parafia' ),
					'add_new_item'  => __( 'Dodaj ogłoszenie', 'parafia' ),
					'edit_item'     => __( 'Edytuj ogłoszenie', 'parafia' ),
				),
				'public'        => true,
				'menu_icon'     => 'dashicons-megaphone',
				'menu_position' => 5,
				'has_archive'   => 'aktualnosci',
				'rewrite'       => array( 'slug' => 'aktualnosci', 'with_front' => false ),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
				'show_in_rest'  => true,
			)
		);

		register_post_type(
			'intencja',
			array(
				'labels'       => array(
					'name'          => __( 'Intencje mszalne', 'parafia' ),
					'singular_name' => __( 'Tydzień intencji', 'parafia' ),
					'add_new_item'  => __( 'Dodaj tydzień intencji', 'parafia' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-calendar-alt',
				'has_archive'  => 'intencje',
				'rewrite'      => array( 'slug' => 'intencje', 'with_front' => false ),
				'supports'     => array( 'title', 'revisions' ),
				'show_in_rest' => false, // edycja przez metabox z polami powtarzalnymi
			)
		);

		register_post_type(
			'ksiadz',
			array(
				'labels'       => array(
					'name'          => __( 'Księża', 'parafia' ),
					'singular_name' => __( 'Ksiądz', 'parafia' ),
					'add_new_item'  => __( 'Dodaj księdza', 'parafia' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-groups',
				'has_archive'  => 'ksieza',
				'rewrite'      => array( 'slug' => 'ksieza', 'with_front' => false ),
				'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ), // page-attributes = kolejność
				'show_in_rest' => true,
			)
		);

		register_post_type(
			'galeria',
			array(
				'labels'       => array(
					'name'          => __( 'Galerie', 'parafia' ),
					'singular_name' => __( 'Galeria', 'parafia' ),
					'add_new_item'  => __( 'Dodaj galerię', 'parafia' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-format-gallery',
				'has_archive'  => 'galeria',
				'rewrite'      => array( 'slug' => 'galeria', 'with_front' => false ),
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
				'show_in_rest' => true, // zdjęcia dodaje się blokiem Galeria
			)
		);
	}
);

/**
 * Księża sortowani polem „kolejność” (menu_order), nie datą.
 */
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_post_type_archive( 'aktualnosc' ) ) {
			$q->set( 'posts_per_page', 10 );
		}
	}
);
