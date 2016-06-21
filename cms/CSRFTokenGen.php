<?php 

	/**
	 * Generates a randomized Cross-Stie Request Forgery (CSRF) token
	 */
	class CSRFTokenGen{

		// Empty and private constuctor so that class is never instantiated
		private function __construct(){}

		/**
		 * Randomly generates 32 byte token
		 * then sets it to session variable.
		 * Returns the session variable
		 */
		public static function generateToken(){
			if(!isset($_SESSION)) { session_start(); }
			$_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
			return $_SESSION['csrf_token'];
		}

		public static function checkToken($token){
			if(!isset($_SESSION)) { session_start(); }
			if(isset($_SESSION['csrf_token'])){
				if (hash_equals($_SESSION['csrf_token'], $token))
					return true;
				else
					return false;
			}
			else
				return false;
		}

		// Prints token to js console for simple debugging
		public static function print_token(){
			$output = "<script>console.log(' Token =  " . $_SESSION['csrf_token'] . " ');</script>";
			echo $output;
		}

	}

	
 ?>