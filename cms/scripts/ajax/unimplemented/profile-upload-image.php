<?php
	if(!isset($_SESSION)) { session_start(); }
	require realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	// Helper function to return whether or not an image name is unique for user
	function isUniqueImageName($db_pdo, $name) {
		$query = "SELECT * FROM Images WHERE name=? AND user_id=?";
		$stmt = $db_pdo->prepare($query);
		$stmt->execute(array($name, $_SESSION['user_id']));
		$result = $stmt->fetchColumn();
		return empty($result);
	}


	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

	$target_directory = "../../images/users/";
  $target_file = $target_directory . basename($_FILES['file']['name']);

	$upload_status = 1;
  $image_file_type = pathinfo($target_file, PATHINFO_EXTENSION);
  $errormsg = "";
  // Check if image file is an actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES['file']['tmp_name']);
    if(!$check) {
      $upload_status = 0;
			$errormsg = "actual image";
    }
  }

  // Limit file size - 25 MB
  define('MB', 1024 * 1024);
  if($_FILES["user-picture"]["size"] > (25 * MB)) {
    // Error: file too large
    $upload_status = 0;
		$errormsg = "too large";
  }

  // Limit file type to .JPG, .JPEG, .PNG, .GIF
  if( $image_file_type != "jpg" &&
      $image_file_type != "png" &&
      $image_file_type != "jpeg" &&
      $image_file_type != "gif") {
        $upload_status = 0;
				$errormsg = "bad file type";
  }

  if($upload_status) {
	  // Attempt to upload the file
	  // Change the url of $target_file to a randomized 10-digit number
	  $new_name = "";
	  for($i=0;$i<10;$i++) {
	    $new_name .= strval(rand(0,9));
	  }

	  // If for some reason there is a conflict, add a new digit at the end of the image
	  while(!isUniqueImageName($db_pdo, $new_name)) {
	    $new_name .= strval(rand(0,9));
	  }

	  // the image will be stored in cms/images/users/ with a file structure of <user_id>_<image_name>
		$filename = $_SESSION['user_id'] . "_" . $new_name . "." . $image_file_type;
		$target_file = $target_directory . $filename;

	  $move_file = move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
		// Successful upload!
	  if($move_file) {
			$query = "INSERT INTO Images (name,user_id,privacy,file_extension,time_upload) VALUES (?,?,?,?,?)";
			$stmt = $db_pdo->prepare($query);
			$privacy = "public";	// TODO needs to be user-specified
			$stmt->execute(array($new_name,$_SESSION['user_id'], $privacy, $image_file_type, get_current_time_stamp()));

			$upload_status = 1;
	  }
		// Upload failed
		else {
			$upload_status = 0;
		}
	}

	print_r(array("upload_status" => $upload_status, "error_msg" => $errormsg, "filename" => $filename,
		// TODO delete
		"_FILES" => $_FILES['file'], "target_file" => $target_file));
?>
