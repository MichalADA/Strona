<?php
/**
 * Galeria zdjęć — układ prototypu: każda galeria to nagłówek i siatka miniatur.
 * Zdjęcia pochodzą z bloku „Galeria” (lub pojedynczych obrazków) w treści wpisu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap section">
	<p class="kicker"><?php esc_html_e( 'Galeria', 'parafia' ); ?></p>
	<h1><?php esc_html_e( 'Galeria zdjęć', 'parafia' ); ?></h1>
	<?php if ( parafia_opt( 'tryb_demo' ) ) : ?>
		<p class="demo-note"><?php esc_html_e( 'Treść demonstracyjna — miejsca na zdjęcia.', 'parafia' ); ?></p>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			$ids = parafia_gallery_image_ids( get_post(), 6 );
			?>
			<h2 style="margin-top:var(--space-8)"><a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a></h2>
			<?php if ( $ids ) : ?>
				<div class="gallery-grid">
					<?php foreach ( $ids as $id ) : ?>
						<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php echo wp_get_attachment_image( $id, 'parafia-card', false, array( 'loading' => 'lazy', 'style' => 'display:block;width:100%;height:auto;aspect-ratio:4/3;object-fit:cover' ) ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php elseif ( (int) get_post_meta( get_the_ID(), '_parafia_demo_miejsca', true ) ) : ?>
				<div class="gallery-grid">
					<?php for ( $i = 1; $i <= (int) get_post_meta( get_the_ID(), '_parafia_demo_miejsca', true ); $i++ ) : ?>
						<div class="ph"><?php echo esc_html( sprintf( __( 'zdjęcie %d', 'parafia' ), $i ) ); ?></div>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
		<?php the_posts_pagination( array( 'mid_size' => 1, 'screen_reader_text' => __( 'Stronicowanie', 'parafia' ) ) ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Galerie zdjęć pojawią się tutaj po ich dodaniu.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
