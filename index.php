<?php
	if(!isset($_SESSION)) { session_start(); }
	require realpath(  dirname( __FILE__ ) ) .'/cms/scripts/functions.php';

  // Are we logged in?
  $logged_in = isset( $_SESSION[ 'user_id' ] ) && user_exists( $_SESSION[ 'user_id' ] );
	if ( $logged_in ) {
		redirect('/cms/profile.php');
	}
  else {
    redirect('/cms/front-page.php');
  }
?>
