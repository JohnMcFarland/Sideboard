<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="height: 600px; padding: 10px 0;">
	<input type="hidden" name="pro-profile-id" value="<?php echo $profile_user['ID']; ?>" />
	<input type="hidden" name="pro-visitor-id" value="<?php echo $visitor_id; ?>" />
	<div class="row" style="margin: 0 0 10px; padding: 18px 10px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
		<div class="row" style="margin: 0;">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
				<img src="images/placeholder-1.png" style="height: 140px; width: 100%; max-width: 180px; border-radius: 20%;"/>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
			<!-- THIS IS A PLACEHOLDER FOR USERNAME ETC -->
			</div>

		</div>
	</div>

	<div class="row" style="margin: 10px 0 0 0; padding: 0 0 0 10px; border-radius: 4px; border: 1px solid #ddd; height: 172px;">
		<div class="row" style="margin: 0; padding: 0;">
			<div style="height: 25px; margin-bottom: 0; font-size: 18px; font-weight: 700;"><a href="./user-deck-display.php">Decks </a><span class="badge"><?php echo get_num_decks($profile_user['ID']) ?></span></div>
		</div>
		<div class="row" style="margin: 0; height: 120px;">
			<div style="width: 200px; float: left;">
				<?php foreach($user_decks as $deck) { ?>
					<div style="height: 20px; margin: 2px 0px;">
							<!--DONAT VUCETAJ 03/23 LINK DECK NAME HERE TO DECK DISPLAY -->
						  <a href="deck-display.php?deck=<?php echo $deck['ID']; ?>"><?php echo $deck['name']; ?></a>
					</div>

		    <?php } ?>
			</div>
		</div>


		<div class="row" style="margin: 0;">

			<input type="file" name="pro-upload-deck" id="pro-upload-deck" style="width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1;" />

			<label for="pro-upload-deck" style="font-size: 12px; font-weight: 700; float: left; margin: 0 10px 0 0; color: white; background-color: #DF474B; padding: 1px 10px; display: inline-block; cursor: pointer; height: 20px; border-radius: 4px;">Choose a file</label>

			<p name="pro-upload-deck-text" style="margin: 0 10px 0 0; padding: 2px 0 0 0; font-size: 12px; height: 20px; width: 105px; float: left; overflow: hidden;">Upload a deck</p>

			<button name="pro-upload-deck-trigger" class="btn btn-info" style="height: 20px; font-size: 12px; font-weight: 700; background-color: #DF474B; padding: 1px 10px; border-color: #Df474B;">Upload</button>

			<!-- <form action="scripts/load-deck.php">
				<input type="text" name="pro-deck-name" class="form-control" />

				<input type="file" name="pro-upload-deck" style="font-size: 12px; font-weight: 700; float: left; margin: 0 10px 0 0; color: white; background-color: #DF474B; padding: 1px 10px; display: inline-block; cursor: pointer; height: 20px; border-radius: 4px;"/>

				<input type="submit" name="pro-upload-deck-trigger" class="btn btn-info" style="height: 20px; font-size: 12px; font-weight: 700; background-color: #DF474B; padding: 1px 10px; border-color: #Df474B;" />
			</form> -->

		</div>
	</div>
</div>
