<?php

function is_admin($user_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT COUNT(*) From Users WHERE ID=? AND admin=1";
  $stmt = $db_pdo->prepare($query);
  if(!$stmt->execute(array($user_id)))
    return false;

  db_log_command($query);
  return ($stmt->fetchColumn() > 0);
}

?>
