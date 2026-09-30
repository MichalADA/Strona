<?php
/**
 * Template Name: Transmisja na żywo
 *
 * Szablon dedykowany: jeden cel użytkownika — obejrzeć Mszę. Markup 1:1 z prototypu.
 * Nad odtwarzaczem tylko nagłówek i jedno zdanie. Pod odtwarzaczem nic,
 * co odciągałoby uwagę — żadnych przycisków ani informacji technicznych.
 *
 * Adres osadzenia, zdanie pod nagłówkiem i komunikat „niedostępna”:
 * Parafia → Transmisja na żywo.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrap" style="padding-top:var(--space-4);padding-bottom:var(--space-3)">
	<h1 style="margin:0;font-size:clamp(26px,4vw,38px)"><?php esc_html_e( 'Transmisja na żywo', 'parafia' ); ?></h1>
	<?php if ( parafia_opt( 'transmisja_opis' ) ) : ?>
		<p class="text-muted" style="margin:6px 0 0;font-size:17px"><?php echo esc_html( parafia_opt( 'transmisja_opis' ) ); ?></p>
	<?php endif; ?>
</div>
<div class="wrap" style="padding-bottom:var(--space-6)">
	<div class="player">
		<?php parafia_stream_embed(); ?>
	</div>
</div>
<div style="padding-bottom:var(--space-8)"></div>

<?php
get_footer();
