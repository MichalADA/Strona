<?php
/**
 * Archiwa pozostałych typów treści — lista w układzie ogłoszeń z prototypu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap wrap-narrow section">
	<h1><?php the_archive_title(); ?></h1>

	<?php if ( have_posts() ) : ?>
		<div style="margin-top:var(--space-8);display:grid;gap:var(--space-6)">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card', 'aktualnosc' );
			endwhile;
			?>
		</div>
		<?php the_posts_pagination( array( 'mid_size' => 1, 'screen_reader_text' => __( 'Stronicowanie', 'parafia' ) ) ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Brak wpisów w tym dziale.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
