<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="height: 600px; padding: 10px 0;">
	<div class="row" style="margin: 0; padding: 5px; border: 1px solid #ddd; border-radius: 4px; height: 176px;"> <!---FRIEND BOX TOP START-->
		<div class="row" style="margin: 0;">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0 0 0 5px;">
				<div style="height: 25px; margin-bottom: 0; font-size: 14px;">
					<h2 style="font-size: 16px; font-weight: 700; margin: 0 10px 0 0; float: left;"><a href="./allFriends.php">Friends</a></h2>
					<span class="badge"><?php echo get_num_friends($profile_user['ID']); ?></span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="margin: 0; padding: 0;">
				<div name="pro-friend-request-container" style="float: right;">
					<?php if(can_send_friend_request($visitor_id, $profile_user['ID'], $profile_user['contact_privacy'])) { ?>
					<button type="button" class="btn btn-info" name="pro-friend-request" style="height: 20px; padding: 0 10px; float: right;">Send</button>
					<?php }
					$friend_status = get_friend_status($visitor_id, $profile_user['ID']);
					if($friend_status != NULL) {
						switch($friend_status['status']) {
							case 0:	// Friend Request Sent
							case 2:	// Friend Request Hidden (won't display notification)
								if($friend_status['sender_id'] == $visitor_id) { ?>
								<div name="pro-cancel-friend-request-container" style="margin: 0; padding: 0;">
									<div class="dropdown">
										<button name="pro-friend-request-sent-button" class="btn btn-info dropdown-toggle" type="button" id="privacy-button" style="height: 20px; padding: 0 10px; float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Sent <span class="caret"></span></button>
										<ul class="dropdown-menu" aria-labelledby="pro-cancel-friend-request-button">
											<li><a name="pro-cancel-friend-request-button" href="#"><i class="fa fa-ban"></i> Cancel</a></li>
										</ul>
									</div>
								</div>
								<?php } else if($friend_status['sender_id'] == $profile_user['ID']) { ?>
								<div name="pro-respond-friend-request-container" style="margin: 0; padding: 0;">
									<div class="dropdown">
										<button name="pro-friend-request-sent-button" class="btn btn-info dropdown-toggle" type="button" style="height: 20px; padding: 0 10px; float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Respond <span class="caret"></span></button>
										<ul class="dropdown-menu" aria-labelledby="pro-respond-friend-request-button-accept">
											<li><a name="pro-respond-friend-request-button-accept" href="#"><i class="fa fa-check"></i> Accept</a></li>
											<li><a name="pro-respond-friend-request-button-delete" href="#"><i class="fa fa-times"></i> Delete</a></li>
										</ul>
									</div>
								</div>
								<?php }
								break;
							case 1: // Friend Request Accepted ?>
							<div name="pro-friends-container" style="margin: 0; padding: 0;">
								<div class="dropdown">
									<button name="pro-friends-button" class="btn btn-info dropdown-toggle" type="button" style="height: 20px; padding: 0 10px; float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-check"></i> Friends <span class="caret"></span></button>
									<ul class="dropdown-menu" aria-labelledby="pro-respond-friend-request-button-accept" style="left: -60px;">
										<li><a name="pro-friends-button-unfriend" href="#" style="padding: 0 0 0 10px; font-size: 12px;"><i class="fa fa-ban"></i> Unfriend</button></li>
									</ul>
								</div>
							</div>
							<?php	break;
							default:
								echo "ERROR! BAD FRIENDSHIP CASE! (". $friend_status['status'] .")";
						} // end switch: Friend_status
					}  ?>
				</div>
			</div>
		</div>
		<?php
		$friends = get_friends($profile_user['ID']);
		foreach($friends as $friend) { ?>
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
		<?php } ?>
	</div>
	<!---FRIEND BOX TOP END -->
	<?php
	if($visitor_id == $profile_user['ID']) {
	$pending_friends = get_all_friend_requests($profile_user['ID']); ?>
	<div class="row" style="margin: 10px 0 0 0; padding: 5px; border: 1px solid #ddd; border-radius: 4px; height: 172px;"><!---FRIEND BOX BOTTOM START-->
		<div class="row" style="margin: 0; height: 25px; padding: 0 0 0 5px;">
			<h2 style="font-size: 16px; font-weight: 700; margin: 0 10px 0 0; float: left;">Pending Friends</h2>
			<span class="badge"><?php echo count($pending_friends); ?></span>
		</div>
		<?php foreach($pending_friends as $pending_friend) { ?>
			<div class="row" style="margin: 0;">
				<div class="col-xs-9 col-sm-9 col-md-9 scol-lg-9" style="padding: 0 0 0 5px;">
					<h2 style="font-size: 14px; margin: 0;"><a href="<?php echo get_profile_url($pending_friend['sender_id']); ?>"><?php echo $pending_friend['firstname'] . ' ' . $pending_friend['lastname']; ?></a></h2>
					<p>Accept Decline</p>
				</div>
			</div>
		<?php } ?>
	</div><!---PENDING FRIEND BOX BOTTOM END-->
	<?php } ?>
</div>
