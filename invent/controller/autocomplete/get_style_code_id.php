<?php
$style = new style();
$txt = $_REQUEST['term'];
$field = 'id, code';
$limit = 50; //---- limit result

$sc = array();

$qs = $style->autocomplete($txt, $field, $limit);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->code.' | '.$rs->id;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
