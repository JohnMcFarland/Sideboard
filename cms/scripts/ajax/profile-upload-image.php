<?php

require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

$success = upload_image($_FILES);

print_r(json_encode(array("success" => $success, "post" => $_POST, "file" => $_FILES)));

?>
