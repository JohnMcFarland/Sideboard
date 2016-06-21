<?php

/** Get the required XP for this level.
  @param int $level The level you are requesting
  Returns int Required xp for level
**/
function get_exp_required_for_level($level) {
  if($level == 1)
    return 0;
    
  return intval(pow($level, 3));
}

?>
