<?php
  $sc 			= TRUE;
  $id_order = $_POST['id_order'];
  $id_box		= $_POST['id_box'];
  $product	= $_POST['product'];
  $order    = new order();
  $buffer   = new buffer();
  $qc       = new qc();
  $pd       = new product();

  if(empty($product))
  {
    $sc = FALSE;
    $message = 'ไม่พบรายการตรวจสินค้า';
  }

  if($sc === TRUE)
  {
    startTransection();

    foreach($product as $id_pd => $qty)
    {
      $orderQty = $order->getOrderQty($id_order, $id_pd);
      $bufferQty = $buffer->getSumQty($id_order, $id_pd);
      $qcQty = $qc->getSumQty($id_order, $id_pd);

      //--- ยอดที่จัดมาต้องน้อยกว่า หรือ เท่ากับยอดที่สั่ง
      //--- ถ้ามากกว่าให้ใช้ยอดที่สั่งในการตรวจสอบ
      $prepared = $bufferQty <= $orderQty ? $bufferQty : $orderQty;

      //--- ยอดที่จะบันทึกตรวจต้องรวมกันแล้วไม่เกินยอดที่จัดและต้องไม่เกินยอดสั่ง
      $updateQty = $qcQty + $qty;

      /*
      if($updateQty > $prepared)
      {
        $sc = FALSE;
        $message = $pd->getCode($id_pd).' ยอดตรวจเกินยอดจัดหรือยอดสั่ง';
        break;
      }
      */
      //--- update ยอดตรวจ
      if(!$qc->updateChecked($id_order, $id_box, $id_pd, $qty))
      {
        $sc = FALSE;
        $message = $pd->getCode($id_pd).' บันทึกยอดตรวจไม่สำเร็จ';
      }
      dbQuery("DELETE FROM tbl_qc WHERE id_order = '".$id_order."' AND qty <= 0");

    } //--- end foreach

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

echo $sc == TRUE ? 'success' : $message;

 ?>
