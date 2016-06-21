<?php 	
	 require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	 function friend_request($sender_id, $receiver_id){
	 	if(!isset($_SESSION)) { session_start(); }

	 	$success = send_friend_request($sender_id, $receiver_id);

	 	return array("success" => $success);
	 }
 ?>