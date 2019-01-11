<?php
$sc = TRUE;
$date_add = dbDate($_POST['date_add'], TRUE);
$id_order = $_POST['id_order'];
$id_zone = $_POST['id_zone'];
$remark = addslashes($_POST['remark']);
$qtys = $_POST['qty'];

if(!empty($qtys))
{
  startTransection();
  $order = new order($id_order);
  $lend = new lend($id_order);
  $cs = new return_lend();
  $zone = new zone();
  $pd = new product();
  $stock = new stock();
  $mv = new movement();

  $fWarehouse = $zone->getWarehouseId($lend->id_zone);
  $tWarehouse = $zone->getWarehouseId($id_zone);
  $fZone = $lend->id_zone;
  $tZone = $id_zone;
  $reference = $cs->getNewReference($date_add);

  $arr = array(
    'bookcode' => getConfig('BOOKCODE_LEND'),
    'reference' => $reference,
    'id_order' => $order->id,
    'order_code' => $order->reference,
    'id_customer' => $order->id_customer,
    'id_employee' => getCookie('user_id'),
    'remark' => $remark,
    'date_add' => $date_add
  );

  $id = $cs->add($arr);
  if($id !== FALSE && is_numeric($id) == TRUE)
  {
    //---- วนเพิ่มรายการในเอกสาร และ update ข้อมูลในตารางค้างรับ
    foreach($qtys as $id_rd => $qty )
    {
      //---- ดึงข้อมูลรายการที่ยืมไปมาตรวจสอบ
      $ds = $lend->getDetail($id_rd);

      //--- ยอดคงเหลือค้างรับ
      $balanceQty = $ds->qty - $ds->received;

      //--- ยอดคืนต้องไม่เกินยอดค้างรับ
      $returnQty = $qty <= $balanceQty ? $qty : $balanceQty;

      //--- ดึงข้อมูลสินค้า
      $pd->getData($ds->id_product);

      $arr = array(
        'id_return_lend' => $id,
        'id_product' => $ds->id_product,
        'product_code' => $pd->code,
        'from_zone' => $fZone,
        'to_zone' => $tZone,
        'qty' => $returnQty
      );

      //--- เพิ่มรายการเข้าตารางรับคืน
      if($cs->addDetail($arr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มรายการรับคืนไม่สำเร็จ ['.$cs->error.']';
      }

      //--- update ยอดค้างร้บ
      if($lend->updateReceived($id_rd, $returnQty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับยอดค้างรับไม่สำเร็จ';
      }


      //--- ย้ายสินค้าออกจากโซนที่ยืม
      if($stock->updateStockZone($fZone, $ds->id_product, (-1 * $returnQty)) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับยอดสินค้าออกจากโซนไม่สำเร็จ';
      }

      //--- รับสินค้าเข้าโซน
      if($stock->updateStockZone($tZone, $ds->id_product, $returnQty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับยอดสินค้าเข้าโซนไม่สำเร็จ';
      }

      //--- บันทึก movement
      if($mv->move_out($reference, $fWarehouse, $fZone, $ds->id_product, $returnQty, $date_add) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึก movement ออกจากโซนไม่สำเร็จ';
      }

      //--- บันทึก movement
      if($mv->move_in($reference, $tWarehouse, $tZone, $ds->id_product, $returnQty, $date_add) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึก movement เข้าโซนไม่สำเร็จ';
      }

    } //--- end foreach

    if($sc === TRUE)
    {
      //---- ตรวจสอบว่ารับครบแล้วทุกรายการหรือยัง
      if($lend->isCompleted($id_order) === TRUE)
      {
        //--- ถ้าครบแล้ว ปิดเอกสารยืมสินค้า
        $lend->closed($id_order);
      }
    }

    //---- check if everything is ok or not
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
    $message = 'เพิ่มเอกสารรับคืนไม่สำเร็จ ['. $cs->error.']';
  }
}
else
{
  $sc = FALSE;
  $message = 'ไม่พบรายการคืนสินค้า';
}

echo $sc === TRUE ? $id : $message;

 ?>
