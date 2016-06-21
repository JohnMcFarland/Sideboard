$( 'button' ).load(function (){
	var $name = $( this ).attr( 'name' );
	if ( $name == 'delete') {
    console.log("clicked");



    $.ajax({
    type: 'POST',
    headers: { 'csrfToken' : $token },
    data: { main_deck: $main_deck },
    url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/delete-deck.php',
  }).then(function( response ) {
      console.log("back from ajax");
  };
});
