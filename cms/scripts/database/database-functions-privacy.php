<?php

// Can user_id view this thing?
// Params:
  // 1) Our ID (the person who owns the thing)
  // 2) Other ID (the person who we're comparing for (can other view our thing))
  // 3) Privacy flags (please refer to defines.php "PRIVACY" section)
// Returns: boolean if we can view this or not
// Example call: can_user_view_item(2, 0, PRIVACY_SELF | PRIVACY_FRIENDS); /* checks if user 0 can view this item (true if it is himself or he is a friend of us)*/
function can_user_view_item($our_id, $other_id, $privacy_flag = PRIVACY_SELF) {
  // Public posts are always visible, irrelevant of if users are logged in or not
  if(($privacy_flag & PRIVACY_PUBLIC) == PRIVACY_PUBLIC) {
    return true;
  }

  // Checks after this require valid users
  if(!user_exists($our_id)) return false;
  if(!user_exists($other_id)) return false;

  // Is it ourself?
  if(($privacy_flag & PRIVACY_SELF) == PRIVACY_SELF) {
    if($our_id == $other_id)
      return true;
  }
  // Are we a friend?
  if(($privacy_flag & PRIVACY_FRIENDS) == PRIVACY_FRIENDS) {
    if(is_friends($our_id, $other_id))
      return true;
  }

  // Are we a friend of a friend?
  if(($privacy_flag & PRIVACY_FRIEND_FRIENDS) == PRIVACY_FRIEND_FRIENDS) {
    if(is_friend_of_friend($our_id, $other_id))
      return true;
  }

  // Are we part of the custom?
  if(($privacy_flag & PRIVACY_CUSTOM) == PRIVACY_CUSTOM) {
    // TODO implement
  }

  return false;
}

?>
