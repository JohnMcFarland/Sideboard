$token = $('input[name=xx4access]').val(); 

$('[data-toggle="popover"]').popover();

$( 'button' ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'pro-post-status-trigger' ) {
		var $profile_user = $( 'input[name=pro-profile-id]' ).val(),
				$user_id = $( 'input[name=pro-visitor-id]' ).val(),
				$post_content = $( 'textarea[name=pro-post-content]').val(),
				$post_privacy = $( 'input[name=pro-create-post-privacy]' ).val();

		$.ajax({
			type: 'post',
			data: { profile_user_id : $profile_user, user_id : $user_id, post_content : $post_content, post_privacy : $post_privacy },
			headers: { 'csrfToken' : $token },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/profile-create-post',
		}).then(function( response ) {
			console.log( response );

			if ( response.success ) {
				$( 'textarea[name=pro-post-content]' ).val( '' );
				$( '#post-container' ).prepend(
					'<div class="row" style="padding: 5px 10px; margin: 0 0 10px 0; border-radius: 4px; border: 1px solid #ddd; height: auto;">' +
						'<div class="row" style="margin: 0;">' +
							'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="padding: 0;">' +
								'<p style="margin: 0; font-size: 14px;"><a href="/profile.php?profile=' + response.profile_page_name + '">' + response.user_name.firstname + ' ' + response.user_name.lastname + '</a> posted a comment</p>' +
							'</div>' +
							'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">' +
								'<i name="pro-post-delete" data-toggle="modal" data-target="#pro-post-delete-modal" class="fa fa-times" data-id="' + response.post_id + '" style="float: right; cursor: pointer;"></i>' +
								'<i name="pro-post-edit" class="fa fa-pencil" data-id="' + response.post_id + '" style="float: right; margin: 0 10px; font-size: 0.9em; padding-top: 1px; cursor: pointer;"></i>' +
							'</div>' +
						'</div>' +
						'<div class="row" style="margin: 0;">' +
							'<p style="margin: 0; float: left;">' + response.date + '</p>' +
						'</div>' +
						'<div class="row" style="margin: 10px 0; min-height: 80px; padding: 0;">' +
							'<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">' +
								'<div style="height: 60px; width: auto; border-radius: 20%; border: 1px solid #ddd;">' +
									'<img src="/cms/images/' + response.profile_pic_url + '" style="height: 100%; width: 100%; border-radius: 20%;" />' +
								'</div>' +
							'</div>' +
							'<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">' +
								'<p>' + $post_content + '</p>' +
							'</div>' +
						'</div>' +
						'<div class="row">' +
							'<div class="col-xs-offset-5 col-sm-offset-5 col-md-offset-5 col-lg-offset-5 col-xs-7 col-sm-7 col-md-7 col-lg-7">' +
								'<div class="row">' +
									'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
										'<i class="fa fa-thumbs-up" style="float: left; margin: 2px 5px 0 0;"></i>' +
										'<p style="margin: 0;">Like</p>' +
									'</div>' +
									'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">' +
										'<i class="fa fa-comment" style="float: left; margin: 2px 5px 0 0;"></i>' +
										'<p style="margin: 0;">Comment</p>' +
									'</div>' +
									'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">' +
										'<i class="fa fa-share" style="float: left; margin: 2px 5px 0 0;"></i>' +
										'<p style="margin: 0;">Share</p>' +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>'
				);
			}
		});
	}
});

$( 'a' ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'privacy-option' ) {
		var $data_privacy = $( this ).attr( 'data-privacy' ),
				$privacy_id;

		switch ( $data_privacy ) {
			// See defines.php for privacy flag constants
			case "public":     $privacy_id = 9; break;
			case "friends":    $privacy_id = 3; break;
			case "self":       $privacy_id = 1; break;
		}

		// Update hidden input profile privacy
		$( 'input[name=pro-create-post-privacy]' ).val( $privacy_id );
		console.log("Changed privacy to " + $privacy_id + " (" + $( 'input[name=pro-create-post-privacy]' ).val() + ")");

		if ( $privacy_id == 9 ) {
			$( 'button[name=privacy-button]' ).html( '<i class="fa fa-globe"></i> Public <i class="fa fa-caret-down"></i>' );
		} else if ( $privacy_id == 3 ) {
			$( 'button[name=privacy-button]' ).html( '<i class="fa fa-users"></i> Friends <i class="fa fa-caret-down"></i>' );
		} else if ( $privacy_id == 1 ) {
			$( 'button[name=privacy-button]' ).html( '<i class="fa fa-lock"></i> Only Me <i class="fa fa-caret-down"></i>' );
		}
	}
});

$( '#post-container' ).on( 'click', 'i', function() {
	var $name = $( this ).attr( 'name' ),
			$post_id = $( this ).data( 'id' ),
			$post_content = $( 'p[name=pro-post-content][data-id=' + $post_id + ']' ).text();

		if ( $name == 'pro-post-edit' ) {
			$( 'div[name=pro-post-content-container][data-id=' + $post_id + ']' ).html(
			'<div class="row" style="margin: 0 0 10px 0;">' +
				'<textarea name="pro-post-edit-content" placeholder="' + $post_content + '" data-id="' + $post_id + '" data-initial-content="' + $post_content + '" style="resize: none; width: 100%;"></textarea>' +
			'</div>' +
			'<div class="row" style="margin: 0;">' +
				'<div class="col-xs-offset-5 col-sm-offset-5 col-md-offset-5 col-lg-offset-5 col-xs-7 col-sm-7 col-md-7 col-lg-7">' +
					'<button type="button" name="pro-post-edit-submit" data-id="' + $post_id + '" class="btn btn-info" style="height: 20px; width: 95px; padding: 0 10px; float: right;">Submit</button>' +
					'<button type="button" name="pro-post-edit-cancel" data-id="' + $post_id + '" class="btn btn-info" style="margin: 0 10px 0 0; float: right; height: 20px; width: 95px; padding: 0 10px;">Cancel</button>' +
				'</div>' +
			'</div>'
			);
		}
});

$( '#post-container' ).on( 'click', 'button', function() {
	var $name = $( this ).attr( 'name' ),
			$post_id = $( this ).data( 'id' ),
			$post_content = $( 'textarea[name=pro-post-edit-content][data-id=' + $post_id + ']' ).data( 'initial-content' );

	if ( $name == 'pro-post-edit-cancel' ) {
		console.log( $post_content );
		$( 'div[name=pro-post-content-container][data-id=' + $post_id + ']' ).html(
			'<p name="pro-post-content" data-id="' + $post_id + '">' + $post_content + '</p>'
		);
	} else if ( $name == 'pro-post-edit-submit' ) {
		console.log( 'TODO AJAX' );
		// TODO
		// change Unedited to edited
	}

});

$( 'button[name=pro-post-delete-modal-confrim]' ).click(function() {
	console.log( 'rdy for ajax request' );
});
