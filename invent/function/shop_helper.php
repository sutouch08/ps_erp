<?php
function getShopIn($txt)
{
  $sc = "";
  $cs = new shop();
  $qs = $cs->search($txt);
  if( dbNumRows($qs) > 0 )
  {
    $i = 1;
    while( $rs = dbFEtchObject($qs) )
    {
      $sc .= $i == 1 ? "'".$rs->id."'" : ", '".$rs->id."'";
      $i++;
    }
  }
  else
  {
    $sc .= "'0'";
  }

  return $sc;
}


function shopName($id)
{
  $cs = new shop($id);
  return $cs->name;
}

 ?>
