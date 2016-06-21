<?php
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function forgot_password($email){
		if(!isset($_SESSION)) { session_start(); }

		$success = forgot_password_send_email($email);
		$response_array = array("success"  => $success);

		return $response_array;
	}
 ?>
