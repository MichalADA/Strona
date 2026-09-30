<?php
/**
 * Intencje mszalne — domyślnie bieżący (najnowszy) tydzień w układzie prototypu.
 * Z parametrem ?widok=archiwum — lista wszystkich opublikowanych tygodni.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$archive_url = add_query_arg( 'widok', 'archiwum', parafia_archive_url( 'intencja', 'intencje' ) );
$is_list     = isset( $_GET['widok'] ) && 'archiwum' === sanitize_key( wp_unslash( $_GET['widok'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
$week        = $is_list ? null : parafia_current_intencje();
?>
<div class="wrap section">
	<p class="kicker"><?php esc_html_e( 'Intencje mszalne', 'parafia' ); ?></p>
	<h1><?php echo $is_list ? esc_html__( 'Archiwum intencji', 'parafia' ) : esc_html__( 'Intencje mszalne', 'parafia' ); ?></h1>

	<?php if ( $week ) : ?>
		<p class="text-muted" style="font-size:19px"><?php echo esc_html( sprintf( __( 'Tydzień %s', 'parafia' ), get_the_title( $week ) ) ); ?></p>
	<?php endif; ?>

	<div style="display:flex;flex-wrap:wrap;gap:12px;margin-block:var(--space-6)">
		<a class="btn btn-secondary" href="<?php echo esc_url( parafia_page_url( 'msze' ) ); ?>"><?php esc_html_e( 'Porządek Mszy Świętych', 'parafia' ); ?></a>
		<?php if ( $is_list ) : ?>
			<a class="btn btn-secondary" href="<?php echo esc_url( parafia_archive_url( 'intencja', 'intencje' ) ); ?>"><?php esc_html_e( 'Bieżący tydzień', 'parafia' ); ?></a>
		<?php else : ?>
			<a class="btn btn-secondary" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Archiwum intencji', 'parafia' ); ?></a>
		<?php endif; ?>
	</div>

	<?php if ( $is_list ) : ?>
		<?php
		$weeks = get_posts( array( 'post_type' => 'intencja', 'posts_per_page' => 100 ) );
		if ( $weeks ) :
			?>
			<ul class="native-list" style="max-width:900px">
				<?php foreach ( $weeks as $w ) : ?>
					<li><a class="native-name" href="<?php echo esc_url( get_permalink( $w ) ); ?>"><?php echo esc_html( get_the_title( $w ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><?php esc_html_e( 'Brak opublikowanych tygodni intencji.', 'parafia' ); ?></p>
		<?php endif; ?>
	<?php elseif ( $week ) : ?>
		<?php parafia_render_intencje_week( $week ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Intencje na bieżący tydzień nie zostały jeszcze opublikowane.', 'parafia' ); ?></p>
	<?php endif; ?>
	<?php if ( parafia_opt( 'tryb_demo' ) ) : ?>
		<p class="demo-note" style="margin-top:var(--space-8)"><?php esc_html_e( 'Treść demonstracyjna.', 'parafia' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
