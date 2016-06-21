<?php
	require_once realpath( dirname( __FILE__ ) ) . '/../../../cms/scripts/functions.php';

	require_once realpath( dirname( __FILE__ ) ) .'/../../Slim/Slim.php';
	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();
	$app->response->headers->set('Content-Type', 'application/json');


	// User id from db - Global Variable
	$user_id = NULL;

	/**
	 * Verifying required params posted or not
	 *
	 */
	// May not need this function
	function verifyRequiredParams($required_fields) {
		$app = \Slim\Slim::getInstance();
	    $error = false;
	    $error_fields = "";
	    $request_params = array();
	    $request_params = $_REQUEST;
	    // Handling PUT request params
	    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	        // $app = \Slim\Slim::getInstance();
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
	        // echo error json and stop the app
	        $response = array();
	        $response["error"] = true;
	        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
	        echoResponse(400, $response);
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

	    echo json_encode($response);
	}


	// Code to access CRUD functions
	// TODO: registration

	/**
	 * User Login
	 * url - /login
	 * method - POST
	 * params - email, password,
	 */
	$app->post('/login', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));

            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');

            $response = array();

            // check for correct email and password
            if (login_user($email, $password, false, true)) {
                $response = get_user_data($email);
                $response['error'] = false;
            }else {
                // user credentials are wrong
                $response['error'] = true;
                $response['message'] = 'Login failed. Incorrect credentials';
            }

            echoResponse(200, $response);
        });

	/*
	 * Signup a new user
	 * url -/signup
	 * method - post
	 * params - email, password, Firstname, lastname, birthday
	 * sends out a confirmation email to the new user as well
	 * Must call /check/:email first to ensure that the email doesnt already exist
	 * Must validate all input on front-end
	 */
	$app->post('signup', function() use ($app){
		$response = array();

		// reading post params
        $email = $app->request()->post('email');
        $password = $app->request()->post('password');
        $firstname = $app->request()->post('firstname');
        $lastname = $app->request()->post('lastname');
        $birthday = $app->request()->post('birthday');
        $gender = $app->request()->post('gender');

        signup_user($email, $password, $firstname, $lastname, $birthday, $gender);

        $response['error'] = false;
        $response['message'] = 'Successful signup';

        echoResponse(200, $response);
	});

	/**
	 * Check if email is in databse
	 * url -/check/:email
	 * method - get
	 * params - none
	 * Retrurns true in json if it exists and false if it doesnt
	 */
	$app->get('/check/:email', function($email){
		$response = array();

		if (does_email_already_exist($email)){
			$response['exists'] = true;
			$response['message'] = 'Email already exists';
		}else{
			$response['exists'] = false;
			$response['message'] = 'Email doesnt exist';
		}

		echoResponse(200, $response);
	});

	$app->run();

 ?>
