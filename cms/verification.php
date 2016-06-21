<!DOCTYPE html>
<html lang='en' style="text-align: center; -webkit-font-smoothing: antialiased;">
<?php require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php'; ?>


<head>
	<title>Sideboard.io | Verification Success</title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" href="css/vendors.min.css" />
	<link rel="stylesheet" href="style.min.css" />
	<link href='https://fonts.googleapis.com/css?family=Roboto:100' rel='stylesheet' type='text/css'>

</head>

<body style="background: #fff; vertical-align: middle; font-family: 'Roboto', sans-serif; padding-top: 100px; display: inline-block;">

	<?php

	if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
	  $email = $_GET['email'];
	  $hash = $_GET['hash'];

	  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	  $query = "SELECT COUNT(*) FROM Users WHERE UPPER(email) LIKE UPPER(?) AND signup_hash=? AND valid=0";
	  $stmt = $db_pdo->prepare($query);
	  $stmt->execute(array($email, $hash));
	  db_log_command($query);

	  // if we find a row in the DB, then a match has been found!
	  $valid = ($stmt->fetchColumn() > 0);

	  if($valid) {
	    // Set validation in database to be true
	    $query = "UPDATE Users SET valid='1' WHERE UPPER(email) LIKE UPPER(?)";
	    $stmt = $db_pdo->prepare($query);
	    $stmt->execute(array($email));
		  db_log_command($query);

	    $query = "SELECT firstname, lastname FROM Users WHERE UPPER(email) LIKE (?)";
	    $stmt = $db_pdo->prepare($query);
	    $stmt->execute(array($email));
		  db_log_command($query);
	    $results = $stmt->fetch();
	    ?>
	<div style="padding: 1.5rem;">
		<span class="animated lightSpeedIn" style="display: block">
			<h1 style="padding: 5px; font-size: 6rem; font-weight: 100; letter-spacing: -.05em; color: #f35626; background-image: -webkit-linear-gradient(92deg,#f35626,#feab3a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; -webkit-animation: hue 60s infinite linear;">Sideboard.io</h1>
		</span>
	</div>

	<div class="row animated fadeIn" id="fp-login" style="border: 1px solid #ccc; padding: 25px; border-radius: 20px; margin-top: 40px; width: 430px; background: rgb(238,238,238); background: -moz-linear-gradient(top, rgba(238,238,238,1) 0%, rgba(204,204,204,1) 100%); background: -webkit-linear-gradient(top, rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); background: linear-gradient(to bottom, rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 );">
		<fieldset>
			<legend>Login</legend>
			<p style="margin: 0;">Validation success!</p>
			<p style="margin: 0;">Welcome to Sideboard <strong><?php echo $_GET['first'] . ' ' . $_GET['last']; ?></strong>!</p>
			<p class="bg-danger" name="fp-login-fail" style="display: none; padding: 5px; margin: 0 0 15px 0; border-radius: 4px; border: 1px solid rgb(204, 204, 204);"></p>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon" id="login-email-icon"><i class="fa fa-user"></i></span>
					<input type="email" class="form-control" name="fp-login-email" data-valid="false" placeholder="Email" aria-describedby="login-email-icon" style="font-weight: 700;" maxlength="256"/>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon" id="login-pasword-icon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" name="fp-login-password" data-valid="false" placeholder="Password"aria-describedby="login-password-icon" style="font-weight: 700;" maxlength="32"/>
				</div>
				<a href="#" name="fp-forgot-password" style="float: right; color: #337ab7; font-weight: 700;">Forgot your password?</a>
			</div>
		</fieldset>

		<fieldset>
			<div class="row">
				<div class="form-group">
					<div class="checkbox" style="margin-top: 19px; margin-bottom: 0;">
						<label><input type="checkbox" name="fp-login-persistent" style="margin-top: 5px;"/> Remember me</label>
					</div>
				</div>
			</div>
		</fieldset>
		<button name="fp-login-submit" class="btn btn-info" style="width: 100%; font-weight: 700; border-radius: 5px;" disabled>Submit</button>
	</div>

	<!-- <div class="animated fadeIn" style="margin-bottom: 150px; font-size: 33px; border: 1px solid #ddd; padding: 25px; border-radius: 4px;">
		<p style="margin: 0;">Validation success!</p>
		<p style="margin: 0;">Welcome to Sideboard <strong><?php echo $_GET['first'] . ' ' . $_GET['last']; ?></strong>!</p>
		<p style="margin: 0;">Your account has been verified, and you are ready to begin your adventure in Magic!</p>
		<a href="http://sideboard.io/refactor/cms/<?php echo get_profile_url( $email ); ?>"><p style="margin: 0;">Enter Site</p></a>
	</div> -->

	<div class="row">
		<div class="bg-danger fp-danger"></div>
	</div>

	<?php
		}
		// Validation failed
		else {
			displayError();
		}
	}
		// bad GET variables
	else {
		displayError();
	}

	// This function will display HTML code as an error
	function displayError() { ?>

		<div style="padding: 1.5rem;">
			<span class="animated lightSpeedIn" style="display: block">
				<h1 style="padding: 5px; font-size: 6rem; font-weight: 100; letter-spacing: -.05em; color: #f35626; background-image: -webkit-linear-gradient(92deg,#f35626,#feab3a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; -webkit-animation: hue 60s infinite linear;">Sideboard.io</h1>
			</span>
		</div>

		<div class="animated zoomIn" style="margin-bottom: 150px; font-size: 33px; border: 1px solid #ddd; padding: 25px; border-radius: 4px;">
			<p style="margin: 0;">Oops!</p>
			<p style="margin: 0;">You've gotten to this page by error.</p>
			<p style="margin: 0;">Please return to the front page of the site by clicking <a href="front-page.php">here</a>.</p></p>
		</div>

		<div class="row">
			<div class="bg-danger fp-danger"></div>
		</div>

	<?php } ?>

	<script type="text/javascript" src="js/src/local.js"></script>
	<script type="text/javascript" src="js/vendors.min.js"></script>
	<script type="text/javascript" src="js/sideboard.min.js"></script>

</body>

</html>
