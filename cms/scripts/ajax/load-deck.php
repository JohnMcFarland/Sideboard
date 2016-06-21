<?php
	if(!isset($_SESSION)) { session_start(); }
	require realpath(  dirname( __FILE__ ) ) .'/../functions.php';


	$url_FAIL = "http://staging.sideboard.io/cms/user-deck-display.php";
	$url_SUCCESS = "http://staging.sideboard.io/cms/user-deck-display.php";

	//JOHN(4/6/16): This will need to be dynamic at some point.
	$upload_folder = "/cms/deck_uploads/ ";



	// TO DO Utilities file perhaps

	// Make sure the session ID exists so we know what user to put in
	//if(!isset($_SESSION['user_id'])) $error .= "<br /> User ID did not exist for some reason. (Back-end issue)";
	if(!isset($_POST['user_id'])) $error .= "<br /> User ID did not exist for some reason. (Back-end issue)";

	//Creates the deck in the deck table. Validates input to ensure that all fields are filled, that there are no naming conflicts, and creates the deck.

	//Validate input
	// if(!$_POST['deck-name']) $error .= "<br /> Please enter a name for the deck.";
	// if($error) {
	// 	$_SESSION[ 'error' ] = 'There were error(s) in your deck name details: ' . $error;
	// 	redirect( $url_FAIL );
	// }

	$deckname = $_POST['name'];
  $userID = $_POST['user_id'];
  $deckID = get_deck_ID($deckname, $userID);
	// $file = $_FILES['file'];

	//John(3/4/16): If no rows are found, it adds the deck.
	if(empty($deckID)) {
		add_deck($deckname, $userID);
	}else{
		$_SESSION[ 'error' ] = "Error: You already have a deck named (" . $deckname .")". " Please choose a different name";
		redirect( $url_FAIL );
	}

	//Gets deckID
	$deckID = get_deck_ID($deckname, $userID);
	//$deckID = $db_pdo->lastInsertId();

	//Sets up temp directory to hold file while it's loaded.
	//$uploaddir = sys_get_temp_dir();
	//$uploadfile = $uploaddir . '/' . basename($_FILES['file']['name']);
	//move_uploaded_file($_FILES['file']['tmp_name'], $upload_folder . $_FILES['file']['name']);
	//$uploadfile = $upload_folder . $_FILES['file']['name'];
	//$uploadfile = $_FILES['file']['tmp_name'] ."/" . $_FILES['file']['name'];
	$uploadfile = $_FILES['file']['tmp_name'];




	//JOHN(4/6/16): Checks to see if file was uploaded and stored properly in the temp directory.
	//ERROR HANDLING SHOULD PROBABLY BE added


	//Checks that the file exists before it parses it.
	//If it exists and is where it's supposed to be, it parses through it.

	if(file_exists($uploadfile)){
		//$path_parts = pathinfo($uploadfile);
		$path_parts = pathinfo($_FILES['file']['name']);

		switch ($path_parts['extension']) {
			case 'txt':
				$deck_array = file($uploadfile);
				$sidedeck = 0;

				foreach ($deck_array as $card) {


					//Parses Quantity and Cardname from the text file
					$quantity = strtok($card, ' ');
					$quantity = trim($quantity);
					$cardname = strtok("\r\n");
					$cardname = trim($cardname);

					//Switches to sidedeck when the text file meets an empty line.
					if ( empty($quantity) && empty($cardname)) {
						$sidedeck = 1;
						continue;
					}

					//First statement finds the card in the database and gets it's cardID.
					$cardID = get_card_id($cardname);


					if(empty($cardID)) {
						$_SESSION[ 'error' ] = "Error: The card you specified does not exist in the database (" . $cardname .")";
						continue;
						//redirect( $url_FAIL );
					}

					//Second statement inserts the new card into the deckCard table, adding it to the deck.
					add_deck_card($deckID, $cardID, $quantity, $sidedeck);
				}

				break;

			case 'csv':
				$deck_array = file($uploadfile);
				$sidedeck = 0;
				foreach ($deck_array as $card) {

					if($card[0] != "\""){
						//Checks for first line
						continue;
					}else{

						/*John(3/4/16):
							If first character is a quotation pull card info.
							Use this to determine if the line in the CSV file is
							a card in the deck, or a label.

						*/

						$parsed_csv = str_getcsv($card);
						$presult = print_r($parsed_csv, true);

						//Uses str_getcsv to read the line and grabs the elements.
						$cardname = $parsed_csv[0];
						$quantity  = $parsed_csv[1];
						// $ID  = $parsed_csv[2];
						// $rarity  = $parsed_csv[3];
						$set  = $parsed_csv[4];
						// $collectorNumber  = $parsed_csv[5];
						// $foil  = $parsed_csv[6];
						$sidedeck  = $parsed_csv[7];
						$sidedeck = trim($sidedeck, "\r\n");


						if($sidedeck == "No"){
							$sidedeck = 0;
						}else if($sidedeck == "Yes"){
							$sidedeck = 1;
						}else{
							//John(3/4/16): Needs an error code for when a card isn't either
							//Should throw an exception.
						}

						//Finds the setID by using the set code.
						$setID = get_set_id_from_set($set);
						//$cardID = get_card_id($cardname);

						if(empty($setID)){
							//If setID is empty it grabs the first version of the card
							//it can find
							$cardID = get_card_id($cardname);

						}else{
							//Otherwise it grabs the specific version
							$cardID = get_card_id2($cardname, $setID);


							}

						//If card is empty The Card doesn't exists and isn't added to the deck
						if(empty($cardID)) {
							continue;
						}
						//Second statement inserts the new card into the deckCard table, adding it to the deck.
						add_deck_card($deckID, $cardID, $quantity, $sidedeck);

					}
				}

				break;

		}//switch exit






		/* John(3/4/16):

			SENDS AN EMAIL TO THE USER LETTING THEM KNOW A DECK HAS BEEN CREATED
			Should be a notification to anyone who gets a notification about this.

		*/
		send_deck_notification_email($userID, $deckname, $uploadfile);
		redirect( $url_SUCCESS );


	}else{
		//Otherwise the process failed.
		send_deck_notification_email($userID, $deckname, $uploadfile);
		//print_r (json_encode($_POST['file']['name']));
		redirect( $url_SUCCESS );
	}


	?>
