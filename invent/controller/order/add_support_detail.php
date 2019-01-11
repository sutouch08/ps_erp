<?php

$bd = new support_budget();

foreach( $ds as $items )
{
  foreach( $items as $id => $qty )
  {
    if( $qty > 0 )
    {
      $qty = ceil($qty);
      
      //--- ถ้ามีสต็อกมากว่าที่สั่ง
      if( $stock->getSellStock($id) >= $qty )
      {
        $pd 			= new product($id);

        //---- ถ้ายังไม่มีรายการในออเดอร์
        if( $order->isExistsDetail($order->id, $id) === FALSE )
        {
          //---- คำนวณ ส่วนลดจากนโยบายส่วนลด
          $discount 	=

          $arr = array(
                  "id_order"	    => $order->id,
                  "id_style"		  => $pd->id_style,
                  "id_product"	  => $id,
                  "product_code"	=> $pd->code,
                  "product_name"	=> $pd->name,
                  "cost"          => $pd->cost,
                  "price"	        => $pd->price,
                  "qty"		        => $qty,
                  "total_amount"	=> ($pd->price * $qty)
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
          $detail = $order->getDetail($order->id, $id);
          $qty		= $qty + $detail->qty;
          $arr    = array(
                    "id_product"	=> $id,
                    "qty" => $qty,
                    "total_amount"	=> ($pd->price * $qty),
                    "valid"   => 0,
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
            if( $detail->isSaved == 1 )
            {
              //---- คืนยอดใช้ไปกลับมาก่อน เพื่อรอคำนวณ เครดิตอีกครั้งตอน บันทึก
              $bd->decreaseUsed($order->id_budget, $detail->total_amount);
            }
          }

        }	//--- end if isExistsDetail
      }
      else 	// if getStock
      {
        $error = "Error : สินค้าไม่เพียงพอ";
      } 	//--- if getStock
    }	//--- if qty > 0
  }	//-- foreach items
}	//--- foreach ds
 ?>
