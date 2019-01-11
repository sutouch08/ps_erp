<?php
  //--- ไว้ตรวจสอบความผิดพลาด
  $sc = TRUE;

  //--- ไอดีเอกสาร
  $id_transfer = $_POST['id_transfer'];

  //--- โซนต้นทาง
  $id_zone = $_POST['from_zone'];

  //--- จำนวนที่จะเพิ่ม
  $qty = $_POST['qty'];

  //--- บาร์โค้ดสินค้าที่อยู่ในโซน
  $barcode = trim($_POST['barcode']);

  //--- โซนนี้ติดลบได้หรือไม่ 0 = ไม่ได้, 1 = ติดลบได้
  $isAllowUnderZero = $_POST['isAllowUnderZero'];

  //--- อนุญาติให้ติดลบได้หรือไม่ 0 = ไม่ได้, 1 = ติดลบได้
  $udz = $_POST['underZero'];

  //--- transfer object
  $cs = new transfer($id_transfer);

  //--- barcode object
  $bc = new barcode();

  //--- stock object
  $stock = new stock();

  //--- movement object
  $movement = new movement();

  //--- เริ่มใช้งาน transection
  startTransection();

  //--- หาไอดีสินค้าจากบาร์โค้ด หากไม่มีบาร์โค้ดนี้จะ return false กลับมา
  $id_product	= $bc->getProductId($barcode);

  if( $id_product === FALSE )
  {
    $sc = FALSE;
    $message = 'บาร์โค้ดไม่ถูกต้อง หรือ ไม่มีสินค้านี้ในระบบ';
  }
  else
  {
    //--- ตรวจสอบว่าสินค้าอยู่ในโซนหรือไม่
    //--- return false if not exists return current qty if exists
    $stock_qty = $stock->isExists($id_zone, $id_product);

    //--- ถ้ามีสินค้าในโซนและยอดที่จะย้ายไม่มากกว่ายอดในโซน
    //--- ถ้าไม่มีสินค้าในโซน หรือ ยอดที่จะย้ายมากกว่ายอดในโซน
    //--- แต่โซนติดลบได้และมีการติ๊กอนุญาติให้ติดลบได้มาด้วย
    if( ($stock_qty != FALSE && $qty <= $stock_qty) OR ($isAllowUnderZero == 1 && $udz == 1) )
    {
      //--- เตรียมข้อมูลสำหรับย้าย
      $arr = array(
              "id_transfer" => $id_transfer,
              "id_product"  => $id_product,
              "from_zone"	  => $id_zone,
              "to_zone"		  => 0,
              "qty"		      => $qty
              );

      //--- เพิ่มข้อมูล
      //--- หากมีข้อมูลอยู่แล้ว update
      //--- หากไม่มี insert
      //--- เมื่อ insert สำเร็จจะ return id_transfer_detail กลับมา
      $ra = $cs->updateDetail($arr);

      if( $ra === FALSE )
      {
        $sc = FALSE;
        $message = 'เพิ่ม/ปรับปรุง รายการไม่สำเร็จ';
      }
      else
      {
        //----- If insert or update transfer detail successful  do insert or update transfer temp
        $temp = array(
                  "id_transfer_detail" => $ra,
                  "id_transfer"        => $id_transfer,
                  "id_product"	       => $id_product,
                  "id_zone"		         => $id_zone,
                  "qty"	               => $qty,
                  "id_employee"	       => getCookie('user_id')
                  );


        //--- เพิ่มยอดเข้า temp
        if( $cs->updateTemp($temp) !== TRUE )
        {
          //---- if insert or update transfer temp fail
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
        if( $movement->move_out($cs->reference, $cs->from_warehouse, $id_zone, $id_product, $qty, $cs->date_add) !== TRUE)
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

  } //--- end if $id_product

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

  echo $sc === TRUE ? 'success' : $message;

 ?>
