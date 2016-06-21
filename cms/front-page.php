<?php
	if(!isset($_SESSION)) { session_start(); }
	require_once realpath(  dirname( __FILE__ ) ) .'/scripts/functions.php';

	// Redirect user to profile.php if he is logged in
	if ( isset( $_SESSION[ 'user_id' ] ) && user_exists( $_SESSION[ 'user_id' ] ) ) {
		redirect( get_profile_url( $_SESSION[ 'user_id' ] ) );
	}

	// token that is passed with every ajax request
	// include_once ('CSRFTokenGen.php');
	// $token = CSRFTokenGen::generateToken();
?>

<html id="fp-html" lang='en'>
<head>
	<title>Sideboard.io</title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" href="css/vendors.min.css" />
	<link rel="stylesheet" href="style.min.css" />
	<link href='https://fonts.googleapis.com/css?family=Roboto:100' rel='stylesheet' type='text/css'>

</head>

<body id="fp-body">
	<input type="hidden" name="token_variable" value="<?php echo $token?>">
	<div id="fp-header">
		<span class="">
			<h1>Sideboard.io</h1>
		</span>
	</div>

	<div class="" id="fp-container">

		<div class="row">
			<button class="btn btn-info" name="fp-login" disabled>Login</button>
			<button class="btn btn-info" name="fp-signup">Signup</button>
		</div>

		<div class="row" id="fp-login">
			<fieldset>
				<legend>Login</legend>
				<p class="bg-danger" name="fp-login-fail"></p>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="login-email-icon"><i class="fa fa-user"></i></span>
						<input type="email" class="form-control" name="fp-login-email" data-valid="false" placeholder="Email" aria-describedby="login-email-icon" maxlength="256"/>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="login-pasword-icon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" name="fp-login-password" data-valid="false" placeholder="Password" aria-describedby="login-password-icon" maxlength="32"/>
					</div>
					<a href="#" name="fp-forgot-password">Forgot your password?</a>
				</div>
			</fieldset>

			<fieldset>
				<div class="row">
					<div class="form-group">
						<div class="checkbox">
							<label><input type="checkbox" name="fp-login-persistent" /> Remember me</label>
						</div>
					</div>
				</div>
			</fieldset>
	    <button name="fp-login-submit" class="btn btn-info">Submit</button>
		</div>

		<div class="row" id="fp-signup">
			<fieldset>
				<legend>Sign Up</legend>
				<p class="bg-danger" name="fp-email-fail">This email already exists in the database.</p>
					<div class="row">
						<div class="col-xs-6 form-group">
							<input type="text" class="form-control" name="fp-signup-firstname" placeholder="First name" data-valid="false" maxlength="128"/>
						</div>
						<div class="col-xs-6 form-group">
							<input type="text" class="form-control" name="fp-signup-lastname" placeholder="Last name" data-valid="false" maxlength="128"/>
						</div>
					</div>
			</fieldset>

			<fieldset>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="signup-email-icon"><i class="fa fa-user"></i></span>
						<input type="email" class="form-control" name="fp-signup-email" placeholder="Email" data-valid="false" aria-describedby="signup-email-icon" maxlength="256"/>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="signup-pasword-icon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" name="fp-signup-password" placeholder="Password" data-valid="false" aria-describedby="signup-password-icon" maxlength="32"/>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="signup-pasword-icon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" name="fp-signup-confirm-password" placeholder="Confirm password" data-valid="false" aria-describedby="signup-password-icon" maxlength="32"/>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="row" id="fp-signup-birthday">
					<input type="hidden" name="fp-signup-birthday" data-valid="false" />
					<div class="col-xs-7 form-group">
						<label for="fp-signup-birthday">Date of Birth:</label>
						<input type="hidden" name="fp-signup-birthday" />
						<select name="fp-signup-birthday-month">
							<option>Month</option>
							<option value="01">Jan</option>
							<option value="02">Feb</option>
							<option value="03">Mar</option>
							<option value="04">Apr</option>
							<option value="05">May</option>
							<option value="06">Jun</option>
							<option value="07">Jul</option>
							<option value="08">Aug</option>
							<option value="09">Sep</option>
							<option value="10">Oct</option>
							<option value="11">Nov</option>
							<option value="12">Dec</option>
						</select>
						<select name="fp-signup-birthday-day">
							<option>Day</option>
							<?php
								for( $day = 1; $day <= 31; $day++ ) {
									if ( $day >= 1 && $day <= 9 ) {
										$value_day = '0' . $day;
									} else {
										$value_day = $day;
									}
							?>
							<option value="<?php echo $value_day; ?>"><?php echo $day; ?></option>
							<?php } ?>
						</select>
						<select name="fp-signup-birthday-year">
							<option>Year</option>
							<?php
								for( $year = 2015; $year >= 1910; $year-- ) { ?>
									<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
								<?php } ?>
						</select>
						<div id="birthday-msg">
							<i class="fa fa-exclamation-circle"></i>
						</div>
					</div>
					<div id="fp-signup-gender-container" class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
						<div id="fp-signup-gender" class="row">
							<select name="fp-signup-gender" data-valid="false">
								<option>Gender</option>
								<option value="F">Female</option>
								<option value="M">Male</option>
								<option value="O">Non-binary</option>
							</select>
						</div>
						<div id="gender-msg">
							<i class="fa fa-exclamation-circle"></i>
						</div>
					</div>
				</div>
			</fieldset>

	    <button name="fp-signup-submit" class="btn btn-info" disabled>Submit</button>
		</div>

		<div class="row" id="fp-forgot-password">
			<fieldset>
				<legend>Forgot Password</legend>
				<p class="bg-danger" name="fp-password-reset-fail"></p>
				<p>Please enter your email, and we will send you a randomly generated password that you can login with. Once logged in, we recommend you change your password.</p>
				<div class="form-group">
					<input type="email" class="form-control" name="fp-forgot-password-email" placeholder="Email" />
				</div>
			</fieldset>

			<button class="btn btn-info" name="fp-forgot-password-submit">Submit</button>
		</div>

		<div class="row" id="fp-password-reset">
			<fieldset>
				<legend>Forgot Password</legend>
				<p>Please enter the six digit password reset key.</p>
				<div class="form-group">
					<input type="text" class="form-control" name="fp-forgot-password-reset-key" placeholder="Password Reset Key" maxlength="6" />
				</div>
			</fieldset>

			<button class="btn btn-info" name="fp-password-reset-submit">Submit</button>
		</div>

		<div class="row" id="fp-change-password">
			<fieldset>
				<legend>Change Password</legend>
				<p>Please change your password.</p>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="change-password-pasword-icon"><i class="fa fa-lock"></i></span>
						<input type="hidden" name="fp-change-password-email" value="<?php echo $_POST[ 'email' ]; ?>" />
						<input type="password" class="form-control" name="fp-change-password-password" placeholder="Password" data-valid="false" aria-describedby="change-password-password-icon" maxlength="32"/>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="change-password-pasword-icon"><i class="fa fa-lock"></i></span>
						<input type="password" class="form-control" name="fp-change-password-confirm-password" placeholder="Confirm Password" data-valid="false" aria-describedby="change-password-password-icon" maxlength="32"/>
					</div>
				</div>
			</fieldset>

			<button class="btn btn-info" name="fp-change-password-submit">Submit</button>
		</div>

		<div class="row" id="fp-signup-success">
			<p>Congratulations you have officially signed up to Sideboard.io!</p>
			<p>Please verify your account by going to your email.</p>
		</div>

	</div>

	<script type="text/javascript" src="js/src/local.js"></script>
	<script type="text/javascript" src="js/vendors.min.js"></script>
	<script type="text/javascript" src="js/sideboard.min.js"></script>

</body>

</html>
