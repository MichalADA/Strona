<?php
/**
 * Fallback wymagany przez WordPressa.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap section">
	<?php if ( have_posts() ) : ?>
		<div class="grid grid-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card', 'aktualnosc' );
			endwhile;
			?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'Brak treści.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
