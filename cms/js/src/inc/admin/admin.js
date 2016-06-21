/************************** USER TABLE FUNCTIONALITY ***********************/

//enables datatables
if ( $( '#user-table' ).length ) {
  $( '#user-table' ).dataTable();
}

//select/deselect all (bulk selector)
var selectedUser = false;

$( 'input[name=bulk-checkbox-user]' ).click(function() {
  if (selectedUser == false) {
    console.log( 'clicked' );
    $( '.user-checkbox' ).prop( 'checked', true );
    selectedUser = true;
  } else {
    console.log( 'unclicked' );
    $( '.user-checkbox' ).prop( 'checked', false );
    selectedUser = false;
  }
});

$users_uid_array = [];
$( '#user-table input[type=checkbox]' ).click(function() {
  // If checked, append id to users_uid_array array, otherwise delete
  if ( $( this ).is( ':checked' ) ) {
    $uid = $( this ).parent().data( 'uid' );
    $users_uid_array.push( $uid );
  }
  else {
    $uid = $( this ).parent().data( 'uid' );
    $index = $users_uid_array.indexOf( $uid );
    if($index > -1) {
      $users_uid_array.splice($index, 1);
    }
  }
});

$( 'button' ).click(function() {
  var $name = $( this ).attr( 'name' ),
      $action, $code;

  if ( $name == 'admin-user-apply' ) {
    // Confirm before doing anything!!!
    if(!confirm("Are you sure you want to apply this action?"))
      return;

    // Retrieve the action
    $action = $( '#user-table select option:selected' ).data( 'user-action' );

    $.ajax({
      type: "POST",
      data: { users: $users_uid_array, action: $action },
      url: Sideboard.protocol + '//' + Sideboard.hostname + '/cms/scripts/ajax/admin-user-actions.php',
      success: function( response, statusText, xhr ) {
        console.log( response );
  			$response = $.parseJSON( response );
        if($response.success) {
          console.log( 'Performed action: ' + $action + ' on ' + $users_uid_array.length + ' users.' );
        }
      }
    });

    // location.reload();
  }
});

//listener on input type = checkbox on click event, get user ids ()

/************************** USER TABLE END *********************************/


/************************** DECK TABLE FUNCTIONALITY ***********************/

//enables datatables
if ( $( '#deck-table' ).length ) {
  $( '#deck-table' ).dataTable();
}

//select/deselect all
var selectedDeck = false;

$( 'input[name=bulk-checkbox-deck]' ).click(function() {
  if (selectedDeck == false) {
    console.log( 'clicked' );
    $( '.deck-checkbox' ).prop( 'checked', true );
    selectedDeck = true;
  } else {
    console.log( 'unclicked' );
    $( '.deck-checkbox' ).prop( 'checked', false );
    selectedDeck = false;
  }
});

// $( 'button' ).click( function() {
//   $name = $( this ).data('name');
//
//   if ($name == "expand-plus") {
//     console.log("clicked here");
//     $( '#admin-cards' ).toggle('show');
//   }
// });

/************************** DECK TABLE END *********************************/


/********************** CARD TABLE (MODAL) FUNCTIONALITY *******************/

//enables datatables
if ( $( '#card-table' ).length ) {
  $( '#card-table' ).dataTable();
}

//select/deselect all
var selectedCard = false;

$( 'input[name=bulk-checkbox-card]' ).click(function() {
  if (selectedCard == false) {
    console.log( 'clicked' );
    $( '.card-checkbox' ).prop( 'checked', true );
    selectedCard = true;
  } else {
    console.log( 'unclicked' );
    $( '.card-checkbox' ).prop( 'checked', false );
    selectedCard = false;
  }
});

/************************** CARD TABLE END *********************************/


/************************ DATABASE TABLE FUNCTIONALITY *********************/

//enables datatables
if ( $( '#database-table' ).length ) {
  $( '#database-table' ).dataTable();
}

//select/deselect all
var selectedUser = false;

$( 'input[name=bulk-checkbox-database]' ).click(function() {
  if (selectedUser == false) {
    $( '.database-table-checkbox' ).prop( 'checked', true );
    selectedUser = true;
  } else {
    $( '.database-table-checkbox' ).prop( 'checked', false );
    selectedUser = false;
  }
});

/***************************** DATABASE TABLE END **************************/


//switching between tables (work in progress)
$( 'button' ).click( function() {
   var $data_name = $( this ).data( 'name' );

   if ( $data_name == "users" ) {
     $( '#user' ).removeClass( 'hide' );
     $( '#deck, #database').addClass( 'hide' );
   } else if ( $data_name == "decks" ) {
     $( '#deck' ).removeClass( 'hide' );
     $( '#user, #database' ).addClass( 'hide' );
   } else if ( $data_name == "database" ) {
     $( '#database' ).removeClass( 'hide' );
     $( '#user, #deck' ).addClass( 'hide' );
   }else if ( $data_name == "view-all" ) {
     $( '#user, #deck, #database' ).removeClass( 'hide' );
   } else if($data_name == "cards" ){
      window.location = Sideboard.protocol + '//' + Sideboard.hostname + '/cms/admin-card-util.php';
   }

 });

  // This won't work :(
  // When you click the button on the deck row to bring up the card modal, update the modal
  // } else if ($data_name == 'deck-table-deck-id' ) {
  //   var $deckID = $( this ).data( 'deck-id' );
  //   $( 'input[name=admin-card-modal-deck-id]' ).val( $deckID );
  //}
