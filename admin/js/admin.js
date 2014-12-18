
jQuery( document ).ready( function( $ ) {

	$( '#image_sizes_panel .info a' ).click( function( e ) {

		$( this ).closest( 'tr' ).toggleClass( 'open' );
		e.preventDefault();

	} );

} );
