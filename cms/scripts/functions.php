<?php

  require_once realpath( dirname( __FILE__ ) ) . '/../../config.php';
  require_once realpath( dirname( __FILE__ ) ) . '/defines.php';
  require_once realpath( dirname( __FILE__ ) ) . '/exp.php';

  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-admin.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-decks.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-friends.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-geo.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-login.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-password.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-privacy.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-profile-content.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-signup.php';
  require_once realpath( dirname( __FILE__ ) ) . '/database/database-functions-user.php';

  function is_valid_email($email){
    if(empty($email)) return false;

    // okay filter_val() is a good thing to you, but who wrote this code above??? ^^^
    $valid_email_format = filter_var($email, FILTER_VALIDATE_EMAIL );

    // Split email into user (i.e. JohnDoe) and domain (i.e. gmail.com)
    $email_parts = explode("@", $email);
    if(count($email_parts) != 2) return false;

    list($email_user, $email_domain) = $email_parts;
    if(empty($email_domain)) return false;
    $valid_domain = checkdnsrr($email_domain);
    return $valid_email_format && $valid_domain;
  }

  /*
    Return current time (in GMT)
  */
  function get_current_time_stamp() {
    return gmdate("Y-m-d H:i:s");
  }

  /*
    Convert a date into local time
    $date: Date string (from database?)
    Returns: the date in local time
  */
  function get_local_time_from_GMT($date) {
    if(!isset($_SESSION['timezone'])) return $date;

    $local_date = date_create($date, timezone_open('GMT'));
    date_timezone_set($local_date, timezone_open($_SESSION['timezone']));
    return date_format($local_date, "Y-m-d H:i:s");
  }

  /*
    Return a string that details the time difference from $date
    Uses current time (ex. if it is 23:00 and $date is 20:00, then it should return "3 hours ago")
    $date: The date string we are comparing it
    Returns: A string displaying the time difference from current time to the date
  */
  function get_time_difference_string($date) {
    $compare = date_create($date);   // time we are comparing with
    $current = date_create(get_current_time_stamp());        // current time

    if($compare > $current) return "Unknown time";

    $difference = date_diff($compare, $current);

    if($difference->y > 0 || $difference->m > 0 || $difference->d > 3) { return date_format($compare, "F jS, Y g:i A"); }
    else if($difference->d > 0) { return $difference->format('%d days ago'); }
    else if($difference->h > 0) { return $difference->format('%h hours ago'); }
    else if($difference->i > 0) { return ($difference->i == 1 ? $difference->format('%i minute ago') : $difference->format('%i minutes ago')); }
    else { return "Just now"; }
  }

  /*
    Calculates the age of a user from the current time php date() function.
    $birthday: The user's birthday
    Returns: The current age
  */
  function calculate_age($birthday) {
    return date_diff(date_create($birthday), date_create('today'))->y;
  }

  function debug_to_console( $data ) {
    if ( is_array( $data ) )
      $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
      $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
    echo $output;
  }

  function string_contains($str1, $str2) {
    return (strpos($str1, $str2) !== false);
  }

  /* be sure to call before any HTML output is rendered! */
  function redirect( $url ) {
    header( 'Location: ' . $url);
    exit();
  }

  /** Log the current query. (I know this is technically a database function)
  * @param string $query Query to be logged
  * Returns boolean TRUE if the statement was successfully executed
  **/
  function db_log_command( $query ) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO db_log(query, time) VALUES (?,?)";
    $stmt = $db_pdo->prepare($sql);
    $time = get_current_time_stamp();

    return $stmt->execute(array($query, $time));
  }
?>
