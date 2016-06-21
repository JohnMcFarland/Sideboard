// $( '#draw-hand-button' ).parent().parent().find( 'li:first-child' ).text();
$token = $('input[name=xx4access]').val(); 

var $deck = [];
var $work_deck = [];
var $firstRun = 1;


/*
John(02/23/16): Taken from http://stackoverflow.com/questions/6274339/how-can-i-shuffle-an-array-in-javascript
*/

function shuffle(a) {
    var j, x, i;
    for (i = a.length; i; i -= 1) {
        j = Math.floor(Math.random() * i);
        x = a[i - 1];
        a[i - 1] = a[j];
        a[j] = x;
    }
    return a;
}
// Donat (02/28/16):
// adding a toggle visibility function for mulligan button
function toggle_visibility(id) {
		 var e = document.getElementById(id);
		 if(e.style.display == 'none')
				e.style.display = 'inline';
		 else
				e.style.display = 'inline';
	}


/*
John(02/23/16): Added a check to see if it was the first click of draw hand. If it was the first pull the deck from the database through the php script, if it's not the first then use the already loaded deck with JS to reshuffle it and draw a new hand.

Some discrepency because the shuffles are different. We should offer multiple types of shuffling.
*/

$( 'button' ).click( function() {

	var $name = $( this ).attr( 'name' );

	if ( $name == 'draw-hand') {
		if($firstRun == 1){
      console.log( $firstRun);
			$main_deck = $( 'input[name=main-deck]' ).val();
			$firstRun = 0;
      console.log( $firstRun);




			$.ajax({
				type: 'POST',
				headers: { 'csrfToken' : $token },
				data: { main_deck: $main_deck },
				url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/draw_hand',
			}).then(function( response ) {
//        $( 'button[name=next-card]' ).attr( 'disabled', false );
//        $( 'button[name=mulligan]' ).attr( 'disabled', false );

				$deck = response;
				$work_deck = response;

				console.log( response );


				// if ( $( 'div[name=draw-hand-display]' ).css( 'display' ) == 'none' ) {
				// 	$( 'div[name=draw-hand-display]' ).show();
				// } else {
				// 	$( 'div[name=draw-hand-display]' ).hide();
				// 	setTimeout(function() {
				// 		$( 'div[name=draw-hand-display]' ).show();
				// 	}, 100);
				//}

				$( 'div[name=draw-hand-display]' ).show();
				$( 'div[name=add-card]' ).hide();

				for( var i = 0; i < 7; i++ ) {
					$( 'img[data-id=' + i +']' ).attr( 'src', response[i].name );
          var $mullCount = 1;
				}

			})

		}else{
			$work_deck = $deck;
			shuffle($work_deck);
      console.log("Not pulling from the database");


			// $( 'div[name=draw-hand-display]' ).hide();
			$( 'div[name=add-card]' ).hide();
			// setTimeout(function() {
			// 	$( 'div[name=draw-hand-display]' ).show();
			// }, 500);

			for( var i = 0; i < 7; i++ ) {
				// $( 'img[data-id=' + i +']' ).attr( 'src', $deck[i].name );
				$( 'img[data-id=' + i +']' ).attr( 'src', $work_deck[i].name );
			}
		}

//			});

		/* John(02/23/16):

			CREATE ANOTHER CONDITIONAL THAT KEEPS TRACK OF IF IT'S NOT THE FIRST TIME THE BUTTON HAS BEEN PRESSED.
			PREVENT MULTIPLE DATABSE CALLS FROM HAPPENING.
			CANT STUMP THE TRUMP

			ALSO ADD MULLIGAN FUNCTIONALITY BY USING A COUNT FOR TIMES MULLIGANED.
			M CAN NEVER BE GREATER THAN 7. ALWAYS DISPLAY ONE CARD.
			FEEL THE BERN.
		*/


	} else if ( $name == 'next-card' ) {
		// console.log( 'Global Deck' );
		// console.log( $deck );
    console.log("In the next card");

		$( 'div[name=add-card]' ).show();
		// conditional check needs to be implemented if the deck has no more cards in it
		var $next_card = $work_deck.pop();
		// this algorithm will need to change
		$( 'img[data-id=7]').attr( 'src', $next_card.name );



	} else if ( $name == 'mulligan' ) {
//		$work_deck = $deck;
//		shuffle($work_deck);
//
//		for( var i = 0; i < 7; i++ ) {
//			$( 'img[data-id=' + i +']' ).attr( 'src', $work_deck[i].name );
if($firstRun == 0){
  console.log( $firstRun);
  $main_deck = $( 'input[name=main-deck]' ).val();
  $firstRun = 0;
  console.log( $firstRun);




  $.ajax({
    type: 'POST',
    headers: { 'csrfToken' : $token },
    data: { main_deck: $main_deck },
    url: Sideboard.protocol + '//' + Sideboard.hostname + '/plugins/Web-API/index.php/draw_hand',
  }).then(function( response ) {
    $deck = response;
    $work_deck = response;

    console.log( response );


    // if ( $( 'div[name=draw-hand-display]' ).css( 'display' ) == 'none' ) {
    // 	$( 'div[name=draw-hand-display]' ).show();
    // } else {
    // 	$( 'div[name=draw-hand-display]' ).hide();
    // 	setTimeout(function() {
    // 		$( 'div[name=draw-hand-display]' ).show();
    // 	}, 100);
    //}

    for( var i = 0; i < 7 - $mullCount; i++ ) {
      $( 'img[data-id=' + i +']' ).attr( 'src', response[i].name );
      $mullCount++;
    }


  })

}else{
  $work_deck = $deck;
  shuffle($work_deck);
  console.log("Not pulling from the database");


  // $( 'div[name=draw-hand-display]' ).hide();
  $( 'div[name=add-card]' ).hide();
  // setTimeout(function() {
  // 	$( 'div[name=draw-hand-display]' ).show();
  // }, 500);

  for( var i = 0; i < 7 - $mullCount; i++ ) {
    // $( 'img[data-id=' + i +']' ).attr( 'src', $deck[i].name );
    $( 'img[data-id=' + i +']' ).attr( 'src', $work_deck[i].name );
    $mullCount++;
  }
}

		}
	}
);
