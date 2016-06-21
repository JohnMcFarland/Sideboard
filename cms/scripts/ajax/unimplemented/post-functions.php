<?php

if(!isset($_SESSION)) { session_start(); }
require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

define(POST_CREATE, 0);
define(POST_EDIT,   1);
define(POST_DELETE, 2);

// Does post_id belong to user_id?
// Do this so that we don't accidentally edit or delete another user's post!
function isMyPost($user_id, $post_id) {
  if($user_id == -1) return false;
  if($post_id == -1) return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT * FROM Posts WHERE ID=? AND user_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->bindParam(1, $post_id);
  $stmt->bindParam(2, $user_id);
  $stmt->execute();

  $result = $stmt->fetchColumn();

  return (!empty($result));
}

// User creates a new post on user_location_id's wall
function createPost($user_id, $user_location_id, $post_content) {
  if($user_id == -1) exit("bad user ID");
  if($user_location_id == -1) exit("no location user ID");
  if($post_content == "") exit("No text.");
  if(strlen($post_content) >= 65535) exit("Text is too long");

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "INSERT INTO Posts (content, post_date, user_id, location_user_id) VALUES (?,?,?,?)";
  $stmt = $db_pdo->prepare($query);
  $date = get_current_time_stamp();
  $stmt->bindParam(1, $post_content);
  $stmt->bindParam(2, $date);
  $stmt->bindParam(3, $user_id);
  $stmt->bindParam(4, $user_location_id);
  $stmt->execute();

  $post_id = $db_pdo->lastInsertId();

  // We need image URL to display profile public
  $query = "SELECT Images.name, Images.file_extension FROM Images JOIN Users ON Images.ID = Users.profile_pic_id WHERE Users.ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->bindParam(1, $user_id);
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $image_url = empty($result) ? "placeholder.png" : $user_id . "_" . $result['name'] . "." . $result['file_extension'];

  $data = json_encode(array(
    'date'              => get_time_difference_string($date),
    'user'              => get_user($user_id),
    'profile_page_name' => get_profile_page_name($user_id),
    'post_id'           => $post_id,
    'image_name'        => $image_url,
    'success'           => true));

  print_r($data);
}


function editPost($user_id, $post_id, $post_content) {
  if($user_id == -1) exit("bad user ID");
  if($post_id == -1) exit("bad post ID");
  if($post_content == "") exit("No text.");
  if(!isMyPost($user_id, $post_id)) exit("I don't own this post.");
  if(strlen($post_content) >= 65535) exit("Text is too long");

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "UPDATE Posts SET content=?, last_edit_date=? WHERE ID=? AND user_id=?";
  $stmt = $db_pdo->prepare($query);
  $date = get_current_time_stamp();
  $stmt->bindParam(1, $post_content);
  $stmt->bindParam(2, $date);
  $stmt->bindParam(3, $post_id);
  $stmt->bindParam(4, $user_id);
  $stmt->execute();

  print_r(json_encode(array('success' => true)));
}

function deletePost($user_id, $post_id) {
  if($user_id == -1) exit("bad user ID");
  if($post_id == -1) exit("bad post ID");
  if(!can_delete_post($user_id, $post_id)) exit("I cant't delete this post.");

  delete_post($post_id);

  print_r(array('success' => true));
}

// Initialize variables based on GETs
$type = isset($_GET['type']) ? $_GET['type'] : -1;
$user_id = isset($_GET['user-id']) ? $_GET['user-id'] : -1;
$user_location_id = isset($_GET['user-location-id']) ? $_GET['user-location-id'] : -1;  // !!
$post_content = isset($_GET['post-content']) ? $_GET['post-content'] : "";
$post_id = isset($_GET['post-id']) ? $_GET['post-id'] : -1;

// Execute a certain query based on type parameter
switch($type) {
  case POST_CREATE: createPost($user_id, $user_location_id, $post_content); break;
  case POST_EDIT:   editPost($user_id, $post_id, $post_content); break;
  case POST_DELETE: deletePost($user_id, $post_id); break;
  default: exit("Invalid type");
}

?>
