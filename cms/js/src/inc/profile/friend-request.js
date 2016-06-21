$token = $('input[name=xx4access]').val();

$( 'button' ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'pro-friend-request' ) {

		var $sender_id = $( 'input[name=pro-visitor-id]' ).val(),
				$receiver_id = $( 'input[name=pro-profile-id]' ).val();

		$.ajax({
			type: 'post',
			data: { sender_id : $sender_id, receiver_id : $receiver_id },
			headers: { 'csrfToken' : $token },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/profile-send-friend-request',
		}).then(function( response ) {
			if( response.success ) {
				$( 'button[name=pro-friend-request]' ).hide();
				$( 'div[name=pro-friend-request-container]' ).appendTo( '<div name="pro-cancel-friend-request-container" style="margin: 0; padding: 0; display: none;"><div class="dropdown"><button name="pro-friend-request-sent-button" class="btn btn-default dropdown-toggle" type="button" id="privacy-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Friend Request Sent <span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="pro-cancel-friend-request-button"><li><a name="pro-cancel-friend-request-button" href="#">Cancel Friend Request</a></li></ul></div></div>' );
			}
		});

	}
});

$( 'a' ).click(function() {
	var $name = $( this ).attr( 'name' );

	// Cancel Friend request
	if ( $name == 'pro-cancel-friend-request-button' ) {
		var $sender_id = $( 'input[name=pro-visitor-id]' ).val(),
				$receiver_id = $( 'input[name=pro-profile-id]' ).val();

		$.ajax({
			type: 'post',
			data: { sender_id : $sender_id, receiver_id : $receiver_id },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/profile-remove-friendship.php',
			// url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/profile-cancel-friend-request',
		}).then(function( response ) {
			$response = $.parseJSON( response );

			if( $response.success ) {
				// TODO
			}
		});
	}
	// Respond Friend Request - Accept
	else if ( $name == 'pro-respond-friend-request-button-accept' ) {
		var $responder_id = $( 'input[name=pro-visitor-id]' ).val(),
				$sender_id = $( 'input[name=pro-profile-id]' ).val(),
				$fr_accept = 1;

		$.ajax({
			type: 'post',
			data: { sender_id : $sender_id, responder_id : $responder_id, fr_accept: $fr_accept },
			headers: { 'csrfToken' : $token },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/profile-handle-friend-request',
		}).then(function( response ) {
			console.log( response );

			// Only execute on success
			if( response.success ) {
				// TODO
			}
		});
	// Respond Friend Request - Hide (this is only on the notification)
	} else if ( $name == 'pro-respond-friend-request-button-hide' ) {
		var $responder_id = $( 'input[name=pro-visitor-id]' ).val(),
				$sender_id = $( 'input[name=pro-profile-id]' ).val(),
				$fr_accept = 0;

		$.ajax({
			type: 'post',
			data: { sender_id : $sender_id, responder_id : $responder_id, fr_accept: $fr_accept },
			headers: { 'csrfToken' : $token },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/profile-handle-friend-request',
		}).then(function( response ) {
			console.log( response );

			// Only execute on success
			if( response.success ) {
				// TODO
			}
		});
	// Respond Friend Request - Delete
	// Friends Dropdown - Unfriend (same behavior)
} else if ( $name == 'pro-friends-button-unfriend' ) {
		var $responder_id = $( 'input[name=pro-visitor-id]' ).val(),
				$sender_id = $( 'input[name=pro-profile-id]' ).val();

		$.ajax({
			type: 'post',
			data: { user_one_id: $sender_id, user_two_id: $responder_id },
			//headers: { 'csrfToken' : $token },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/profile-remove-friendship.php',
		}).then(function( response ) {
			console.log( response );

			$response = $.parseJSON( response );
			if( $response.success ) {
				// TODO
			}
		});
	}

});
