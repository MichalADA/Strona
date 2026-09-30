<?php
/**
 * Strona główna — bramka, nie streszczenie serwisu. Markup 1:1 z prototypu:
 * fotografia kościoła + tożsamość parafii + dzisiejsze Msze → transmisja →
 * cztery kafle → dwa ostatnie ogłoszenia → stopka.
 *
 * Treści zarządzane z WordPressa:
 * - fotografia: Wygląd → Dostosuj → Fotografia na stronie głównej (domyślnie zdjęcie z motywu),
 * - nazwa, adres, diecezja: Parafia → Tożsamość parafii / Kontakt,
 * - godziny Mszy: Parafia → Porządek Mszy Świętych,
 * - ogłoszenia: Ogłoszenia.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$schedule = parafia_today_schedule();
$hero_id  = (int) get_theme_mod( 'parafia_hero_image' );
$hero_alt = __( 'Kościół św. Stanisława Biskupa i Męczennika w Andrychowie od strony ulicy — wieża z krzyżami i zielone dachy', 'parafia' );
$kicker   = implode( ' · ', array_filter( array( parafia_opt( 'miejscowosc' ), parafia_address_first_line() ) ) );
?>

<section class="hero-id" aria-labelledby="h-parafia">
	<div class="wrap hero-grid">
		<figure class="hero-fig">
			<?php
			if ( $hero_id && wp_attachment_is_image( $hero_id ) ) {
				$alt = trim( (string) get_post_meta( $hero_id, '_wp_attachment_image_alt', true ) );
				echo wp_get_attachment_image(
					$hero_id,
					'parafia-hero',
					false,
					array(
						'class'         => 'hero-photo plate',
						'alt'           => $alt ? $alt : $hero_alt,
						'fetchpriority' => 'high',
						'loading'       => false,
						'decoding'      => 'async',
						'sizes'         => '(min-width: 1180px) 600px, (min-width: 900px) 52vw, 100vw',
					)
				);
			} else {
				printf(
					'<img class="hero-photo plate" src="%1$s" alt="%2$s" fetchpriority="high" decoding="async" />',
					esc_url( parafia_img( 'kosciol-dzien.jpg' ) ),
					esc_attr( $hero_alt )
				);
			}
			?>
		</figure>
		<div class="hero-text">
			<?php if ( $kicker ) : ?>
				<p class="kicker"><?php echo esc_html( $kicker ); ?></p>
			<?php endif; ?>
			<h1 id="h-parafia" class="hero-title"><?php echo esc_html( parafia_opt( 'nazwa' ) ); ?> <?php if ( parafia_opt( 'wezwanie' ) ) : ?><span class="hero-title-sub"><?php echo esc_html( parafia_opt( 'wezwanie' ) ); ?></span><?php endif; ?></h1>
			<?php if ( parafia_opt( 'diecezja' ) ) : ?>
				<p class="hero-diocese"><?php echo esc_html( parafia_opt( 'diecezja' ) ); ?></p>
			<?php endif; ?>
			<div class="hero-today">
				<p class="hero-today-label"><?php esc_html_e( 'Msze Święte dzisiaj', 'parafia' ); ?></p>
				<?php if ( $schedule['times'] ) : ?>
					<ul class="hero-times">
						<?php foreach ( $schedule['times'] as $time ) : ?>
							<li><?php echo esc_html( $time ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<p style="margin:0 0 10px"><?php esc_html_e( 'Porządek Mszy na dziś nie został jeszcze uzupełniony.', 'parafia' ); ?></p>
				<?php endif; ?>
				<a href="<?php echo esc_url( parafia_page_url( 'msze' ) ); ?>"><?php esc_html_e( 'Pełny porządek Mszy →', 'parafia' ); ?></a>
			</div>
		</div>
	</div>
</section>

<div class="wrap">
	<a class="quick-live" href="<?php echo esc_url( parafia_page_url( 'transmisja' ) ); ?>">
		<span class="live-icon" aria-hidden="true">
			<?php echo parafia_icon( 'live' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</span>
		<span class="live-text">
			<span class="live-label"><?php esc_html_e( 'Transmisja na żywo', 'parafia' ); ?></span>
			<span class="live-sub"><?php esc_html_e( 'Oglądaj Mszę Świętą z naszego kościoła', 'parafia' ); ?></span>
		</span>
		<?php echo parafia_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</a>
	<nav class="quick" aria-label="<?php esc_attr_e( 'Najważniejsze informacje', 'parafia' ); ?>">
		<?php
		$tiles = array(
			array( parafia_page_url( 'msze' ), 'clock', __( 'Msze', 'parafia' ), __( 'Porządek i godziny', 'parafia' ) ),
			array( parafia_archive_url( 'intencja', 'intencje' ), 'calendar', __( 'Intencje', 'parafia' ), __( 'Na bieżący tydzień', 'parafia' ) ),
			array( parafia_archive_url( 'aktualnosc', 'aktualnosci' ), 'megaphone', __( 'Ogłoszenia', 'parafia' ), __( 'Sprawy parafii', 'parafia' ) ),
			array( parafia_page_url( 'kontakt' ), 'phone', __( 'Kontakt', 'parafia' ), __( 'Kancelaria i adres', 'parafia' ) ),
		);
		foreach ( $tiles as $tile ) :
			?>
			<a href="<?php echo esc_url( $tile[0] ); ?>">
				<?php echo parafia_icon( $tile[1] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="quick-label"><?php echo esc_html( $tile[2] ); ?></span>
				<span class="quick-sub"><?php echo esc_html( $tile[3] ); ?></span>
			</a>
		<?php endforeach; ?>
	</nav>
</div>

<?php
// Ogłoszenia na stronie głównej: wyłącznie zaznaczone „Pokaż na stronie głównej”,
// najwyżej 2 najnowsze. Bez zaznaczonych — sekcji nie ma, pod kaflami od razu stopka.
$featured = parafia_featured_ogloszenia( 2 );
if ( $featured ) :
	?>
	<section class="wrap home-featured" aria-labelledby="h-wazne">
		<h2 id="h-wazne" class="screen-reader-text"><?php esc_html_e( 'Ważne ogłoszenia', 'parafia' ); ?></h2>
		<div class="featured-list featured-list--<?php echo count( $featured ); ?>">
			<?php foreach ( $featured as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride ?>
				<?php setup_postdata( $post ); ?>
				<article class="featured-item">
					<p class="kicker featured-kicker"><?php esc_html_e( 'Ogłoszenie', 'parafia' ); ?> · <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( parafia_post_date() ); ?></time></p>
					<h3 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php $excerpt = wp_trim_words( get_the_excerpt(), 24 ); ?>
					<?php if ( $excerpt ) : ?>
						<p class="featured-excerpt"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>
					<a class="featured-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Czytaj więcej', 'parafia' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span></a>
				</article>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>
	<?php
else :
	// Bez wyróżnionych ogłoszeń: tylko naturalny odstęp między kaflami a stopką.
	echo '<div class="home-news-spacer"></div>';
endif;

get_footer();
