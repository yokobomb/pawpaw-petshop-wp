/* PawPaw: filter katalog produk dan form kirim ke WhatsApp. */
( function () {
	'use strict';

	/* ---------- Filter kategori di katalog ---------- */
	document.querySelectorAll( '[data-katalog]' ).forEach( function ( wrap ) {
		var chips = wrap.querySelectorAll( '.chip' );
		var blocks = wrap.querySelectorAll( '.cat-block' );
		var ids = Array.prototype.map.call( blocks, function ( b ) { return b.getAttribute( 'data-cat' ); } );

		function apply( id ) {
			chips.forEach( function ( c ) { c.setAttribute( 'aria-pressed', String( c.getAttribute( 'data-f' ) === id ) ); } );
			blocks.forEach( function ( b ) { b.hidden = id !== 'semua' && b.getAttribute( 'data-cat' ) !== id; } );
		}
		function fromHash() {
			var h = decodeURIComponent( location.hash.replace( '#', '' ) );
			apply( ids.indexOf( h ) > -1 ? h : 'semua' );
		}
		chips.forEach( function ( c ) {
			c.addEventListener( 'click', function () {
				var id = c.getAttribute( 'data-f' );
				apply( id );
				history.replaceState( null, '', id === 'semua' ? location.pathname + location.search : '#' + id );
			} );
		} );
		window.addEventListener( 'hashchange', fromHash );
		fromHash();
	} );

	/* ---------- Form kontak -> WhatsApp ---------- */
	document.querySelectorAll( 'form[data-wa-form]' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var f = new FormData( form );
			var msg =
				'Halo ' + ( form.getAttribute( 'data-nama' ) || 'kak' ) + '! 🐾\n\n' +
				'Nama: ' + ( f.get( 'nama' ) || '-' ) + '\n' +
				'Hewan: ' + ( f.get( 'hewan' ) || '-' ) + '\n' +
				'Keperluan: ' + ( f.get( 'keperluan' ) || '-' ) + '\n\n' +
				( f.get( 'pesan' ) || '' );
			window.open( form.getAttribute( 'data-wa' ) + '?text=' + encodeURIComponent( msg ), '_blank', 'noopener' );
		} );
	} );
} )();
