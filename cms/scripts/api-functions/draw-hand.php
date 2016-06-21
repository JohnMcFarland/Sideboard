<?php 
	require_once realpath(  dirname( __FILE__ ) ) .'/../functions.php';

	function draw_hand_api($main_deck){
		if(!isset($_SESSION)) { session_start(); }
		$deck_ID = $main_deck;

		$deck_cards = get_deck_cards($deck_ID);

		$deck = array();

		foreach($deck_cards as $card) {
			$card_info = get_card_info($card['cardID']);
			$set_code = get_set_code($card_info['set_id']);
			$setCode = $set_code['code'];


			if($card['sidedeck'] == 0){
				for ($i = 0 ; $i < $card["quantity"] ; $i++){

					if( strlen($set_code['code']) > 3){

						$link = "https://s3.amazonaws.com/sideboardimages1/Cards/Back.jpg";
						$name = str_replace(' ', '+', $card_info['name']);
						array_push($deck, array( 'name' => $link ) );
						continue;

						// $tempSetId = get_set_id($card_info['name']);
						// $tempSetCode = get_set_code($tempSetId['set_id']);
						// $setCode = $tempSetCode['code'];
						
						

					}

					$name = str_replace(' ', '+', $card_info['name']);

					if($card_info['name'] == "Island" )
						$link ="https://s3.amazonaws.com/sideboardimages1/Cards/ZEN/Island1.jpg";
					else if($card_info['name'] == "Forest" )
						$link = "https://s3.amazonaws.com/sideboardimages1/Cards/ZEN/Forest1.jpg";
					else if($card_info['name'] == "Plains" )
						$link = "https://s3.amazonaws.com/sideboardimages1/Cards/ZEN/Plains1.jpg";
					else if($card_info['name'] == "Mountain" )
						$link ="https://s3.amazonaws.com/sideboardimages1/Cards/ZEN/Mountain1.jpg";
					else if($card_info['name'] == "Swamp" )
						$link ="https://s3.amazonaws.com/sideboardimages1/Cards/ZEN/Swamp1.jpg";
					else
						$link ="https://s3.amazonaws.com/sideboardimages1/Cards/" . $setCode . "/" . $name . ".jpg";
					
					array_push($deck, array( 'name' => $link ) );
						//array_push($deck, array( 'name' => $card_info['name'] ) );
						//array_push($deck, $card['cardID']);
				}
			}
		}

		$hand = array();
		shuffle($deck);

		return $deck;

	}


 ?>