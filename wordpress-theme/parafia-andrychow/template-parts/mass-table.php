<?php
/**
 * Tabela stałego porządku Mszy — dane z ekranu „Parafia”, markup z prototypu.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

$rows = array(
	'msze_niedziela'        => __( 'Niedziele', 'parafia' ),
	'msze_niedziela_lato'   => __( 'Niedziele (lipiec i sierpień)', 'parafia' ),
	'msze_swieta_zniesione' => __( 'Święta zniesione', 'parafia' ),
	'msze_powszednie'       => __( 'Dni powszednie', 'parafia' ),
	'msze_adwent'           => __( 'Adwent', 'parafia' ),
	'msze_powszednie_lato'  => __( 'Dni powszednie (lipiec i sierpień)', 'parafia' ),
);
?>
<table class="sched" style="max-width:820px;margin-top:var(--space-4)">
	<tbody>
	<?php foreach ( $rows as $key => $label ) : ?>
		<?php $times = parafia_parse_times( parafia_opt( $key ) ); ?>
		<?php if ( $times ) : ?>
			<tr><th scope="row"><?php echo esc_html( $label ); ?></th><td><?php echo esc_html( implode( ' · ', $times ) ); ?></td></tr>
		<?php endif; ?>
	<?php endforeach; ?>
	</tbody>
</table>
