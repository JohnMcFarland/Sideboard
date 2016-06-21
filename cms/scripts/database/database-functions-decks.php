<?php

/** Retrieve ALL decks from the database
* Returns: PHPArray containing all of the decks
**/



//DECK INFORMATION FROM DECK TABLE

function get_decks()  {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT ID, name, userID, date_created, last_edit From Decks";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute();
  db_log_command($query);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* Helper function for listing the user Decks in dropdowns.
  Returns the statement containing all the rows of the user's Decks from Decks table. */
function get_user_deck_list($user_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT ID, name, date_created, last_edit, deck_color FROM Decks WHERE userID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id));
  db_log_command($query);
  return $stmt->fetchAll();
}

function delete_deck($deck_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "DELETE FROM Decks WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($deck_id));
  db_log_command($query);

  $query = "DELETE FROM DeckCard WHERE deckID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($deck_id));
  db_log_command($query);
}

function get_num_decks($user_id) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT COUNT(*) FROM Decks WHERE userID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($user_id));
  db_log_command($query);
  return $stmt->fetchColumn();
}


function get_deck_ID($deckname, $userID){
  //This function was in load-deck line:25
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT ID FROM Decks WHERE name=? AND userID=?";
  $stmt = $db_pdo->prepare($query);

  // $deckname = $_POST['pro-deck-name'];
  // $userID = $_SESSION['user_id'];

  $stmt->bindParam(1, $deckname);
  $stmt->bindParam(2, $userID);
  $stmt->execute();
  db_log_command($query);
  return $stmt->fetchColumn();
  //when it was inline it was $result = $stmt->fetchColumn();
}


function add_deck($deckname, $userID){
    //Was in load-deck.php:33
    $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "INSERT INTO Decks (name, userID, date_created) VALUES(?,?,?)";
    $stmt = $db_pdo->prepare($query);
    $stmt->execute(array($deckname, $userID, get_current_time_stamp()));
    db_log_command($query);
}












// CONTENTS OF DECK FROM DECKCARD TABLE

function get_deck_cards($deckID) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT cardID, quantity, sidedeck FROM DeckCard WHERE deckID=?";
	$stmt = $db_pdo->prepare($query);
  $stmt->execute(array($deckID));
  db_log_command($query);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);

  // $deckID = $_POST['deck-select'];
	// $stmt->execute(array($deckID));
	// $col = $stmt->fetch(PDO::FETCH_ASSOC);
}


function add_deck_card($deckID, $cardID, $quantity, $sidedeck){
  //Was in load-deck.php:86 and 152
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "INSERT INTO DeckCard (deckID, cardID, quantity, sidedeck) VALUES(?,?,?,?)";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($deckID, $cardID, $quantity, $sidedeck));

}



//GETTING INFORMATION ABOUT CARD INFORMATION FROM THE CARDS TABLE.


/** Get the information for one card in the database.
* @param int $cardID The ID of the card in the database
Returns: PHPArray Card information from the database
**/

function get_card_table_size(){
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT COUNT(*) FROM Cards";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute();
  db_log_command($query);

  return $stmt-> fetchColumn();
}

function get_all_cards(){
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Cards";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute();
  db_log_command($query);

  return $stmt-> fetchAll(PDO::FETCH_ASSOC);
}

// Will eventually pass a range of cards as params
function get_all_cards_in_range(){
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Cards LIMIT 100";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute();
  db_log_command($query);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_card_info($cardID) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Cards WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($cardID));
  db_log_command($query);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_set_id($name) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT set_id FROM Cards WHERE name=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($name));
  db_log_command($query);

  return $stmt->fetch(PDO::FETCH_ASSOC);
  //return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_card_id($cardname){
  //was in load-deck.php:77 and 137
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT ID FROM Cards WHERE name=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($cardname));
  db_log_command($query);

  return $stmt->fetchColumn();
  
  //return $stmt->fetch(PDO::FETCH_ASSOC);
  //$result = $stmt->fetch(PDO::FETCH_BOUND);

}

function get_card_id2($cardname, $setID){
  //was in load-deck.php:142
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT ID FROM Cards WHERE name=? AND set_id = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($cardname, $setID));
  db_log_command($query);

  return $stmt->fetchColumn();
  //$cardID = $stmt->fetchColumn();
}


//GETTING INFORMATION FROM THE SETS TABLE

function get_set_code($set_id){
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT code FROM Sets WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($set_id));
  db_log_command($query);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_set_id_from_set($setCode){
  //was in load-deck.php:132
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT ID FROM Sets WHERE code = ?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($setCode));
  db_log_command($query);

  return $stmt->fetchColumn();
  //$setID = $stmt->fetchColumn();

}



//MISC FUNCTIONS USED IN DECK FEATURES
function send_deck_notification_email($userID, $deckname, $uploadfile){
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $query = "SELECT email FROM Users WHERE ID=?";
  $stmt = $db_pdo->prepare($query);
  $stmt->execute(array($userID));
  $result = $stmt->fetchColumn();

  $subject = "[Sideboard] You've created a new deck.";
  $message = "Congrats on created a deck" . "\r\n" . "UserID: "   . $userID . "\r\n" . "Deckname: "     . $deckname     . "\r\n" . "Uploadfile: " . $uploadfile . "\r\n";

  mail( $result, $subject, $message);

}



















?>
