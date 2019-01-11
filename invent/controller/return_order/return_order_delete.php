<?php
$sc = TRUE;
$reference = $_POST['reference'];
$cs = new return_order($reference);
$stock = new stock();
$zone = new zone();
$movement = new movement();
$qs = $cs->getDetails($reference);
if( dbNumRows($qs) > 0)
{
  $isAllowUnderZero = $zone->isAllowUnderZero($cs->id_zone);
  startTransection();
  while( $rs = dbFetchObject($qs))
  {
    //--- ถ้ามีการคืนสินค้า และ บันทึกแล้ว
    if( $rs->valid == 1 && $rs->isReturn == 1)
    {
      $isEnough = $stock->isEnough($rs->id_zone, $rs->id_product, $rs->received);
      if( $isEnough === FALSE && $isAllowUnderZero === FALSE )
      {
        $sc = FALSE;
        $message = 'สินค้าคงเหลือในโซนไม่พอให้ลบ';
      }
      else
      {
        //--- ลดยอดสต็อกในโซนที่เคยรับคืน
        if( $stock->updateStockZone( $rs->id_zone, $rs->id_product, ($rs->received * -1) ) !== TRUE )
        {
          $sc = FALSE;
          $message = 'ปรับยอดสต็อกในโซนไม่สำเร็จ';
        }


        //--- ลบ movement
        if( $movement->dropMoveIn($reference, $rs->id_zone, $rs->id_product) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบ movement ไม่สำเร็จ';
        }

      } //--- end if isEnough

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
