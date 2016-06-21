$( 'button' ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'pro-upload-picture-trigger' ) {

		var imageData = new FormData();
		imageData.append( 'user_id', $( 'input[name=pro-profile-id]' ).val() );
		imageData.append( 'file', $( 'input[name=pro-upload-picture]' )[0].files[0] );

		$.ajax({
			type: 'POST',
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/profile-upload-image.php',
			data: imageData,
			cache: false,
			contentType: false,
			processData: false,
		}).then(function( response ) {
			console.log( response );
			// TODO
		});

	}
});

$( 'input[name=pro-upload-picture]' ).change(function() {
	$( 'p[name=pro-upload-picture-text]' ).text( $( this )[0].files[0].name );
});
