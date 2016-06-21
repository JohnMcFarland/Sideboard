<html lang='en' style="-webkit-font-smoothing: antialiased;">

<head>
	<title>Sideboard.io</title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" href="css/vendors.min.css" />
	<link rel="stylesheet" href="style.min.css" />
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto:100' rel='stylesheet' type='text/css' />
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

</head>

<?php

	include_once ('CSRFTokenGen.php');
	$token= CSRFTokenGen::generateToken();

 ?>


<body style="background: #fff; vertical-align: middle; font-family: 'Open Sans', sans-serif; overflow-x: hidden; margin: 0 auto; width: 1200px; margin: 0 auto; width: 100%; max-width: 1200px;">
	<input type="hidden" name="token_variable" value="<?php echo $token?>">
	<div id="header-container" class="row-fluid">
		<div class="container" style="width: 1200px; padding: 0;">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
				<a id="header-logo" href="front-page.php">Sideboard</a>
			</div>
			<?php
				if(isset($_SESSION['user_id'])) {
					$session_user = get_user_data($_SESSION['user_id']);
					if($session_user != NULL) { ?>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
							<h1 id="header-logout" name="header-logout">Logout</h1>
							<a id="header-username" href="<?php echo get_profile_url($session_user['email']); ?>"><?php echo $session_user['firstname'] . ' ' . $session_user['lastname']?></a>
						</div>
			<?php } } ?>
		</div>
	</div>
