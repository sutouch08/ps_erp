<?php

  $sc = TRUE;

  $id_transfer = $_POST['id_transfer'];

  //--- โซนที่ย้ายออก
  $id_zone		 = $_POST['id_zone'];

  //--- จำนวนที่ย้ายออก Array
  $moveQty 	   = $_POST['moveQty'];

  //--- สินค้าที่ย้ายออก Array
  $pd			     = $_POST['id_product'];

  //--- อนุญาติให้ติดลบได้หรือไม่ (ที่โซน)
  $underZero   = isset( $_POST['underZero'] ) ? $_POST['underZero'] : array();

  //--- ติดลบได้หรือไม่(ที่รายการ) Array
  $udz			   = isset( $_POST['allowUnderZero'] ) ? $_POST['allowUnderZero'] : array();

  //--- transfer objcet
  $cs    = new transfer($id_transfer);

  //--- stock object
  $stock = new stock();

  //--- movement object
  $mv    = new movement();


  startTransection();

  foreach( $moveQty as $name => $val)
  {

    if( $val != '' && $val != 0 )
    {
      $id_product	= $pd[$name];
      $qty		    = $val;
      $arr        = array(
                      "id_transfer" => $id_transfer,
                      "id_product"	=> $id_product,
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
        if( $mv->move_out($cs->reference, $cs->from_warehouse, $id_zone, $id_product, $qty, $cs->date_add) !== TRUE)
        {
          //--- ถ้าบันทึก movement ไม่สำเร็จ
          $sc = FALSE;
          $message = 'บันทึก movement ไม่สำเร็จ';
        }

      } //--- end if $ra === FALSE

    } //--- end if $val != 0

  } //--- end foreach

  if( $sc === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

  echo $sc === TRUE ? 'success' : $message;


 ?>
