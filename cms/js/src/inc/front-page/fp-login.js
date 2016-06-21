$( '#fp-login' ).on( 'focus', 'input', function() {
	if ( $( 'p[name=fp-login-fail]' ).is( ':visible' ) ) {
		$( 'p[name=fp-login-fail]' ).text( '' );
		$( 'p[name=fp-login-fail]' ).hide();
	}
});

$( 'button' ).click(function() {
	var $name = $( this ).attr( 'name' ), $email, $password, $password_key, $login_persistent;

	if ( $name == 'fp-login' ) {
		$( this ).prop( 'disabled', true );
		$( 'button[name=fp-signup]' ).prop( 'disabled', false );

		if ( $( '#fp-signup' ).is( ':visible' ) ) {
			$( '#fp-signup' ).hide();
		}

		if ( $( '#fp-forgot-password' ).is( ':visible' ) ) {
			$( '#fp-forgot-password' ).hide();
		}

		if ( $( '#fp-password-reset' ).is( ':visible' ) ) {
			$( '#fp-password-reset' ).hide();
		}

		if ( $( '#fp-change-password' ).is( ':visible' ) ) {
			$( '#fp-change-password' ).hide();
		}

		if ( $( '#fp-signup-success' ).is( ':visible' ) ) {
			$( '#fp-signup-success' ).hide();
		}

		$( '#fp-login' ).show();
	} else if ( $name == 'fp-login-submit' ) {
		$email = $( 'input[name=fp-login-email]' ).val();
		$password = $( 'input[name=fp-login-password]' ).val();
		$login_persistent = $( 'input[name=fp-login-persistent]' ).is( ':checked' );

		$.ajax({
			type: 'post',
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/login',
			data: { email: $email, password : $password, login_persistent: $login_persistent },
		}).then(function( response ) {
			if ( response.success ) {
				window.location = Sideboard.protocol + '//' + Sideboard.hostname + '/cms/' + response.profile_url;
			} else {
				$( 'p[name=fp-login-fail]' ).html( response.error );
				$( 'p[name=fp-login-fail]' ).show();
			}
		});

	} else if ( $name == 'fp-forgot-password-submit' ) {
		$email = $( 'input[name=fp-forgot-password-email]' ).val();

		$.ajax({
			type: 'post',
			data: { email: $email },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/forgot-password',
		}).then(function( response ) {
			if ( response.success ) {
				$( '#fp-forgot-password' ).hide();
				$( '#fp-password-reset' ).show();
			}
		});
	} else if ( $name == 'fp-password-reset-submit' ) {
		$email = $( 'input[name=fp-forgot-password-email]' ).val();
		$password_key = $( 'input[name=fp-forgot-password-reset-key]' ).val();

		$.ajax({
			type: 'post',
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/verify-password-code',
		}).then(function( response ) {
			if ( response.success ) {
				$( '#fp-password-reset' ).hide();
				$( '#fp-change-password' ).show();
			}
		});
	}
});

$( 'a' ).click(function() {
	$name = $( this ).attr( 'name' );

	if ( $name == 'fp-forgot-password' ) {
		$('button[name=fp-login]').prop('disabled', false);
		$( '#fp-login' ).hide();
		$( '#fp-forgot-password' ).show();
	}
});

$( '#fp-login' ).on( 'click', 'a', function() {
	var $name = $( this ).attr( 'name' ),
			$email = $( 'input[name=fp-login-email]' ).val();

	if ( $name == 'resend-validation' ) {

		$.ajax({
			type: 'POST',
			data: { email: $email },
			url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/login-resend-validation.php',
		}).then(function( response ) {
			$response = $.parseJSON( response );
			if ( $response.success ) {
				$( 'p[name=fp-login-fail]' ).removeClass( 'bg-danger' ).addClass( 'bg-success' ).text( 'We have resent a verification email.' );
			} else {
				$( 'p[name=fp-login-fail]' ).text( 'That email does not exist in the database.' );
			}
		});
	}
});
