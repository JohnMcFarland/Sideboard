
<?php
  if(!isset($_SESSION)) { session_start(); }
  require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';
  include( 'header.php' );
  $profile_user = NULL;

	// If we get to profile page without a profile_page_name, redirect first with GET = OUR profile
	if(isset($_GET['profile'])) {
		$profile_user = get_profile_user( $_GET['profile'] );
	}
	else if(isset($_SESSION['user_id'])) {
		$profile_user = get_user_data( $_SESSION['user_id'] );

	}

  ?>
  <style type="text/css">
    #friends-tabs li{
      margin: 0 20px;
      display: inline;
    }
    #friends-tabs ul
    { list-style: none outside none;
      margin:0;
      padding: 0;
      text-align: center;
    }
    #friends-list{
      height: 630px;
    }
    #friend-container{
      width: 280px;
      height: 80px;
      float: left;
      padding: 0px;
      margin: 5px 8px;
      overflow-x: hidden;
      overflow-y: hidden;
      border: 1px solid;
      border-radius: 5%;
    }
    #icon-col{
      overflow: hidden;
      float:left;
      height: 80px;
      width: 100px;
    }
    #friend-info-col{
      float: left;
      width: 178px;
      font-size: 13px;
      padding: 15px 5px 5px 5px;
    }
  </style>
<div class="container" style="height:85px;">
  <!--EMPTY SPACE TO ACCOMODATE FOR HEADER-->
</div>

<div class="container" style="width: 940px; max-width: 940; min-width: 940px;  height: 750px;">
  <div class="row" style=" width: auto; text-align: center; margin: 10px 0px 0px 0px; ">
    <h1>FRIENDS</h1>
  </div>
  <hr />
  <div id="friends-list">
    <?php
    $friends = get_friends($profile_user['ID']);
    foreach($friends as $friend) { ?>
      <div id="friend-container">
        <div class="col" id="icon-col">
          <img src="images/icon.png" />
        </div>
        <div class="col" id="friend-info-col">
          <div class="col">
                <div class="row" style="margin: 0;">
                  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" style="padding: 0 0 0 5px;">
                    <h2 style="font-size: 14px; margin: 0;"><a href="<?php echo get_profile_url($friend['ID']); ?>"><?php echo $friend['firstname'] . ' ' . $friend['lastname']; ?></a></h2>
                  </div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <?php if (is_online($friend['ID'])) { ?>
                      <img src="images/green_circle.gif" alt="online-circle" style="float: right;" />
                    <?php } ?>
                  </div>
                </div>
          </div>
          <div id="friend-options">

          </div>
        </div>
      </div>
      <?php } ?>

  </div>
</div>


<?php include( 'footer.php' ); ?>
