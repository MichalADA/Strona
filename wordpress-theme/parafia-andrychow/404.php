<?php
/**
 * Strona 404.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap wrap-narrow section">
	<h1><?php esc_html_e( 'Nie znaleziono strony', 'parafia' ); ?></h1>
	<p><?php esc_html_e( 'Strona mogła zostać przeniesiona lub usunięta.', 'parafia' ); ?></p>
	<p>
		<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'parafia' ); ?></a>
		<a class="btn btn-secondary" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>"><?php esc_html_e( 'Kontakt', 'parafia' ); ?></a>
	</p>
</div>
<?php
get_footer();
