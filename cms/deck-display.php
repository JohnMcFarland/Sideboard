<?php
	if(!isset($_SESSION)) { session_start(); }
	require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';
	include 'header.php' ;
	include_once('CSRFTokenGen.php');
	$token = CSRFTokenGen::generateToken();

	//$deckID = 1;
	$deckID = $_GET['deck'];
	$deck_cards = get_deck_cards($deckID);

	$deck = array();
	$sampleHandDeck = array();
	$mainDeck = array();
	$spells = array();
	$creatures = array();
	$lands = array();
	$sideboard = array();
	//$colorPie = array('White' => 0, 'Black' => 0, 'Red' => 0, 'Green' => 0, 'Blue' => 0, 'Colorless' => 0);


	foreach($deck_cards as $card) {
		$card_info = get_card_info($card['cardID']);


		/*
			John (2/28/16):
				Added the foundation for the color pie using an associative array.
				Case will need to be changed to see if the string contains that for multicolor.
				We should eventually switch this to only take cards that arent lands so we don't skew the results.
				Display this with charts and graphs. Maybe change the page css based on deck-color-identity.
		*/

		// switch ($card_info['colors']) {
		//
		// 	case 'White':
		// 		$colorPie['White']++;
		// 		break;
		//
		// 	case 'Black':
		// 		$colorPie['Black']++;
		// 		break;
		//
		// 	case 'Red':
		// 		$colorPie['Red']++;
		// 		break;
		//
		// 	case 'Green':
		// 		$colorPie['Green']++;
		// 		break;
		//
		// 	case 'Blue':
		// 		$colorPie['Blue']++;
		// 		break;
		//
		// 	default:
		// 		$colorPie['Colorless']++;
		// 		break;
		// }



		array_push($deck, $card);

		// push to sideboard if sidedeck, otherwise mainDeck
		if($card['sidedeck'] == 0){
			array_push($mainDeck, $card);
		}else{
			array_push($sideboard, $card);
			continue;
		}

		if(string_contains($card_info["types"],'Land'))
			array_push($lands, $card);
		else if(string_contains($card_info["types"],'Creature'))
			array_push($creatures, $card);
		else
			array_push($spells, $card);
	}

	// $newJson = fopen('/js/src/inc/deck/sampleDeck.json', 'w');
	// fwrite($newJson, json_encode($mainDeck));
	// fclose($newJson);

	?>

	<input type="hidden" name="xx4access" value="<?php echo $token?>">
	<div style="padding: 65px;">

		<div class="row" style="height: 350px; padding: 20px 50px">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="min-height: 350px;">
				<!-- <legend>Creatures</legend> -->
				<!-- php//loop through creatures array -->

				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Creature</strong></h2>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Quantity</strong></h2>
					</div>
				</div>

				<?php foreach($creatures as $creature) {
					$card_info = get_card_info($creature['cardID']); ?>

				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<a href="card-display.php?card=<?php echo $card_info['ID']; ?>"><?php echo $card_info['name']; ?></a>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<?php echo $creature['quantity']; ?>
					</div>
				</div>
				<?php } ?>
			</div>

			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="min-height: 350px;">
				<!-- <legend>Spell</legend> -->
				<!-- php//loop through spell array -->
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Spell</strong></h2>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Quantity</strong></h2>
					</div>
				</div>


				<?php foreach($spells as $spell) {
					$card_info = get_card_info($spell['cardID']); ?>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<a href="card-display.php?card=<?php echo $card_info['ID']; ?>"><?php echo $card_info['name']; ?></a>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<?php echo $spell['quantity']; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="row" style="height: 230px; padding: 20px 50px;">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="min-height: 350px;">
				<!-- <legend>Lands</legend> -->
				<!-- php//loop through the lands array -->
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Land</strong></h2>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Quantity</strong></h2>
					</div>
				</div>


				<?php foreach($lands as $land) {
					$card_info = get_card_info($land['cardID']); ?>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<a href="card-display.php?card=<?php echo $card_info['ID']; ?>"><?php echo $card_info['name']; ?></a>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<?php echo $land['quantity']; ?>
					</div>
				</div>
				<?php } ?>
			</div>

			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="min-height: 350px;">
				<!-- <legend>Sidedeck</legend> -->
				<!-- php//loop through the sidedeck array -->
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Sidedeck</strong></h2>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<h2 style="font-size: 14px;"><strong>Quantity</strong></h2>
					</div>
				</div>


				<?php foreach($sideboard as $sideboard_card) {
					$card_info = get_card_info($sideboard_card['cardID']); ?>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<a href="card-display.php?card=<?php echo $card_info['ID']; ?>"><?php echo $card_info['name']; ?></a>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
						<?php echo $sideboard_card['quantity']; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>


	<div class="row" style="min-height: 500px; border-radius: 4px; border: 1px solid #ddd; margin-top: 100px; margin-bottom: 100px;">
		<div class="row" style="margin: 0 auto; max-width: 300;">
			<input type="hidden" name="main-deck" value="<?php echo( $deckID ); ?>" />
			<button type="button" name="draw-hand" class="btn btn-info" style="margin-right: 5px;">Draw Hand</button>
			<button type="button" name="next-card" class="btn btn-info" disabled>Next Card</button>
			<button type="button" name="mulligan" class="btn btn-info" disabled>Mulligan</button>
	</div>
		<div name="draw-hand-display" class="row" style="margin: 40px 0 0 0; display: none;">
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="0" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="1" src="" style="width: 100%; height: 100%; border-radius: 5px;" />
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="2" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="3" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="4" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="5" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%">
				<img data-id="6" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
			<div name="add-card" class="col-xs-2 col-sm-2 col-md-2 col-lg-2  fadeInRight" style="min-width: 150px; min-height: 250px; height: 250px; width: 14.28%; display: none;">
				<img data-id="7" src="" style="height: 100%; width: 100%; border-radius: 5px;" />
			</div>
		</div>




			<!--
			<div data-id="0" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="1" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="2" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="3" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="4" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="5" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="6" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			<div data-id="7" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width: 14.285714%; min-width: 150px; height: 250px;">card</div>
			-->
		</div>
  </div>


</script>




<?php include( 'footer.php' ); ?>
