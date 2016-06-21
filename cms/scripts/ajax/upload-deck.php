<?php
	ob_start();
	session_start();
	require realpath(  dirname( __FILE__ ) ) .'/functions.php';
	$db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$url_FAIL = "Location: " . SIDEBOARD_ROOT . '/cms/profile.php';
	$url_SUCCESS = "Location: " . SIDEBOARD_ROOT . '/cms/profile.php';

	// TODO Utilities file perhaps

	// Make sure the session ID exists so we know what user to put in
	if(!isset($_SESSION['user_id'])) $error .= "<br /> User ID did not exist for some reason. (Back-end issue)";

	//Opens error log for testing.
	//$errorLogFile = fopen("uploadlog.txt", "w") or die("Unable to open file!");
	//fwrite($errorLogFile, "Attempt at: " . get_current_time_stamp() . "\n");


	//Creates the deck in the deck table. Validates input to ensure that all fields are filled, that there are no naming conflicts, and creates the deck.

	// Validate input
	if(!$_POST['pro-deck-name']) $error .= "<br /> Please enter a name for the deck.";
	if($error) {
		$_SESSION[ 'error' ] = 'There were error(s) in your deck name details: ' . $error;
		redirect( $url_FAIL );
	}

	$query = "SELECT ID FROM Decks WHERE name=? AND userID=?";
	$stmt = $db_pdo->prepare($query);

	$deckname = $_POST['pro-deck-name'];
	$userID = $_SESSION['user_id'];

	$stmt->bindParam(1, $deckname);
	$stmt->bindParam(2, $userID);
	$stmt->execute();

	// you can't use rowCount() with SELECT statements in PDO. That sucks.
	$result = $stmt->fetchColumn();

	//If no rows are found, it adds the deck.
	if(empty($result)) {
	
		$query = "INSERT INTO Decks (name, userID, date_created) VALUES(?,?,?)";
		$stmt = $db_pdo->prepare($query);
		$stmt->execute(array($deckname, $userID, get_current_time_stamp()));

	
	} else{
		$_SESSION[ 'error' ] = "Error: You already have a deck named (" . $deckname .")". " Please choose a different name";
	
				//fwrite($errorLogFile, $_SESSION['error']." Stopping Script.\n");
				redirect( $url_FAIL );
	}

	$deckID = $db_pdo->lastInsertId();

	//Writes deck data to error log, and sets up temp directory.
	//fwrite($errorLogFile, $deckname. ":" . $deckID . "\n");
	$uploaddir = sys_get_temp_dir();
	$uploadfile = $uploaddir . '/' . basename($_FILES['pro-upload-deck']['name']);
	//fwrite($errorLogFile, $uploadfile."\r\n");

	//Checks to see if file was uploaded and stored properly in the temp directory.
	if (move_uploaded_file($_FILES['pro-upload-deck']['tmp_name'], $uploadfile)) {
	    //fwrite($errorLogFile, "File is valid, and was successfully uploaded.\n");
	} else {
	    //fwrite($errorLogFile, "File not Found!\n");
	}


	//Checks that the file exists before it parses it. If it exists and is where it's supposed to be, it parses through it. TODO:: Add functionality to determine file type.
	if(file_exists($uploadfile)){
		//fwrite($errorLogFile, "File exists, entering card upload!\n");


		$path_parts = pathinfo($uploadfile);
		//fwrite($errorLogFile, $path_parts['extension'] . "\n");


		switch ($path_parts['extension']) {
			case 'txt':
				//$deck_array = explode( "\n" , file_get_contents($uploadfile));
				$deck_array = file($uploadfile);
				$sidedeck = 0;
				foreach ($deck_array as $card) {


					//Parses Quantity and Cardname from the text file
					$quantity = strtok($card, ' ');
					$quantity = trim($quantity);
					//fwrite($errorLogFile, $quantity ." ");
					$cardname = strtok("\n");
					$cardname = trim($cardname);
					//fwrite($errorLogFile, $cardname." ");


					//Switches to sidedeck when the text file meets an empty line.
					if ( empty($quantity) && empty($cardname)) {
						//fwrite($errorLogFile, "\r\nSwitching to Sideboard\r\n\r\n");
						$sidedeck = 1;
						continue;
					}

					//fwrite($errorLogFile, $sidedeck." ");

					//First statement finds the card in the database and gets it's cardID.
					$query = "SELECT ID FROM Cards WHERE name=?";
					$stmt = $db_pdo->prepare($query);
					$stmt->execute(array($cardname));
					$stmt->bindColumn('ID', $cardID);	// same thing as bind_result()

					//if(mysqli_stmt_num_rows($stmt) == 0) {
					$result = $stmt->fetch(PDO::FETCH_BOUND);
					if(empty($result)) {
						$_SESSION[ 'error' ] = "Error: The card you specified does not exist in the database (" . $cardname .")";
						//echo $_SESSION['error'];
						//fwrite($errorLogFile, $_SESSION['er4ror']." Stopping Script.\n");
						redirect( $url_FAIL );
					}

				 	// Bind the result (and fetch because we have to) to $cardID
				 	//fwrite($errorLogFile, $cardID."\n");

					//Second statement inserts the new card into the deckCard table, adding it to the deck.
					$query = "INSERT INTO DeckCard (deckID, cardID, quantity, sidedeck) VALUES(?,?,?,?)";
					$stmt = $db_pdo->prepare($query);
					$stmt->execute(array($deckID, $cardID, $quantity, $sidedeck));


				}

				break;

			case 'csv':
				//fwrite($errorLogFile, "In the CSV case. \r\n");
				$deck_array = file($uploadfile);
				$sidedeck = 0;
				foreach ($deck_array as $card) {

					if($card[0] != "\""){
						//fwrite($errorLogFile, "First Line!\r\n");
						continue;
					}else{

					//if first character is a quotation pull card info
					//fwrite($errorLogFile, "Not the first line!\n");
					//fwrite($errorLogFile, "Reading in card\r\n");
					$parsed_csv = str_getcsv($card);
					$presult = print_r($parsed_csv, true);
					//fwrite($errorLogFile, $presult . "\r\n");

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


					// //OLD WAY OF USING TOKENIZER
					// $cardname = strtok($card, '\",');
					// $cardname = trim($cardname, '"');
					// $quantity  = strtok(',');
					// $ID  = strtok(',');
					// $rarity  = strtok(',');
					// $set  = strtok(',');
					// $collectorNumber  = strtok(',');
					// $foil  = strtok(',');
					// $sidedeck  = strtok('\n');
					// $sidedeck = trim($sidedeck, "\r\n");

					//fwrite($errorLogFile, "Converting Sidedeck value\r\n");
					//fwrite($errorLogFile, "Sidedeck: ". $sidedeck . "\r\n");


					if($sidedeck == "No"){
						//fwrite($errorLogFile, "Converting Sidedeck from No to 0!\r\n");
					 	$sidedeck = 0;

					}else if($sidedeck == "Yes"){
						//fwrite($errorLogFile, "Converting Sidedeck from Yes to 1!\r\n");
					 	$sidedeck = 1;

					}else{
						//fwrite($errorLogFile, "Sidedeck is either undefined, or not a No or a Yes!\n");
					}


					//fwrite($errorLogFile, $quantity. " " . $cardname . " " . $sidedeck . " " . $set ."\r\n\r\n");


					//Finds the setID by using the set code.
					$query = "SELECT ID FROM Sets WHERE code = ?";
					$stmt = $db_pdo->prepare($query);
					$stmt->execute(array($set));
					$setID = $stmt->fetchColumn();

					//First statement finds the card in the database and gets it's cardID.
					if(empty($setID)){

						$query = "SELECT ID FROM Cards WHERE name=?";
						$stmt = $db_pdo->prepare($query);
						$stmt->execute(array($cardname));
						$cardID = $stmt->fetchColumn();

					}else{


						$query = "SELECT ID FROM Cards WHERE name=? AND set_id = ?";
						$stmt = $db_pdo->prepare($query);
						$stmt->execute(array($cardname, $setID));
						$cardID = $stmt->fetchColumn();
					}

					//If card is empty it prints an error
					if(empty($cardID)) {

						//fwrite($errorLogFile, "Card doesn't exist\r\n");
						continue;
					}

					//fwrite($errorLogFile, "cardID: " . $cardID ."\r\n");


					// //First statement finds the card in the database and gets it's cardID.
					// $query = "SELECT ID FROM Cards WHERE name=? AND set_id = ?";
					// $stmt = $db_pdo->prepare($query);
					// $stmt->execute(array($cardname, $setID));
					// $cardID = $stmt->fetchColumn();

					// fwrite($errorLogFile, "cardID: " . $cardID ."\r\n");

					// if(empty($cardID)){
					// 	$query = "SELECT ID FROM Cards WHERE name=?";
					// 	$stmt = $db_pdo->prepare($query);
					// 	$stmt->execute(array($cardname));
					// 	$cardID = $stmt->fetchColumn();



					//Second statement inserts the new card into the deckCard table, adding it to the deck.
					$query = "INSERT INTO DeckCard (deckID, cardID, quantity, sidedeck) VALUES(?,?,?,?)";
					$stmt = $db_pdo->prepare($query);
					$stmt->execute(array($deckID, $cardID, $quantity, $sidedeck));

					}
				}

				break;

		}//switch exit


		//SENDS AN EMAIL TO THE USER LETTING THEM KNOW A DECK HAS BEEN CREATED
		$query = "SELECT email FROM Users WHERE ID=?";
		$stmt = $db_pdo->prepare($query);
		$stmt->execute(array($userID));
		$result = $stmt->fetchColumn();

		//fwrite($errorLogFile, "UserID: " . $userID . "\r\n");
		//fwrite($errorLogFile, "Email: " . $result . "\r\n");
		$subject = "[Sideboard] You've created a new deck.";
		$message = "Congrats on created a deck" . "\r\n" . "UserID: " 	. $userID . "\r\n" . "Deckname: " 		. $deckname 		. "\r\n";

		mail( $result, $subject, $message);

		//fclose($errorLogFile);
		echo("success");
		redirect( $url_SUCCESS );



	}else{
		echo("Fail");
		//fwrite($errorLogFile, "File doesn't exist.\n");
		//fclose($errorLogFile);
		echo ("File doesn't exist");

	}





	?>