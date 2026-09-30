<?php
/**
 * Ogłoszenie na liście — markup z prototypu (data, tytuł, zajawka, „Czytaj więcej”).
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class(); ?> style="border-top:1px solid var(--color-divider);padding-top:var(--space-4)">
	<p class="card-meta" style="margin:0 0 6px"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( parafia_post_date() ); ?></time></p>
	<h2 style="margin:0 0 10px;font-size:26px"><a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a></h2>
	<p style="margin:0 0 10px"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
	<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Czytaj więcej', 'parafia' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span></a>
</article>
