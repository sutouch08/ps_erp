<?php
//---	ไว้ตรวจสอบผลลัพภ์
$sc	= TRUE;

//---
$id = $_POST['id_receive_transform'];

//---	ไอดีออเดอร์เบิกแปรสภาพ
$id_order  = $_POST['id_order'];

//---	เลขที่เอกสารเบิกแปรสภาพ
$orderCode = trim($_POST['order_code'] );

//---	เลขที่ใบรับสินค้า (ไม่บังคับ)
$invoice	= trim(addslashes($_POST['invoice'] ));

$cs = new receive_transform($id);
$order     = new order();
$product	 = new product();
$zone      = new zone();
$st			   = new stock();
$mv			   = new movement();
$cost      = new product_cost();
$od        = new transform($id_order);

//---	โซนที่จะรับสินค้าเข้า
$id_zone	= $_POST['id_zone'];

//---	ไอดีของคลังที่จะรับเข้า
$id_wh		= $zone->getWarehouseId($id_zone);

//---	รายการที่มีการคีย์ยอดรับเข้ามา
$data				= $_POST['receive'];

//---	ไอดีโซนของคลังระหว่างทำ
$from_Zone = $od->id_zone;

//---	ไอดีของคลังระหว่างทำ
$from_WH 	= $zone->getWarehouseId($from_Zone);

//---	รหัสเล่มเอกสารรับเข้าจากการผลิด (FR)
$bookcode = getConfig('BOOKCODE_RECEIVE_TRANSFORM');

//--- วันที่เอกสาร
$date_add = $cs->date_add;

//--- เลขที่เอกสาร
$reference = $cs->reference;

//--- ตรวจสอบสถานะของเอกสาร
if($cs->isSaved == 1)
{
  $sc = FALSE;
  $message = 'เอกสารถูกบันทึกไปแล้วโดยผู้อื่น';
}

//--- ตรวจสอบสถานะใบเบิกแปรสภาพ
if($od->is_closed == 1)
{
  $sc = FALSE;
  $message = "ใบสั่งซื้อไม่ถูกต้อง ถูกปิด หรือ ถูกยกเลิก";
}

//--- ตรวจสอบสินค้าที่รับเข้า
if(count($data) == 0)
{
  $sc = FALSE;
  $message = "ไม่พบรายการรับเข้า";
}

//--- ถ้าไม่มีอะไรผิดพลาด
if($sc === TRUE)
{
  startTransection();

  //--- update Document
  $arr = array(
    'id_order' => $id_order,
    'order_code' => $orderCode,
    'invoice' => $invoice,
    'isSaved' => 1
  );

  if($cs->update($cs->id, $arr))
  {
    commitTransection();
  }
  else
  {
    $sc = FALSE;
    $message = 'ปรับปรุงข้อมูลเอกสารไม่สำเร็จ';
  }

  if($sc === TRUE)
  {
    foreach( $data as $id_pd => $qty )
    {
      $arr = array(
              "id_receive_transform"	=> $id,
              "id_style"							=> $product->getStyleId($id_pd),
              "id_product"						=> $id_pd,
              "qty"										=> $qty,
              "id_warehouse"					=> $id_wh,
              "id_zone"								=> $id_zone
            );

          //------ เพิ่มรายการรับเข้า
      if(!$cs->insertDetail($arr))
      {
        $sc = FALSE;
        $message = 'เพิ่มรายการรับเข้าไม่สำเร็จ';
      }

      //---	บันทึกยอดสต็อกเข้าโซนที่รับสินค้าเข้า
      if(!$st->updateStockZone($id_zone, $id_pd, $qty))
      {
        $sc = FALSE;
        $message = 'บันทึกยอดสต็อกเข้าโซนไม่สำเร็จ';
      }

      //---	บันทึก movement เข้าโซนที่รับสินคาเข้า
      if(!$mv->move_in($reference, $id_wh, $id_zone, $id_pd, $qty, $date_add))
      {
        $sc = FALSE;
        $message = 'บันทึก movement ไม่สำเร็จ';
      }

      //--- บันทึกต้นทุนสินค้า
      if(!$cost->addCostList($id_pd, $product->getCost($id_pd), $qty, $date_add))
      {
        $sc = FALSE;
        $message = 'บันทึกต้นทุนสินค้าไม่สำเร็จ';
      }


      //--- บันทึกยอดรับใน tbl_order_transform_detail
      if($sc === TRUE)
      {
        $qs = $od->getDetail($id_order, $id_pd);

          //---	ยอดสินค้าที่รับแล้ว
        $received_qty = $qty;

        //---	มีรายการที่ต้อง update กี่รายการ
        $row = dbNumRows($qs);

        //---	วนลูป update ทีละรายการ
        while( $res = dbFetchObject($qs))
        {
          //---	ถ้าเป็นรายการเดียว หรือ เป็นรอบสุดท้าย ใช้ยอดที่เหลือ รับเข้ารายการสุดท้ายเลย
          //---	ถ้าไมใช่รอบสุดท้าย ให้ใช้ยอดไม่เกินที่เปิดบิลมา
          $received = $row == 1 ? $received_qty : ($res->sold_qty <= $received_qty ? $res->sold_qty : $received_qty);
          if(!$od->received($res->id, $received))
          {
            $sc = FALSE;
            $message = 'ปรับปรุงรายการค้างรับไม่สำเร็จ';
          }

          $row--;
          $received_qty -= $received;

          if($sc === FALSE)
          {
            break;
          }
        }	//--- endwhile $res
      } //--- end if

      if($sc === TRUE)
      {
        commitTransection();
      }
      else
      {
        dbRollback();
        break;
      }

    }	//--- foreach data

    //--- ถ้ารับเข้าครบทุกรายการแล้ว ปิดเอกสารเลย
    if($sc === TRUE && $od->isCompleted($id_order) === TRUE)
    {
      $od->closed($id_order);
    }

  }

  endTransection();

} //-- end if


echo $sc === TRUE ? 'success' : $message;

 ?>
