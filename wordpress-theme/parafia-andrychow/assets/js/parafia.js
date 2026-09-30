/* Jedyny skrypt front-endu. Bez zależności.
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
	// Ramka zastępuje całe pole zgody ([data-consent-box]), więc wypełnia odtwarzacz / miejsce mapy.
	document.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '.stream-consent, .map-consent' );
		if ( ! btn ) {
			return;
		}
		var frame = document.createElement( 'iframe' );
		frame.src = btn.getAttribute( 'data-embed' );
		frame.title = btn.getAttribute( 'data-title' ) || 'Transmisja na żywo';
		frame.allow = 'autoplay; fullscreen; picture-in-picture';
		frame.allowFullscreen = true;
		frame.referrerPolicy = 'strict-origin-when-cross-origin';
		if ( btn.classList.contains( 'map-consent' ) ) {
			frame.className = 'map-frame';
		}
		var box = btn.closest( '[data-consent-box]' ) || btn;
		box.parentNode.replaceChild( frame, box );
	} );
}() );
