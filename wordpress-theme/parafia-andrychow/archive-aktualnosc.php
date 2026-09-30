<?php
/**
 * Ogłoszenia parafialne — lista w układzie prototypu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap wrap-narrow section">
	<p class="kicker"><?php esc_html_e( 'Ogłoszenia', 'parafia' ); ?></p>
	<h1><?php esc_html_e( 'Ogłoszenia parafialne', 'parafia' ); ?></h1>
	<?php if ( parafia_opt( 'tryb_demo' ) ) : ?>
		<p class="demo-note"><?php esc_html_e( 'Treść demonstracyjna — wpisy poniżej są przykładowe.', 'parafia' ); ?></p>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div style="margin-top:var(--space-8);display:grid;gap:var(--space-6)">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card', 'aktualnosc' );
			endwhile;
			?>
		</div>
		<?php
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => __( '← Nowsze', 'parafia' ),
				'next_text'          => __( 'Starsze →', 'parafia' ),
				'screen_reader_text' => __( 'Stronicowanie', 'parafia' ),
			)
		);
		?>
	<?php else : ?>
		<p><?php esc_html_e( 'Brak ogłoszeń.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
