<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function signup_email($email){
		//if(!isset($_SESSION)) { session_start(); }
		$valid = !does_email_already_exist($email);

		return array(
			"success" => $valid 
			//"POST" => $email
		);
	}
 ?>