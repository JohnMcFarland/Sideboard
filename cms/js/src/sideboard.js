<<<<<<< HEAD
// var jQuery = require( /*need jquery require */);
var Router = Backbone.Router.extend({
	routes: {
		'/staging/cms/:slug':'home'
	},

	home: function() {
		this.view = new Deck({
			el: $( '.excerpt' )
		});
		this.view.render();
	}

});
=======
$(function() {
>>>>>>> refactor

	// admin
	require( './inc/admin/admin.js' );

	// deck
	require( './inc/deck/deck-display.js' );

	// front-page
	require( './inc/front-page/fp-login.js' );
	require( './inc/front-page/fp-signup.js' );
	require( './inc/front-page/fp-change-password.js' );

	// header
	require( './inc/header/header.js' );

	// profile
	require( './inc/profile/post.js' );
	require( './inc/profile/friend-request.js' );
	require( './inc/profile/upload-picture.js' );
	require( './inc/profile/upload-deck.js' );

});
