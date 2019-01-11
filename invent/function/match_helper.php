<?php
function toFixed($amount, $i = 0)
{
  $arr = explode('.', $amount);
  if( count($arr) == 2)
  {
    $intNumber = $arr[0];
    $decmalNumber = substr($arr[1], 0, $i);
    $amount = $i > 0 ? $intNumber.'.'.$decmalNumber : $intNumber;
  }

  return $amount;
}


 ?>
