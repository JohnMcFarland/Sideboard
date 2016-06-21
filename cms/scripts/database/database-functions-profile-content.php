<?php

// Profile content includes images, posts, comments

function get_image_data($image_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT name, user_id, privacy, file_extension, time_upload FROM Images WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  if($stmt->execute(array($image_id)))
    return NULL;

  db_log_command($query);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function can_delete_image($user_id, $image_id) {
  if($user_id == -1 || is_null($user_id)) return false;
  if($image_id == -1 || is_null($image_id)) return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT user_id FROM Images WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($image_id));
  db_log_command($query);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // No post exists with this post ID
  if(!$result) return false;

  // Get the result
  $my_post = ($user_id == $result['user_id']);
  return $my_post;
}

function delete_image($image_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Get internal filename of file
  $query = "SELECT user_id, name, file_extension FROM Images WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($image_id));
  db_log_command($query);

  // make sure to FETCH_BOUND when using bindColumn()!
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // Image stored in <UserID>_<ImageName> format
  $file_path = realpath(  dirname( __FILE__ ) ) . '/../images/users/' . $result['user_id'] . "_" . $result['name'] . "." . $result['file_extension'];
  if(!unlink($file_path)) {
    debug_to_console("Error deleting from server!");
  }

  // Delete from images
  $query = "DELETE FROM Images WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($_GET['image-id']));
  db_log_command($query);

  // Update profile pic
  $query = "UPDATE Users SET profile_pic_id = -1 WHERE profile_pic_id=? AND ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($image_id, $result['user_id']));
  db_log_command($query);
}

// Delete post from database without regard to who is deleting it
function delete_post($post_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "DELETE FROM Posts WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);

  $query = "DELETE FROM Comments WHERE post_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);
}

function can_edit_post( $user_id, $post_id ) {
  if(!user_exists($user_id)) return false;
  if($post_id == -1 || is_null($post_id)) return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT user_id FROM Posts WHERE ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);
  $stmt->bindColumn(1, $post_user_id);
  $result = $stmt->fetch(PDO::FETCH_BOUND);

  if(!$result) return false;

  $my_post = ($user_id == $post_user_id);

  return $my_post;
}

function can_delete_post( $user_id, $post_id ) {
  if($user_id == -1 || is_null($user_id)) return false;
  if($post_id == -1 || is_null($post_id)) return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT user_id, location_user_id FROM Posts WHERE ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);
  $stmt->bindColumn(1, $post_user_id);
  $stmt->bindColumn(2, $post_location_id);
  $result = $stmt->fetch(PDO::FETCH_BOUND);

  // No post exists with this post ID
  if(!$result) return false;

  // Get the result
  $my_post = ($user_id == $post_user_id);
  $my_wall = ($user_id == $post_location_id);

  return ($my_post || $my_wall);
}

function can_edit_comment( $user_id, $comment_id ) {
  if($user_id == -1 || is_null($user_id)) return false;
  if($comment_id == -1 || is_null($comment_id)) return false;

  $query = "SELECT user_id, post_id FROM Comments WHERE ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($comment_id));
  db_log_command($query);
  $stmt->bindColumn(1, $comment_user_id);
  $stmt->bindColumn(2, $comment_post_id);
  $result = $stmt->fetch(PDO::FETCH_BOUND);

  if(!$result) return false;

  // Is this my comment?
  return ($user_id == $comment_user_id);
}

function can_delete_comment( $user_id, $comment_id ) {
  if($user_id == -1 || is_null($user_id)) return false;
  if($comment_id == -1 || is_null($comment_id)) return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT user_id, post_id FROM Comments WHERE ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($comment_id));
  db_log_command($query);
  $stmt->bindColumn(1, $comment_user_id);
  $stmt->bindColumn(2, $comment_post_id);
  $result = $stmt->fetch(PDO::FETCH_BOUND);

  if(!$result) return false;

  // Is this my comment?
  $my_comment = ($user_id == $comment_user_id);

  $query = "SELECT location_user_id FROM Posts WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($comment_post_id));
  db_log_command($query);
  $stmt->bindColumn(1, $post_location_id);
  $result = $stmt->fetch(PDO::FETCH_BOUND);

  if(!$result) return false;

  $my_post = ($user_id == $post_location_id);

  return ($my_post || $my_comment);
}

// Can $user_id create comment on $post_id
function can_create_comment($user_id, $post_id) {
  // Invalid user
  if($user_id == -1 || is_null($user_id)) return false;

  // More complicated logic here
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT user_id, location_user_id FROM Posts WHERE ID = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);
  $stmt->bindColumn(1, $post_user_id);
  $stmt->bindColumn(2, $post_location_user_id);

  // If the post is on my wall or my post, then we can comment
  if($user_id == $post_user_id || $user_id == $post_location_user_id)
    return true;

  // TODO pseudo code - privacy options control whether we can comment
  // switch(post.privacy) {
  //   case "public": return true;
  //   case "friends": return is_my_friend();
  //   case "private": return false;
  //   default: return false;
  // }

  return true;
}

// Did $user_id like $post_id?
function does_user_like_post($user_id, $post_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT COUNT(*) FROM Likes WHERE user_id=? AND post_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id, $post_id));
  db_log_command($query);

  return ($stmt->fetchColumn() > 0);
}

// Did $user_id like $comment_id?
function does_user_like_comment($user_id, $comment_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT COUNT(*) FROM Likes WHERE user_id=? AND comment_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id, $post_id));
  db_log_command($query);

  return ($stmt->fetchColumn() > 0);
}

// Did $user_id like $image_id?
function does_user_like_image($user_id, $image_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT COUNT(*) FROM Likes WHERE user_id=? AND image_id=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id, $post_id));
  db_log_command($query);

  return ($stmt->fetchColumn() > 0);
}

function get_all_users_like_post($post_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT Users.firstname, Users.lastname FROM Users
              JOIN Likes ON Users.ID = Likes.user_id
              WHERE Likes.post_id = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_users_like_comment($comment_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT Users.firstname, Users.lastname FROM Users
              JOIN Likes ON Users.ID = Likes.user_id
              WHERE Likes.comment_id = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($comment_id));
  db_log_command($query);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_users_like_image($image_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT Users.firstname, Users.lastname FROM Users
              JOIN Likes ON Users.ID = Likes.user_id
              WHERE Likes.image_id = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($image_id));
  db_log_command($query);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all posts on this wall
function get_profile_wall_posts($user_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT Users.ID AS user_id, Users.firstname, Users.lastname, Users.profile_page_name, Posts.ID AS post_id, Posts.content, Posts.post_date, Posts.privacy, Images.ID AS image_id FROM Posts
              JOIN Users ON Posts.user_id = Users.ID
              LEFT JOIN Images ON Images.ID = Users.profile_pic_id
                WHERE Posts.location_user_id = ?
                ORDER BY Posts.post_date DESC";

  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id));
  db_log_command($query);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// get newsfeed posts for $user_id
function get_newsfeed_posts($user_id) {
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Friend count - PDO requires us to run COUNT(*) query instead of rowCount() function for portability
  $query = "SELECT COUNT(*) FROM Users
              JOIN Friends
                  ON Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID
                     WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?) AND Users.ID != ? AND Friends.status=1";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id, $user_id, $user_id));
  db_log_command($query);

  // How many friends do we have?
  $result = $stmt->fetchColumn();
  $num_friends = $result ? $result : 0;

  // Run SQL query to get friends
  $query = "SELECT Users.ID FROM Users
              JOIN Friends
                  ON Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID
                     WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?) AND Users.ID != ? AND Friends.status=1";
  $stmt = $db_pdo->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
  $stmt->execute(array($user_id, $user_id, $user_id));
  db_log_command($query);

  // Complicated algorithm here.
  // I want to display a small subset of friends on a newsfeed.
  // To distinguish them, we want to weight their friendship to the logged-in user

  $user_display_array = array();
  // always push the current user's session
  array_push($user_display_array, $_SESSION['user_id']);

  // Only concern ourselves with this logic if we actually have friends
  if($num_friends > 0) {
    // TODO some sort of weighted algorithm
    $friends_index_arr = range(0, $num_friends-1);
    shuffle($friends_index_arr);

    //echo "<br/>" . "result set: "; print_r($stmt->fetchAll());

    $result = $stmt->fetchAll();

    // Create an array for users to display - this will store IDs
    // TODO: magic number - constant in config?
    $MAX_NUM_NEWSFEED_USER_DISPLAY = 25;
    $loops = 0;
    foreach($friends_index_arr as $index) {
      array_push($user_display_array, $result[$index][0]);
      if(++$loops >= $MAX_NUM_NEWSFEED_USER_DISPLAY) break;
    }
  }

  // Construct friends query programmatically
  $query = "SELECT Users.ID, Users.firstname, Users.lastname, Users.profile_page_name, Posts.ID, Posts.content, Posts.post_date, Images.name, Images.file_extension FROM Posts
              JOIN Users ON Posts.user_id = Users.ID
              JOIN Images ON Users.profile_pic_id = Images.ID
                WHERE Posts.user_id IN (";
  foreach($user_display_array as $user_display_id) {
    $query .= "?" . ($user_display_id === end($user_display_array) ? "" : ", ");
  }
  $query .= ") ORDER BY Posts.post_date DESC";


  $stmt = $db_pdo->prepare($query);
  $stmt->execute($user_display_array);
  db_log_command($query);

  return $stmt->fetchAll();
}

function get_post_comments($post_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Run the comment query in a separate statement here, so that we can get the number of comments
  $query = "SELECT Users.ID, Users.firstname, Users.lastname, Users.profile_page_name, Comments.ID, Comments.Content, Comments.post_date, Images.name, Images.file_extension FROM Comments
              JOIN Users ON Users.ID = Comments.user_id
              JOIN Images ON Users.profile_pic_id = Images.ID
              WHERE Comments.post_id = ?
              ORDER BY Comments.post_date ASC";

  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);

  return $stmt->fetchAll();
}

function get_num_comments($post_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Run the comment query in a separate statement here, so that we can get the number of comments
  $query = "SELECT COUNT(*) FROM Comments
              JOIN Users ON Users.ID = Comments.user_id
              JOIN Images ON Users.profile_pic_id = Images.ID
              WHERE Comments.post_id = ?
              ORDER BY Comments.post_date ASC";

  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);

  $result = $stmt->fetchColumn();
  return $result ? $result : 0;
}

// Return edit information for a post
function get_post_edit_data($post_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT last_edit_date FROM Posts WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($post_id));
  db_log_command($query);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result;
}

// Return edit information for a comment
function get_comment_edit_data($comment_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT last_edit_date FROM Comments WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($comment_id));
  db_log_command($query);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result;
}

/** Try to create a post on a wall
  @param int $user_id The ID of the user trying to make the post
  @param int $profile_user_id The ID of the profile's user
  @param string $post_content The content of the post, maximum 60000 chars
**/
function create_post($user_id, $profile_user_id, $post_content, $post_privacy) {
  if($user_id == -1 || $profile_user_id == -1 || empty($post_content) || strlen($post_content > 60000))
    return false;

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $date_now = get_current_time_stamp();

  $query = "INSERT INTO Posts (content, post_date, user_id, location_user_id, privacy) VALUES (?,?,?,?,?)";
  $stmt = $db_pdo->prepare($query);
  if(!$stmt->execute(array($post_content, $date_now, $user_id, $profile_user_id, $post_privacy)))
    return -1;

  db_log_command($query);

  // if no rows were inserted, return -1
  if($stmt->rowCount() == 0) {
    return -1;
  }

  return $db_pdo->lastInsertId();
}

function get_num_images($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT COUNT(ID) FROM Images WHERE (user_id = ?)";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id, ))){
        return 0; //no pictures
    }
    db_log_command($query);
    $results = $stmt->fetchColumn();
    return $results;
}

/** Upload an image to $user_id's images
  @param int $user_id The user who is uploading the image
  @param FILE The file we are uploading
  Return bool If the file was successfully uploaded
**/
function upload_image($file) {
  return false;
}

?>
