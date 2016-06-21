<?php 
	if(!isset($_SESSION)) { session_start(); }
	require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';
	require_once realpath( dirname( __FILE__ ) ) . '/scripts/functions.php';
	include_once('CSRFTokenGen.php');

	$valid = false;
	if(isset($_SESSION['user_id'])) {
	  $valid = is_admin($_SESSION['user_id']);
	}

	if(!$valid) {
	  redirect("404.php");
	}

	require_once realpath( dirname( __FILE__ ) ) . '/header.php';

?>

<div class="search_container" id="search_body">
	Test
</div>