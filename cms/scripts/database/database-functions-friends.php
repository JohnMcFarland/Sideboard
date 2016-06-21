<?php


/*** Checks to see if a user can send a friend request to another user
  @param: int $user_send The user that is sending the friend request
  @param: int $user_receive The user that is receiving the friend request
  @param: int $receive_contact_privacy The privacy setting of the user who is receiving
  Returns: bool True if $user_send can send a friend request to $user_receive
**/
function can_send_friend_request($user_send, $user_receive, $receive_contact_privacy) {
  // Can't friend request yourself
  if($user_send == $user_receive)
    return false;

  // Users must exist
  if(!user_exists($user_send) || !user_exists($user_receive))
    return false;

  // Cannot have any friend status at all
  $friend_status = get_friend_status($user_send, $user_receive);
  if($friend_status != NULL)
    return false;

  // We must match privacy settings (for example, can only send friend requests to friend of friends if enabled)
  if(!can_user_view_item($user_receive, $user_send, $receive_contact_privacy))
    return false;

  return true;
}

  /** Try to send a friend request
    @param: int $user_send_id The ID of the sending user
    @param: int $user_receive_id The ID of the receiving user
    Returns bool Whether the request was successfully sent
  **/
  function send_friend_request($user_send_id, $user_receive_id) {
    $privacy = get_user_privacy($user_receive_id);

    // Already friends - fail
    if(!can_send_friend_request($user_send_id, $user_receive_id, $privacy['contact_privacy'])) {
      return false;
    }

    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "INSERT INTO Friends (user_one_id, user_two_id, status) VALUES (?,?,?)";
		$stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_send_id, $user_receive_id, 0));
    db_log_command($query);

    return ($stmt->rowCount() > 0);
  }

  /** Handle a sent friend request
    @param: int $user_receiver_id The ID of the user who received the FR
    @param: int $user_sender_id The ID of the user who sent the FR
    @param: bool $accept Whether the friend request was accepted (or hidden)
    Returns bool Whether the request was successfully handled
  **/
  function handle_friend_request($user_receiver_id, $user_sender_id, $accept) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "UPDATE Friends SET status=? WHERE user_two_id=? AND user_one_id=? AND status=0";
    $stmt = $db_pdo->prepare($query);
    $status = $accept ? 1 : 2;  // 1 = FR_ACCEPT, 2 = FR_HIDDEN
    // NOTE: array order here is important. When a FR is sent, user_one_id is the SENDER and user_two_id is the RECEIVER
    // Only the RECEIVER should be able to accept a friend request. I've SWAPPED the query for readability
    $stmt->execute(array($status, $user_receiver_id, $user_sender_id));
    db_log_command($query);

    // This will return true if a row was updated
    return ($stmt->rowCount() > 0);
  }

  /** Remove friendship between two users. This can include sent requests, completed friendships, or anything else
    @param: int $user_one_id ID of one user (order isn't important)
    @param: int $user_two_id ID of other user (order isn't important)
    Returns bool Whether or not the friendship was removed
  **/
  function remove_friendship($user_one_id, $user_two_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "DELETE FROM Friends WHERE ((user_one_id = ? AND user_two_id = ?) OR (user_one_id = ? AND user_two_id = ?))";
    $stmt = $db_pdo->prepare($query);

    // Returns boolean!
    $stmt->execute(array($user_one_id, $user_two_id, $user_two_id, $user_one_id));

    // This will return true if a row was updated
    return ($stmt->rowCount() > 0);
  }

  /**
    Flo Salinas (04/24/2016):

    Remove friendship between two users. This can include sent requests, completed friendships, or anything else
    @param: int $user_one_id ID of one user (user who initiated unfriend request)
    @param: int $user_two_id ID of other user
    Returns bool to represent whether friend status was updated to 0 = "no longer friends"
    we do not want to delete any data so choose to change status instead
  **/
  function unfriend($requester, $tobe_deleted) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);


    $query = "SELECT user_one_id FROM Friends WHERE user_one_id =? OR user_two_id=?" ;
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($user_one_id, $user_two_id));
    $a = $stmt->fetchColumn();

    if($requester == $a){
      "UPDATE Friends SET status=0 WHERE user_one_id=$a AND user_two_id=? AND status=1";
      $stmt = $db_pdo->prepare($query);
      $stmt->execute(array($tobe_deleted));

    }
    else if($tobe_deleted == $a){
      "UPDATE Friends SET status=0 WHERE user_one_id=? AND user_two_id=$a AND status=1";
      $stmt = $db_pdo->prepare($query);
      $stmt->execute(array($requester));
    }

    //Returns the number of rows affected by UPDATE
    //This will return true if a row was updated
    return ($stmt->rowCount() > 0);
  }







  /** Checks to see if friend request was sent between two users. Does not differentiate between who actually sent it.
    @param: int $user_one_id ID of one user
    @param: int $user_two_id ID of other user
    Returns int 0 if NO friend request was sent, or $user_id of the person who sent it
  **/
  function is_friend_request_sent($user_one_id, $user_two_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    $query = "SELECT user_one_id, user_two_id FROM Friends WHERE ((user_one_id = ? AND user_two_id = ?) OR (user_one_id = ? AND user_two_id = ?)) AND (status=0)";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_one_id, $user_two_id, $user_two_id, $user_one_id)))
      return 0;


      db_log_command($query);

    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($results))
      return 0;

    $sender_id = $results['user_one_id'];
    return $sender_id;
  }

  /** Get the friendship status between two users
    @param: int $user_one_id ID of one user (order unimportant)
    @param: int $user_two_id ID of other user (order unimportant)
    Returns:  1) NULL if no friendship status
              2) PHPArray Array containing the friendship status
                [status] => 0 (request sent), 1 (friends), 2 (hidden)
                [sender_id] => ID of the user who sent the request
                [receiver_id] => ID of the user who received the request (although this could be inferred)
  **/
  function get_friend_status($user_one_id, $user_two_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

    // First see if any friendship info actually exists
    $query = "SELECT COUNT(*) FROM Friends WHERE ((user_one_id = ? AND user_two_id = ?) OR (user_one_id = ? AND user_two_id = ?))";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_one_id, $user_two_id, $user_two_id, $user_one_id)))
      return NULL;

    db_log_command($query);

    // No friendship info
    if($stmt->fetchColumn() == 0)
      return NULL;

    // Now actually fetch the information we need
    $query = "SELECT status, user_one_id, user_two_id FROM Friends WHERE ((user_one_id = ? AND user_two_id = ?) OR (user_one_id = ? AND user_two_id = ?))";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_one_id, $user_two_id, $user_two_id, $user_one_id)))
      return NULL;

    // Compile information in a more readable array (TODO, change the column in the database is probably smarter)
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    $returnArray = array("status" => $results['status'], "sender_id" => $results['user_one_id'], "receiver_id" => $results['user_two_id']);

    return $returnArray;
  }


  function get_num_friends($user_id) {

    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT COUNT(*) FROM Friends WHERE (user_one_id = ? OR user_two_id = ?) AND status = 1";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id, $user_id)))
        return 0;

    db_log_command($query);
    $results = $stmt->fetchColumn();
    return $results;
  }

  /** Get all incoming friend requests for $user_id (Only requests where they are recipient & not accepted)
    @param int $user_id User ID we are checking
    Returns PHPArray Array contains all of the rows (each row = PHP array) of friend requesters (& user info for each)
      Row array: sender_id, firstname, lastname, image_user_id, image_name, image_ext
  **/
  function get_all_friend_requests($user_id) {
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT Friends.user_one_id AS sender_id, Users.firstname, Users.lastname, Images.user_id AS image_user_id, Images.name AS image_name, Images.file_extension AS image_ext FROM Friends
                JOIN Users ON Friends.user_one_id = Users.ID
                LEFT JOIN Images ON Users.profile_pic_id = Images.ID
                WHERE Friends.user_two_id=? AND status=0";
    $stmt = $db_pdo->prepare($query);
    if(!$stmt->execute(array($user_id)))
        return 0;

    db_log_command($query);
    return $stmt->fetchAll();
  }

?>
