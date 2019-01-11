<?php
  $sc           = TRUE;

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

  if( dbNumRows($qs) > 0 )
  {
    //--- เริ่มใช้งาน transection
    startTransection();

    while( $rs = dbFetchObject($qs) )
    {
      if($sc === FALSE)
      {
        break;
      }

      if( $rs->qty != 0 && $rs->qty > 0 )
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
          break;
        }
        else
        {
          //----- If insert or update move detail successful  do insert or update move temp
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
          if( $cs->updateTemp($temp) !== TRUE )
          {
            //---- if insert or update move temp fail
            $sc = FALSE;
            $message = 'เพิ่ม/ปรับปรุง temp ไม่สำเร็จ';
            break;
          }

          //--- ตัดยอดออกจากโซนต้นทาง
          if( $stock->updateStockZone($id_zone, $rs->id_product, ($rs->qty * -1)) !== TRUE )
          {
            //--- if update stock fail
            $sc = FALSE;
            $message = 'ปรับยอดสต็อกไม่สำเร็จ';
            break;
          }

          //--- บันทึก movement ออก
          if( $mv->move_out($cs->reference, $id_warehouse, $id_zone, $rs->id_product, $rs->qty, $cs->date_add) !== TRUE )
          {
            $sc = FALSE;
            $message = 'บันทึก movement ไม่สำเร็จ';
            break;
          }

        } //--- end if $ra === FALSE

      } //---- end if qty != 0

    } //--- endwhile

    if( $sc === TRUE )
    {
      //--- ถ้าไม่มีอะไรผิดพลาด commit transection
      commitTransection();
    }
    else
    {
      //--- ถ้ามีบางรายการไม่สำเร็จ rollback
      dbRollback();
    }

    //--- จบ transection
    endTransection();
  }
  else
  {
    //--- ถ้าไม่มีสินค้าคงเหลือในโซนเลย
    $sc = FALSE;
    $message = 'ไม่มีสินค้าในโซน';

  } //--- end if dbNumRows


  echo $sc === TRUE ? 'success' : $message;

 ?>
