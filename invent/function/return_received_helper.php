<?php
function returnRecievdStatusLabel($isCancle, $valid)
{
  $rs = '';
  if( $valid == 0){
    $rs = 'NC';
  }
  else
  {
    $rs = 'OK';
  }

  if( $isCancle == 1)
  {
    $rs = 'CN';
  }

  $sc  = '<span class="'.($isCancle == 1 ? 'red' : ($valid == 0 ? 'blue' : 'green')).'">';
  $sc .= $rs;
  $sc .= '</span>';

  return $sc;
}

?>
