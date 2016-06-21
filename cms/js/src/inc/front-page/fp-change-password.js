$( '#fp-change-password' ).on( 'keyup', 'input', function() {
	var $name = $( this ).attr( 'name' ),
			$value = $( this ).val(),
			$char_length_test = false,
			$cap_letter_test = false,
			$num_test = false;

	if ( $name == 'fp-change-password-password' ) {
		if ( $value.length !== 0 ) {
			if ( $value.length >= 8 ) {
				$char_length_test = true;
			}

			if ( $value.match( /([A-Z]){1,}/g ) ) {
				$cap_letter_test = true;
			}

			if ( $value.match( /([0-9]){1,}/g ) ) {
				$num_test = true;
			}

			if ( $char_length_test && $cap_letter_test && $num_test ) {
				$( this ).data( 'valid', true );
			} else {
				$( this ).data( 'valid', false );
			}
		} else {
			$( this ).data( 'valid', false );
		}

		if ( $value !== $( 'input[name=fp-change-password-confirm-password]' ).val() ) {
			$( 'input[name=fp-change-password-confirm-password]' ).data( 'valid', false );
		} else {
			$( 'input[name=fp-change-password-confirm-password]' ).data( 'valid', true );
		}
	} else if ( $name == 'fp-change-password-confirm-password' ) {
		$( this ).css({
			'background-image' : 'none'
		});

		if ( $value !== $( 'input[name=fp-change-password-password]' ).val() ) {
			$( this ).data( 'valid', false );
		} else {
			$( this ).data( 'valid', true );
		}
	}

	if ( $( 'input[name=fp-change-password-password]' ).data( 'valid' ) && $( 'input[name=fp-change-password-confirm-password]' ).data( 'valid' ) ) {
		$( 'button[name=fp-change-password-submit]' ).prop( 'disabled', false );
	} else {
		$( 'button[name=fp-change-password-submit]' ).prop( 'disabled', true );
	}
});

$( '#fp-change-password' ).on( 'focus', 'input', function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'fp-change-password-password' ) {
		$( '[data-toggle="popover"]' ).popover();
		$( this ).css({
			'background-image' : 'none'
		});
		$( this ).attr( 'data-toggle', 'popover' );
		$( this ).attr( 'data-content', 'Enter a combination of at least 8 characters containing one number and one character letter.' );
		$( this ).attr( 'data-trigger', 'focus' );
		$( this ).attr( 'data-placement', 'left' );
		if ( $( this ).data( 'valid' ) ) {
			$( '.popover' ).hide();
		} else {
			$( '.popover' ).show();
		}
	} else if ( $name == 'fp-change-password-confirm-password' ) {
		$( '[data-toggle="popover"]' ).popover();
		$( this ).css({
			'background-image' : 'none'
		});
		$( this ).attr( 'data-toggle', 'popover' );
		$( this ).attr( 'data-content', 'Please confirm your password.' );
		$( this ).attr( 'data-trigger', 'focus' );
		$( this ).attr( 'data-placement', 'left' );
		if ( $( this ).data( 'valid' ) ) {
			$( '.popover' ).hide();
		} else {
			$( '.popover' ).show();
		}
	}
});

$( '#fp-change-password' ).on( 'blur', 'input', function() {
	var $name = $( this ).attr( 'name' ),
			$value = $( this ).val();

	if ( $name == 'fp-change-password-password' ) {
		if ( ! $( this ).data( 'valid' ) && $value.length > 0 ) {
			$( this ).css({
				'background-image' : 'url("images/error-exclamation.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 3px'
			});
		} else if ( $( this ).data( 'valid' ) ) {
			$( this ).css({
				'background-image' : 'url("images/green-check.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 5px'
			});
		}
	} else if ( $name == 'fp-change-password-confirm-password' ) {
		if ( $value !== $( 'input[name=fp-change-password-password]' ).val() ) {
			$( this ).css({
				'background-image' : 'url("images/error-exclamation.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 3px'
			});
		} else if ( $( this ).data( 'valid' ) ) {
			$( this ).css({
				'background-image' : 'url("images/green-check.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 5px'
			});
		}
	}
});

$( 'button' ).click(function() {
	$name = $( this ).attr( 'name' );

	if ( $name == 'fp-change-password-submit' ) {
		var $password = $( 'input[name=fp-change-password-password]' ).val(),
				$email = $( 'input[name=fp-forgot-password-email]' ).val(); 

		$.ajax({
			type: 'POST',
			data: { email: $email, password: $password },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/login-change-password',
		}).then(function( response ) {
			if ( response.success ) {
				window.location = Sideboard.protocol + '//' + Sideboard.hostname + '/cms/front-page.php';
			}
		});
	}
});
