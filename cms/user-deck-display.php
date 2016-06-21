<?php
  if(!isset($_SESSION)) { session_start(); }
  require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';
  //require_once realpath( dirname( __FILE__ ) ) . '/scripts/load-deck.php';
	include 'header.php' ;


  $userID = NULL;
  if(isset($_SESSION['user_id']))
    $userID = $_SESSION['user_id'];


  if($userID == NULL) {
		redirect("404.php");
	}


  $user_decks = get_user_deck_list($userID);

  /* John(3/4/16):
        Unsure why print_r is necessary for this to work.

  */
  //print_r($user_decks);
  ?>



  <div style="padding: 65px;">

    <h2>Upload Deck</h2>
    <form enctype="multipart/form-data" method="post">
      <label for="deck-name">Deck Name: </label>
      <input type="text" name="deck-name" class="form-control" />
      <br />
      <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
      <input type="file" name="deck-file" class="form-control" />
      <br />
      <input type="hidden" name="userID" value="<?php echo $_SESSION['user_id']; ?>" />
      <input type="submit" name="deck-upload" class="btn btn-info" />
      <br />
    </form>


    <?php foreach($user_decks as $deck) { ?>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
          <a href="deck-display.php?deck=<?php echo $deck['ID']; ?>"><?php echo $deck['name']; ?></a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
          <a href="#"><?php echo $deck['date_created']; ?></a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
          <a href="#"><?php echo $deck['deck_color']; ?></a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
          <button type="button" name="delete">Delete</button>
        </div>
      </div>
    <?php } ?>
      </div>
  </div>



  <div style="padding: 65px;">
		<div class="row" style="height: 350px; padding: 20px 50px">
			   <p id="tester" > This is a test </p>
		</div>
	</div>








		</div>
  </div>










  <?php include( 'footer.php' ); ?>
