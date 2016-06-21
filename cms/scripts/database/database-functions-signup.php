<?php

// Sign up a user
// Returns: an error message if there was an error
function signup_user($email, $password, $first, $last, $birthday, $gender) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

	// Randomly generate a salt using a CSPRNG (Cryptographically Secure Pseudo-Random Number Generator) for password use
	$password_salt = base64_encode(openssl_random_pseudo_bytes(32));

  // Generate a profile page name based on given first and last names
  // For now, the format is <first-name><last-name><five-random-digits>
  $profile_page_name = strtolower($first . $last);

  // Append 5 digits to profile page name (to make it unique - keep looping until it's unique. In a rare, or malicious case, this won't be good enough. I don't think we have to worry. There are 100,000 combinations for each first and last name)
  do {
    $test_str = $profile_page_name;

    // append 5 digits
    for($i=0; $i<5; $i++) {
        $test_str .= rand(0, 9);
    }

    $query = "SELECT COUNT(*) FROM Users WHERE profile_page_name LIKE ?";
    $stmt = $db_pdo->prepare($query);
    // add wildcard character
    $test_str .= "%";
    $stmt->execute(array($test_str));
    db_log_command($query);
    // remove wildcard
    $test_str = substr($test_str, 0, -1);
  } while($stmt->fetchColumn() > 0);

  $profile_page_name = $test_str;

  //////////////////////////////////////////////////////
	// CREATE USER IN DATABASE
	//////////////////////////////////////////////////////
  // A hashed identifier - could come in handy
  $identifier = hash("sha256", $email . $password_salt);

  $time = get_current_time_stamp();

  $query = "INSERT INTO Users (identifier, email, password, salt, firstname, lastname, gender, birthday, join_date, profile_privacy, contact_privacy, profile_page_name) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = $db_pdo->prepare($query);

  $db_password = hash_password($password, $password_salt);
  // If the execute() function fails, return an error
  if(!$stmt->execute(array($identifier, $email, $db_password, $password_salt, $first, $last, $gender, $birthday, $time, PRIVACY_DEFAULT, PRIVACY_DEFAULT, $profile_page_name))) {
    return false;
  }
  db_log_command($query);

  signup_send_email($email, $first, $last);
  return true;
}

function signup_send_email($email, $first, $last) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Generate a hash using randomized numbers
  $hash = md5((rand(0,15000) * rand(0,15000) / rand(0,500)));
  $query = "UPDATE Users SET signup_hash=? WHERE UPPER(email) LIKE UPPER(?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($hash, $email));
  db_log_command($query);

  $verify_url = SIDEBOARD_VERIFICATION_URL . "?email=" . $email . "&hash=" . $hash . "&first=" . $first . "&last=" . $last;

  $subject = 'Welcome to Sideboard!';
  $message = "

    Hi " . $first . " " . $last . "!

    Welcome to Sideboard! Before you start using the site, please click the link before to verify your account.
    " . $verify_url . "

    This is to ensure that it's really you who created this account and not someone else.

    Thanks,
    Sideboard Team
    http://sideboard.io

    If you received this message in error, please disregard this message.
  ";

  $headers = 'From:noreply@sideboard.io' . "\r\n";
  mail($email, $subject, $message, $headers);
}

?>
