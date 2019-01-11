<?php
$sc = TRUE;
$id = $_GET['id_consign_check'];
$cs = new consign_check($id);
$stock = new stock();

$qs = $stock->getStockInZone($cs->id_zone);

if(dbNumRows($qs) > 0)
{
  startTransection();
  while($rs = dbFetchObject($qs))
  {
    if($sc == FALSE)
    {
      break;
    }
    
    $arr = array(
      'id_consign_check' => $id,
      'id_product' => $rs->id_product,
      'product_code' => $rs->code,
      'stock_qty' => $rs->qty
    );

    if($cs->addDetail($arr) !== TRUE)
    {
      $sc = FALSE;
      $message = 'บันทึกยอดตั้งต้นไม่สำเร็จ';
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
else
{
  $sc = FALSE;
  $message = 'ไม่พบสินค้าคงเหลือในโซน';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
