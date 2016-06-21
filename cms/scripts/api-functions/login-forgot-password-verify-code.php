<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function verify_code($email, $code){
		if(!isset($_SESSION)) { session_start(); }
		$success = forgot_password_verify_code($email, $code);
		$response_array = array("success"  => $success);

		return $response_array;
	}
 ?>