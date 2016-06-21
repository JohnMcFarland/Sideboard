<?php

require_once realpath( dirname( __FILE__ ) ) . '/scripts/session-scripts.php';

$valid = false;
if(isset($_SESSION['user_id'])) {
  $valid = is_admin($_SESSION['user_id']);
}

if(!$valid) {
  redirect("404.php");
}

require_once realpath( dirname( __FILE__ ) ) . '/header.php';
?>

<!-- This segment of code are the buttons on the top of the admin page that toggle between table views -->
<div class="row text-center" style="padding-top: 150px;">
  <div class="btn-group">
    <button type="button" class="btn btn-default" name="admin-tab" data-name="users" style="width: 200px; height: 40px;">Users</button>
    <button type="button" class="btn btn-default" name="admin-tab" data-name="decks" style="width: 200px; height: 40px;">Decks</button>
    <button type="button" class="btn btn-default" name="admin-tab" data-name="database" style="width: 200px; height: 40px;">Database</button>
    <button type="button" class="btn btn-default" name="admin-tab" data-name="cards" style="width: 200px; height: 40px;">Cards</button>
    <button type="button" class="btn btn-default" name="admin-tab" data-name="view-all" style="width: 200px; height: 40px;">View All</button>
  </div>
</div>

    <!-- <div class="row">
      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 50px 20px 0 50px;">
        <div class="list-group">
          <button type="button" class="list-group-item" name="admin-tabs" data-name="users"><i class="fa fa-users"></i> Users</button>
			    <button type="button" class="list-group-item" name="admin-tabs" data-name="decks"><i class="fa fa-leanpub"></i> Decks</button>
          <button type="button" class="list-group-item" name="admin-tabs" data-name="database"><i class="fa fa-cogs"></i> Database</button>
          <button type="button" class="list-group-item" name="admin-tabs" data-name="view-all"><i class="fa fa-plus-circle"></i> View All</button>
        </div>
      </div>
    </div> -->

<!-- The various files where the tables live -->
<?php include( 'inc/admin-users.php' ); ?>
<?php include( 'inc/admin-decks.php' ); ?>
<?php include( 'inc/admin-modals.php' ); ?>
<?php include( 'inc/admin-database.php' ); ?>

<?php include( 'footer.php' ); ?>
