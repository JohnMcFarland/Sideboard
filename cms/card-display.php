<?php
	if(!isset($_SESSION)) { session_start(); }
	require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';
	include 'header.php' ;


  //$cardname = "One with Nothing";
  //$cardID = get_card_id($cardname);
  //$cardID = 13497;
  $cardID = $_GET['card'];
  $card_info = get_card_info($cardID);

  $set_code = get_set_code($card_info['set_id']);
  $setCode = $set_code['code'];

  $name = str_replace(' ', '+', $card_info['name']);
  $link ="https://s3.amazonaws.com/sideboardimages1/Cards/" . $setCode . "/" . $name . ".jpg";


  ?>
</br></br></br></br>
  <div style="padding: 65px;">
    <?php $card_info = get_card_info($cardID); ?>

    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
          <img data-id="0" src="<?php echo $link ?>" style="height: 100%; width: 100%; border-radius: 5px;" />
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
          <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
                <a href="#"><?php echo $card_info['name']; ?></a>
              </div>
          </div>
          <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
                <a href="#"><?php echo $card_info['colors']; ?></a>
              </div>
          </div>
          <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
                <a href="#"><?php echo $card_info['cmc']; ?></a>
              </div>
          </div>
          <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
                <a href="#"><?php echo $card_info['text']; ?></a>
              </div>
          </div>
          <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
                <a href="#"><?php echo $card_info['flavor']; ?></a>
              </div>
          </div>
          <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: center;">
                <a href="#"><?php echo $card_info['artist']; ?></a>
              </div>
          </div>
        </div>
    </div>


  </div>




<?php include( 'footer.php' ); ?>
