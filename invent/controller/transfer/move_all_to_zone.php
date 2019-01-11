<?php
//--- ใช้สำหรับตรวจสอบความผิดพลาด
$sc = TRUE;

//--- เอกสาร
$id = $_GET['id_transfer'];

//--- โซนปลายทาง
$id_zone = $_GET['to_zone'];

//---  transfer object
$cs  = new transfer($id);

//--- stock object
$stock = new stock();

//--- movement object
$movement = new movement();

//--- ดึงรายการใน temp
$qs = $cs->getTempDetails($id);

if( dbNumRows($qs) > 0 )
{

  startTransection();

  while( $rs = dbFetchObject($qs))
  {
    //---- เพิ่มยอดสินค้าเข้าโซนปลายทาง (tbl_stock)
    if( $stock->updateStockZone($id_zone, $rs->id_product, $rs->qty) === FALSE )
    {
      $sc = FALSE;
      $message = 'ย้ายสินค้าเข้าโซนปลายทางไม่สำเร็จ';
    }

    //--- ตัดยอดออกจาก temp (tbl_transfer_temp)
    if( $cs->removeTempDetail($rs->id_transfer_detail) !== TRUE )
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

    //--- เปลี่ยนสถานะรายการเป็นย้ายเข้าปลายทางแล้ว (valid = 1) ใน tbl_transfer_detail
    if( $cs->validDetail($rs->id_transfer_detail, $id_zone) !== TRUE )
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
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


}
else
{
  $sc = FALSE;
  $message = 'ไม่พบรายการใน temp';
}

echo $sc === TRUE ? 'success' : $message;


 ?>
