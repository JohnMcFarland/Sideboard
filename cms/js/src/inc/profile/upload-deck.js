$( ":submit" ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'deck-upload' ) {
		console.log( "UserID: ");
		console.log( $( 'input[name=userID]' ).val() );
		console.log( "Deck name: ");
		console.log( $( 'input[name=deck-name]' ).val() );
		console.log( $( 'input[name=deck-file]' )[0].files[0] );

		var deckData = new FormData();
		deckData.append( 'user_id', $( 'input[name=userID]' ).val() );
		deckData.append( 'name', $( 'input[name=deck-name]' ).val() );
		deckData.append( 'file', $( 'input[type=file]' )[0].files[0] );


		console.log(deckData['file']);
		$.ajax({
			type: 'POST',
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/load-deck.php',
			data: deckData,
			cache: false,
			contentType: false,
			processData: false,
		}).then(function( response ) {
			console.log( response );
			// TO DO
			//Donat: This is where you're going refresh the decks div on the profile page.
		});

	}
});



// $( 'input[name=pro-upload-deck]' ).change(function() {
// 	$( 'p[name=pro-upload-deck-text]' ).text( $( this )[0].files[0].name );
//});
