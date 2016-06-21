<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="height: 600px; padding: 10px 10px;">
	<input type="hidden" name="pro-create-post-privacy" value="<?php echo PRIVACY_PUBLIC; ?>" />
	<div id="post-status" class="row" style="margin: 0 0 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
		<div class="row" style="margin: 0; border-bottom: 1px solid #ddd;">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"><p style="margin: 0; font-size: 14px;">Update Status</p></div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><p style="margin: 0; font-size: 14px;">Add Photos/Videos</p></div>
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"><p style="margin: 0; font-size: 14px;">Create Photo Album</p></div>
		</div>
		<div class="row" style="margin: 0;">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin: 0;">
				<div class="row" style="margin: 0 auto; padding: 10px; height: 100px; max-width: 100px;">
					<img src="images/placeholder-1.png" style="height: 100%; width: 100%; border-radius: 20%;"/>
				</div>
			</div>
			<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" style="padding: 10px;">
				<textarea name="pro-post-content" placeholder="Whats on your mind?" style="outline: none; width: 100%; resize: none; border-radius: 4px; height: 80px; border: none;" maxlength="60000"></textarea>
			</div>
		</div>
		<div class="row" style="margin: 0;">
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xs-offset-7 col-sm-offset-7 col-md-offset-7 col-ld-offset-7" style="padding: 0;">
				<div class="dropdown">
					<button name="privacy-button" class="btn btn-default dropdown-toggle" type="button" id="privacy-button" style="min-width: 95px; width: auto; float: left; margin: 0 10px 0 0;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-globe"></i> Public <span class="caret"></span></button>
					<ul class="dropdown-menu" aria-labelledby="privacy-button">
						<li class="disabled"><a href="#">Who can see your post?</a></li>
						<li><a href="#" name="privacy-option" data-privacy="public"><i class="fa fa-check"></i> <i class="fa fa-globe"></i> Public</a></li>
						<li><a href="#" name="privacy-option" data-privacy="friends"><i class="fa fa-users"></i> Friends</a></li>
						<li><a href="#" name="privacy-option" data-privacy="self"><i class="fa fa-lock"></i> Only Me</a></li>
					</ul>
				</div>
				<button type="button" name="pro-post-status-trigger" class="btn btn-info" style="width: 95px; font-weight: 700;">Post</button>
			</div>
		</div>
	</div>
	<div class="row" id="post-container" style="margin: 0;">
		<?php
		$posts = get_profile_wall_posts($profile_user['ID']);
		// User:   'ID', 'firstname', 'lastname', 'profile_page_name'
		// Post:	 'content', 'post_date', 'privacy'
		foreach($posts as $post) {
			if(can_user_view_item($profile_user['ID'], $visitor_id, $post['privacy'])) {
				$post_user = get_user_data($post['user_id']);
				$post_name = $post['firstname'] . ' ' . $post['lastname'];
				$post_profile_pic_info = get_image_data($post['image_id']);
		?>
		<div class="row" style="padding: 5px 10px; margin: 0 0 10px 0; border-radius: 4px; border: 1px solid #ddd; height: auto;">
			<div class="row" style="margin: 0;">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
					<p style="margin: 0; font-size: 14px;"><a href="<?php echo 'profile.php?profile=' . $post_user['profile_page_name']?>"><?php echo $post_name; ?></a> posted a comment </p>
				</div>
				<div class="pro-post-toolbar" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
					<i name="pro-post-delete" data-toggle="modal" data-target="#pro-post-delete-modal" class="fa fa-times" data-id="<?php echo $post['post_id']; ?>" style="float: right; cursor: pointer;"></i>
					<i name="pro-post-edit" class="fa fa-pencil" data-id="<?php echo $post['post_id']; ?>" style="float: right; margin: 0 10px; font-size: 0.9em; padding-top: 1px; cursor: pointer;"></i>
				</div>
			</div>
			<div class="row" style="margin: 0;">
				<p style="margin: 0; float: left;"><?php echo get_time_difference_string($post['post_date']); ?></p>
				<i class="fa fa-circle" style="margin: 0 10px; font-size: .5em; padding: 7px 0; float: left;"></i>
				<p style="margin: 0; float: left;">Location</p>
				<i class="fa fa-circle" style="margin: 0 10px; font-size: .5em; padding: 7px 0; float: left;"></i>
				<p name="pro-post-edit-condition" style="margin: 0; float: left;">Unedited</p>
				<i class="fa fa-circle" style="margin: 0 10px; font-size: .5em; padding: 7px 0; float: left;"></i>
				<i class="fa fa-users" style="margin: 0; padding: 2px 0 0 0;"></i>
			</div>
			<div class="row" style="margin: 10px 0; min-height: 80px; padding: 0;">
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
					<div style="height: 60px; width: auto; border-radius: 20%; border: 1px solid #ddd;">
						<img src="<?php echo ( $post_profile_pic_info != NULL ? $post_profile_pic_info['name'] . '.' . $post_profile_pic_info['file_extension'] : 'images/placeholder-1.png' ); ?>" style="height: 100%; width: 100%; border-radius: 20%;" />
					</div>
				</div>
				<div name="pro-post-content-container" data-id="<?php echo $post['post_id']; ?>" class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
					<p name="pro-post-content" data-id="<?php echo $post['post_id']; ?>"><?php echo $post['content']; ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-offset-5 col-sm-offset-5 col-md-offset-5 col-lg-offset-5 col-xs-7 col-sm-7 col-md-7 col-lg-7">
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="cursor: pointer;">
							<i class="fa fa-thumbs-up" style="float: left; margin: 2px 5px 0 0;"></i>
							<p style="margin: 0;">Like</p>
						</div>
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0; cursor: pointer;">
							<i class="fa fa-comment" style="float: left; margin: 2px 5px 0 0;"></i>
							<p style="margin: 0;">Comment</p>
						</div>
						<div name="pro-post-share-container" class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="cursor: pointer;">
							<i class="fa fa-share" style="float: left; margin: 2px 5px 0 0;"></i>
							<p name="pro-post-share" data-toggle="popover" data-content="Text Here" data-trigger="click" data-placement="bottom" style="margin: 0;">Share</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="row" style="margin: 0;">
			<img src="<?php echo ( $post_profile_pic_info != NULL ? $post_profile_pic_info['name'] . '.' . $post_profile_pic_info['file_extension'] : 'images/placeholder-1.png' ); ?>" style="height: 100%; width: 100%; border-radius: 20%;" style="float: left;" />
			<textarea name="" placeholder="Post comment" style="resize: none;"></textarea>
		</div> -->
		<?php } } ?>
	</div>
	<!-- COMMENT TEMPLATE-->
</div>
