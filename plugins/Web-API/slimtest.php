<?php 
	require_once realpath( dirname( __FILE__ ) ) . '/../Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();
	$app->get('/hello/:name', function ($name) {
	    echo "Hello, " . $name . "Welcome to the slim Framework!";
	});
	$app->run();
 ?>