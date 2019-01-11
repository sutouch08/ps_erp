<?php
$sc = TRUE;
$id_zone = $_POST['id_zone'];
$reference = $_POST['reference'];
$cs = new return_received($reference);
$stock = new stock();
$movement = new movement();
$ds = $_POST['qty'];
$detail = $_POST['detail'];

if( ! empty($ds) && ! empty($detail))
{
  startTransection();

  foreach($ds as $id_pd => $qty)
  {
    //---- ไอทีของรายการที่
    $id = $detail[$id_pd];

    //---- ดึงรายละเอียดของรายการ (ได้กลับมาเป็น Object ถ้ามีข้อมูล ถ้าไม่มีข้อมูลจะได้ FALSE)
    $rs = $cs->getDetail($id);

    //--- ตรวจสอบความถูกต้องของข้อมูล
    if($rs !== FALSE && !empty($rs))
    {
      if($rs->isCancle == 0 && $rs->valid == 0)
      {
        //--- ตรวจสอบว่าจำนวนที่คีย์มา ตรงกับจำนวนที่จะคืนไปหรือไม่
        if($qty == $rs->qty)
        {
          //--- ถ้า id_zone == 0 แสดงว่ายังไม่ได้กำหนดโซน
          if( $rs->id_zone != 0 )
          {
            $zone = new zone($rs->id_zone);

            //--- update stock
            if( $stock->updateStockZone($id_zone, $id_pd, (-1 * $qty)) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ปรับยอดสต็อกไม่สำเร็จ';
            }

            //--- บันทึก movement
            if( $movement->move_out($reference, $zone->id_warehouse, $id_zone, $id_pd, $qty, dbDate($cs->date_add, TRUE)) !== TRUE)
            {
              $sc = FALSE;
              $message = 'บันทึก movement ไม่สำเร็จ';
            }

          } //--- endif
        }
        else
        {
          $sc = FALSE;
          $message = $rs->product_code.' จำนวนที่จะตัดไม่ตรงกับจำนวนที่จะคืน';
        } //--- end if $qty == $rs->qty

      }
      else
      {
        $sc = FALSE;
        if($rs->valid == 1)
        {
          $message = $rs->product_code.' ถูกบันทึกไปแล้ว ไม่สามารถบันทึกซ้ำได้';
        }

        if($rs->isCancle == 1)
        {
          $message = $rs->product_code.' ถูกยกเลิกไปแล้ว ไม่สามารถบันทึกได้';
        }

      } //---  end if $rs->isCancle && $rs->valid

    }
    else
    {
      $sc = FALSE;
      $message = 'ไม่พบข้อมูล หรือ ข้อมูลไม่ครบถ้วน';
    } //--- end if !empty $rs

  } //--- end foreach

  if($sc === TRUE)
  {
    if($cs->setValid($reference) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะเอกสารไม่สำเร็จ';
    }
  }

  //--- ถ้าไม่มีอะไรผิดพลาด
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
  $message = 'ไม่พบข้อมูล ไม่สามารถบันทึกรายการได้';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
