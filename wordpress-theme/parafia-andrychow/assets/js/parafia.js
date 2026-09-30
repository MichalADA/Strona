/* Jedyny skrypt front-endu. Bez zależności, ~40 linii.
   1) menu mobilne, 2) zgoda na treści zewnętrzne (transmisja, mapa). */
( function () {
	'use strict';

	var toggle = document.querySelector( '.burger' );
	var menu   = document.getElementById( 'menu-mobilne' );
	if ( toggle && menu ) {
		toggle.addEventListener( 'click', function () {
			var open = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', open ? 'false' : 'true' );
			menu.hidden = open;
		} );
	}

	// Odtwarzacz/mapa ładowane dopiero po kliknięciu — bez zgody nie wysyłamy żądań do dostawcy.
	document.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '.stream-consent, .map-consent' );
		if ( ! btn ) {
			return;
		}
		var frame = document.createElement( 'iframe' );
		frame.src = btn.getAttribute( 'data-embed' );
		frame.title = btn.getAttribute( 'data-title' ) || 'Transmisja na żywo';
		frame.loading = 'lazy';
		frame.allow = 'autoplay; fullscreen; picture-in-picture';
		frame.allowFullscreen = true;
		frame.referrerPolicy = 'strict-origin-when-cross-origin';
		btn.parentNode.replaceChild( frame, btn );
	} );
}() );
