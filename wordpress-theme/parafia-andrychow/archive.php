<?php
/**
 * Archiwa (aktualności, galerie, intencje).
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap section">
	<h1>
		<?php
		if ( is_post_type_archive( 'aktualnosc' ) ) {
			esc_html_e( 'Ogłoszenia parafialne', 'parafia' );
		} else {
			post_type_archive_title();
		}
		?>
	</h1>

	<?php if ( have_posts() ) : ?>
		<div class="grid grid-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card', 'aktualnosc' );
			endwhile;
			?>
		</div>
		<nav class="pagination" aria-label="<?php esc_attr_e( 'Stronicowanie', 'parafia' ); ?>">
			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		</nav>
	<?php else : ?>
		<p><?php esc_html_e( 'Brak wpisów w tym dziale.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
