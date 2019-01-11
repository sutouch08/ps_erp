<?php
//--- Cancle Received transform product
$sc         = 'success';
$result     = TRUE;
$id         = $_POST['id_receive_transform'];
$cs         = new receive_transform($id);
$mv         = new movement();
$stock      = new stock();
$transform  = new transform();
$order      = new order();
$id_order   = $order->getId($cs->order_code);
$product    = new product();
$zone       = new zone();
$cost       = new product_cost();
$emp        = getCookie('user_id');


$pdCode = '';

$qs = $cs->getDetail($id);
if( dbNumRows($qs) > 0)
{
  startTransection();

  while( $rs = dbFetchObject($qs))
  {

    //--- ตรวจสอบว่าโซนที่ต้องเอายอดสินค้าออกติดลบได้หรือไม่
    $auz = $zone->isAllowUnderZero($rs->id_zone);

    //--- ตรวจสอบยอดคงเหลือในโซน
    $qty = $stock->getStockZone($rs->id_zone, $rs->id_product);


    if( $qty < $rs->qty && $auz === FALSE )
    {
      //--- ถ้ายอดคงเหลือน้อยกว่ายอดที่จะยกเลิก และ โซนไม่สามารถติดลบได้
      $result = FALSE;
      $pdCode .= $product->getCode($rs->id_product). ' = ' . $qty.'; ';
    }
    else
    {
      //--- ถ้ายอดคงเหลือพอให้ยกเลิกได้

      //--- Update stock
      if( $stock->updateStockZone($rs->id_zone, $rs->id_product, ($rs->qty * -1)) === FALSE )
      {
        $result = FALSE;
      }

      //---- ลดจำนวนวรายการต้นทุนสินค้าใน tbl_product_cost
      if($cost->deleteCostList($rs->id_product, $product->getCost($rs->id_product), $rs->qty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับปรุงต้นทุนสินค้าไม่สำเร็จ';
      }

      if( $cs->cancleDetail($rs->id) === FALSE)
      {
        $result = FALSE;
        $sc = 'ยกเลิกรายการรับเข้าไม่สำเร็จ';
      }


      //--- บันทึกยอดรับใน tbl_order_transform_detail
      $qa = $transform->getDetail($id_order, $rs->id_product);

      //---	ยอดสินค้าที่จะเอาออก
      $received_qty = $qty;

      //---	มีรายการที่ต้อง update กี่รายการ
      $row = dbNumRows($qa);

      //---	วนลูป update ทีละรายการ
      while( $res = dbFetchObject($qa))
      {
        //---	ถ้าเป็นรายการเดียว หรือ เป็นรอบสุดท้าย ใช้ยอดที่เหลือ รับเข้ารายการสุดท้ายเลย
        //---	ถ้าไมใช่รอบสุดท้าย ให้ใช้ยอดไม่เกินที่เปิดบิลมา
        $received = $row == 1 ? $received_qty  : ($res->received <= $received_qty ? $res->received : $received_qty);
        if( $transform->received( $res->id, ($received * -1) ) == FALSE )
        {
          $result = FALSE;
          $sc = 'ปรับปรุงยอดรับไม่สำเร็จ';
        }

        $row--;
        $received_qty -= $received;
      }	//--- endwhile $res
    }

  }

  //--- ถ้าสต็อกคงเหลือพอและไม่มีรายการไหนผิดพลาด
  if( $result === TRUE)
  {
    //--- ลบ movement ทั้งหมด
    if( $mv->dropMovement($cs->reference) === FALSE )
    {
      $result = FALSE;
      $sc = 'ลบการเคลื่อนไหวไม่สำเร็จ';
    }

    //--- cancle เอกสาร
    if( $cs->cancleReceived($cs->id, $emp) === FALSE)
    {
      $result = FALSE;
      $sc = 'ยกเลิกเอกสารไม่สำเร็จ';
    }
  }


  //--- ตรวจสอบความถูกต้องทั้งหมดก่อน commitTransection
  if( $result === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

  echo $result === TRUE ? $sc : ($pdCode == '' ? $sc : 'สินค้าคงเหลือไม่พอให้ยกเลิก : '.$pdCode);
}


 ?>
