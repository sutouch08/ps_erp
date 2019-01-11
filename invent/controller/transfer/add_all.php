<?php
  $sc           = TRUE;

  $id_transfer 	= $_GET['id_transfer'];
  $id_zone		  = $_GET['id_zone'];
  $udz			    = $_GET['allowUnderZero'];

  //--- transfer object with data
  $cs = new transfer($id_transfer);

  //--- stock object for stock
  $stock = new stock();

  //--- movement objcet
  $mv = new movement();

  //------  ดึงสินค้าทั้งหมดในโซน
  $qs = $stock->getStockInZone($id_zone);

  if( dbNumRows($qs) > 0 )
  {
    //--- เริ่มใช้งาน transection
    startTransection();

    while( $rs = dbFetchObject($qs) )
    {
      if( $rs->qty != 0 && ( $rs->qty > 0 OR $udz == 1 ) )
      {
        //--- เตรียมข้อมูลสำหรับเพิ่มเข้า tbl_transfer_detail
        $arr = array(
              "id_transfer"	 => $id_transfer,
              "id_product"	 => $rs->id_product,
              "from_zone"    => $rs->id_zone,
              "to_zone"      => 0,
              "qty"          => $rs->qty
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
                    "id_product"	       => $rs->id_product,
                    "id_zone"		         => $id_zone,
                    "qty"	               => $rs->qty,
                    "id_employee"	       => getCookie('user_id')
                    );

          //---  เพิ่มยอดเข้า temp
          if( $cs->updateTemp($temp) !== TRUE )
          {
            //---- if insert or update transfer temp fail
            $sc = FALSE;
            $message = 'เพิ่ม/ปรับปรุง temp ไม่สำเร็จ';
          }

          //--- ตัดยอดออกจากโซนต้นทาง
          if( $stock->updateStockZone($id_zone, $rs->id_product, ($rs->qty * -1)) !== TRUE )
          {
            //--- if update stock fail
            $sc = FALSE;
            $message = 'ปรับยอดสต็อกไม่สำเร็จ';
          }

          //--- บันทึก movement ออก
          if( $mv->move_out($cs->reference, $cs->from_warehouse, $id_zone, $rs->id_product, $rs->qty, $cs->date_add) !== TRUE )
          {
            $sc = FALSE;
            $message = 'บันทึก movement ไม่สำเร็จ';
          }

        } //--- end if $ra === FALSE

      } //---- end if qty != 0

    } //--- endwhile

  }
  else
  {
    //--- ถ้าไม่มีสินค้าคงเหลือในโซนเลย
    $sc = FALSE;
    $message = 'ไม่มีสินค้าในโซน';

  } //--- end if dbNumRows


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

  echo $sc === TRUE ? 'success' : $message;

 ?>
