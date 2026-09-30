<?php
/**
 * Karta aktualności.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'parafia-card', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
		</a>
	<?php endif; ?>
	<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p class="card-meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
	<p class="card-body"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
	<p><a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Czytaj więcej', 'parafia' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span></a></p>
</article>
