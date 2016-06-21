<?php
  if(!isset($_SESSION)) { session_start(); }
  require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

  // We are expecting the following information:
    // $_GET['user-id']     // who is liking something
    // $_GET['post-id']     // post being liked or unliked (if any)
    // $_GET['comment-id']  // comment being liked or unliked (if any)
    // $_GET['image-id']    // image being liked or unliked (if any)
    // $_GET['like']        // 1 if like, 0 if unlike

  // Exit if bad user
  if(!user_exists($_GET['user-id']))
    exit("bad user");

  if(!isset($_GET['like']) || $_GET['like'] < 0 || $_GET['like'] > 1)
    exit("bad like value (1 = like, 0 = unlike) (we got: " . $_GET['like'] . ")");

  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  // Like post
  if(isset($_GET['post-id'])) {
    // if(does_user_like_post($user_id, $_GET['post-id']) == $_GET['like'])
    //   exit("An error occured. Cannot like a post you have already liked (or unlike a post you have not liked)");

    $query = $_GET['like'] ?
      "INSERT INTO Likes (user_id, post_id) VALUES (?,?)" : // like
      "DELETE FROM Likes WHERE user_id=? AND post_id=?";    //unlike
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($_GET['user-id'], $_GET['post-id']));

    print_r(json_encode(array('liked_users' => get_all_users_like_post($_GET['post-id']), 'num_likes' => count(get_all_users_like_post($_GET['post-id'])))));
  }

  // Like comment
  if(isset($_GET['comment-id'])) {
    // if(does_user_like_comment($user_id, $_GET['comment-id']) == $_GET['like'])
    //   exit("An error occured. Cannot like a comment you have already liked (or unlike a comment you have not liked)");

    $query = $_GET['like'] ?
      "INSERT INTO Likes (user_id, comment_id) VALUES (?,?)" : // like
      "DELETE FROM Likes WHERE user_id=? AND comment_id=?";    //unlike
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($_GET['user-id'], $_GET['comment-id']));

    print_r(json_encode(array('liked_users' => get_all_users_like_comment($_GET['comment-id']), 'num_likes' => count(get_all_users_like_comment($_GET['comment-id'])))));
  }

  // Like image
  if(isset($_GET['image-id'])) {
      // if(does_user_like_image($user_id, $_GET['image-id']) == $_GET['like'])
      //   exit("An error occured. Cannot like an image you have already liked (or unlike an image you have not liked)");

    $query = $_GET['like'] ?
      "INSERT INTO Likes (user_id, image_id) VALUES (?,?)" : // like
      "DELETE FROM Likes WHERE user_id=? AND image_id=?";    //unlike
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($_GET['user-id'], $_GET['image-id']));

    print_r(json_encode(array('liked_users' => get_all_users_like_image($_GET['image-id']), 'num_likes' => count(get_all_users_like_image($_GET['image-id'])))));
  }
?>
