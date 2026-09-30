<?php
/**
 * Fallback wymagany przez WordPressa.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap wrap-narrow section">
	<?php if ( have_posts() ) : ?>
		<div style="display:grid;gap:var(--space-6)">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card', 'aktualnosc' );
			endwhile;
			?>
		</div>
		<?php the_posts_pagination( array( 'mid_size' => 1, 'screen_reader_text' => __( 'Stronicowanie', 'parafia' ) ) ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Brak treści.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
