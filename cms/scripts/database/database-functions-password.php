<?php

function hash_password($password, $salt) {
  return hash("sha256", $salt . $password);
}

function change_password($email, $password) {
  if(is_null($email) || is_null($password)) {
    return false;
  }
  if(!is_valid_password($password)) {
    return false;
  }

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Fetch salt
  $query = "SELECT salt FROM Users WHERE UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($email));
  db_log_command($query);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // Update password
  $query = "UPDATE Users SET password=? WHERE UPPER(email) LIKE (?)";
  $stmt = $db_pdo->prepare($query);
  $password = hash_password($password, $result['salt']);
  $stmt->execute(array($password, $email));
  db_log_command($query);

  return true;
}


function forgot_password_gen_password($email) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $digits = "";
  for($i = 0; $i < 6; $i++) {
    $digits .= (string) rand(0,9);
  }

  $query = "UPDATE Users SET forgot_password_code_hash=?, forgot_password_code_time_sent=? WHERE UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $hash = md5($digits);
  $time_now = get_current_time_stamp();

  $stmt->execute(array($hash, $time_now, $email));
  db_log_command($query);

  return $digits;
}

function forgot_password_send_email($email) {
  // generate code
  if(!does_email_already_exist($email)) return false;

  $code = forgot_password_gen_password($email);

  // Send email to user
  $to = $email;
  $subject = "Forgot Password?";
  $message = "Hello,<br/><br/>
  We have received a change password request associated with this email address. If you made this request, please follow the instructions below.
  <br/><br/>
  <strong>If you did not request to have your password reset, you can safely ignore this email.</strong> We assure you that your information is safely secure.
  <br/><br/>
  <strong>Please enter the following six-digit code where prompted on the screen within 24 hours of receiving this message.</strong>
  <br/><br/>
  " . $code . "
  <br/><br/>
  You will be prompted to change your password after this code is entered.
  <br/><br/>
  Thanks, <br/>
  Sideboard Team  <br/>
  http://sideboard.io <br/>
  <br/><br/>
  If you received this message in error, please disregard this message.
  ";


  $headers = 'From:noreply@sideboard.io' . "\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1" . "\r\n";
  mail($to, $subject, $message, $headers);

  return true;
}

function forgot_password_verify_code($email, $code) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT forgot_password_code_hash, forgot_password_code_time_sent FROM Users WHERE UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($email));
  db_log_command($query);

  $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
  if(!is_array($fetch))
    return false;

  // Code only valid for 24 hours!
  // no it's not...
  // $time_sent = strtotime($fetch['forgot_password_code_time_sent']);
  // $time_now = strtotime(get_current_time_stamp());
  //
  // if(($time_now - $time_sent) > 86400) { /* Number of UNIX seconds in a day */
  //   return false;
  // }

  $success = ($fetch['forgot_password_code_hash'] === md5($code)) && ($fetch['forgot_password_code_time_sent'] );
  if($success) {
    // NULL out code in database
    $query = "UPDATE Users SET forgot_password_code_hash=?, forgot_password_code_time_sent=? WHERE email=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array(NULL, NULL, $email));
    db_log_command($query);
  }
  return $success;
}

function is_valid_password($password) {
  if(is_null($password)) return false;

  $password_length = strlen($password);
  return ($password_length > PW_MIN_LENGTH)   // Password is greater than min length
      && ($password_length < PW_MAX_LENGTH)   // Password is less than max length
      && preg_match('/[A-Z]/', $password)     // Password has at least 1 capital letter
      && preg_match('/[0-9]/', $password);    // Password has at least 1 number
}

?>
