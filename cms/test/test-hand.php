<?php

require realpath(  dirname( __FILE__ ) ) .'/../scripts/functions.php';

function print_hand($hand) {
  foreach($hand as $card) {
    echo $card['name'] . ', ';
  }
}

print_hand(draw_random_hand(1, 7)); echo '<br/>';
print_hand(draw_random_hand(1, 7)); echo '<br/>';
print_hand(draw_random_hand(1, 7)); echo '<br/>';
print_hand(draw_random_hand(2, 7)); echo '<br/>';
print_hand(draw_random_hand(2, 7)); echo '<br/>';
print_hand(draw_random_hand(2, 7)); echo '<br/>';

?>
