<?php

  /** Set that this user has visited a page (aka is active)
    @param int $user_id The ID we want to update
    Returns bool Whether the query was successful
  **/
  function update_site_visit($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "UPDATE Users SET last_updated_time=? WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $success = $stmt->execute(array(get_current_time_stamp(), $_SESSION['user_id']));
    db_log_command($query);
    return $success;
  }

  function does_email_already_exist($email) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT COUNT(*) FROM Users WHERE UPPER(email) LIKE UPPER(?)"; // ignore case
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($email));
    db_log_command($query);
    return ($stmt->fetchColumn() > 0);
  }

  function user_exists($user_id) {
  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT COUNT(*) FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id));
    db_log_command($query);
    return ($stmt->fetchColumn() > 0);
  }

  /** Is this a valid user (they exist & validated their account)
    Returns: true or false, if the validated their account*/
  function is_user_validated($id_or_email) {
  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT COUNT(*) FROM Users WHERE (ID=? OR UPPER(email) LIKE UPPER(?)) AND valid=1";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($id_or_email, $id_or_email));
    db_log_command($query);
    return ($stmt->fetchColumn() > 0);
  }

  // Retrieve login info (email + hashed password)
  function get_login_data($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT email,password FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id));
    db_log_command($query);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function get_password_data($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT password,salt,pw_last_update FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id));
    db_log_command($query);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function get_profile_url($email_or_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT profile_page_name FROM Users WHERE ID=? OR UPPER(email) LIKE UPPER(?)";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($email_or_id, $email_or_id))) {
      return "";
    }

    db_log_command($query);
    $result = $stmt->fetch();
    return "profile.php?profile=" . $result['profile_page_name'];
  }

  // Determines whether user is online or not.
  function is_online($user_id) {
    if(is_null($user_id)) return false;

  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT COUNT(*) from Users WHERE last_updated_time >= ? AND ID=?";
    $stmt = $db_pdo->prepare($query);
    $offset = gmdate("Y-m-d H:i:s", strtotime("-" . USER_LAST_LOGGED_OFFSET_SEC . " seconds"));
    $stmt->execute(array($offset, $user_id));

    return ($stmt->fetchColumn() > 0);
  }

  function get_profile_user($page_name) {
  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT ID FROM Users WHERE profile_page_name=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($page_name));
    return get_user_data($stmt->fetchColumn());
  }

  function get_users() {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT * FROM Users";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function get_user_persistent_login_data($identifier) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT email, password, login_token, last_login_time FROM Users WHERE identifier=?";
    $stmt = $db->prepare($query);
    if(!$stmt->execute(array($identifier))) {
      return NULL;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function get_user_data($id_or_email) {
  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT ID, profile_page_name, email, lastname, firstname, middlename, birthday, profile_privacy, contact_privacy, deck_favorite_id, profile_pic_id, occupation, admin, join_date, last_login_time, xp, level FROM Users WHERE ID=? OR UPPER(email) = UPPER(?)";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($id_or_email, $id_or_email))) {
      return NULL;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function get_profile_pic($user_id) {
  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query  = "SELECT Images.name, Images.file_extension, Images.time_upload FROM Images
                          JOIN Users ON Users.profile_pic_id = Images.ID
                          AND Images.user_id = ?";

    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id));
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /** Return user's name
    @param: int $user_id ID of the user
    Returns: PHPArray Array with firstname and lastname, or NULL if failed
  **/
  function get_name($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT firstname, lastname FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);

    if(!$stmt->execute(array($user_id))) {
      return NULL;
    }

    return $stmt->fetch();
  }

  function get_profile_page_name($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT profile_page_name FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id));
    $result = $stmt->fetch();
    if(!$result) return "";

    return $result['profile_page_name'];
  }

  function get_user_id_email($email){
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT ID FROM Users WHERE email=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($email));
    $result = $stmt->fetch();
    if(!$result) return -1;

    return $result['ID'];
  }

  function is_user_valid($id_or_email) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT valid FROM Users WHERE ID=? OR email=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($id_or_email, $id_or_email));

    $result = $stmt->fetchColumn();

    return ($result == true);
  }

  // How many friends does $user_id have?
  function num_friends($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT COUNT(*) FROM Friends WHERE (user_one_id=? OR user_two_id=?) AND status=1";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id, $user_id));

    $result = $stmt->fetchColumn();
    return $result ? $result : 0;
  }

  // How many decks does $user_id have?
  function num_decks($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT COUNT(*) FROM Decks WHERE userID=?";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id));

    $result = $stmt->fetchColumn();
    return $result ? $result : 0;
  }

  // Permanent delete
  function delete_user($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $stmt = $db_pdo->prepare("DELETE FROM Users WHERE ID=?");
    if(!$stmt->execute(array($user_id)))
      return false;
    $stmt = $db_pdo->prepare("DELETE FROM Friends WHERE user_one_id=? OR user_two_id=?");
    if(!$stmt->execute(array($user_id)))
      return false;
    $stmt = $db_pdo->prepare("DELETE FROM Likes WHERE user_id=?");
    if(!$stmt->execute(array($user_id)))
      return false;
    $stmt = $db_pdo->prepare("DELETE FROM Comments WHERE user_id=?");
    if(!$stmt->execute(array($user_id)))
      return false;

    // Delete all images from this user
    $stmt = $db_pdo->prepare("SELECT * FROM Images WHERE user_id=?");
    $stmt->execute(array($user_id));
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($images as $image) {
      delete_image($image['ID']);
    }

    // Delete all posts from this user
    $stmt = $db_pdo->prepare("SELECT * FROM Posts WHERE user_id=?");
    $stmt->execute(array($user_id));
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($posts as $post) {
      delete_post($post['ID']);
    }

    // Delete all decks from this user
    $stmt = $db_pdo->prepare("SELECT * FROM Decks WHERE userID=?");
    $stmt->execute(array($user_id));
    $decks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($decks as $deck) {
      delete_deck($deck['ID']);
    }

    return true;
  }

  // Get $user_id's friends
  function get_friends($user_id) {
  	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT Users.ID, Users.profile_page_name, Users.firstname, Users.middlename, Users.lastname FROM Users
                JOIN Friends
                    ON Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID
                       WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?) AND Users.ID != ? AND Friends.status=1 ORDER BY Users.lastname ASC";

    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_id, $user_id, $user_id));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Are $user_one and $user_two friends?
  function is_friends($user_one, $user_two) {
      if(!user_exists($user_one) || !user_exists($user_two)) return false;

    	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

      $query = "SELECT COUNT(*) FROM Friends WHERE (user_one_id=? AND user_two_id=?) OR (user_one_id=? AND user_two_id=?) AND status=1";
      $stmt = $db_pdo->prepare($query);
      $stmt->execute(array($user_one, $user_two, $user_two, $user_one));
      return ($stmt->fetchColumn() > 0);
  }

  // Is $user_one a friend of friend of $user_two?
  // Returns: true or false, if user_one and user_two are either friends or friends of friends
  function is_friend_of_friend($user_one, $user_two) {
    if(!user_exists($user_one)) return false;
    if(!user_exists($user_two)) return false;
    if($user_one == $user_two) return false;  // maybe the query below should handle it, but i don't understand it yet, so accept this hack

    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    // Friend of friend query. Taken from Stack Overflow, I honestly don't know how it works.
    $query = "SELECT COUNT(*) AS count
                  FROM Users
                  WHERE Users.ID IN (
                    SELECT Users.ID
                    FROM Users
                    LEFT JOIN Friends ON ((Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID) AND Friends.status = 1)
                    WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?)
                    AND Users.ID != ?
                  )
                  AND Users.ID IN (
                    SELECT Users.ID
                    FROM Users
                    LEFT JOIN Friends ON ((Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID) AND Friends.status = 1)
                    WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?)
                    AND Users.ID != ?
                  )";
      $stmt = $db_pdo->prepare($query);
      $stmt->execute(array($user_one, $user_one, $user_one, $user_two, $user_two, $user_two));
      return ($stmt->fetchColumn() > 0);
  }

  function get_friend_of_friends($user_one, $user_two) {
    if(!user_exists($user_one)) return 0;
    if(!user_exists($user_two)) return 0;
    if($user_one == $user_two) return 0;  // maybe the query below should handle it, but i don't understand it yet, so accept this hack

    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    // Friend of friend query. Taken from Stack Overflow, I honestly don't know how it works.
    $query = "SELECT COUNT(*) AS count
                  FROM Users
                  WHERE Users.ID IN (
                    SELECT Users.ID
                    FROM Users
                    LEFT JOIN Friends ON ((Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID) AND Friends.status = 1)
                    WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?)
                    AND Users.ID != ?
                  )
                  AND Users.ID IN (
                    SELECT Users.ID
                    FROM Users
                    LEFT JOIN Friends ON ((Friends.user_one_id = Users.ID OR Friends.user_two_id = Users.ID) AND Friends.status = 1)
                    WHERE (Friends.user_one_id = ? OR Friends.user_two_id = ?)
                    AND Users.ID != ?
                  )";
      $stmt = $db_pdo->prepare($query);
      $stmt->execute(array($user_one, $user_one, $user_one, $user_two, $user_two, $user_two));
      return $stmt->fetchAll();
  }

  /** Get the privacy settings of a user
    @param int $user_id UserID to check
    Returns PHPArray array of user privacy information, or NULL
  **/
  function get_user_privacy($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT profile_privacy, contact_privacy FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id)))
      return NULL;

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /** Get experience for a user
    @param int $user_id ID of the userID
    Returns int Total (lifetime) experience of the user
  **/
  function get_experience($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT xp FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id)))
      return 0;

    return $stmt->fetchColumn();
  }

  /** Returns the level of the user
    @param int $user_id The xp we are checking
    Returns int Level based on xp
  **/
  function get_level($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT level FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id)))
      return 0;

    return $stmt->fetchColumn();
  }

  /** Returns amount of experience we have gained toward next level
    @param int $user_id User ID we are searching for
    Returns int Amount of experience we are at for this level
  **/
  function get_experience_gained_for_next_level($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT xp, level FROM Users WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id)))
      return 0;

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    $exp_required = get_exp_required_for_level($results['level']);
    $exp_current = $results['xp'];

    return ($exp_current - $exp_required);
  }

  /** Give XP to user
    @param int $user_id User to give xp to
    @param int $exp Amount of xp to give
    Returns bool If XP was changed or not
  **/
  function change_experience($user_id, $exp) {
    $current_xp = get_experience($user_id);
    $new_xp = $current_xp + $exp;

    while($new_xp >= get_exp_required_for_level(get_level($user_id))) {
      award_level($user_id);
    }

    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "UPDATE Users SET xp=(xp+?) WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    if($stmt->execute(array($exp, $user_id)))
      return false;

    return ($stmt->rowCount() > 0);
  }

  /** Award level to user
    @param int $user_id User to level up
    Returns bool If user was leveled up
  **/
  function award_level($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "UPDATE Users SET level=(level+1) WHERE ID=?";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id)))
      return false;

    return ($stmt->rowCount() > 0);
  }
?>
