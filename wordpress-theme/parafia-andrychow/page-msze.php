<?php
/**
 * Template Name: Msze Święte
 *
 * Najpierw DZISIAJ, potem stały porządek. Parafianin najczęściej pyta
 * o dzisiejsze godziny, nie o tabelę całoroczną. Markup 1:1 z prototypu;
 * godziny z ekranu Parafia → Porządek Mszy Świętych, dodatkowa treść z edytora strony.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$schedule = parafia_today_schedule();
the_post();
?>
<div class="wrap section">
	<p class="kicker"><?php esc_html_e( 'Porządek nabożeństw', 'parafia' ); ?></p>
	<h1><?php esc_html_e( 'Msze Święte', 'parafia' ); ?></h1>

	<section style="margin-top:var(--space-6)">
		<?php $dopisek = has_excerpt() ? get_the_excerpt() : ''; // Zajawka strony = dopisek pod datą (np. wspomnienie dnia). ?>
		<?php if ( $dopisek ) : ?>
			<h2 style="margin:0 0 var(--space-2)"><?php echo esc_html( $schedule['label'] ); ?></h2>
			<p class="text-muted" style="margin:0 0 var(--space-4);font-size:17px"><?php echo esc_html( $dopisek ); ?></p>
		<?php else : ?>
			<h2 style="margin:0 0 var(--space-4)"><?php echo esc_html( $schedule['label'] ); ?></h2>
		<?php endif; ?>
		<?php if ( $schedule['times'] ) : ?>
			<ul class="today-times">
				<?php foreach ( $schedule['times'] as $time ) : ?>
					<li><?php echo esc_html( $time ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><?php esc_html_e( 'Porządek Mszy na dziś nie został jeszcze uzupełniony.', 'parafia' ); ?></p>
		<?php endif; ?>
		<p class="text-muted" style="margin-top:var(--space-4)"><?php echo esc_html( $schedule['note'] ); ?></p>
		<p style="margin-top:var(--space-4)"><a class="btn btn-primary" href="<?php echo esc_url( parafia_archive_url( 'intencja', 'intencje' ) ); ?>"><?php esc_html_e( 'Intencje mszalne', 'parafia' ); ?></a></p>
	</section>

	<section style="margin-top:var(--space-8);padding-top:var(--space-8);border-top:1px solid var(--color-divider)">
		<h2><?php esc_html_e( 'Stały porządek', 'parafia' ); ?></h2>
		<?php get_template_part( 'template-parts/mass-table' ); ?>
	</section>

	<?php
	// Treść strony z edytora (np. nabożeństwa okresowe) — pod tabelą.
	if ( trim( get_the_content() ) ) :
		?>
		<section class="entry" style="margin-top:var(--space-8);padding-top:var(--space-8);border-top:1px solid var(--color-divider)"><?php the_content(); ?></section>
		<?php
	endif;
	?>
</div>
<?php
get_footer();
