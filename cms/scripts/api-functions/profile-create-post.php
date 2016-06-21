<?php
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function profile_create_post($user_id, $profile_user_id, $post_content, $post_privacy){
		if(!isset($_SESSION)) { session_start(); }

		$post_id = create_post($user_id, $profile_user_id, $post_content, $post_privacy);

		$success = ($post_id != -1);
		$post_info = NULL;

		if($success) {
		    $profile_pic = get_profile_pic($user_id);
		    $profile_pic_url = empty($profile_pic) ? "placeholder-1.png" : $user_id . '_' . $profile_pic['name'] . "." . $profile_pic['file_extension'];
		    $post_info = array( 'success'						=> $success,
														'date'              => get_time_difference_string(get_current_time_stamp()),
		                        'user_name'         => get_name($user_id),
		                        'profile_page_name' => get_profile_page_name($user_id),
		                        'profile_pic_url'   => $profile_pic_url,
		                        'post_id'           => $post_id);
		}
		else {
			// kinda dumb but it logically makes sense...
			$post_info = array( 'success' => false );
		}

		return $post_info;
	}
 ?>
