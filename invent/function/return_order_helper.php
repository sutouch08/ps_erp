<?php
function returnStatusLabel($isCancle, $valid)
{
  $rs = '';
  if( $valid == 0){
    $rs = 'NC';
  }

  if( $isCancle == 1)
  {
    $rs = 'CN';
  }

  $sc  = '<span class="'.($isCancle == 1 ? 'red' : ($valid == 0 ? 'blue' : '')).'">';
  $sc .= $rs;
  $sc .= '</span>';

  return $sc;
}

?>
