<?php
$cs = new zone();
$txt = $_REQUEST['term'];
$field = 'id_zone, zone_name';
$limit = 100; //---- limit result

$sc = array();

$qs = $cs->autocomplete($txt, $field, $limit);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->zone_name. ' | '. $rs->id_zone;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
