<?php
  $sc = TRUE;
  $message = "";
  $id_order = $_POST['id_order'];
  $barcode 	= $_POST['barcode'];
  $id_zone	= $_POST['id_zone'];
  $qty 			= intval($_POST['qty']);
  $valid    = 0;


  $bc = new barcode();
  $stock = new stock();
  $buffer = new buffer();
  $order = new order($id_order);
  $prepare = new prepare();
  $product = new product();
  $zone = new zone($id_zone);
  $pd = $bc->getDetail($barcode);

  //--- ตรวจสอบสถานะออเดอร์ 4 == กำลังจัดสินค้า
  if($order->state != 4)
  {
    $sc = FALSE;
    $message = "สถานะออเดอร์ถูกเปลี่ยน ไม่สามารถจัดสินค้าต่อได้";
  }

  //--- ตรวจสอบบาร์โค้ดที่ยิงมา
  if(empty($pd))
  {
    $sc = FALSE;
    $message = "ไม่มีสินค้าในระบบ หรือ บาร์โค้ดไม่ถูกต้อง";
  }

  //--- ตรวจสอบสินค้าว่ามีในออเดอร์หรือไม่
  if($sc === TRUE)
  {
    //---	ถ้ามีออเดอร์สั่งมา
    $orderQty = $order->getOrderQty($id_order, $pd->id_product);
    if($orderQty <= 0)
    {
      $sc = FALSE;
      $message = "ไม่มีสินค้านี้ในออเดอร์";
    }
  }

  //---- ถ้าไม่มีอะไรผิดพลาด
  if( $sc === TRUE)
  {
    //--- ดึงยอดที่จัดแล้ว
    $preparedQty = $buffer->getSumQty($id_order, $pd->id_product);

    //--- ยอดคงเหลือค้างจัด
    $bQty = $orderQty - $preparedQty;

    //---- ตรวจสอบยอดที่ยังไม่ครบว่าจัดเกินหรือเปล่า
    if( $bQty < $qty)
    {
      $sc = FALSE;
      $message = "สินค้าเกิน กรุณาคืนสินค้าแล้วจัดสินค้าใหม่อีกครั้ง";
    }

    //---	ถ้ามีสต็อกคงเหลือเพียงพอให้ตัด
    if(!$stock->isEnough($id_zone, $pd->id_product, $qty) && !$zone->allowUnderZero)
    {
        $sc = FALSE;
        $message = "สินค้าไม่เพียงพอ กรุณากำหนดจำนวนสินค้าใหม่";
    }

    if($sc === TRUE)
    {
      startTransection();

      //---	ตัดยอดสอนค้าออกจากโซน
      if(!$stock->updateStockZone($id_zone, $pd->id_product, (-1 * $qty)))
      {
        $sc = FALSE;
        $message = 'ตัดยอดสต็อกจากโซนไม่สำเร็จ';
      }

      $id_style = $product->getStyleId($pd->id_product);
      $id_wh    = $zone->id_warehouse;

      //---	เพิ่มยอดเข้า buffer
      if(!$buffer->updateBuffer($id_order, $id_style, $pd->id_product, $id_zone, $id_wh , $qty))
      {
        $sc = FALSE;
        $message = 'เพิ่มยอดเข้า Buffer ไม่สำเร็จ';
      }

      //--- เพิ่มรายการจัดสินค้าเข้าตารางจัด
      if(!$prepare->updatePrepare($id_order, $pd->id_product, $id_zone, $qty))
      {
        $sc = FALSE;
        $message = 'ปรับปรุงรายการจัดสินค้าไม่สำเร็จ';
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

      //---	ตรวจสอบออเดอร์ว่าครบแล้วหรือยัง
      if($orderQty == $buffer->getSumQty($id_order, $pd->id_product))
      {
        $order->validDetail($id_order, $pd->id_product);
        $valid = 1;
      }

    } //--- end if sc

  }

echo $sc === TRUE ? json_encode(array("id_product" => $pd->id_product, "qty" => $qty, "valid" => $valid)) : $message;

?>
