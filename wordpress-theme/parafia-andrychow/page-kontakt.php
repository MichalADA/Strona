<?php
/**
 * Kontakt i kancelaria — układ prototypu. Adres, telefon, e-mail, godziny
 * kancelarii i mapa pochodzą z ekranu „Parafia”; dodatkowe informacje
 * (np. jak dojechać) — z treści strony w edytorze.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$email   = parafia_opt( 'email' );
$mapa    = parafia_map_url();
$rows    = parafia_kancelaria_rows();
$closed  = parafia_kancelaria_closed_sentence();
$pelna   = trim( parafia_opt( 'nazwa' ) . ' ' . parafia_opt( 'wezwanie' ) );
?>
<div class="wrap section">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<p class="kicker"><?php echo esc_html( parafia_page_kicker( get_post() ) ?: __( 'Kontakt', 'parafia' ) ); ?></p>
		<h1><?php the_title(); ?></h1>
		<div class="split" style="margin-top:var(--space-8)">
			<div>
				<h2><?php esc_html_e( 'Adres', 'parafia' ); ?></h2>
				<address style="font-style:normal;font-size:20px;line-height:1.8">
					<?php echo esc_html( $pelna ); ?><br />
					<?php parafia_the_address_lines(); ?>
					<?php if ( parafia_opt( 'telefon' ) ) : ?>
						<br /><?php parafia_the_phone(); ?>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<br /><a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a>
					<?php endif; ?>
				</address>
				<?php if ( ! $email && parafia_opt( 'tryb_demo' ) ) : ?>
					<p class="text-muted"><?php esc_html_e( 'Adres e-mail parafii nie został podany do wersji demonstracyjnej.', 'parafia' ); ?></p>
				<?php endif; ?>

				<?php if ( $rows ) : ?>
					<h2 style="margin-top:var(--space-8)"><?php esc_html_e( 'Kancelaria parafialna', 'parafia' ); ?></h2>
					<table class="sched">
						<tbody>
						<?php foreach ( $rows as $row ) : ?>
							<tr><th scope="row"><?php echo esc_html( $row[0] ); ?></th><td><?php echo esc_html( $row[1] ); ?></td></tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
				<?php if ( $closed ) : ?>
					<p style="margin-top:var(--space-4)"><?php echo esc_html( $closed ); ?></p>
				<?php endif; ?>
			</div>
			<div>
				<h2><?php esc_html_e( 'Jak dojechać', 'parafia' ); ?></h2>
				<?php if ( $mapa ) : ?>
					<div data-consent-box style="border:1px solid var(--color-divider);padding:var(--space-6);text-align:center">
						<p style="font-size:17px"><?php esc_html_e( 'Mapa pochodzi z serwisu zewnętrznego. Kliknij, aby ją załadować — dopiero wtedy Twoje dane trafią do dostawcy mapy.', 'parafia' ); ?></p>
						<button class="btn btn-primary map-consent" type="button" data-embed="<?php echo esc_url( $mapa ); ?>" data-title="<?php esc_attr_e( 'Mapa dojazdu', 'parafia' ); ?>"><?php esc_html_e( 'Załaduj mapę', 'parafia' ); ?></button>
					</div>
				<?php endif; ?>
				<?php if ( trim( get_the_content() ) ) : ?>
					<div class="entry" style="margin-top:var(--space-4)"><?php the_content(); ?></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; ?>
</div>
<?php
get_footer();
