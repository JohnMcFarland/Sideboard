<?php
  if(!isset($_SESSION)) { session_start(); }
  require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

  if(!isset($_SESSION['user_id']) || !isset($_GET['image-id'])) exit();

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Update profile pic
  $query = "UPDATE Users SET profile_pic_id=? WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($_GET['image-id'], $_SESSION['user_id']));

  // Construct image name so we can send back through AJAX
  $query = "SELECT name, file_extension FROM Images WHERE ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($_GET['image-id']));

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $image_url = empty($result) ? "placeholder.png" : $_SESSION['user_id'] . "_" . $result['name'] . "." . $result['file_extension'];

  print_r(array('image_name' => $image_url));
?>
