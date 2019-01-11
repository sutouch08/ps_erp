<?php
$sc = TRUE;
$id = $_POST['id_consign_check'];
$id_box = $_POST['id_box'];
$id_pd = $_POST['id_product'];

$cs = new consign_check();

//----- get product checked in this box
$qs = $cs->getConsignBoxDetail($id, $id_box, $id_pd);

if(dbNumRows($qs) == 1)
{
  $rs = dbFetchObject($qs);
  startTransection();

  $qty = $rs->qty * -1;
  //--- ลดยอดตรวจนับ
  if($cs->updateCheckedQty($id, $id_pd, $qty) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบยอดตรวจนับรวมไม่สำเร็จ';
  }
  else
  {
    if( $cs->deleteCheckedProductByBox($id, $id_box, $id_pd) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบยอดสินค้าในกล่องไม่สำเร็จ';
    }
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
