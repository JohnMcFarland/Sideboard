$token = $('input[name=token_variable]').val(); 

$( 'h1' ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'header-logout' ) {
		$.ajax({
			type: 'get',
			headers: { 'csrfToken' : $token },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/logout',
		}).then(function( response ) {
			if( response.success ) {
				window.location = 'http://' + Sideboard.hostname + '/cms/front-page.php';
			}
		});
	}
});

// $( window ).scroll(function() {
// 	if ( 25 < $( window ).scrollTop() ) {
// 		$( '#header-container' ).addClass( 'shadow shrink' );
// 		$( '#header-logo' ).addClass( 'shrink' );
// 		$( '#header-username' ).addClass( 'shrink' );
// 		$( '#header-logout' ).addClass( 'shrink' );
// 	} else {
// 		$( '#header-container' ).removeClass( 'shadow shrink' );
// 		$( '#header-logo' ).removeClass( 'shrink' );
// 		$( '#header-username' ).removeClass( 'shrink' );
// 		$( '#header-logout' ).removeClass( 'shrink' );
// 	}
// });
