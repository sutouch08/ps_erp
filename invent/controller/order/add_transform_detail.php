<?php

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

          $arr = array(
                    "id_order"	      => $order->id,
                    "id_style"		    => $pd->id_style,
                    "id_product"	    => $id,
                    "product_code"    => $pd->code,
                    "product_name"    => $pd->name,
                    "cost"            => $pd->cost,
                    "price"	          => $pd->price,
                    "qty"		          => $qty,
                    "discount"	      => 0.00,
                    "discount_amount" => 0.00,
                    "total_amount"	  => ($pd->price * $qty),
                    "gp"              => 0
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

          $arr = array(
                  "id_product"      => $id,
                  "qty"             => $qty,
                  "discount"	      => 0,
                  "discount_amount"	=> 0,
                  "total_amount"	  => ($pd->price * $qty),
                  "valid"           => 0,
                  "isSaved"	        => 0, //--- ย้อนกลับมาเป็นยังไม่ได้บันทึกอีกครั้ง
                  "gp"              => 0
                  );

          if( $order->updateDetail($detail->id, $arr) === FALSE )
          {
            $result = FALSE;
            $error = "Error : Update Fail";
            $err_qty++;
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
