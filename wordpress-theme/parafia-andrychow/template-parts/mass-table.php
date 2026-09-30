<?php
/**
 * Tabela stałego porządku Mszy — dane z ekranu „Parafia”.
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
<table class="sched">
	<caption class="text-muted"><?php esc_html_e( 'Stały porządek Mszy Świętych', 'parafia' ); ?></caption>
	<tbody>
	<?php foreach ( $rows as $key => $label ) : ?>
		<?php $value = parafia_opt( $key ); ?>
		<?php if ( $value ) : ?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td><?php echo esc_html( str_replace( ',', ' ·', $value ) ); ?></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
	</tbody>
</table>
