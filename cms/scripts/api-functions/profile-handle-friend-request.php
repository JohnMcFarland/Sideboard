<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function profile_handle_friend_request($responder_id, $sender_id, $fr_accept){

		$success = handle_friend_request($responder_id, $sender_id, $fr_accept);

		return array("success" => $success);
	}
 ?>