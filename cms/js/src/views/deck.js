// var $ = require( 'jquery' );
var Card = require( '../models/deck' );

module.exports = Backbone.View.extend({
	initialize: function() {
		this.model = new Card({
			'ID': Sideboard.currentDeck
		});
		this.template = _.template( $( 'script.deck' ).html() );
	},

	render: function() {
		var self = this;

		this.model.fetch().done( function() {
			var content        = self.template({
					'deck': self.model.toJSON()
				}),
		} );
	}
});
