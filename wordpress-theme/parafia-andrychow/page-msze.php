<?php
/**
 * Template Name: Msze Święte
 *
 * Najpierw DZISIAJ, potem stały porządek. Parafianin najczęściej pyta
 * o dzisiejsze godziny, nie o tabelę całoroczną.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$schedule = parafia_today_schedule();
?>
<div class="wrap section">
	<p class="kicker"><?php esc_html_e( 'Porządek nabożeństw', 'parafia' ); ?></p>
	<h1><?php esc_html_e( 'Msze Święte', 'parafia' ); ?></h1>

	<section>
		<h2><?php echo esc_html( $schedule['label'] ); ?></h2>
		<?php if ( $schedule['times'] ) : ?>
			<ul class="today-times">
				<?php foreach ( $schedule['times'] as $time ) : ?>
					<li><?php echo esc_html( $time ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><?php esc_html_e( 'Porządek Mszy na dziś nie został jeszcze uzupełniony.', 'parafia' ); ?></p>
		<?php endif; ?>
		<p class="text-muted"><?php echo esc_html( $schedule['note'] ); ?></p>
		<p><a class="btn btn-primary" href="<?php echo esc_url( get_post_type_archive_link( 'intencja' ) ); ?>"><?php esc_html_e( 'Intencje mszalne', 'parafia' ); ?></a></p>
	</section>

	<section class="section-divided">
		<h2><?php esc_html_e( 'Stały porządek', 'parafia' ); ?></h2>
		<?php get_template_part( 'template-parts/mass-table' ); ?>
	</section>

	<?php
	// Treść strony z edytora (np. nabożeństwa okresowe) — pod tabelą.
	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) ) :
			?>
			<section class="section-divided"><?php the_content(); ?></section>
			<?php
		endif;
	endwhile;
	?>
</div>
<?php
get_footer();
