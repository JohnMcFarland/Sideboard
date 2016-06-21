<?php
/*
  Validates a login and stores results in session variables
  $email: The given email
  $password: The password string
  $persistent: Whether or not to have persistent login
  $hash_password: Whether or not we want to hash the password when we compare (useful when we already have the hash)
  Returns: An error code
    0: Success
    1: Username or email doesn't exist
    2: Password doesn't match
*/
function login_user($email, $password, $persistent = false, $hash_password = true) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  if(!is_user_validated($email)) {
    return false;
  }

  $query = "SELECT ID,identifier,email FROM Users WHERE UPPER(email) LIKE UPPER(?) LIMIT 1";
  $stmt = $db_pdo->prepare($query);

  if(!$stmt->execute(array($email))) {
    return false;
  }

  db_log_command($query);

  db_log_command($query);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // Compare password in DB with password given
  if(!validate_password($result['ID'], $password, $hash_password)) {
    return false;
  }

  $query = "UPDATE Users SET last_login_time=? WHERE UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array(get_current_time_stamp(), $email));

  db_log_command($query);

  create_session($result['ID'], $result['email'], $result['identifier'], $persistent);

  return true;
}

function logout_user($email) {
  if($_SESSION['email'] != $email) return false;
  session_unset();

  // Remove persistent stuff from DB - useless now
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "UPDATE Users SET persist_key=?, timeout=? WHERE UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array(NULL, NULL, $email));
  db_log_command($query);

  // Unset authentication cookie on logout
  if(isset($_COOKIE['authentication'])) {
    unset($_COOKIE['authentication']);
    setcookie('authentication', null, -1, '/');
  }

  return true;
}

function create_session($user_id, $email, $identifier, $persistent) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Create a session
  $_SESSION['user_id'] 	= $user_id;
  $_SESSION['email']    = $email;

	// Login successful - check the checkbox now
	if($persistent)
	{
    // create a random key
    $token = uniqid(rand(), true);  // store real token in cookie
    $token_db = md5($token);        // only store hash in database.

    // calculate a timeout one week from now (7 days * 24 hours * 60 minutes * 60 seconds)
    $timeout = time() + PERSIST_LOGIN_TIMEOUT;

    // Set our cookie
    setcookie('login_token', "$identifier:$token", $timeout, "/", null);

    // Update database
    $query = "UPDATE Users SET login_token=? WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($token_db, $user_id));

    db_log_command($query);
	}
}

function attempt_persistent_login($cookie) {
  // Split cookie (as it should be in form of $identifier:$token)
  list($identifier, $token) = explode(':', $cookie);
  if(!ctype_alnum($identifier) || !ctype_alnum($token))
    return false;

  // Attempt to retriever persistent login information
  $login_data = get_user_persistent_login_data($identifier);
  if($login_data == NULL)
    return false;

  // Make sure cookie and database information match
  $keys_match = (md5($token) === $login_data['login_token']);	// Key matches?
  $time_valid = ($now > (strtotime($login_data['last_login_time']) + PERSIST_LOGIN_TIMEOUT));	// Time valid?

  // if either fails, abort
  if(!$keys_match || !$time_valid)
    return false;

  return login_user($login_data['email'], $login_data['password'], true, false);
}

/*
  Validates password for user
  $user_id: The ID of the user in the database
  $password: The password string
  $hash_password: Whether or not to hash the password
  Returns: True if password matches database
*/
function validate_password($user_id_or_email, $password, $hash_password) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT password,salt FROM Users WHERE ID=? OR UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id_or_email, $user_id_or_email));
  db_log_command($query);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $check_password = $hash_password ? hash_password($password, $result['salt']) : $password;
  $match = ($check_password === $result['password']);

  return $match;
}
?>
