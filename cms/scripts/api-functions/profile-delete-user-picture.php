<?php 
	 require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	 function delete_user_picture($image_id){
	 	if(!isset($_SESSION)) { session_start(); }
	 	delete_image($image_id);
	 }
 ?>