<?php

  $id_move 	= $_GET['id_move'];

  //--- move object with data
  $cs = new move($id_move);

  //--- stock object for stock
  $stock = new stock();

  //--- movement objcet
  $mv = new movement();

  //--- zone object
  $zone = new zone();

  $id_zone		  = $_GET['id_zone'];

  $id_warehouse = $zone->getWarehouseId($id_zone);


  //------  ดึงสินค้าทั้งหมดในโซน
  $qs = $stock->getStockInZone($id_zone);

  $rows = dbNumRows($qs);
  $error = 0;
  $success = 0;

  if( $rows > 0 )
  {
    //--- เริ่มใช้งาน transection
    startTransection();

    while( $rs = dbFetchObject($qs) )
    {
      $sc  = TRUE;

      if( $rs->qty < 0 OR $rs->qty > 0 )
      {
        //--- เตรียมข้อมูลสำหรับเพิ่มเข้า tbl_move_detail
        $arr = array(
              "id_move"	     => $id_move,
              "id_product"	 => $rs->id_product,
              "id_warehouse" => $id_warehouse,
              "from_zone"    => $rs->id_zone,
              "to_zone"      => 0,
              "qty"          => $rs->qty
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

        //----- If insert or update move detail successful  do insert or update move temp
        if($sc === TRUE)
        {
          $temp = array(
                    "id_move_detail" => $ra,
                    "id_move"        => $id_move,
                    "id_product"	   => $rs->id_product,
                    "id_warehouse"   => $id_warehouse,
                    "id_zone"		     => $id_zone,
                    "qty"	           => $rs->qty,
                    "id_employee"	   => getCookie('user_id')
                    );

          //---  เพิ่มยอดเข้า temp
          if(!$cs->updateTemp($temp))
          {
            //---- if insert or update move temp fail
            $sc = FALSE;
            $message = 'เพิ่ม/ปรับปรุง temp ไม่สำเร็จ';
          }

          //--- ตัดยอดออกจากโซนต้นทาง
          if( !$stock->updateStockZone($id_zone, $rs->id_product, ($rs->qty * -1)))
          {
            //--- if update stock fail
            $sc = FALSE;
            $message = 'ปรับยอดสต็อกไม่สำเร็จ';
          }

          //--- บันทึก movement ออก
          if(!$mv->move_out($cs->reference, $id_warehouse, $id_zone, $rs->id_product, $rs->qty, $cs->date_add))
          {
            $sc = FALSE;
            $message = 'บันทึก movement ไม่สำเร็จ';
          }

        }

        if($sc === TRUE)
        {
          commitTransection();
          $success++;
        }
        else
        {
          dbRollback();
          $error++;
        }

      } //---- end if qty != 0

    } //--- endwhile

    //--- จบ transection
    endTransection();
  }
  else
  {
    //--- ถ้าไม่มีสินค้าคงเหลือในโซนเลย
    $message = 'ไม่มีสินค้าในโซน';
  } //--- end if dbNumRows

  if($error == 0)
  {
    $message == 'success';
  }

  echo $message;

 ?>
