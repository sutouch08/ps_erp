<?php
  //--- ไว้ตรวจสอบความผิดพลาด
  $sc = TRUE;

  //--- ไอดีเอกสาร
  $id_move = $_POST['id_move'];

  //--- move object
  $cs = new move($id_move);

  //--- barcode object
  $bc = new barcode();

  //--- stock object
  $stock = new stock();

  //--- movement object
  $movement = new movement();

  //--- zone object
  $zone = new zone();

  //--- โซนต้นทาง
  $id_zone = $_POST['from_zone'];

  //--- คลังต้นทาง
  $id_warehouse = $zone->getWarehouseId($id_zone);

  //--- จำนวนที่จะเพิ่ม
  $qty = $_POST['qty'];

  //--- บาร์โค้ดสินค้าที่อยู่ในโซน
  $barcode = trim($_POST['barcode']);


  //--- หาไอดีสินค้าจากบาร์โค้ด หากไม่มีบาร์โค้ดนี้จะ return false กลับมา
  $id_product	= $bc->getProductId($barcode);

  if( $id_product === FALSE )
  {
    $sc = FALSE;
    $message = 'บาร์โค้ดไม่ถูกต้อง หรือ ไม่มีสินค้านี้ในระบบ';
  }
  else
  {

    //--- เริ่มใช้งาน transection
    startTransection();

    //--- ตรวจสอบว่าสินค้าอยู่ในโซนหรือไม่
    //--- return false if not exists return current qty if exists
    $stock_qty = $stock->isExists($id_zone, $id_product);

    //--- ถ้ามีสินค้าในโซนและยอดที่จะย้ายไม่มากกว่ายอดในโซน
    //--- ถ้าไม่มีสินค้าในโซน หรือ ยอดที่จะย้ายมากกว่ายอดในโซน
    //--- แต่โซนติดลบได้และมีการติ๊กอนุญาติให้ติดลบได้มาด้วย
    if( ($stock_qty != FALSE && $qty <= $stock_qty) )
    {
      //--- เตรียมข้อมูลสำหรับย้าย
      $arr = array(
              "id_move"      => $id_move,
              "id_product"   => $id_product,
              "id_warehouse" => $id_warehouse,
              "from_zone"	   => $id_zone,
              "to_zone"		   => 0,
              "qty"		       => $qty
              );

      //--- เพิ่มข้อมูล
      //--- หากมีข้อมูลอยู่แล้ว update
      //--- หากไม่มี insert
      //--- เมื่อ insert สำเร็จจะ return id_move_detail กลับมา
      $ra = $cs->updateDetail($arr);

      if( $ra === FALSE )
      {
        $sc = FALSE;
        $message = 'เพิ่ม/ปรับปรุง รายการไม่สำเร็จ';
      }
      else
      {
        //----- If insert or update move detail successful  do insert or update move temp
        $temp = array(
                  "id_move_detail" => $ra,
                  "id_move"        => $id_move,
                  "id_product"	   => $id_product,
                  "id_warehouse"  => $id_warehouse,
                  "id_zone"		     => $id_zone,
                  "qty"	           => $qty,
                  "id_employee"	   => getCookie('user_id')
                  );


        //--- เพิ่มยอดเข้า temp
        if( $cs->updateTemp($temp) !== TRUE )
        {
          //---- if insert or update move temp fail
          $sc = FALSE;
          $message = 'เพิ่ม/ปรับปรุง temp ไม่สำเร็จ';
        }

        //--- ตัดยอดออกจากโซนต้นทาง
        if( $stock->updateStockZone($id_zone, $id_product, ($qty * -1)) !== TRUE )
        {
          //--- if update stock fail
          $sc = FALSE;
          $message = 'ตัดยอดสต็อกจากโซนต้นทางไม่สำเร็จ';
        }

        //--- บันทึก movement ออก
        if( $movement->move_out($cs->reference, $id_warehouse, $id_zone, $id_product, $qty, $cs->date_add) !== TRUE)
        {
          //--- ถ้าบันทึก movement ไม่สำเร็จ
          $sc = FALSE;
          $message = 'บันทึก movement ไม่สำเร็จ';
        }

      } //--- end if $ra === FALSE

    }
    else
    {
      $sc = FALSE;
      $message = 'สินค้าคงเหลือในโซนไม่เพียงพอ';
    } //--- end if stock_qty

    //--  ตรวจสอบความถูกต้อง
    if( $sc === TRUE )
    {
      //--- ทุกอย่างถูกต้อง ยืนยันทรานเซ็คชั่น
      commitTransection();
    }
    else
    {
      //--- ถ้ามีบางอย่างไม่ถูกต้อง ย้อนกลับ
      dbRollback();
    }

    //--- สิ้นสุดการใช้งาน transection
    endTransection();


  } //--- end if $id_product


  echo $sc === TRUE ? 'success' : $message;

 ?>
