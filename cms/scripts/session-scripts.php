<?php
  if(!isset($_SESSION)) { session_start(); }
  require_once realpath(  dirname( __FILE__ ) ) . '/functions.php';

  // If user is not logged in, then check if persistent login cookie exists
  if(!isset($_SESSION['user_id'])) {
    if(isset($_COOKIE['login_token']) && !empty($_COOKIE['login_token'])) {
      attempt_persistent_login($_COOKIE['login_token']);
    }
  }

  if(isset($_SESSION['user_id'])) {
    update_site_visit($_SESSION['user_id']);
  }
?>
