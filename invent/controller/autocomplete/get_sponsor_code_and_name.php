<?php

$txt = $_REQUEST['term'];
$limit = 50; //---- limit result

$sc = array();

$qr = "SELECT cus.code, cus.name ";
$qr .= "FROM tbl_sponsor AS sp ";
$qr .= "JOIN tbl_customer AS cus ON sp.id_customer = cus.id ";
if($txt != '*')
{
  $qr .= "WHERE cus.code LIKE '%".$txt."%' OR cus.name LIKE '%".$txt."%' ";
}
$qr .= "ORDER BY cus.code ASC LIMIT ".$limit;

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->code.' | '.$rs->name;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
