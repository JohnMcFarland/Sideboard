<!-- Modal -->
<div class="modal fade" id="card-modal" role="dialog">
  <div class="modal-dialog" style="width:80%">

    <!-- Modal content-->
    <div class="modal-content" style="height:80%">
      <div class="modal-header">
        <input type="hidden" name="admin-card-modal-deck-id" value="0"/>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align:center">Card-Table</h4>
      </div>
      <div class="modal-body" style="overflow-x: scroll;">
        <table id="card-table" class="table table-hover nowrap" style="padding-top:10px;">
          <thead>
            <tr>
              <td><input type="checkbox" name="bulk-checkbox-card" /></td>
              <td>ID</td>
              <td>Name</td>
              <td>Supertypes</td>
              <td>Type</td>
              <td>Types</td>
              <td>Color</td>
              <td>Multiverse_id</td>
              <td>Layout</td>
              <td>Subtype</td>
              <td>Power</td>
              <td>Toughness</td>
              <td>Text</td>
              <td>Flavor</td>
              <td>Mana_cost</td>
              <td>Variations</td>
              <td>Image_name</td>
              <td>Cmc</td>
              <td>Rarity</td>
              <td>Artist</td>
              <td>Reserved</td>
              <td>Set_id</td>
              <td>Sideboard</td>
            </tr>
          </thead>

          <tbody>
            <?php
            	// This returns a PHP Array with the following keys:
               // cardID, quantity, sidedeck
            	$deckID = 1;
            	$cards = get_deck_cards($deckID);
                foreach($cards as $card){
                    for($i = 0; $i < $card['quantity']; $i++){
            	           $card_info = get_card_info($card['cardID']);                   ?>
                            <tr>
                              <td><input type="checkbox" class="card-checkbox" /></td>
                              <td> <?php echo $card_info['ID']; ?> </td>
                              <td> <?php echo $card_info['name']; ?> </td>
                              <td> <?php echo $card_info['supertypes']; ?> </td>
                              <td> <?php echo $card_info['type']; ?> </td>
                              <td> <?php echo $card_info['types']; ?> </td>
                              <td> <?php echo $card_info['colors']; ?> </td>
                              <td> <?php echo $card_info['multiverse_id']; ?> </td>
                              <td> <?php echo $card_info['layout']; ?> </td>
                              <td> <?php echo $card_info['sub_types']; ?> </td>
                              <td> <?php echo $card_info['power']; ?> </td>
                              <td> <?php echo $card_info['toughness']; ?> </td>
                              <td> <?php echo $card_info['mana_cost']; ?> </td>
                              <td> <?php echo $card_info['text']; ?> </td>
                              <td> <?php echo $card_info['flavor']; ?> </td>
                              <td> <?php echo $card_info['variations']; ?> </td>
                              <td> <?php echo $card_info['image_name']; ?> </td>
                              <td> <?php echo $card_info['cmc']; ?> </td>
                              <td> <?php echo $card_info['rarity']; ?> </td>
                              <td> <?php echo $card_info['artist']; ?> </td>
                              <td> <?php echo $card_info['reserved']; ?> </td>
                              <td> <?php echo $card_info['set_id']; ?> </td>
                              <td> <?php echo ($card['sidedeck'] ?  "Yes" : "No"); ?> </td>
                            </tr>
            	   <?php } ?>
                <?php } ?>
          </tbody>
        </table>

      </div>

        <!-- change bulk actions accordingly -->
        <select name="card-modal-bulk-options" style="-webkit-appearance: menulist-button; height: 35px; background: #fff;">
          <option>Bulk Actions</option>
          <!-- <option>Suspend</option>
          <option>Deactivate</option>
          <option>Activate</option> -->
          <option data-id="0">Delete</option>
        </select>

        <button type="button" name="admin-apply" class="btn btn-primary">Apply</button>

    </div>
  </div>
</div>
