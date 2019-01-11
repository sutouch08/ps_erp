<?php
$sc = TRUE;
$id = $_POST['id_return_lend'];

$cs   = new return_lend($id);
$mv   = new movement();
$stock = new stock();
$wh   = new warehouse();
$zone = new zone();
$pd   = new product();
$lend = new lend();
$order = new order();

/*****
1. ลบ movement in
2. ลด stock โซนปลายทาง
3. ลบ movement out
4. เพิ่ม stock โซนยืมสินค้า
5. ลดยอดรับคืน + เปลี่ยนสถานะ valid เป็น 0
//// ทำซ้ำ จนหมด ///

6. เปลี่ยนรายการรับคืนเป็น ยกเลิก
7. ยกเลิกการปิดเอกสารยืมสินค้า
8. เปลียนสถานะเอกสารรับคืนเป็น ยกเลิก

****/

$id_order = $order->getId($cs->order_code);
$qs = $cs->getDetails($id);

if(dbNumRows($qs) > 0)
{
  startTransection();
  
  while($rs = dbFetchObject($qs))
  {
    //--- สต็อกมีพอให้ตัดหรือไม่
    $isEnough = $stock->isEnough($rs->to_zone, $rs->id_product, $rs->qty);

    //--- โซนอนุญาติให้ติดลบหรือไม่
    $isAUZ    = $zone->isAllowUnderZero($rs->to_zone);

    if($isEnough === FALSE && $isAUZ === FALSE)
    {
      $sc = FALSE;
      $message = $rs->product_code.' มียอดคงเหลือในโซนไม่เพียงพอ';
    }
    else
    {
      //--- 1. ลบ movement in
      if($mv->dropMoveIn($cs->reference, $rs->to_zone, $rs->id_product) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบ movement ขาเข้า ไม่สำเร็จ';
      }

      //--- 2. ลด stock โซนปลายทาง
      if($stock->updateStockZone($rs->to_zone, $rs->id_product, (-1 * $rs->qty)) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ตัดยอดสินค้าออกจากโซนไม่สำเร็จ';
      }

      //--- 3. ลบ movement out
      if($mv->dropMoveOut($cs->reference, $rs->from_zone, $rs->id_product) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบ movement ขาออก ไม่สำเร็จ';
      }

      //--- 4. เพิ่ม stock โซนยืมสินค้า
      if($stock->updateStockZone($rs->from_zone, $rs->id_product, $rs->qty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มยอดสินค้าเข้าโซนไม่สำเร็จ';
      }

      //--- 5. ลดยอดรับคืน + เปลี่ยนสถานะ valid เป็น 0
      if($lend->unReceived($id_order, $rs->id_product, $rs->qty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับปรุงยอดค้างรับไม่สำเร็จ';
      }

    } //--- end if isEnough

  } //-- end while

  if($sc === TRUE)
  {
    //---6. เปลี่ยนรายการรับคืนเป็น ยกเลิก
    if($cs->cancleDetails($id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
    }

    //--- 7. เปลียนสถานะเอกสารรับคืนเป็น ยกเลิก
    if($cs->setCancle($id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะเอกสารไม่สำเร็จ';
    }

    //--- 8. ยกเลิกการปิดเอกสารยืมสินค้า
    if($lend->unClose($id_order) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ยกเลิกการปิดเอกสารยืมสินค้าไม่สำเร็จ';
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
  $message = 'ไม่พบรายการคืนสินค้า';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
