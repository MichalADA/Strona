<?php
/**
 * Pojedynczy wpis (ogłoszenie, galeria, ksiądz) — układ prototypu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$type    = get_post_type();
$back    = array(
	'aktualnosc' => array( parafia_archive_url( 'aktualnosc', 'aktualnosci' ), __( '← Wszystkie ogłoszenia', 'parafia' ), __( 'Ogłoszenia', 'parafia' ) ),
	'galeria'    => array( parafia_archive_url( 'galeria', 'galeria' ), __( '← Wszystkie galerie', 'parafia' ), __( 'Galeria', 'parafia' ) ),
	'ksiadz'     => array( parafia_archive_url( 'ksiadz', 'ksieza' ), __( '← Nasi księża', 'parafia' ), __( 'Nasi księża', 'parafia' ) ),
);
$context = $back[ $type ] ?? null;
?>
<div class="wrap wrap-narrow section">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<?php if ( $context ) : ?>
				<p class="card-meta"><a href="<?php echo esc_url( $context[0] ); ?>"><?php echo esc_html( $context[1] ); ?></a></p>
				<p class="kicker"><?php echo esc_html( $context[2] ); ?></p>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( 'ksiadz' === $type ) : ?>
				<p class="text-muted"><?php echo esc_html( get_post_meta( get_the_ID(), '_parafia_rola', true ) ); ?></p>
			<?php else : ?>
				<p class="text-muted"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( parafia_post_date() ); ?></time></p>
			<?php endif; ?>
			<?php if ( has_post_thumbnail() && 'galeria' !== $type ) : ?>
				<?php the_post_thumbnail( 'large', array( 'style' => 'display:block;width:100%;height:auto;aspect-ratio:16/9;object-fit:cover;margin-block:var(--space-6)' ) ); ?>
			<?php endif; ?>
			<div class="entry"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</div>
<?php
get_footer();
