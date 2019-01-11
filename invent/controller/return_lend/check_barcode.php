<?php
$sc = TRUE;
$id_order = $_GET['id_order'];
$barcode = trim($_GET['barcode']);
$bc = new barcode();
$id_pd = $bc->getProductId($barcode);
if($id_pd === FALSE)
{
  $sc = FALSE;
  $message = 'บาร์โค้ดไม่ถูกต้อง';
}
else
{
  $qs = dbQuery("SELECT id FROM tbl_order_lend_detail WHERE id_order = '".$id_order."' AND id_product = '".$id_pd."'");
  if(dbNumRows($qs) == 1)
  {
    list($id) = dbFetchArray($qs);
  }
  else
  {
    $sc = FALSE;
    $message = 'สินค้าไม่ถูกต้อง';
  }
}

echo $sc === TRUE ? $id : $message;

 ?>
