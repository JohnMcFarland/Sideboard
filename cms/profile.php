<?php
	if(!isset($_SESSION)) { session_start(); }
	require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';
	require_once realpath( dirname( __FILE__ ) ) . '/scripts/functions.php';
	include_once('CSRFTokenGen.php');

	// Get profile information one of two ways - if the GET does not exist, then search for session variables, otherwise search for profile
	$profile_user = NULL;

	// If we get to profile page without a profile_page_name, redirect first with GET = OUR profile
	if(isset($_GET['profile'])) {
		$profile_user = get_profile_user( $_GET['profile'] );
	}
	else if(isset($_SESSION['user_id'])) {
		$profile_user = get_user_data( $_SESSION['user_id'] );
		// redirect to profile.php?profile=???0
		if($profile_user != NULL) {
			redirect(get_profile_url($profile_user['ID']));
		}
	}

	// Profile does not exist.
	if($profile_user == NULL) {
		redirect("404.php");
	}

	// We cannot view the profile page for some reason
	$visitor_id = isset( $_SESSION['user_id'] ) ? $_SESSION['user_id'] : -1;
	if(!can_user_view_item( $visitor_id, $profile_user['ID'], $profile_user['profile_privacy'] )) {
		exit("We do not have permissions to view this page: " . $visitor_id . ' ' . $profile_user['ID'] . ' ' . $profile_user['privacy']);
	}

	// Access granted!
	include 'header.php';
	// token that is passed with every ajax request
	$token= CSRFTokenGen::generateToken();

	//$user_decks = get_user_deck_list($_SESSION['user_id']);
	$user_decks = get_user_deck_list($profile_user['ID']);
	//print_r($user_decks);
?>

<div class="row-fluid" style="padding-top: 85px;">
<input type="hidden" name="xx4access" value="<?php echo $token?>">
	<?php
		include( './inc/profile-left-column.php' );
		include( './inc/profile-center-column.php' );
		include( './inc/profile-right-column.php' );
	?>
</div>

<?php
	include( './inc/profile-modals.php' );
	include( 'footer.php' );
?>
