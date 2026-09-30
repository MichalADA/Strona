<?php
/**
 * Tydzień intencji mszalnych.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap section">
	<?php
	while ( have_posts() ) :
		the_post();
		$rows = get_post_meta( get_the_ID(), '_parafia_intencje', true );
		$rows = is_array( $rows ) ? $rows : array();
		?>
		<p class="kicker"><?php esc_html_e( 'Intencje mszalne', 'parafia' ); ?></p>
		<h1><?php the_title(); ?></h1>

		<?php if ( ! $rows ) : ?>
			<p><?php esc_html_e( 'Intencje na ten okres nie zostały jeszcze opublikowane.', 'parafia' ); ?></p>
		<?php else : ?>
			<?php
			$grouped = array();
			foreach ( $rows as $row ) {
				$grouped[ $row['dzien'] ][] = $row;
			}
			foreach ( $grouped as $dzien => $items ) :
				?>
				<section>
					<h2><?php echo esc_html( $dzien ? wp_date( 'l, j F Y', strtotime( $dzien ) ) : '' ); ?></h2>
					<table class="sched">
						<tbody>
						<?php foreach ( $items as $item ) : ?>
							<tr>
								<th scope="row" class="tnum"><?php echo esc_html( $item['godzina'] ); ?></th>
								<td><?php echo esc_html( $item['tresc'] ); ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</section>
				<?php
			endforeach;
			?>
		<?php endif; ?>
	<?php endwhile; ?>
</div>
<?php
get_footer();
