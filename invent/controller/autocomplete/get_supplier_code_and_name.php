<?php
$cs = new supplier();
$txt = $_REQUEST['term'];
$field = 'code, name';
$limit = 100; //---- limit result

$sc = array();

$qs = $cs->autocomplete($txt, $field, $limit);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->code. ' | '. $rs->name;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
