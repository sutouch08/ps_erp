<?php
$sc = TRUE;
$id = $_POST['id_consign'];
$cs = new consign($id);

//--- จะยกเลิกการบันทึก
//--- สถานะเอกสารต้องอยู่ที่ บันทึกแล้ว
//--- เอกสารต้องไม่ถูกยกเลิก
if($cs->isSaved == 1 && $cs->isCancle == 0)
{
  //--- ดึงรายการที่บันทึกแล้วเพื่อย้อนกระบวนการ
  $qs = $cs->getSavedDetails($cs->id);
  if(dbNumRows($qs) > 0)
  {
    $order = new order();
    $st    = new stock();
    $mv    = new movement();
    $pd    = new product();

    startTransection();
    while($rs = dbFetchObject($qs))
    {
      if($sc == FALSE)
      {
        break;
      }

      $pd->getData($rs->id_product);

      if($pd->count_stock == 1)
      {
        //--- 1.  ลบ movement
        $move_out = $rs->qty * (-1);
        if($mv->updateMoveOut($cs->reference, $cs->id_zone, $rs->id_product, $move_out) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ลบ movement '.$rs->product_code.' ไม่สำเร็จ';
        }

        //--- 2. คืนยอดสินค้ากลับเข้าโซน
        if($st->updateStockZone($cs->id_zone, $rs->id_product, $rs->qty) !== TRUE)
        {
          $sc = FALSE;
          $message = 'เพิ่มยอด '.$rs->product_code.' กลับเข้าโซนไม่สำเร็จ';
        }

      }

      //--- 3. ลบยอดขาย
      $sold_id = $order->getConsignSoldDetailRowId($cs->reference, $rs->id_product, $cs->id_zone, $rs->price, $rs->qty, $rs->discount);

      if($order->unsold($sold_id) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบรายการขาย '.$rs->product_code.' ไม่สำเร็จ';
      }

      //--- 4. เปลี่ยนสถานะรายการเป็น 0 (ยังไม่บันทึก)
      if($cs->updateDetail($rs->id, array('status' => 0)) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เปลี่ยนสถานะรายการ '.$rs->product_code.' ไม่สำเร็จ';
      }

    } //--- end while

    if($sc === TRUE)
    {
      //--- เปลี่ยนสถานะเอกสารเป็นยังไม่บันทึก
      if($cs->setSaved($cs->id, 0) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เปลี่ยนสถานะเอกสารไม่สำเร็จ';
      }
    }

    if($sc === TRUE)
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
    $message = 'ไม่พบรายการที่บันทึกแล้ว';
  }
}
else  //--- if(isSaved == 1 && isCancle == 0)
{
  $sc = FALSE;
  $message = 'สถานะเอกสารไม่ถูกต้อง ไม่สามารถดำเนินการได้';
} //--- end if


echo $sc === TRUE ? 'success' : $message;
 ?>
