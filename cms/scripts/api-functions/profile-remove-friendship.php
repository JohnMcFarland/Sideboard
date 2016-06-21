<?php
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	$sender = POST['sender_id'];
	$receiver = POST['receiver_id'];

	$success = unfriend($sender_id, $receiver_id);

	print_r(json_encode($success));

	// function api_remove_friendship($sender_id, $receiver_id){
	//
	// 	$success = unfriend($sender_id, $receiver_id);
	//
	// 	return array("sucess" => $success);
	// }
?>
