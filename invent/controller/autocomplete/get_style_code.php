<?php
$style = new style();
$txt = $_REQUEST['term'];
$field = 'code';
$limit = 100; //---- limit result

$sc = array();

$qs = $style->autocomplete($txt, $field, $limit);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->code;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
