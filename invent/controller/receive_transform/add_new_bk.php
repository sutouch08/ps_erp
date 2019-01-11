<?php
$order     = new order();
$product	 = new product();
$zone      = new zone();
$cs 			 = new receive_transform();
$st			   = new stock();
$mv			   = new movement();
$cost      = new product_cost();

//---	ไว้ตรวจสอบผลลัพภ์
$sc		 = TRUE;

//---	เลขที่เอกสารเบิกแปรสภาพ
$orderCode = trim( $_POST['reference'] );

//---	ไอดีออเดอร์เบิกแปรสภาพ
$id_order  = $order->getId($orderCode);

//---	ถ้ามีออเดอร์แปรสภาพอยู่จริง
if( $id_order !== FALSE )
{
  //---	วันที่เอกสาร (ใช้สร้างเอกสาร และ บันทึก movement)
  $date_add	= dbDate( $_POST['date'], TRUE );

  //---	เลขที่ใบรับสินค้า (ไม่บังคับ)
  $invoice	= trim(addslashes($_POST['invoice'] ));

  //---	หมายเหตุของเอกสาร
  $remark		= trim( addslashes($_POST['remark'] ));

  //---	โซนที่จะรับสินค้าเข้า
  $id_zone	= $_POST['id_zone'];

  //---	ไอดีของคลังที่จะรับเข้า
  $id_wh		= $zone->getWarehouseId($id_zone);

  //---	รายการที่มีการคีย์ยอดรับเข้ามา
  $ds				= $_POST['receive'];

  $transform = new transform($id_order);

  //---	ไอดีโซนของคลังระหว่างทำ
  $from_Zone = $transform->id_zone;

  //---	ไอดีของคลังระหว่างทำ
  $from_WH 	= $zone->getWarehouseId($from_Zone);

  //---	รหัสเล่มเอกสารรับเข้าจากการผลิด (FR)
  $bookcode = getConfig('BOOKCODE_RECEIVE_TRANSFORM');



  $data			= array();

  foreach( $ds as $id => $val )
  {
    if( is_numeric($val) )
    {
      $data[$id]	= $val;
    }
  }


  if( count( $data ) > 0 )
  {
    startTransection();

    $reference	= $cs->getNewReference($date_add);

    if( $_POST['approvKey'] == "" )
    {
      $arr = array(
                  "bookcode"	  => $bookcode,
                  "reference"	  => $reference,
                  "id_order"	  => $id_order,
                  "order_code"  => $orderCode,
                  "invoice" 		=> $invoice,
                  "date_add" 	  => $date_add,
                  "id_employee" => getCookie('user_id'),
                  "remark"		  => $remark
                );
    }
    else
    {
      $arr = array(
                  "bookcode"	  => $bookcode,
                  "reference"	  => $reference,
                  "id_order"	  => $id_order,
                  "order_code"  => $orderCode,
                  "invoice" 		=> $invoice,
                  "date_add" 	  => $date_add,
                  "id_employee" => getCookie('user_id'),
                  "remark"		  => $remark,
                  "approver"	  => $_POST['id_emp'],
                  "approvKey"	  => $_POST['approvKey']
                );
    }

    $id = $cs->add($arr);

    if($id !== FALSE && intval($id))
    {
      foreach( $data as $id_pd => $qty )
      {
        if($sc === FALSE)
        {
          break;
        }
        $arr = array(
                "id_receive_transform"	=> $id,
                "id_style"							=> $product->getStyleId($id_pd),
                "id_product"						=> $id_pd,
                "qty"										=> $qty,
                "id_warehouse"					=> $id_wh,
                "id_zone"								=> $id_zone
              );

          //------ เพิ่มรายการรับเข้า
          if( $cs->insertDetail($arr) !== TRUE)
          {
            $sc = FALSE;
            $message = 'เพิ่มรายการรับเข้าไม่สำเร็จ';
            break;
          }

          //---	บันทึกยอดสต็อกเข้าโซนที่รับสินค้าเข้า
          if( $st->updateStockZone($id_zone, $id_pd, $qty) !== TRUE )
          {
            $sc = FALSE;
            $message = 'บันทึกยอดสต็อกเข้าโซนไม่สำเร็จ';
            break;
          }

          if( $cost->addCostList($id_pd, $product->getCost($id_pd), $qty, $date_add) !== TRUE)
          {
            $sc = FALSE;
            $message = 'บันทึกต้นทุนสินค้าไม่สำเร็จ';
            break;
          }

          //---	บันทึก movement เข้าโซนที่รับสินคาเข้า
          if( $mv->move_in( $reference, $id_wh, $id_zone, $id_pd, $qty, $date_add ) !== TRUE)
          {
            $sc = FALSE;
            $message = 'บันทึก movement ไม่สำเร็จ';
            break;
          }


          //--- บันทึกยอดรับใน tbl_order_transform_detail
          $qs = $transform->getDetail($id_order, $id_pd);

          //---	ยอดสินค้าที่รับแล้ว
          $received_qty = $qty;

          //---	มีรายการที่ต้อง update กี่รายการ
          $row = dbNumRows($qs);

          //---	วนลูป update ทีละรายการ
          while( $res = dbFetchObject($qs))
          {
            if($sc === FALSE)
            {
              break;
            }
            //---	ถ้าเป็นรายการเดียว หรือ เป็นรอบสุดท้าย ใช้ยอดที่เหลือ รับเข้ารายการสุดท้ายเลย
            //---	ถ้าไมใช่รอบสุดท้าย ให้ใช้ยอดไม่เกินที่เปิดบิลมา
            $received = $row == 1 ? $received_qty : ($res->sold_qty <= $received_qty ? $res->sold_qty : $received_qty);
            if( $transform->received($res->id, $received) === FALSE )
            {
              $sc = FALSE;
              $message = 'ปรับปรุงรายการค้างรับไม่สำเร็จ';
            }

            $row--;
            $received_qty -= $received;
          }	//--- endwhile $res

        }	//--- foreach data

      }
      else
      {
          $sc = FALSE;
          $message = 'เพิ่มเอกสารไม่สำเร็จ';
      }

      if( $sc === TRUE )
      {
        commitTransection();
      }
      else
      {
        dbRollback();
      }

      endTransection();
  }
  else //-- if count
  {
    $sc = FALSE;
    $message = "ไม่พบรายการรับเข้า";

  }//--- if count

}
else //---- if id_order !== FALSE
{
  $sc = FALSE;
  $message = "ใบสั่งซื้อไม่ถูกต้อง ถูกปิด หรือ ถูกยกเลิก";

}//--- if id_order !== FALSE


echo $sc === TRUE ? 'success | '.$id : 'fail | '.$message;

 ?>
