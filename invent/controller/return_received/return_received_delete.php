<?php
$sc = TRUE;
$reference = $_POST['reference'];
$cs = new return_received($reference);
$stock = new stock();
$zone = new zone();
$movement = new movement();
$qs = $cs->getDetails($reference);
if( dbNumRows($qs) > 0)
{

  startTransection();

  while( $rs = dbFetchObject($qs))
  {
    //--- ถ้าบันทึกแล้ว
    if( $rs->valid == 1)
    {
      //---- คืนยอดสินค้ากลับเข้าโซนเดิม
      if( $stock->updateStockZone( $rs->id_zone, $rs->id_product, $rs->qty ) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ปรับยอดสต็อกในโซนไม่สำเร็จ';
      }

      //--- ลบ movement
      if( $movement->dropMoveOut($reference, $rs->id_zone, $rs->id_product) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบ movement ไม่สำเร็จ';
      }

    } //--- end if valid

    //--- ลบรายการรับคืน
    if( $cs->delete($rs->id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบรายการไม่สำเร็จ';
    }

  } //--- end while

  if( $sc === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

} //--- end if dbNumrows
else
{
  $sc = FALSE;
  $message = 'ไม่พบรายการ';
}

echo $sc === TRUE ? 'success' : $message;
 ?>
