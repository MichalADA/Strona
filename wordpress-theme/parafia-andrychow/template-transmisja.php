<?php
/**
 * Template Name: Transmisja na żywo
 *
 * Szablon dedykowany: jeden cel użytkownika — obejrzeć Mszę.
 * Nad odtwarzaczem tylko nagłówek i jedno zdanie. Pod odtwarzaczem nic,
 * co odciągałoby uwagę — żadnych przycisków ani informacji technicznych.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrap stream-head">
	<h1><?php esc_html_e( 'Transmisja na żywo', 'parafia' ); ?></h1>
	<?php if ( parafia_opt( 'transmisja_opis' ) ) : ?>
		<p class="text-muted"><?php echo esc_html( parafia_opt( 'transmisja_opis' ) ); ?></p>
	<?php endif; ?>
</div>

<div class="wrap stream-wrap">
	<div class="player"><?php parafia_stream_embed(); ?></div>
</div>

<?php
get_footer();
