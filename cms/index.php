<?php
	if(!isset($_SESSION)) { session_start(); }
	require realpath(  dirname( __FILE__ ) ) .'/scripts/functions.php';


  // Are we logged in?
  $logged_in = isset( $_SESSION[ 'user_id' ] ) && user_exists( $_SESSION[ 'user_id' ] );
	if ( $logged_in ) {
		redirect('profile.php');
	}
  else {
    redirect('front-page.php');
  }
?>
