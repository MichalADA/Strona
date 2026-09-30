<?php
/**
 * Strona główna — bramka, nie streszczenie serwisu.
 *
 * Świadomie krótka: tożsamość parafii → pięć głównych akcji (z wyróżnioną
 * transmisją) → krótka zajawka aktualności → stopka. Porządek Mszy, księża,
 * kancelaria i sakramenty mają własne strony i tam pozostają.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hero = (int) get_theme_mod( 'parafia_hero_image' );
?>

<?php if ( $hero ) : ?>
	<div class="hero">
		<?php
		echo wp_get_attachment_image(
			$hero,
			'parafia-hero',
			false,
			array(
				'class'         => 'hero-photo plate',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
				'alt'           => esc_attr( get_bloginfo( 'name' ) ),
			)
		);
		?>
	</div>
<?php endif; ?>

<div class="wrap hero-title">
	<h1><?php bloginfo( 'name' ); ?></h1>
	<p class="text-muted"><?php bloginfo( 'description' ); ?></p>
</div>

<div class="wrap">
	<nav class="quick" aria-label="<?php esc_attr_e( 'Najważniejsze informacje', 'parafia' ); ?>">
		<a href="<?php echo esc_url( home_url( '/msze/' ) ); ?>"><?php esc_html_e( 'Msze', 'parafia' ); ?></a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'intencja' ) ); ?>"><?php esc_html_e( 'Intencje', 'parafia' ); ?></a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'aktualnosc' ) ); ?>"><?php esc_html_e( 'Ogłoszenia', 'parafia' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>"><?php esc_html_e( 'Kontakt', 'parafia' ); ?></a>
	</nav>

	<a class="quick-live" href="<?php echo esc_url( home_url( '/transmisja/' ) ); ?>">
		<svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="m16 13 5.2 3.1a1 1 0 0 0 1.5-.9V8.8a1 1 0 0 0-1.5-.9L16 11"></path><rect x="1.5" y="5.5" width="14.5" height="13" rx="2"></rect></svg>
		<span>
			<span class="live-label"><?php esc_html_e( 'Transmisja na żywo', 'parafia' ); ?></span>
			<span class="live-sub"><?php esc_html_e( 'Oglądaj Mszę Świętą', 'parafia' ); ?></span>
		</span>
	</a>
</div>

<?php
$news = new WP_Query( array( 'post_type' => 'aktualnosc', 'posts_per_page' => 2, 'ignore_sticky_posts' => true ) );
if ( $news->have_posts() ) :
	?>
	<section class="section wrap" aria-labelledby="h-akt">
		<div class="section-head">
			<div>
				<p class="kicker"><?php esc_html_e( 'Ogłoszenia', 'parafia' ); ?></p>
				<h2 id="h-akt"><?php esc_html_e( 'Ostatnie ogłoszenia', 'parafia' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'aktualnosc' ) ); ?>"><?php esc_html_e( 'Wszystkie ogłoszenia →', 'parafia' ); ?></a>
		</div>

		<?php
		$first = true;
		while ( $news->have_posts() ) :
			$news->the_post();
			?>
			<article class="teaser">
				<p class="card-meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php if ( $first ) : ?>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
					<p><a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Czytaj więcej', 'parafia' ); ?></a></p>
				<?php endif; ?>
			</article>
			<?php
			$first = false;
		endwhile;
		wp_reset_postdata();
		?>
	</section>
	<?php
endif;

get_footer();
