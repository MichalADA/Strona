<?php
/**
 * Strona statyczna (sakramenty, historia, o parafii, kontakt).
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
		<h1><?php the_title(); ?></h1>
		<div class="entry"><?php the_content(); ?></div>
	<?php endwhile; ?>
</div>
<?php
get_footer();
