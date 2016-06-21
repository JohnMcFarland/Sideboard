<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function signup($firstname, $lastname, $password, $email, $birthday, $gender){
		if(!isset($_SESSION)) { session_start(); }
		$success = signup_user($email, $password, $firstname, $lastname, $birthday, $gender);

		return  array(
			'success' => $success,
			'name' => $firstname . ' ' . $lastname,
			'password' => $password,
			'gender' => $gender,
			'email' => $email,
			'birthday' => $birthday
			);
	}
 ?>