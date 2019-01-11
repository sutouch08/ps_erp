<?php
//--- ใช้สำหรับตรวจสอบความผิดพลาด
$sc = TRUE;

//--- รายการที่จะย้ายเข้า
$id = $_POST['id_move_detail'];

//--- เอกสาร
$id_move = $_POST['id_move'];

//--- โซนปลายทาง
$id_zone = $_POST['id_zone'];

//--- ไอดีคลังปลายทาง
$id_warehouse = $_POST['id_warehouse'];

//--- บาร์โค้ดสินค้า
$barcode = $_POST['barcode'];

//--- จำนวนที่ย้ายเข้า
$qty = $_POST['qty'];

//---  move object
$cs  = new move($id_move);

//--- stock object
$stock = new stock();

//--- movement object
$movement = new movement();

//--- barcode object;
$bc = new barcode();

//--- zone object
$zone = new zone();

//--- ไอดีสินค้า
$id_product = $bc->getProductId($barcode);


if( $id_product === FALSE )
{
  $sc = FALSE;
  $message = 'บาร์โค้ดไม่ถูกต้อง หรือ ไม่มีสินค้านี้ในระบบ';
}
else
{
  /*
    1. เพิ่มยอดเข้าโซนปลายทาง (ตรวจสอบก่อนว่าก่อนหน้าเคยมีการย้ายเข้าแล้วหรือยัง ถ้ามีแล้วต้องเข้าโซนเดิมเท่านั้น)
    2. บันทึก movement
    3. ลดยอดใน temp
    4. update โซนปลายทาง ใน tbl_move_detail (ถ้ายังไม่มีการย้ายเข้าโซนมาก่อน)
    5. ส่งผลลัพธ์ กลับ
  */
  //---   เริ่มทรานเซ็คชั่น
  startTransection();

  //--- ดึงรายการโอนออก
  $rd = $cs->getDetail($id);

  //--- ดึงรายการใน temp
  $qs = $cs->getTempDetail($id);

  if( dbNumRows($qs) == 1 )
  {
    $rs = dbFetchObject($qs);

    //--- ตรวจสอบยอดโอน (ต้องน้อยกว่า หรือ เท่ากับยอดใน temp)
    if( $qty <= $rs->qty)
    {
      //--- ตรวจสอบโซนปลายทางเคยโอนเข้าแล้วหรือยัง
      //--- ถ้าโซนปลายทางเป็น 0 แสดงว่ายังไม่เคยโอนเข้าปลายทาง
      //--- ถ้าเป็นตัวเลขอื่นแสดงว่าเคยโอนมาแล้ว ต้องโอนเข้าโซนเดิมเท่านั้น
      if( $rd->to_zone == 0 OR $rd->to_zone == $id_zone)
      {
        //--- เพิ่มยอดเข้าโซนปลายทาง
        if( $stock->updateStockZone($id_zone, $id_product, $qty ) !== TRUE )
        {
          $sc = FALSE;
          $message = 'โอนยอดเข้าปลายทางไม่สำเร็จ';
        }

        //--- บันทึก movement เข้า
        if( $movement->move_in($cs->reference, $id_warehouse, $id_zone, $id_product, $qty, $cs->date_add) !== TRUE )
        {
          $sc = FALSE;
          $message = 'บันทึก movement ไม่สำเร็จ';
        }

        //--- ลดยอดใน temp
        if( $cs->updateMoveTemp($rs->id, ($qty * -1)) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ปรับปรุง ยอดใน temp ไม่สำเร็จ';
        }

        //--- ลบ temp ที่ยอดเป็น 0
        $cs->dropZeroTemp($id);

        //--- update ไอดีปลายทางใน tbl_move_detail (to_zone)
        if( $rd->to_zone == 0)
        {
          if( $cs->updateToZone($id, $id_zone) !== TRUE )
          {
            $sc = FALSE;
            $message = 'บันทึกโซนปลายทางไม่สำเร็จ';
          }
        }

        //--- update valid detail
        if( $qty == $rs->qty)
        {
          if( $cs->setValid($id, 1) !== TRUE)
          {
            $sc = FALSE;
            $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
          }
        }
      }
      else
      {
        $sc = FALSE;
        $message = 'ไม่สามารถโอนสินค้าเข้าโซนนี้ได้ ต้องโอนเข้าโซน '. $zone->getName($rd->to_zone).' เท่านั้น';

      } //--- end if $rd->to_zone

    }
    else
    {
      $sc = FALSE;
      $message = 'ยอดโอนสินค้าต้องไม่มากกว่ายอดรอโอนเข้า';

    } //--- end if $qty <= $rs->qty

  }
  else
  {
    $sc = FALSE;
    $message = 'ไม่มีสินค้ารอโอนเข้า';

  } //--- end if dbNumRows($qs)

  if( $sc === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

} //--- end if $id_product === FALSE

echo $sc === TRUE ? 'success' : $message;

 ?>
