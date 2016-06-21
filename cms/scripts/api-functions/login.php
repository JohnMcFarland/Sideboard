<?php

// if(!isset($_SESSION)) { session_start(); }
require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

/**
  * Attempt to log the user given the passed credentials
  * @param: String email The user's email
  * @param: String password The user's password
  * @param: Boolean persistent Whether or not to remember the user's login information.
**/
function login($email, $password, $persistent) {
  if(!isset($_SESSION)) { session_start(); }
  $success = false;
  $error = "";

  $email_empty      = empty($email);
  $email_valid      = is_valid_email($email);
  $email_exists     = does_email_already_exist($email);
  $password_empty   = empty($password);
  $password_matches = validate_password($email, $password, true);
  $valid_check      = is_user_validated($email);
  $persistent       = empty($persistent) ? false : $persistent;         // set persistent to false if empty

  // Evaluate any errors (only one will appear, in order of heirachy)
  if($email_empty)            $error = 'You have not entered an email (required).';
  else if(!$email_valid)      $error = 'You have entered an email in an invalid format.';
  else if(!$email_exists)     $error = 'You have entered an email that does not exist in our database.';
  else if($password_empty)    $error = 'You have not entered a password (required).';
  else if(!$password_matches) $error = 'The entered password does not match our entry in the database.';
  else if(!$valid_check)      $error = 'This account is not validated. Please validate your username by clicking the link in your email.<br/>If you lost your validation email, please <a href="#" name="resend-validation"style="color: #2098D1; font-weight: 700;">click here</a> to send a new validation email.';
  else {
    // Attempt to login using the $_POST information
    $success = login_user($email, $password, $persistent);
  }

  return array(
    "success" => $success,
    "error" => $error,
    "profile_url" => get_profile_url($email)
  );
}

?>
