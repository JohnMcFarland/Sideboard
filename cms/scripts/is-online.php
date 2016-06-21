<?php
  if(!isset($_SESSION)) { session_start(); }
  require realpath(  dirname( __FILE__ ) ) .'/functions.php';

  if(isset($_SESSION['user_id'])) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "UPDATE Users SET last_updated_time=? WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array(get_current_time_stamp(), $_SESSION['user_id']));
    db_log_command($query);
  }

?>
