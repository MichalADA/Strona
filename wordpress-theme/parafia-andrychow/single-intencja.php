<?php
/**
 * Tydzień intencji mszalnych — ten sam układ co bieżący tydzień w prototypie.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap section">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<p class="kicker"><?php esc_html_e( 'Intencje mszalne', 'parafia' ); ?></p>
		<h1><?php esc_html_e( 'Intencje mszalne', 'parafia' ); ?></h1>
		<p class="text-muted" style="font-size:19px"><?php echo esc_html( sprintf( __( 'Tydzień %s', 'parafia' ), get_the_title() ) ); ?></p>
		<div style="display:flex;flex-wrap:wrap;gap:12px;margin-block:var(--space-6)">
			<a class="btn btn-secondary" href="<?php echo esc_url( parafia_page_url( 'msze' ) ); ?>"><?php esc_html_e( 'Porządek Mszy Świętych', 'parafia' ); ?></a>
			<a class="btn btn-secondary" href="<?php echo esc_url( add_query_arg( 'widok', 'archiwum', parafia_archive_url( 'intencja', 'intencje' ) ) ); ?>"><?php esc_html_e( 'Archiwum intencji', 'parafia' ); ?></a>
		</div>
		<?php parafia_render_intencje_week( get_post() ); ?>
	<?php endwhile; ?>
</div>
<?php
get_footer();
