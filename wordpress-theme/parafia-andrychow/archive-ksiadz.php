<?php
/**
 * Nasi księża — dwie rozłączne grupy:
 * 1) duszpasterze aktualnie posługujący w parafii (karty z portretem),
 * 2) kapłani pochodzący z parafii (lista chronologiczna wg roku święceń).
 *
 * Druga grupa NIE jest numerowana — to nie ranking.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$duszpasterze = get_posts(
	array(
		'post_type'      => 'ksiadz',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'meta_query'     => array(
			'relation' => 'OR',
			array( 'key' => '_parafia_rodzic', 'value' => 'rodak', 'compare' => '!=' ),
			array( 'key' => '_parafia_rodzic', 'compare' => 'NOT EXISTS' ),
		),
	)
);

$rodacy = get_posts(
	array(
		'post_type'      => 'ksiadz',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value_num',
		'meta_key'       => '_parafia_rok_swiecen',
		'order'          => 'ASC',
		'meta_query'     => array(
			array( 'key' => '_parafia_rodzic', 'value' => 'rodak' ),
		),
	)
);
?>
<div class="wrap section">
	<p class="kicker"><?php esc_html_e( 'Nasi księża', 'parafia' ); ?></p>
	<h1><?php esc_html_e( 'Nasi księża', 'parafia' ); ?></h1>

	<section>
		<h2><?php esc_html_e( 'Duszpasterze parafii', 'parafia' ); ?></h2>
		<?php if ( $duszpasterze ) : ?>
			<div class="grid grid-4">
				<?php foreach ( $duszpasterze as $ksiadz ) : ?>
					<article class="card">
						<?php echo get_the_post_thumbnail( $ksiadz, 'parafia-portrait', array( 'class' => 'priest-photo', 'loading' => 'lazy' ) ); ?>
						<h3 class="card-title"><?php echo esc_html( get_the_title( $ksiadz ) ); ?></h3>
						<p class="card-meta"><?php echo esc_html( get_post_meta( $ksiadz->ID, '_parafia_rola', true ) ); ?></p>
						<?php
						$tel = get_post_meta( $ksiadz->ID, '_parafia_telefon', true );
						if ( $tel ) :
							?>
							<p class="card-body"><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $tel ) ); ?>"><?php echo esc_html( $tel ); ?></a></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Dane duszpasterzy nie zostały jeszcze uzupełnione.', 'parafia' ); ?></p>
		<?php endif; ?>
	</section>

	<?php if ( $rodacy ) : ?>
		<section class="section-divided">
			<h2><?php esc_html_e( 'Kapłani pochodzący z naszej parafii', 'parafia' ); ?></h2>
			<p><?php esc_html_e( 'Kapłani pochodzący z parafii św. Stanisława Biskupa i Męczennika w Andrychowie.', 'parafia' ); ?></p>
			<ul class="native-list">
				<?php foreach ( $rodacy as $ksiadz ) : ?>
					<li>
						<span class="native-name"><?php echo esc_html( get_the_title( $ksiadz ) ); ?></span>
						<?php $rok = get_post_meta( $ksiadz->ID, '_parafia_rok_swiecen', true ); ?>
						<?php if ( $rok ) : ?>
							<span class="native-year"><?php esc_html_e( 'Święcenia kapłańskie:', 'parafia' ); ?> <?php echo esc_html( $rok ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
</div>
<?php
get_footer();
