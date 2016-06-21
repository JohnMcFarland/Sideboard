// var $ = require( 'jquery' );
var Card = require( '../models/card' );

module.exports = Backbone.View.extend({
	initialize: function() {
		this.model = new Card({
			'ID': Sideboard.currentCard
		});
		this.template = _.template( $( 'script.card' ).html() );
	},

	render: function() {
		var self = this;

		this.model.fetch().done( function() {
			var content        = self.template({
					'card': self.model.toJSON()
				}),
				singlePost     = self.$el.html( content );
		} );
	}
});
