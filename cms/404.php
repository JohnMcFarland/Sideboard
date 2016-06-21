<?php include( 'header.php' ); ?>
	</div>
	<div class="row" id="errorContainer">
		<p id="errorText">404</p>
	</div>

	<div class="row" id="errorMessage">
		<span style="font-weight:700">Whoops!</span><br>
		Looks like you stumbled upon a missing page.<br><br>
		<a href="<?php echo "../index.php"; /* TODO: this needs to be dynamic */ ?>">Let's go back home</a>
	</div>

<?php include( 'footer.php' ); ?>
