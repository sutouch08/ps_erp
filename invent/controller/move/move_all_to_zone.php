<?php
//--- ใช้สำหรับตรวจสอบความผิดพลาด
$sc = TRUE;

//--- เอกสาร
$id = $_GET['id_move'];

//---  move object
$cs  = new move($id);

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



//--- ดึงรายการใน temp
$qs = $cs->getTempDetails($id);

if( dbNumRows($qs) > 0 )
{

  startTransection();

  while( $rs = dbFetchObject($qs))
  {
    if($sc === FALSE)
    {
      break;
    }

    //---- เพิ่มยอดสินค้าเข้าโซนปลายทาง (tbl_stock)
    if( $stock->updateStockZone($id_zone, $rs->id_product, $rs->qty) !== TRUE )
    {
      $sc = FALSE;
      $message = 'ย้ายสินค้าเข้าโซนปลายทางไม่สำเร็จ';
      break;
    }

    //--- ตัดยอดออกจาก temp (tbl_move_temp)
    if( $cs->removeTempDetail($rs->id_move_detail) !== TRUE )
    {
      $sc = FALSE;
      $message = 'ตัดยอดออกจาก temp ไม่สำเร็จ';
      break;
    }

    //--- บันทึก movement เข้า (tbl_stock_movement)
    if( $movement->move_in($cs->reference, $id_warehouse, $id_zone, $rs->id_product, $rs->qty, $cs->date_add) !== TRUE )
    {
      $sc = FALSE;
      $message = 'บันทึก movement เข้า ไม่สำเร็จ';
      break;
    }

    //--- เปลี่ยนสถานะรายการเป็นย้ายเข้าปลายทางแล้ว (valid = 1) ใน tbl_move_detail
    if( $cs->validDetail($rs->id_move_detail, $id_zone) !== TRUE )
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
      break;
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
