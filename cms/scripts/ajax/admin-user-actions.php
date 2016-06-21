<?php

if(!isset($_SESSION)) { session_start(); }
require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

function delete_users($users) {
  if(!is_array($users)) return false;

  foreach($users as $user)
    $success = delete_user($user['ID']);

  return $success;
}

$users = $_POST['users'];

$success = false;
switch($_POST['action']) {
  case "admin-user-delete": $success = delete_users($users);
  default: $success = false;
}
print_r(json_encode(array("success" => $success)));

?>
