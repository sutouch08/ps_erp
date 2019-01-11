<?php

/*
ขั้นตอนการบันทึก
1.ดึงรายการที่ยังไม่ไม่ยอด มาปรับยอดทีละรายการ แล้วเปลี่ยนสถานะ tbl_adjust_detail.valid = 1
2.บันทึก movement
3.ทำซ้ำข้อ 1-2 จนครบทุกรายการ แล้ว เปลี่ยนสถานะ tbl_adjust.isSaved = 1
4.ส่งผลลัพธ์
*/


//--- ไว้ตรวจสอบความถูกต้อง
$sc = TRUE;

//--- id เอกสาร
$id = $_POST['id_adjust'];

//--- adjust Object
$cs = new adjust($id);

//--- stock object ไว้จัดการสต็อก
$stock = new stock();

//--- zone object ไว้จัดการโซน
$zone = new zone();

//--- movement ไว้จัดการ movement
$movement = new movement();

//--- ดึงรายการทั้งหมดในเอกสาร
$qs = $cs->getDetails($id);

//--- ถ้าในเอกสารมีรายการอยู่
if( dbNumRows($qs) > 0)
{
  startTransection();

  while($rs = dbFetchObject($qs))
  {
    //--- 1. ปรับยอดเฉพาะรายการที่ยังไม่บันทึก
    if( $rs->valid == 0 && $rs->qty != 0)
    {
      //----- Update stock
      if( $rs->qty > 0)
      {
        if( $stock->updateStockZone($rs->id_zone, $rs->id_product, $rs->qty) !== TRUE )
        {
          $sc = FALSE;
          $message = 'ปรับยอดสต็อกเพิ่มไม่สำเร็จ';
        }
      }
      else
      {
        //--- ถ้าเป็นการปรับยอดลดลง
        //--- ตรวจสอบว่าในโซนมียอดเพียงพอหรือไม่ โดยเอายอดที่ติดลบ x -1 เพื่อกลับยอดให้เป็นบวก
        $isEnough = $stock->isEnough($rs->id_zone, $rs->id_product, ($rs->qty * -1));
        $isAllowUnderZero = $zone->isAllowUnderZero($rs->id_zone);

        //--- ถ้ายอดคงเหลือไม่เพียงพอ และ โซนไม่อนุญาติให้ติดลบ
        if( $isEnough === FALSE && $isAllowUnderZero === FALSE )
        {
          $sc = FALSE;
          $message = 'ยอดคงเหลือในโซน '.$zone->getName($rs->id_zone).' ไม่เพียงพอให้ปรับลด';
        }
        else
        {
          //--- ถ้ายอดคงเหลือเพียงพอ หรือ โซนอนุญาติให้ติดลบได้
          //--- ปรับยอดตามรายการ
          if( $stock->updateStockZone($rs->id_zone, $rs->id_product, $rs->qty) !== TRUE )
          {
            $sc = FALSE;
            $message = 'ปรับยอดสต็อกลดลงไม่สำเร็จ';
          }
        }
      } //--- end update stock zone

      //--- 1.1 เปลียนสถานะรายการเป็น บันทึกแล้ว
      if( $cs->setValidDetail($rs->id) !== TRUE )
      {
        $sc = FALSE;
        $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
      }


      //--- 2. บันทึก movement
      if( $rs->qty > 0)
      {
        $re = $movement->move_in($cs->reference, $zone->getWarehouseId($rs->id_zone), $rs->id_zone, $rs->id_product, $rs->qty, $cs->date_add);
        if( $re !== TRUE )
        {
          $sc = FALSE;
          $message = 'บันทึก movement ไม่สำเร็จ';
        }
      }
      else
      {
        $re = $movement->move_out($cs->reference, $zone->getWarehouseId($rs->id_zone), $rs->id_zone, $rs->id_product, ($rs->qty * -1), $cs->date_add);
        if( $re !== TRUE )
        {
          $sc = FALSE;
          $message = 'บันทึก movement ไม่สำเร็จ';
        }
      }

    } //--- end if valid = 0

  } //--- end while

  //---  3. บันทึกเอกสาร
  if( $cs->setSaved($id) !== TRUE )
  {
    $sc = FALSE;
    $message = 'บันทึกเอกสารไม่สำเร็จ';
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
  $message = 'ไม่พบรายการในเอกสาร';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
