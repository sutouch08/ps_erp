<?php
//--- ไว้ตรวจสอบความถูกต้อง
$sc = TRUE;

//--- ไอดีของรายการที่จะลบ
$id = $_POST['id_adjust'];

$cs = new adjust($id);

//--- stock object สำหรับจัดการสต็อก
$stock = new stock();

//--- zone object สำหรับจัดการโซน
$zone = new zone();

//--- movement object สำหรับจัดการ movement
$movement = new movement();

/*
ขั้นตอน
กรณีปรับยอด(บันทึก)ไปแล้ว
1. ปรับปรุงสต็อกในโซนที่ปรับ เพิ่ม/ลด
  - หากจำนวนเพียงพอ หรือ ติดลบได้ ทำข้อ 2
  - หากจำนวนไม่พอ และ ไม่สามารถติดลบได้ ทำข้อ 4
2. ลบ movement โซนที่มีการปรับ
3. ลบรายการปรับยอด
4. แจ้งผลกลับ

กรณีที่ยังไม่ได้ปรับยอด(บันทึก)
1. ลบรายการปรับยอด
2. แจ้งผลกลับ
*/

if( $cs->isCancle == 0)
{
  //--- ดึงข้อมูลรายการปรับยอดที่จะลบออก
  $qs = $cs->getDetails($id);
  if( dbNumRows($qs) > 0)
  {
    startTransection();

    while( $rs = dbFetchObject($qs))
    {
      //--- หากมีการปรับยอด(บันทึก)ไปแล้ว (สินค้าในโซนมีการเพิ่มหรือลดตามรายการไปแล้ว)
      if( $cs->isValidDetail($rs->id) === TRUE )
      {
        //--- ตรวจสอบว่ายอดในโซนพอหรือไม่
        //--- ถ้ายอดที่เคยปรับเพิ่มใป(จำนวนเป็นบวก) ต้องเช็คว่าพอหรือไม่
        //--- ถ้ายอดที่เคยปรับลดไป(จำนวนเป็นลบ) ไม่ต้องเช็ค (ให้เป็น TRUE ได้เลย)
        $isEnough = $rs->qty > 0 ? $stock->isEnough($rs->id_zone, $rs->id_product, $rs->qty) : TRUE;

        //--- โซนนี้อนุญาติให้ติดลบได้หรือไม่
        $isAllowUnderZero = $zone->isAllowUnderZero($rs->id_zone);

        //--- ถ้ามียอดให้ไม่พอตัดออก และ โซนไม่อนุญาติให้ติดลบได้
        if( $isEnough === FALSE AND $isAllowUnderZero === FALSE)
        {
          $sc = FALSE;
          $message = 'สต็อกไม่สามารถติดลบได้';
        }
        else
        {
          //--- ถ้ามียอดให้พอตัดออก หรือ โซนอนุญาติให้ติดลบได้
          //--- ปรับยอดในโซนถ้าเคยปรับเพิ่ม ให้ปรับลด ถ้าเคยปรับลด ให้ปรับเพิ่ม ( x -1 เพื่อกลับบวกเป็นลบ กลับลบเป็นบวก)
          if($stock->updateStockZone($rs->id_zone, $rs->id_product, $rs->qty * -1) !== TRUE)
          {
            $sc = FALSE;
            $message = 'การปรับยอดสินค้าในโซนล้มเหลว';
          }

          //------ ลบ movement
          //--- ถ้าเคยปรับเพิ่ม
          if( $rs->qty > 0)
          {
            if( $movement->dropMoveIn($cs->reference, $rs->id_zone, $rs->id_product) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ลบ movement ไม่สำเร็จ';
            }
          }

          //--- ถ้าเคยปรับลด
          if( $rs->qty < 0)
          {
            if( $movement->dropMoveOut($cs->reference, $rs->id_zone, $rs->id_product) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ลบ movement ไม่สำเร็จ';
            }
          }

          //--- ลบรายการปรับยอด
          if( $cs->deleteDetail($rs->id) !== TRUE)
          {
            $sc = FALSE;
            $message = 'ลบรายการไม่สำเร็จ';
          }

        } //--- endf if isEnough

      }
      else
      {
        //--- หากยังไม่มีการปรับยอด(บันทึก)
        //--- ลบรายการปรับยอด
        if( $cs->deleteDetail($rs->id) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบรายการไม่สำเร็จ';
        }

      }

    } //--- end while

    if( $sc === TRUE )
    {
      if($cs->setCancle($id) !== TRUE)
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

} //--- end if isCancle

echo $sc === TRUE ? 'success' : $message;

 ?>
