<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function logout($email){
		$success = logout_user($email);

		return array('success' => $success);
	}
 ?>