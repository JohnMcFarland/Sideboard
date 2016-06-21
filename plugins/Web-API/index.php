<?php
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/login.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/login_forgot_password.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/login-forgot-password-verify-code.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/login-forgot-password-change-password.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/signup-email.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/signup.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/logout.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/profile-create-post.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/profile-send-friend-request.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/profile-delete-user-picture.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/profile-handle-friend-request.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/profile-remove-friendship.php';
	require_once realpath( dirname( __FILE__ ) ) . '/../../cms/scripts/api-functions/draw-hand.php';

	require_once realpath( dirname( __FILE__ ) ) .'/../Slim/Slim.php';

	require_once realpath( dirname( __FILE__ ) ) .'/../../cms/CSRFTokenGen.php';

	\Slim\Slim::registerAutoloader();

	// Slim instance
	$app = new \Slim\Slim();

	// Setting response type to JSON
	$app->response->headers->set('Content-Type', 'application/json');

	// Stats code constants
	$SUCCESS = 200;
	$FORBIDDEN = 403;
	$BAD_REQUEST= 400;

	/**
	 * Verifying required params posted or not
	 * @param Array of neccessary ajax parameters
	 * Returns if API endpoint is hit with missing paramaters
	 */
	function verifyRequiredParams($required_fields) {
		global $BAD_REQUEST;
		$app = \Slim\Slim::getInstance();
	    $error = false;
	    $error_fields = "";
	    $request_params = array();
	    $request_params = $_REQUEST;
	    // Handling PUT request params
	    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	        parse_str($app->request()->getBody(), $request_params);
	    }
	    foreach ($required_fields as $field) {
	        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
	            $error = true;
	            $error_fields .= $field . ', ';
	        }
	    }

	    if ($error) {
	        // Required field(s) are missing or empty
	        // Return Json with missing fields and bad request code
	        // of 400
	        $response = array();
	        $response["missing"] = substr($error_fields, 0, -2);
	        echoResponse($BAD_REQUEST, $response);
	        $app->stop();
	    }
	}

	/**
	 * Checks validity of token
	 * @param $token
	 * echoResponse() a forbidden error code and message
	 * if $token does not match session token
	 */

	function validate($token){
		global $FORBIDDEN;
		$app = \Slim\Slim::getInstance();
		if (!CSRFTokenGen::checkToken($token)){
			echoResponse($FORBIDDEN, 'Invalid token');
			$app->stop();
		}
	}

	/**
	 * Checks if X-RequestWith header is from ajax/xhr
	 * If not, returns with status code of 403
	 */
	function validateHeader(){
		global $FORBIDDEN;
		$app = \Slim\Slim::getInstance();

		if (!$app->request->isAjax()){
			echoResponse($FORBIDDEN, 'Can only access endpoints through ajax');
			$app->stop();
		}
	}


	/**
	 * Echoing json response to client
	 * @param String $status_code Http response code
	 * @param Int $response Json response
	 */
	function echoResponse($status_code, $response) {
	    $app = \Slim\Slim::getInstance();
	    // Http response code
	 	$app->response->setStatus($status_code);

	 	// Not sure if print_r or echo is better
	    print_r(json_encode($response));
	}

	/**
	 * If non existing endpoint is called
	 * Redirect to 404 page
	 */
	// $app->notFound(function() use ($app) {
	// 	$app->render(realpath( dirname( __FILE__ ) ) . '/../../cms/404.php');
	// });


	/**
	 * User Login
	 * URL - /login
	 * Method - Post
	 * Params - email, password, login_persistent
	 */
	$app->post('/login', 'validateHeader', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		// Parameter checking for login is done in login function
		// verifyRequiredParams(array('email', 'password', 'login_persistent'));
		// $token = $app->request->headers->get('csrfToken');
		// validate($token);
		$email = $app->request()->post('email');
		$password = $app->request()->post('password');
		$persistent = $app->request()->post('login_persistent');

		$response = login($email, $password, $persistent);
		echoResponse($SUCCESS, $response);
	});

	/**
	 * Forgot Password
	 * URL - /forgot-password
	 * Method - Post
	 * Params - email
	 */
	$app->post('/forgot-password', 'validateHeader', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		// Check authorization
		verifyRequiredParams(array('email'));

		// $token = $app->request->headers->get('csrfToken');
		// validate($token);

		$email = $app->request()->post('email');

		$response = forgot_password($email);

		echoResponse($SUCCESS, $response);
	});

	/**
	 * Verify Password Code
	 * URL - /verify-password-code
	 * Method - Post
	 * Params - email, code
	 */
	$app->post('/verify-password-code', 'validateHeader',  function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('email', 'code'));

		// Check authorization
		// $token = $app->request->headers->get('csrfToken');
		// validate($token);

		$email = $app->request()->post('email');
		$code = $app->request()->post('code');

		$response = verify_code($email, $code);
		echoResponse($SUCCESS, $response);
	});

	/**
	 * Login Change Password
	 * URL - /login-change-password
	 * Method - Post
	 * Params - email, password
	 */
	$app->post('/login-change-password', 'validateHeader', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('email', 'password'));

		// Check Authorization
		// $token = $app->request->headers->get('csrfToken');
		// validate($token);

		$email = $app->request()->post('email');
		$password = $app->request()->post('password');

		$response = login_change_password($email, $password);
		echoResponse($SUCCESS, $response);

	});


	/**
	 * Signup Email
	 * URL - /signup-email
	 * Method - Post
	 * Params - email
	 */
	$app->post('/signup-email', 'validateHeader', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('email'));

		// Check Authorization
		// $token = $app->request->headers->get('csrfToken');
		// validate($token);

		$email = $app->request()->post('email');

		$response = signup_email($email);

		echoResponse($SUCCESS, $response);

	});

	/**
	 * Signup
	 * URL - /signup
	 * Method - Post
	 * Params - email, password, first, last, birthday, gender, signup_persistent
	 */
	$app->post('/signup', 'validateHeader', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('email', 'password', 'first',
			'last', 'birthday', 'gender', 'signup_persistent'));

		//Check Authorization
		// $token = $app->request->headers->get('csrfToken');
		// validate($token);

		// Create request object
		$request = $app->request();

		$email = $request->post('email');
		$password = $request->post('password');
		$first = $request->post('first');
		$last = $request->post('last');
		$birthday = $request->post('birthday');
		$gender = $request->post('gender');
		// As of now were not doing anything with sign up persistent
		$signup_persistent = $request->post('signup_persistent');

		$response = signup($first, $last, $password, $email, $birthday, $gender);

		echoResponse($SUCCESS, $response);
	});

	/**
	 * Logout
	 * URL - /logout
	 * Method - get
	 * Params - none
	 */
	$app->get('/logout', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		//verifyRequiredParams(array('email'));

		//check authorization
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		if(!isset($_SESSION)) { session_start(); }
		$email = $_SESSION['email'];

		$response = logout($email);

		echoResponse($SUCCESS, $response);
	});

	/**
	 * Profile Create Post
	 * URL - /profile-create-post
	 * Method - post
	 * Params - user_id, profile_user_id, post_content, post_privacy
	 */
	$app->post('/profile-create-post', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('user_id', 'profile_user_id', 'post_content', 'post_privacy'));

		//Check Authorization
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		$request = $app->request();

		$user_id = $request->post('user_id');
		$profile_user_id = $request->post('profile_user_id');
		$post_content = $request->post('post_content');
		$post_privacy = $request->post('post_privacy');

		$response = profile_create_post($user_id, $profile_user_id, $post_content, $post_privacy);

		echoResponse($SUCCESS, $response);
	});

	/**
	 * Send Friend Request
	 * URL - /profile-send-friend-request
	 * Method - post
	 * Params - sender_id, receiver_id
	 */
	$app->post('/profile-send-friend-request', function () use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('sender_id', 'receiver_id'));

		// Check Authorization
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		$sender_id = $app->request()->post('sender_id');
		$receiver_id = $app->request()->post('receiver_id');

		$response = friend_request($sender_id, $receiver_id);

		echoResponse($SUCCESS, $response);

	});

	/**
	 * Handle Friend Request
	 * URL - /profile-handle-friend-request
	 * Method - Post
	 * Params - responder_id, sender_id, fr_accept
	 */
	$app->post('/profile-handle-friend-request', function() use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('responder_id', 'sender_id', 'fr_accept'));

		// Check Authorization
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		$responder_id = $app->request()->post('responder_id');
		$sender_id = $app->request()->post('sender_id');
		$fr_accept = $app->request()->post('fr_accept');

		$response = profile_handle_friend_request($responder_id, $sender_id, $fr_accept);

		echoResponse($SUCCESS, $response);
	});

	/**
	 * Remove Friendship
	 * URL - /profile-remove-friendship
	 * Method - Post
	 * Params - sender_id, responder_id
	 */
	$app->post('/profile-remove-friendship', function () use ($app) {
		global $SUCCESS, $FORBIDDEN;
		verifyRequiredParams(array('sender_id', 'receiver_id'));

		// Check Authoriation
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		$sender_id = $app->request()->post('sender_id');
		$receiver_id = $app->request()->post('receiver_id');

		$response = api_remove_friendship($sender_id, $receiver_id);

		echoResponse($SUCCESS, $response);
	});

	// $app->get('/profile-change-user-picture/:image-id', function($image_id) {
	// 	global $SUCCESS, $FORBIDDEN;

	// 	if(!isset($_SESSION)) { session_start(); }
	// 	$user_id = $_SESSION['user_id'];

	// 	//Check Authorization

	// 	$response
	// });

	/**
	 * Delete user picture
	 * URL -/profile-delete-user-picture/:image-id
	 * Method - get
	 * Params - None
	 */
	$app->get('/profile-delete-user-picture/:image_id', function ($image_id) {
		global $SUCCESS, $FORBIDDEN, $BAD_REQUEST;

		//Check Authorization
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		if($image_id == NULL)		echoResponse($BAD_REQUEST, array('Null Image Id'));
		else 						echoResponse($SUCCESS, delete_user_picture($image_id));

	});

	/**
	 * Draw hand
	 * URL - /draw-hand
	 * Method - Post
	 * Params - main_deck
	 */
	$app->post('/draw_hand', function () use ($app) {
		global $SUCCESS, $FORBIDDEN, $BAD_REQUEST;
		verifyRequiredParams(array('main_deck'));

		//Check Authorization
		$token = $app->request->headers->get('csrfToken');
		validate($token);

		$main_deck = $app->request()->post('main_deck');

		$response = draw_hand_api($main_deck);

		echoResponse($SUCCESS, $response);

	});

	/**
	 * Upload Deck
	 * URL - /upload-deck
	 * Method - Post
	 * Params - pro_deck_name
	 */
	$app->post('/upload-deck', function () use ($app) {
		global $SUCCESS, $FORBIDDEN, $BAD_REQUEST;
		verifyRequiredParams(array('pro_deck_name'));

		$token = $app->request->headers->get('csrfToken');
		validate($token);

		//TODO
	});


	$app->run();

 ?>
