<?php
$txt = $_REQUEST['term'];
$field = 'id_employee, first_name, last_name';
$limit = 50; //---- limit result

$sc = array();

$qr = "SELECT ".$field." FROM tbl_employee ";
if($txt != '*')
{
  $qr .= "WHERE first_name LIKE '%".$txt."%' OR last_name LIKE '%".$txt."%' ";
}

$qr .= "ORDER BY first_name ASC LIMIT ".$limit;

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->first_name.' '.$rs->last_name.' | '.$rs->id_employee;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
