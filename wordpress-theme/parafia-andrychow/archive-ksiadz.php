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
	<?php if ( parafia_opt( 'tryb_demo' ) ) : ?>
		<p class="demo-note"><?php esc_html_e( 'Dane demonstracyjne.', 'parafia' ); ?></p>
	<?php endif; ?>

	<section style="margin-top:var(--space-8)">
		<h2><?php esc_html_e( 'Duszpasterze parafii', 'parafia' ); ?></h2>
		<?php if ( $duszpasterze ) : ?>
			<div class="grid grid-4" style="margin-top:var(--space-6)">
				<?php foreach ( $duszpasterze as $ksiadz ) : ?>
					<?php
					$tel   = get_post_meta( $ksiadz->ID, '_parafia_telefon', true );
					$email = get_post_meta( $ksiadz->ID, '_parafia_email', true );
					$opis  = wp_strip_all_tags( $ksiadz->post_content );
					?>
					<article class="card">
						<?php if ( has_post_thumbnail( $ksiadz ) ) : ?>
							<?php echo get_the_post_thumbnail( $ksiadz, 'parafia-portrait', array( 'class' => 'priest-photo', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<div class="ph priest-photo"><?php esc_html_e( 'portret', 'parafia' ); ?></div>
						<?php endif; ?>
						<h3 class="card-title" style="margin:0"><?php echo esc_html( get_the_title( $ksiadz ) ); ?></h3>
						<p class="card-meta" style="margin:0"><?php echo esc_html( get_post_meta( $ksiadz->ID, '_parafia_rola', true ) ); ?></p>
						<?php if ( $tel || $email ) : ?>
							<p class="card-body" style="margin:0">
								<?php if ( $tel ) : ?>
									<a href="<?php echo esc_url( parafia_tel_href( $tel ), array( 'tel' ) ); ?>"><?php echo esc_html( $tel ); ?></a>
								<?php endif; ?>
								<?php if ( $tel && $email ) : ?><br /><?php endif; ?>
								<?php if ( $email ) : ?>
									<a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a>
								<?php endif; ?>
							</p>
						<?php elseif ( $opis ) : ?>
							<p class="card-body" style="margin:0"><?php echo esc_html( wp_trim_words( $opis, 20 ) ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Dane duszpasterzy nie zostały jeszcze uzupełnione.', 'parafia' ); ?></p>
		<?php endif; ?>
	</section>

	<?php if ( $rodacy ) : ?>
		<section style="margin-top:var(--space-8);padding-top:var(--space-8);border-top:1px solid var(--color-divider)">
			<h2><?php esc_html_e( 'Kapłani pochodzący z naszej parafii', 'parafia' ); ?></h2>
			<p style="max-width:640px"><?php esc_html_e( 'Kapłani pochodzący z parafii św. Stanisława Biskupa i Męczennika w Andrychowie.', 'parafia' ); ?></p>
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
