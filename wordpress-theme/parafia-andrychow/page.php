<?php
/**
 * Strona informacyjna (sakramenty, o parafii, historia, grupy, ofiara, prywatność) —
 * układ artykułu z prototypu: nadtytuł, tytuł, lead, fotografia, treść z edytora.
 *
 * Źródła treści:
 * - nadtytuł: pole „Nadtytuł” (metabox „Układ strony”) lub tytuł strony nadrzędnej,
 * - lead: pole „Zajawka” strony,
 * - fotografia: obrazek wyróżniający,
 * - treść: edytor blokowy (nagłówki H2, akapity, listy).
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
		$kicker = parafia_page_kicker( get_post() );
		?>
		<?php if ( $kicker ) : ?>
			<p class="kicker"><?php echo esc_html( $kicker ); ?></p>
		<?php endif; ?>
		<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?>
			<p style="font-size:21px;line-height:1.6"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure style="margin:var(--space-6) 0 0">
				<?php
				$meta  = wp_get_attachment_metadata( get_post_thumbnail_id() );
				$ratio = ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ? ';aspect-ratio:' . (int) $meta['width'] . ' / ' . (int) $meta['height'] : '';
				the_post_thumbnail(
					'large',
					array(
						'class'   => 'plate',
						'loading' => 'lazy',
						'style'   => 'display:block;width:100%;height:auto' . $ratio,
						'sizes'   => '(min-width: 760px) 720px, 100vw',
					)
				);
				?>
			</figure>
		<?php endif; ?>
		<div class="entry"><?php the_content(); ?></div>
		<?php if ( ! get_post_meta( get_the_ID(), '_parafia_bez_kancelarii', true ) ) : ?>
			<hr class="rule" style="margin-top:var(--space-8)" />
			<p style="margin-top:var(--space-6)"><?php esc_html_e( 'Sprawy formalne załatwiamy w kancelarii parafialnej —', 'parafia' ); ?> <a href="<?php echo esc_url( parafia_page_url( 'kontakt' ) ); ?>"><?php esc_html_e( 'godziny otwarcia i kontakt', 'parafia' ); ?></a>.</p>
		<?php endif; ?>
	<?php endwhile; ?>
</div>
<?php
get_footer();
