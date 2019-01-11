<?php
//--- ใช้สำหรับตรวจสอบความผิดพลาด
$sc = TRUE;

//--- รายการที่จะย้ายเข้า
$id = $_GET['id_transfer_detail'];

//--- เอกสาร
$id_transfer = $_GET['id_transfer'];

//--- โซนปลายทาง
$id_zone = $_GET['to_zone'];

//---  transfer object
$cs  = new transfer($id_transfer);

//--- stock object
$stock = new stock();

//--- movement object
$movement = new movement();

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
    $zone = new zone();
    $message = 'รายการนี้มีการย้ายเข้าโซน '.$zone->getName($ds->to_zone).' แล้วบางส่วน คุณต้องย้ายเข้าโซนเดิมเท่านั้น';
  }
  else
  {
    //---- เพิ่มยอดสินค้าเข้าโซนปลายทาง (tbl_stock)
    if( $stock->updateStockZone($id_zone, $rs->id_product, $rs->qty) === FALSE )
    {
      $sc = FALSE;
      $message = 'ย้ายสินค้าเข้าโซนปลายทางไม่สำเร็จ';
    }

    //--- ตัดยอดออกจาก temp (tbl_transfer_temp)
    if( $cs->removeTempDetail($id) !== TRUE )
    {
      $sc = FALSE;
      $message = 'ตัดยอดออกจาก temp ไม่สำเร็จ';
    }

    //--- บันทึก movement เข้า (tbl_stock_movement)
    if( $movement->move_in($cs->reference, $cs->to_warehouse, $id_zone, $rs->id_product, $rs->qty, $cs->date_add) !== TRUE )
    {
      $sc = FALSE;
      $message = 'บันทึก movement เข้า ไม่สำเร็จ';
    }


    //--- เปลียนโซนปลายทางจาก 0 เป็นโซนปลายทางที่ถูกต้อง
    //--- เปลี่ยนสถานะรายการเป็นย้ายเข้าปลายทางแล้ว (valid = 1) ใน tbl_transfer_detail
    if( $cs->validDetail($id, $id_zone) !== TRUE )
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
    }

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
