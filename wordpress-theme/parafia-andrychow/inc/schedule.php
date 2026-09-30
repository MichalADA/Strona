<?php
/**
 * Porządek Mszy — odczyt ustawień i wyliczenie harmonogramu na dziś.
 *
 * Świadomie prosta logika: dzień tygodnia + okres (wakacje / adwent).
 * Żadnego kalendarza liturgicznego — parafia nie musi go utrzymywać.
 * Święta zniesione i odstępstwa ogłaszane są w aktualnościach.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Rozbija zapis „6:30, 7:00” na tablicę godzin.
 *
 * @param string $value Wartość z ustawień.
 * @return string[]
 */
function parafia_parse_times( $value ) {
	$parts = array_map( 'trim', explode( ',', (string) $value ) );
	return array_values( array_filter( $parts, 'strlen' ) );
}

/**
 * Harmonogram na dziś.
 *
 * @return array{label:string,times:string[],note:string}
 */
function parafia_today_schedule() {
	$tz    = wp_timezone();
	$now   = new DateTimeImmutable( 'now', $tz );
	$dow   = (int) $now->format( 'w' );
	$month = (int) $now->format( 'n' );
	$day   = (int) $now->format( 'j' );

	$summer = in_array( $month, array( 7, 8 ), true );
	$advent = ( 12 === $month && $day <= 24 );

	if ( 0 === $dow ) {
		$times = parafia_parse_times( parafia_opt( $summer ? 'msze_niedziela_lato' : 'msze_niedziela' ) );
		$note  = $summer ? __( 'Porządek wakacyjny (lipiec i sierpień).', 'parafia' ) : __( 'Porządek niedzielny.', 'parafia' );
	} elseif ( $summer ) {
		$times = parafia_parse_times( parafia_opt( 'msze_powszednie_lato' ) );
		$note  = __( 'Porządek wakacyjny (lipiec i sierpień).', 'parafia' );
	} elseif ( $advent ) {
		$times = parafia_parse_times( parafia_opt( 'msze_adwent' ) );
		$note  = __( 'Porządek adwentowy.', 'parafia' );
	} else {
		$times = parafia_parse_times( parafia_opt( 'msze_powszednie' ) );
		$note  = __( 'Zwykły porządek dnia powszedniego.', 'parafia' );
	}

	$dni   = array( 'niedziela', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota' );
	$mies  = array( 1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia' );
	$label = sprintf( 'Dzisiaj – %s, %d %s', $dni[ $dow ], $day, $mies[ $month ] );

	return array(
		'label' => $label,
		'times' => $times,
		'note'  => $note,
	);
}
