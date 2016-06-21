<?php

if(!isset($_SESSION)) { session_start(); }
require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

// Similar to post-functions, except these handle the comment system

// $_GET['user-id'] : user performing comment action (add, edit, or delete)
// $_GET['post-id'] : post # to place comment
// $_GET['comment-id'] : comment # to modify
// $_GET['comment-content'] : comment's content in a string
// $_GET['comment-action-type'] : 0,1,2, corresponding to actions

define(COMMENT_ADD, 0);
define(COMMENT_EDIT, 1);
define(COMMENT_DELETE, 2);

// Helper function to determine if this is my comment or not
function isMyComment($user_id, $comment_id) {
  if($user_id == -1) return false;
  if($comment_id == -1) return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT * FROM Comments WHERE ID=? AND user_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->bindParam(1, $comment_id);
  $stmt->bindParam(2, $user_id);
  $stmt->execute();

  $result = $stmt->fetchColumn();

  return (!empty($result));
}

function addComment($user_id, $post_id, $content) {
  if(!user_exists($user_id)) exit("invalid user ID");
  if($post_id == -1) exit("invalid post ID");
  if($content == "") exit("comment content empty");
  if(strlen($content) >= 65535) exit("Text is too long");

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "INSERT INTO Comments (content, post_date, user_id, post_id) VALUES (?,?,?,?)";
  $stmt = $db_pdo->prepare($query);
  $date = get_current_time_stamp();
  $stmt->bindParam(1, $content);
  $stmt->bindParam(2, $date);
  $stmt->bindParam(3, $user_id);
  $stmt->bindParam(4, $post_id);
  $stmt->execute();

  $comment_id = $db_pdo->lastInsertId();

  $query = "SELECT COUNT(*) FROM Comments WHERE post_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->bindParam(1, $post_id);
  $stmt->execute();
  $num_comments = $stmt->fetchColumn();

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
    'comment_id'        => $comment_id,
    'num_comments'      => $num_comments,
    'image_name'        => $image_url,
    'success'           => true));

  print_r($data);
}

function editComment($user_id, $comment_id, $content) {
  if(!user_exists($user_id)) exit("invalid user ID");
  if($comment_id == -1) exit("invalid comment ID");
  if($content == "") exit("comment content empty");
  if(!isMyComment($user_id, $comment_id)) exit("I don't own this comment.");
  if(strlen($content) >= 65535) exit("Text is too long");

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "UPDATE Comments SET content=?, last_edit_date=? WHERE ID=? AND user_id=?";
  $stmt = $db_pdo->prepare($query);
  $date = get_current_time_stamp();
  $stmt->bindParam(1, $content);
  $stmt->bindParam(2, $date);
  $stmt->bindParam(3, $comment_id);
  $stmt->bindParam(4, $user_id);
  $stmt->execute();

  print_r(json_encode(array('success' => true)));
}

function deleteComment($user_id, $comment_id, $post_id) {
  if(!user_exists($user_id)) exit("invalid user ID");
  if($comment_id == -1) exit("invalid comment ID");
  if(!can_delete_comment($user_id, $comment_id)) exit("I can't delete this comment.");

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "DELETE FROM Comments WHERE ID=? AND user_id=?";
  $stmt = $db_pdo->prepare($query);
  $date = get_current_time_stamp();
  $stmt->bindParam(1, $comment_id);
  $stmt->bindParam(2, $user_id);
  $stmt->execute();

  $query = "SELECT COUNT(*) FROM Comments WHERE post_id=?";
  $stmt = $db_pdo->prepare($query);
  $date = get_current_time_stamp();
  $stmt->bindParam(1, $post_id);
  $stmt->execute();
  $num_comments = $stmt->fetchColumn();

  print_r(json_encode(array('num_comments' => $num_comments, 'success' => true)));
}

$action_type = isset($_GET['comment-action-type']) ? $_GET['comment-action-type'] : -1;
$user_id = isset($_GET['user-id']) ? $_GET['user-id'] : -1;
$post_id = isset($_GET['post-id']) ? $_GET['post-id'] : -1;
$comment_id = isset($_GET['comment-id']) ? $_GET['comment-id'] : -1;
$content = isset($_GET['comment-content']) ? $_GET['comment-content'] : "";

switch($action_type) {
  case COMMENT_ADD: addComment($user_id, $post_id, $content); break;
  case COMMENT_EDIT: editComment($user_id, $comment_id, $content); break;
  case COMMENT_DELETE: deleteComment($user_id, $comment_id, $post_id); break;
  default: exit("invalid type ID");
}

?>
