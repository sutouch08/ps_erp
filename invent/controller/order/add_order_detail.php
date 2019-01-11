<?php
foreach( $ds as $items )
{
  foreach( $items as $id => $qty )
  {
    if( $qty > 0 )
    {
      $pd = new product($id);
      $bf = new buffer();
      $cn = new cancle_zone();
      $qty = ceil($qty);

      //--- ยอดรวมสินค้าในโซน + สินค้าที่อยู่ใน buffer ที่ถูกจัดออกไปด้วยออเดอร์นี้
      $sumStock = $stock->getSellStock($id);// + $bf->getSumQty($order->id, $id);
      //$cancleQty = $cn->getCancleQty($id);

      //--- ถ้ามีสต็อกมากว่าที่สั่ง หรือ เป็นสินค้าไม่นับสต็อก
      if( $sumStock >= $qty OR $pd->count_stock == 0 )
      {

        //---- ถ้ายังไม่มีรายการในออเดอร์
        if( $order->isExistsDetail($order->id, $id) === FALSE )
        {
          //---- คำนวณ ส่วนลดจากนโยบายส่วนลด
          $discount 	= $disc->getItemDiscount($pd->id, $order->id_customer, $qty, $order->id_payment, $order->id_channels, $order->date_add);

          $arr = array(
                  "id_order"	=> $order->id,
                  "id_style"		=> $pd->id_style,
                  "id_product"	=> $id,
                  "product_code"	=> $pd->code,
                  "product_name"	=> $pd->name,
                  "cost"  => $pd->cost,
                  "price"	=> $pd->price,
                  "qty"		=> $qty,
                  "discount"	=> $discount['discount'],
                  "discount_amount" => $discount['amount'],
                  "total_amount"	=> ($pd->price * $qty) - $discount['amount'],
                  "id_rule"	=> $discount['id_rule'],
                  "isCount" => $pd->count_stock
                );

          if( $order->addDetail($arr) === FALSE )
          {
            $result = FALSE;
            $error = "Error : Insert fail";
            $err_qty++;
          }

        }
        else  //--- ถ้ามีรายการในออเดอร์อยู่แล้ว
        {
          $detail 	= $order->getDetail($order->id, $id);
          $qty			= $qty + $detail->qty;
          $discount = $disc->getItemDiscount($pd->id, $order->id_customer, $qty, $order->id_payment, $order->id_channels, $order->date_add);
          $arr = array(
                    "id_product"	=> $id,
                    "qty" => $qty,
                    "discount"	=> $discount['discount'],
                    "discount_amount"	=> $discount['amount'],
                    "total_amount"	=> ($pd->price * $qty) - $discount['amount'],
                    "id_rule"	=> $discount['id_rule'],
                    "valid" => 0, //--- ย้อนกลับไปยังไม่จัดอีกครั้ง
                    "isSaved"	=> 0 //--- ย้อนกลับมาเป็นยังไม่ได้บันทึกอีกครั้ง เพื่อคำนวณเคดิตใหม่
                    );


          if( $order->updateDetail($detail->id, $arr) === FALSE )
          {
            $result = FALSE;
            $error = "Error : Update Fail";
            $err_qty++;
          }
          else
          {
            //---- ถ้าเป้นเครดิตแล้วบันทึกไปแล้ว
            if( $payment->hasTerm == 1 && $detail->isSaved == 1 && $detail->isCount == 1 )
            {
              //---- คืนยอดใช้ไปกลับมาก่อน เพื่อรอคำนวณ เครดิตอีกครั้งตอน บันทึก
              $credit->decreaseUsed($order->id_customer, $detail->total_amount);
            }
          }

        }	//--- end if isExistsDetail
      }
      else 	// if getStock
      {
        $result = FALSE;
        $error = "Error : สินค้าไม่เพียงพอ";
      } 	//--- if getStock
    }	//--- if qty > 0
  }	//-- foreach items
}	//--- foreach ds
 ?>
