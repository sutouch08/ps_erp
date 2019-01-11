<?php
$sc = TRUE;
$id_zone = $_POST['id_zone'];
$reference = $_POST['reference'];
$cs = new return_order($reference);
$stock = new stock();
$movement = new movement();
$ds = $_POST['qty'];
if( ! empty($ds))
{
  startTransection();

  foreach($ds as $id_pd => $qty)
  {
    //--- update received qty (ต้องเท่ากับ qty)
    if($cs->updateReceived($cs->bookcode, $reference, $id_pd, $qty) !== TRUE)
    {
      $sc = FALSE;
      $message = 'บันทึกรับเข้าไม่สำเร็จ';
    }

    //--- ถ้ามีการคืนสินค้า (กรณีลูกค้าซื้อแล้วคืน)
    if( $cs->isReturn == 1 )
    {
      //--- update stock
      if( $stock->updateStockZone($id_zone, $id_pd, $qty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับยอดสต็อกไม่สำเร็จ';
      }

      //--- บันทึก movement
      if( $movement->move_in($reference, $cs->id_warehouse, $id_zone, $id_pd, $qty, dbDate($cs->date_add, TRUE)) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึก movement ไม่สำเร็จ';
      }

    } //--- endif


  } //--- end foreach

  //----  ถ้าไม่มีอะไรผิดพลาด
  if( $sc === TRUE )
  {
    //--- เปลี่ยนโซนให้ตรงตามที่กำหนดมา
    $cs->setZone($reference, $id_zone);
    
    //--- เปลี่ยนสถานะรายการเป็นบันทึกแล้ว (valid = 1)
    if( $cs->setValid($reference, 1) !== TRUE)
    {
      $sc = FALSE;
      $message = 'บันทึกเอกสารไม่สำเร็จ';
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

echo $sc === TRUE ? 'success' : $message;

 ?>
