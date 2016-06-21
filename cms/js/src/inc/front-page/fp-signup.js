// whenever the user registers a key stroke in any input field on the signup form,
// perform logic on the input field according to their name attribute
$( '#fp-signup' ).on( 'keyup', 'input', function() {
	var $name = $( this ).attr( 'name' ),
			$value = $( this ).val(),
			$char_length_test = false,
			$cap_letter_test = false,
			$num_test = false,
			$email = $( 'input[name=fp-signup-email]' ).data( 'valid' ),
			$password = $( 'input[name=fp-signup-password]' ).data( 'valid' ),
			$firstname = $( 'input[name=fp-signup-firstname]' ).data( 'valid' ),
			$lastname = $( 'input[name=fp-signup-lastname]' ).data( 'valid' ),
			$birthday = $( 'input[name=fp-signup-birthday]' ).data( 'valid' );

	if ( $name == 'fp-signup-email' ) {
		$( 'input[name=fp-signup-email]' ).css({
			'background-image' : 'none'
		});

		if ( ! $value.match( /[@]+[a-z]+[.]+[a-z]{1,}/g ) ) {
			$( 'input[name=fp-signup-email]' ).data( 'valid', false );
		} else {
			$( 'input[name=fp-signup-email]' ).data( 'valid', true );
			$.ajax({
				type: 'post',
				url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/signup-email',
				data: { email: $value }
			}).then(function( response ) {
				if ( ! response.success ) {
					$( 'input[name=fp-signup-email]' ).css({
						'background-image' : 'url("images/error-exclamation.png")',
						'background-repeat' : 'no-repeat',
						'background-position' : 'right 3px'
					});

					$( 'p[name=fp-email-fail]' ).show();
					$( 'input[name=fp-signup-email]' ).data( 'valid', false );
				}	else {
					$( 'input[name=fp-signup-email]' ).css({
						'background-image' : 'url("images/green-check.png")',
						'background-repeat' : 'no-repeat',
						'background-position' : 'right 5px'
					});
					$( 'p[name=fp-email-fail]' ).hide();
				}
			});
		}
	} else if ( $name == 'fp-signup-password' ) {
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

		if ( $value !== $( 'input[name=fp-signup-confirm-password]' ).val() ) {
			$( 'input[name=fp-signup-confirm-password]' ).data( 'valid', false );
		} else {
			$( 'input[name=fp-signup-confirm-password]' ).data( 'valid', true );
		}
	} else if ( $name == 'fp-signup-confirm-password' ) {
		$( this ).css({
			'background-image' : 'none'
		});

		if ( $value !== $( 'input[name=fp-signup-password]' ).val() ) {
			$( this ).data( 'valid', false );
		} else {
			$( this ).data( 'valid', true );
		}
	} else if ( $name == 'fp-signup-firstname' ) {
		$( this ).css({
			'background-image' : 'none'
		});

		if ( $value.match( /[^a-zA-Z\s]{1,}/g ) || ! $value.match( /[a-zA-Z]{1,}/g ) ) {
			$( this ).data( 'valid', false );
		} else {
			$( this ).data( 'valid', true );
		}
	} else if ( $name == 'fp-signup-lastname' ) {
		$( this ).css({
			'background-image' : 'none'
		});

		if ( $value.match( /[^a-zA-Z\s]{1,}/g ) || ! $value.match( /[a-zA-Z]{1,}/g ) ) {
			$( this ).data( 'valid', false );
		} else {
			$( this ).data( 'valid', true );
		}
	}

	// this is the check at the end of each key stroke that checks every data-valid attribute of each of the input fields,
	// if they are all true ( meaning they are all valid ), then the button becomes enabled, otherwise it will remain disabled
	if ( $( 'input[name=fp-signup-email]' ).data( 'valid' ) && $( 'input[name=fp-signup-password]' ).data( 'valid' ) && $( 'input[name=fp-signup-confirm-password]' ).data( 'valid' ) && $( 'input[name=fp-signup-firstname]' ).data( 'valid' ) && $( 'input[name=fp-signup-lastname]' ).data( 'valid' ) && $( 'input[name=fp-signup-birthday]' ).data( 'valid' ) && $( 'select[name=fp-signup-gender]' ).data( 'valid' ) ) {
		$( 'button[name=fp-signup-submit]' ).prop( 'disabled', false );
	} else {
		$( 'button[name=fp-signup-submit]' ).prop( 'disabled', true );
	}

});

$( '#fp-signup' ).on( 'change', 'select', function() {
	$name = $( this ).attr( 'name' );

	if ( $name == 'fp-signup-gender' ) {
		if ( $( 'select[name=fp-signup-gender] option:selected' ).text() == 'Gender' ) {
			$( 'select[name=fp-signup-gender]' ).data( 'valid', false );
		} else {
			$( 'select[name=fp-signup-gender]' ).data( 'valid', true );
		}
	} else {
		var $birthday = $( 'select[name=fp-signup-birthday-month]' ).val() + '/' + $( 'select[name=fp-signup-birthday-day]' ).val() + '/' + $( 'select[name=fp-signup-birthday-year]' ).val(),
				$valid = moment( $birthday, 'MM/DD/YYYY', true ).isValid(),
				$max_date = moment().subtract( '12', 'years' ).calendar();

		if ( moment( new Date( $birthday ) ).isBefore( $max_date ) ) {
			$( 'input[name=fp-signup-birthday]' ).data( 'valid', true );
		} else {
			$( 'input[name=fp-signup-birthday]' ).data( 'valid', false );
		}
	}

	if ( $( 'input[name=fp-signup-email]' ).data( 'valid' ) && $( 'input[name=fp-signup-password]' ).data( 'valid' ) && $( 'input[name=fp-signup-confirm-password]' ).data( 'valid' ) && $( 'input[name=fp-signup-firstname]' ).data( 'valid' ) && $( 'input[name=fp-signup-lastname]' ).data( 'valid' ) && $( 'input[name=fp-signup-birthday]' ).data( 'valid' ) && $( 'select[name=fp-signup-gender]' ).data( 'valid' ) ) {
		$( 'button[name=fp-signup-submit]' ).prop( 'disabled', false );
	} else {
		$( 'button[name=fp-signup-submit]' ).prop( 'disabled', true );
	}

});

$( '#fp-signup' ).on( 'blur', 'select', function() {
	$name = $( this ).attr( 'name' );

	if ( $name == 'fp-signup-gender' ) {
		if ( $( 'select[name=fp-signup-gender] option:selected' ).text() == 'Gender' ) {
			$( '#gender-msg' ).html( '<i class="fa fa-exclamation-circle" style="color: #B94A48; font-size: 1.8em; position: absolute; top: 28px; right: 24px;"></i>' );
			$( '#gender-msg' ).show();
		} else {
			$( '#gender-msg' ).html( '<i class="fa fa-check-circle" style="color: green; font-size: 1.8em; position: absolute; top: 28px; right: 24px;"></i>' );
			$( '#gender-msg' ).show();
		}
	} else {
		var $birthday = $( 'select[name=fp-signup-birthday-month]' ).val() + '/' + $( 'select[name=fp-signup-birthday-day]' ).val() + '/' + $( 'select[name=fp-signup-birthday-year]' ).val(),
				$valid = moment( $birthday, 'MM/DD/YYYY', true ).isValid(),
				$max_date = moment().subtract( '12', 'years' ).calendar();

		if ( ! moment( new Date( $birthday ) ).isBefore( $max_date ) ) {
			$( '#birthday-msg' ).html( '<i class="fa fa-exclamation-circle" style="color: #B94A48; font-size: 1.8em; position: absolute; top: 28px; right: -10px;"></i>' );
			$( '#birthday-msg' ).show();
		} else if ( $( 'input[name=fp-signup-birthday]' ).data( 'valid' ) ) {
			$( '#birthday-msg' ).html( '<i class="fa fa-check-circle" style="color: green; font-size: 1.8em; position: absolute; top: 28px; right: -10px;"></i>' );
			$( '#birthday-msg' ).show();
		} else {
			$( '#birthday-msg' ).html( '<i class="fa fa-exclamation-circle" style="color: #B94A48; font-size: 1.8em; position: absolute; top: 28px; right: -10px;"></i>' );
			$( '#birthday-msg' ).show();
		}
	}
});

$( '#fp-signup' ).on( 'focus', 'select', function() {
	$name = $( this ).attr( 'name' );

	if ( $name == 'fp-signup-birthday-month' || $name == 'fp-signup-birthday-day' || $name == 'fp-signup-birthday-year' ) {
		if ( $( '#birthday-msg' ).is( ':visible' ) ) {
			$( '#birthday-msg' ).hide();
		}
	}

	if ( $name == 'fp-signup-gender' ) {
		if ( $( '#gender-msg' ).is( ':visible' ) ) {
			$( '#gender-msg' ).hide();
		}
	}

	// right now this doesnt work
	$( '[data-toggle="popover"]' ).popover({
		container: $( 'input[name=fp-signup-birthday-month]' ).parent().parent()
	});

	$( 'birthday-msg' ).hide();
	$( 'input[name=fp-signup-birthday-month]' ).attr( 'data-toggle', 'popover' );
	$( 'input[name=fp-signup-birthday-month]' ).attr( 'data-content', 'Please select a birthday. You can change this later.' );
	$( 'input[name=fp-signup-birthday-month]' ).attr( 'data-trigger', 'focus' );
	$( 'input[name=fp-signup-birthday-month]' ).attr( 'data-placement', 'left' );
	if ( $( 'input[name=fp-signup-birthday]' ).data( 'valid' ) ) {
		$( '.popover' ).hide();
	} else {
		$( '.popover' ).show();
	}
});


// whenever the user leaves an input field on the signup form,
// perform style changes according to their name attribute
$( '#fp-signup' ).on( 'blur', 'input', function() {
	var $name = $( this ).attr( 'name' ),
			$value = $( this ).val();

	if ( $name == 'fp-signup-email' ) {
		if ( ! $value.match( /[@]+[a-z]+[.]+[a-z]{1,}/g ) || $value.match( /[@]+[a-z]+[.]+[a-z]{1,}/g ) && ! $( this ).data( 'valid' ) ) {
			if ( $value.length !== 0 ) {
				$( this ).css({
					'background-image' : 'url("images/error-exclamation.png")',
					'background-repeat' : 'no-repeat',
					'background-position' : 'right 3px'
				});
			}
		}	else if ( $( this ).data( 'valid' ) ) {
			$( this ).css({
				'background-image' : 'url("images/green-check.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 5px'
			});
			$( 'p[name=fp-email-fail]' ).hide();
		}
	} else if ( $name == 'fp-signup-password' ) {
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
	} else if ( $name == 'fp-signup-confirm-password' ) {
		if ( $( this ).data( 'valid' ) && $( 'input[name=fp-signup-password]' ).data( 'valid' ) ) {
			$( this ).css({
				'background-image' : 'url("images/green-check.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 5px'
			});
		} else {
			$( this ).css({
				'background-image' : 'url("images/error-exclamation.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 3px'
			});
		}
	} else if ( $name == 'fp-signup-firstname' || $name == 'fp-signup-lastname' ) {
		if ( $value.match( /[^a-zA-Z\s]{1,}/g ) || ! $value.match( /[a-zA-Z]{1,}/g ) ) {
			if ( $value.length !== 0 ) {
				$( this ).css({
					'background-image' : 'url("images/error-exclamation.png")',
					'background-repeat' : 'no-repeat',
					'background-position' : 'right 3px'
				});
			}
		} else if ( $( this ).data( 'valid' ) ) {
			$( this ).css({
				'background-image' : 'url("images/green-check.png")',
				'background-repeat' : 'no-repeat',
				'background-position' : 'right 5px'
			});
		}
	}
});

// whenever the user enters an input field on the signup form,
// performs popover manipulation according to their name attributes
$( '#fp-signup' ).on( 'focus', 'input', function() {
	var $name = $( this ).attr( 'name' );
	var $value = $( this ).val();

	if ( $name == 'fp-signup-email' ) {
		$( '[data-toggle="popover"]' ).popover();
		$( this ).css({
			'background-image' : 'none'
		});
		$( this ).attr( 'data-toggle', 'popover' );
		$( this ).attr( 'data-content', 'You\'ll use this when you log in and if you ever need to reset your password.' );
		$( this ).attr( 'data-trigger', 'focus' );
		$( this ).attr( 'data-placement', 'left' );
		if ( $( this ).data( 'valid' ) ) {
			$( '.popover' ).hide();
		} else {
			$( '.popover' ).show();
		}
	} else if ( $name == 'fp-signup-password' ) {
		$( '[data-toggle="popover"]' ).popover();
		$( this ).css({
			'background-image' : 'none'
		});
		$( this ).attr( 'data-toggle', 'popover' );
		$( this ).attr( 'data-content', 'Password must have a minimum of 8 characters and must contain at least one number and one capital letter.' );
		$( this ).attr( 'data-trigger', 'focus' );
		$( this ).attr( 'data-placement', 'left' );
		if ( $( this ).data( 'valid' ) ) {
			$( '.popover' ).hide();
		} else {
			$( '.popover' ).show();
		}
	} else if ( $name == 'fp-signup-confirm-password' ) {
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
	} else if ( $name == 'fp-signup-firstname' || $name == 'fp-signup-lastname' ) {
		if ( $name == 'fp-signup-lastname' ) {
			$placement = 'bottom';
			$( '[data-toggle="popover"]' ).popover({
				container: $( this ).parent().parent()
			});
		} else {
			$placement = 'left';
			$( '[data-toggle="popover"]' ).popover();
		}

		$( this ).css({
			'background-image' : 'none'
		});
		$( this ).attr( 'data-toggle', 'popover' );
		$( this ).attr( 'data-content', 'What\'s your name?' );
		$( this ).attr( 'data-trigger', 'focus' );
		$( this ).attr( 'data-placement', $placement );
		if ( $( this ).data( 'valid' ) ) {
			$( '.popover' ).hide();
		} else {
			$( '.popover' ).show();
		}
	}
});

$( '#fp-signup' ).on( 'click', 'input[type=radio]', function() {
	$( 'input[name=fp-signup-gender]' ).data( 'valid', true );
	$( 'input[name=fp-signup-gender]' ).val( $( this ).val() );

	if ( $( 'input[name=fp-signup-email]' ).data( 'valid' ) && $( 'input[name=fp-signup-password]' ).data( 'valid' ) && $( 'input[name=fp-signup-confirm-password]' ).data( 'valid' ) && $( 'input[name=fp-signup-firstname]' ).data( 'valid' ) && $( 'input[name=fp-signup-lastname]' ).data( 'valid' ) && $( 'input[name=fp-signup-birthday]' ).data( 'valid' ) && $( 'select[name=fp-signup-gender]' ).data( 'valid' ) ) {
		$( 'button[name=fp-signup-submit]' ).prop( 'disabled', false );
	} else {
		$( 'button[name=fp-signup-submit]' ).prop( 'disabled', true );
	}
});

$( 'button' ).click(function() {
	var $name = $( this ).attr( 'name' );

	if ( $name == 'fp-signup' ) {
		$( this ).prop( 'disabled', true );
		$( 'button[name=fp-login]' ).prop( 'disabled', false );

		if ( $( '#fp-login' ).is( ':visible' ) ) {
			$( '#fp-login' ).hide();
		}

		if ( $( '#fp-forgot-password' ).is( ':visible' ) ) {
			$( '#fp-forgot-password' ).hide();
		}

		if ( $( '#fp-change-password' ).is( ':visible' ) ) {
			$( '#fp-change-password' ).hide();
		}

		if ( $( '#fp-signup-success' ).is( ':visible' ) ) {
			$( '#fp-signup-success' ).hide();
		}

		$( '#fp-signup' ).show();
	} else if ( $name == 'fp-signup-submit' ) {
		$email = $( 'input[name=fp-signup-email]' ).val();
		$password = $( 'input[name=fp-signup-password]' ).val();
		$username = $( 'input[name=fp-signup-username]' ).val();
		$first = $( 'input[name=fp-signup-firstname]' ).val();
		$last = $( 'input[name=fp-signup-lastname]' ).val();
		$birthday = $( 'select[name=fp-signup-birthday-year]' ).val() + '-' + $( 'select[name=fp-signup-birthday-month]' ).val() + '-' + $( 'select[name=fp-signup-birthday-day]' ).val();
		$gender = $( 'select[name=fp-signup-gender] option:selected' ).val();
		$signup_persistent = $( 'input[name=fp-signup-persistent]').is( ':checked' );

		$.ajax({
			type: 'post',
			data: { email: $email, password : $password, first: $first, last: $last, birthday: $birthday, signup_persistent: $signup_persistent, gender: $gender },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/signup',
		}).then(function( response ) {

			if ( response.success ) {
				$( '#fp-signup' ).hide();
				$( '#fp-signup-success' ).show();
			}
		});
	}
});
