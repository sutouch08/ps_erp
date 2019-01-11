<?php

  $sc = TRUE;

  $id_move = $_POST['id_move'];

  //--- move objcet
  $cs    = new move($id_move);

  //--- movement object
  $mv    = new movement();

  //--- warehouse object
  $wh  = new warehouse();

  //--- คลังที่ย้ายออก
  $id_warehouse = $_POST['id_warehouse'];

  //--- โซนที่ย้ายออก array
  $zone = $_POST['zone'];

  //--- จำนวนที่ย้ายออก Array
  $moveQty = $_POST['moveCancleQty'];

  //--- สินค้าที่ย้ายออก Array
  $pd	= $_POST['product'];

  //--- id_order จากโซน cancle : array
  $order = $_POST['order'];

  $cn = new cancle_zone();




  startTransection();

  foreach( $moveQty as $id_cancle => $val)
  {

    if($sc === FALSE)
    {
      break;
    }

    if( $val != '' && $val != 0 )
    {
      $id_product	= $pd[$id_cancle];
      $id_zone    = $zone[$id_cancle];
      $id_order   = $order[$id_cancle];
      $qty		    = $val;
      $arr        = array(
                      "id_move"      => $id_move,
                      "id_warehouse" => $id_warehouse,
                      "id_product"	 => $id_product,
                      "from_zone"	   => $id_zone,
                      "to_zone"		   => 0,
                      "qty"		       => $qty,
                      "from_cancle"  => 1,
                      "id_order"     => $id_order
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
                  "id_warehouse"   => $id_warehouse,
                  "id_zone"		     => $id_zone,
                  "qty"	           => $qty,
                  "id_employee"	   => getCookie('user_id'),
                  "from_cancle"    => 1,
                  "id_order"       => $id_order
                  );


        //--- เพิ่มยอดเข้า temp
        if( $cs->updateTemp($temp) !== TRUE )
        {
          //---- if insert or update move temp fail
          $sc = FALSE;
          $message = 'เพิ่ม/ปรับปรุง temp ไม่สำเร็จ';
        }

        //--- ตัดยอดออกจากโซน cancle
        $isEnough = $cn->isEnough($id_cancle, $qty);

        if( $isEnough === FALSE )
        {
          $sc = FALSE;
          $message = 'ยอดในโซนยกเลิกไม่พอให้ย้าย';
        }
        else
        {
          if( $cn->move_out($id_cancle, $qty) !== TRUE )
          {
            //--- if update stock fail
            $sc = FALSE;
            $message = 'ตัดยอดสต็อกจากโซนต้นทางไม่สำเร็จ';
          }

          //--- ลบรายการที่เป็น 0 ใน cancle
          $cn->dropZero();

          //--- บันทึก movement ออก
          if( $mv->move_out($cs->reference, $id_warehouse, $id_zone, $id_product, $qty, $cs->date_add) !== TRUE)
          {
            //--- ถ้าบันทึก movement ไม่สำเร็จ
            $sc = FALSE;
            $message = 'บันทึก movement ไม่สำเร็จ';
          }

        } //--- end if isEnough


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
