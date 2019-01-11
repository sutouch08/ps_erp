<?php
$sc = TRUE;

$id = $_POST['id_consign'];
$cs = new consign($id);

$order = new order();
$stock = new stock();
$movement = new movement();
$zone = new zone($cs->id_zone);

/*
1. เพิ่มสต็อกในโซนที่ถูกตัด
2. ลบ movement
3. ลบรายการบันทึกขาย
4. ลบรายการตัดยอด
5. เปลี่ยนสถานะเอกสารเป็นยกเลิก
*/


$qs = $cs->getDetails($cs->id);
if( dbNumRows($qs) > 0)
{
  startTransection();

  while($rs = dbFetchObject($qs))
  {
    if($sc == FALSE)
    {
      break;
    }

    //--- 1. เพิ่มสต็อกในโซนที่ถูกตัด
    if( $stock->updateStockZone($cs->id_zone, $rs->id_product, $rs->qty) !== TRUE)
    {
      $sc = FALSE;
      $message = 'คืนสต็อกกลับเข้าโซนไม่สำเร็จ';
    }

    //--- 2. ลบ movement
    if( $movement->dropMoveOut($cs->reference, $cs->id_zone, $rs->id_product) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบ movement ไม่สำเร็จ';
    }

    //--- 3. ลบรายการบันทึกขาย
    if($cs->isSaved == 1)
    {
      $sold = $order->getConsignSoldDetail($cs->reference, $rs->id_product, $cs->id_zone);
      if( $sold === FALSE )
      {
        $sc = FALSE;
        $message = 'ไม่พบรายการบันทึกขาย';
      }
      else
      {
        if( $order->unSold($sold->id) !== TRUE )
        {
          $sc = FALSE;
          $message = 'ลบรายการบันทึกขายไม่สำเร็จ';
        }
      } //--- end if id_sold === false
    }
    

    //--- 4. ลบรายการตัดยอด
    if( $cs->deleteDetail($rs->id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบรายการตัดยอดไม่สำเร็จ';
    }

  } //--- end while

  if( $sc === TRUE )
  {
    if( $cs->setCancle($cs->id, 1) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ยกเลิกเอกสารไม่สำเร็จ';
    }
  }

  if( $sc === TRUE )
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
  $message = 'ไม่พบรายการที่ต้องการยกเลิก';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
