<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function login_change_password($email, $password){
		if(!isset($_SESSION)) { session_start(); }

		$success = change_password($email, $password);

		if($success)
	    	$success = login_user($email, $password); // 0 = no error code

		return array("success"  => $success);
	}

 ?>