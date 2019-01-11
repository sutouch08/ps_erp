<?php
//$pd = new product();
$txt = trim($_REQUEST['term']);
$field = 'code';
$limit = 300; //---- limit result

$sc = array();

$qr  = "SELECT pd.code FROM tbl_product AS pd ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON pd.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";

if($txt != '*')
{
  $qr .= "WHERE pd.code LIKE '%".$txt."%' ";
}

$qr .= "ORDER BY ps.code ASC, pd.code ASC ";
$qr .= "LIMIT ".$limit;

$qs = dbQuery($qr);

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
