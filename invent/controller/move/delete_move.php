<?php

/*
ขั้นตอนคือ
1. ตรวจสอบว่าย้ายไปปลายทางแล้วหรือยัง
  1.1 ถ้าย้ายแล้ว
    1.1.1 ตรวจสอบยอดคงเหลือที่ปลายทางว่าพอให้ย้ายกลับหรือไม่
            - ถ้าพอทำข้อ 2
            - ถ้าไม่พอ ทำข้อ 5
  1.2 ถ้ายังไม่ย้าย
        - ทำข้อ 3

2. ย้ายสินค้ากลับ (ถ้าสินค้าพอ)
  2.1 ลดยอดสินค้าจากโซนปลายทาง
  2.2 ลบ movement ขาเข้าโซนปลายทาง
  2.3 เพิ่มยอดเข้าโซนต้นทาง
  2.4 ลบ movement ขาออกจากโซนต้นทาง
  2.5 ลบรายการโอนสินค้า

3. ย้ายสินค้าจาก temp กลับเข้าโซนต้นทาง
  3.1 เพิ่มยอดสินค้าเข้าโซน
  3.2 ลบ movement ขาออกจากโซนต้นทาง
  3.3 ลบ temp
  3.4 ลบรายการโอนสินค้า

4. เมื่อทำซ้ำ 1-3 จนครบทุกรายการแล้ว ไม่มีอะไรผิดพลาด
  - เปลี่ยนสถานะเอกสารเป็น ยกเลิก

5. แจ้งผลแล้วออกจากการทำงาน

*/


$sc = TRUE;

//--- เอกสารที่จะลบ
$id = $_POST['id_move'];

//--- move object
$cs = new move($id);

$stock = new stock();

$zone = new zone();

$movement = new movement();


//--- ตรวจสอบรายการภายในก่อน
$qs = $cs->getDetails($id);

//--- ถ้ามีรายการข้างใน
if( dbNumRows($qs) > 0)
{
  startTransection();

  while( $rs = dbFetchObject($qs))
  {
    if($sc === FALSE)
    {
      break;
    }
    
    //--- 1. ตราจสอบว่าย้ายเข้าปลายทางแล้วหรือไม่
    if( $rs->valid == 1)
    {
      //--- ถ้าย้ายเช้าปลายทางแล้ว
      //--- ตรวจสอบวาโซนปลายทางมียอดเหลือพอให้ย้ายมั้ย
      $isEnough = $stock->isEnough($rs->to_zone, $rs->id_product, $rs->qty);

      //--- ตรวจสอบว่าโซนปลายทางอนุญาติให้ติดลบได้หรือไม่
      $isAllowUnderZero = $zone->isAllowUnderZero($rs->to_zone);

      //--- ถ้าสินค้าไม่พอ และ ไม่อนุญาติให้ติดลบ
      if( $isEnough === FALSE &&  $isAllowUnderZero === FALSE )
      {
        $sc = FALSE;
        $message = 'สินค้าคงเหลือในโซน '. $zone->getName($rs->to_zone).' ไม่พอให้ย้ายกลับ';
      }
      else
      {
        //--- ถ้าสินค้าพอย้ายกลับ หรือ โซนสามารถติดลบได้
        //--- ลดยอดโซนปลายทาง
        if( $stock->updateStockZone($rs->to_zone, $rs->id_product, ($rs->qty * -1) ) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลดยอดสต็อกปลายทางไม่สำเร็จ';
        }

        //--- ลบ movement ขาเข้าโซนปลายทาง
        if( $movement->dropMoveIn($cs->reference, $rs->to_zone, $rs->id_product) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบ movement ขาเข้าโซนปลายทางไม่สำเร็จ';
        }

        //--- เพิ่มยอดสินค้าเข้าโซนต้นทาง
        if( $stock->updateStockZone($rs->from_zone, $rs->id_product, $rs->qty) !== TRUE)
        {
          $sc = FALSE;
          $message = 'เพิ่มยอดสต็อกกลับเข้าโซนต้นทางไม่สำเร็จ';
        }

        //--- ลบ movement ขาออกจากโซนต้นทาง
        if( $movement->dropMoveOut($cs->reference, $rs->from_zone, $rs->id_product) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบ movement ขาออกจากโซนต้นทางไม่สำเร็จ';
        }

        //--- ลบรายการโอนสินค้า
        if( $cs->deleteDetail($rs->id) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบรายการโอนสินค้าไม่สำเร็จ';
        }
      }
    }
    else
    {
      //--- ถ้ายังไม่ได้ย้ายเข้าปลายทาง
      //---
      $tmp = dbFetchObject($cs->getTempDetail($rs->id));
      if( !empty($tmp))
      {

        //--- ถ้าจำนวนใน temp น้อยกว่า รายการโอน
        //--- แสดงว่ามีการโอนเข้าโซนปลายทางบางส่วนแล้ว (ผ่าน การยิงบาร์โค้ด)
        if( $tmp->qty < $rs->qty )
        {
          $diff_qty = $rs->qty - $tmp->qty;

          //--- ต้องย้ายสินค้าออกจากโซนปลายทางกลับเข้า temp
          //--- ตรวจสอบวาโซนปลายทางมียอดเหลือพอให้ย้ายมั้ย
          $isEnough = $stock->isEnough($rs->to_zone, $rs->id_product, $diff_qty);

          //--- ตรวจสอบว่าโซนปลายทางอนุญาติให้ติดลบได้หรือไม่
          $isAllowUnderZero = $zone->isAllowUnderZero($rs->to_zone);

          //--- ถ้าสินค้าไม่พอ และ ไม่อนุญาติให้ติดลบ
          if( $isEnough === FALSE &&  $isAllowUnderZero === FALSE )
          {
            $sc = FALSE;
            $message = 'สินค้าคงเหลือในโซน '. $zone->getName($rs->to_zone).' ไม่พอให้ย้ายกลับ';
          }
          else
          {
            //--- ถ้าสินค้าพอย้ายกลับ หรือ โซนสามารถติดลบได้
            //--- ลดยอดโซนปลายทาง
            if( $stock->updateStockZone($rs->to_zone, $rs->id_product, ($diff_qty * -1) ) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ลดยอดสต็อกปลายทางไม่สำเร็จ';
            }

            //--- ลบ movement ขาเข้าโซนปลายทาง
            if( $movement->dropMoveIn($cs->reference, $rs->to_zone, $rs->id_product) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ลบ movement ขาเข้าโซนปลายทางไม่สำเร็จ';
            }


            //--- เพิ่มยอดเข้า temp
            if( $cs->updateTransferTemp($tmp->id, $diff_qty ) !== TRUE)
            {
              $sc = FALSE;
              $message = 'Update temp ไม่สำเร็จ';
            }

          } //--- end if isEnough

        } //--- end if $temp->qty < $rs->qty

        //--- เพิ่มยอดเข้าโซนต้นทาง
        if( $stock->updateStockZone($rs->from_zone, $rs->id_product, $rs->qty) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ย้ายสินค้าจาก temp กลับโซนไม่สำเร็จ';
        }

        //--- ลบ movement ขาออกจากโซนต้นทาง
        if( $movement->dropMoveOut($cs->reference, $rs->from_zone, $rs->id_product) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบ movement ขาออกจากโซนต้นทางไม่สำเร็จ';
        }

        //--- ลบ temp
        if( $cs->removeTempDetail($rs->id) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบ temp ไม่สำเร็จ';
        }

        //--- ลบรายการโอนสินค้า
        if( $cs->deleteDetail($rs->id) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบรายการโอนสินค้าไม่สำเร็จ';
        }

      }
      else
      {
        $sc = FALSE;
        $message = 'ไม่พบรายการใน temp';
      }

    } //--- end if $rs->valid

  } //--- end while

  //--- ถ้าดำเนินการครบทุกรายการแล้ว
  if( $sc === TRUE )
  {
    if( $cs->cancled($id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ยกเลิกเอกสารไม่สำเร็จ';
    }
  }

  //--- ถ้าทุกอย่างไม่มีอะไรผิดพลาด
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
  //--- ถ้ายังไม่มีรายการในเอกสาร ยกเลิกได้เลย
  if( $cs->cancled($id) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ยกเลิกเอกสารไม่สำเร็จ';
  }
}

echo $sc === TRUE ? 'success' : $message;

 ?>
