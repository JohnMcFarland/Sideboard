<!-- The decks table is implemented here, the 'hide' class ensures that this table won't display by default (see admin.js for logic) -->

<div class="container hide" id="deck" style="padding: 50px 0;">
  <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11" id="admin-decks" style="background: #fff; height: auto; margin: 0 auto; overflow-x: scroll;">
      <table id="deck-table" class="table table-hover nowrap" style="padding-top: 10px; width: 100%;">
        <thead style="background-color: #9B9B9B; color: white">
          <tr>
            <td><input type="checkbox" name="bulk-checkbox-deck" /></td>
            <td>Deck_ID</td>
            <td>Name</td>
            <td>UID</td>
            <td>Date Created</td>
            <td>Last Modified</td>
            <td></td>
          </tr>
        </thead>

        <tbody>
          <?php
          	$decks = get_decks();
          	foreach($decks as $deck) {	?>
          	<tr>
            	<td><input type="checkbox" class="deck-checkbox" /></td>
            	<td> <?php echo $deck['ID']; ?></td>
            	<td> <?php echo $deck['name']; ?> </td>
            	<td> <?php echo $deck['userID']; ?></td>
            	<td> <?php echo $deck['date_created']; ?> </td>
            	<td> <?php echo $deck['last_edit']; ?> </td>
            	<td><button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#card-modal"><span class="glyphicon glyphicon-plus"></span></button></td>
          	</tr>
          	<?php } ?>
        </tbody>
      </table>

  </div>

  <!-- Bulk action dropdown menu with apply button (see admin.js for logic) -->

  <div class="row">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" id="deck-button" style="padding-top: 15px; margin-left: 25px;">
      <select name="deck-table-bulk-options" style="-webkit-appearance: menulist-button; height: 35px; background: #fff;">
        <option>Bulk Actions</option>
        <option>Suspend</option>
        <option>Deactivate</option>
        <option>Activate</option>
        <option data-id="0">Delete</option>
      </select>

      <button type="button" name="admin-apply" class="btn btn-primary">Apply</button>
    </div>
  </div>
</div>
