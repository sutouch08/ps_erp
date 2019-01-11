<?php
//--- ใช้สำหรับตรวจสอบความผิดพลาด
$sc = TRUE;

//--- รายการที่จะย้ายเข้า
$id = $_GET['id_move_detail'];

//--- เอกสาร
$id_move = $_GET['id_move'];

//---  move object
$cs  = new move($id_move);

//--- stock object
$stock = new stock();

//--- movement object
$movement = new movement();

//--- zone object
$zone = new zone();

//--- โซนปลายทาง
$id_zone = $_GET['to_zone'];

//--- คลังปลายทาง
$id_warehouse = $zone->getWarehouseId($id_zone);

//--- รายการโอน
$ds = $cs->getDetail($id);

//--- ดึงรายการใน temp
$qs = $cs->getTempDetail($id);

if( dbNumRows($qs) == 1 )
{

  $rs = dbFetchObject($qs);

  startTransection();

  //--- ตรวจสอบว่าเคยย้ายเข้าโซนแล้วหรือยัง
  if( $ds->to_zone != 0 && $id_zone != $ds->to_zone)
  {
    $sc = FALSE;
    $message = 'รายการนี้มีการย้ายเข้าโซน '.$zone->getName($ds->to_zone).' แล้วบางส่วน คุณต้องย้ายเข้าโซนเดิมเท่านั้น';
  }
  else
  {
    //--- ตรวจสอบว่า พยายามย้ายสินค้าข้ามคลังหรือไม่
    if( $rs->id_warehouse != $id_warehouse )
    {
      $sc = FALSE;
      $message = 'คลังต้นทางและคลังปลายทางต้องเป็นคลังเดียวกัน';
    }
    else
    {
      //---- เพิ่มยอดสินค้าเข้าโซนปลายทาง (tbl_stock)
      if( $stock->updateStockZone($id_zone, $rs->id_product, $rs->qty) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ย้ายสินค้าเข้าโซนปลายทางไม่สำเร็จ';
      }

      //--- ตัดยอดออกจาก temp (tbl_move_temp)
      if( $cs->removeTempDetail($id) !== TRUE )
      {
        $sc = FALSE;
        $message = 'ตัดยอดออกจาก temp ไม่สำเร็จ';
      }

      //--- บันทึก movement เข้า (tbl_stock_movement)
      if( $movement->move_in($cs->reference, $id_warehouse, $id_zone, $rs->id_product, $rs->qty, $cs->date_add) !== TRUE )
      {
        $sc = FALSE;
        $message = 'บันทึก movement เข้า ไม่สำเร็จ';
      }


      //--- เปลียนโซนปลายทางจาก 0 เป็นโซนปลายทางที่ถูกต้อง
      //--- เปลี่ยนสถานะรายการเป็นย้ายเข้าปลายทางแล้ว (valid = 1) ใน tbl_move_detail
      if( $cs->validDetail($id, $id_zone) !== TRUE )
      {
        $sc = FALSE;
        $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
      }

    } //--- end if $rs->id_warehouse


  } //--- end if $ds->to_zone != 0 && $id_zone != $ds->to_zone





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
  $message = 'ไม่พบรายการใน temp';
}

echo $sc === TRUE ? 'success' : $message;


 ?>
