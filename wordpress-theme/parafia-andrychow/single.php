<?php
/**
 * Pojedynczy wpis (aktualność, galeria, ksiądz).
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap wrap-narrow section">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h1><?php the_title(); ?></h1>
			<p class="text-muted"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="plate"><?php the_post_thumbnail( 'parafia-hero' ); ?></figure>
			<?php endif; ?>
			<div class="entry"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</div>
<?php
get_footer();
