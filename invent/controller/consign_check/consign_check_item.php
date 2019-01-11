<?php
$sc = TRUE;
$id_consign_check = $_POST['id_consign_check'];
$barcode = $_POST['barcode'];  //---- barcode item
$qty = $_POST['qty'];
$id_box = $_POST['id_box'];
$bc = new barcode();
$cs = new consign_check();

$id_pd = $bc->getProductId($barcode);
if($id_pd === FALSE)
{
  $sc = FALSE;
  $message = 'ไม่พบสินค้าในระบบ กรุณาตรวจสอบบาร์โค้ด';
}
else
{

  startTransection();

  //----- update check qty in tbl_consign_check_detail
  if($cs->updateCheckedQty($id_consign_check, $id_pd, $qty) !== TRUE)
  {
    $sc = FALSE;
    $message = 'บันทึกจำนวนตรวจนับไม่สำเร็จ';
  }

  //----  update qty to consign_box
  if($cs->updateConsignBoxDetail($id_box, $id_consign_check, $id_pd, $qty) !== TRUE)
  {
    $sc = FALSE;
    $message = 'บันทึกยอดตรวจนับลงกล่องไม่สำเร็จ';
  }

  if($sc === TRUE)
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();
}

echo $sc === TRUE ? 'success' : $message;

 ?>
