<?php

$id_customer = $_GET['id_customer'];
$id_zone = $_GET['id_zone'];
$cs = new consign_check();
$ds = array();

$qs = $cs->getActiveCheckList($id_zone);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'id' => $rs->id,
      'reference' => $rs->reference,
      'date_add' => thaiDate($rs->date_add)
    );

    array_push($ds, $arr);
  }
}
else
{
  array_push($ds, array('nodata' => 'nodata'));
}

echo json_encode($ds);


 ?>
