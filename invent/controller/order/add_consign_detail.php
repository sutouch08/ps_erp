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
          $discount['discount'] = $order->gp.' %';
          $discount['amount']   = ($pd->price * ($order->gp * 0.01)) * $qty;

          $arr = array(
                    'id_order'	      => $order->id,
                    'id_style'		    => $pd->id_style,
                    'id_product'	    => $id,
                    'product_code'    => addslashes($pd->code),
                    'product_name'    => addslashes($pd->name),
                    'cost'            => $pd->cost,
                    'price'	          => $pd->price,
                    'qty'		          => $qty,
                    'discount'	      => $discount['discount'],
                    'discount_amount' => $discount['amount'],
                    'total_amount'	  => ($pd->price * $qty) - $discount['amount'],
                    'gp'              => $order->gp
                      );

          if( $order->addDetail($arr) === FALSE )
          {
            $result = FALSE;
            $error = 'Error : Insert fail';
            $err_qty++;
          }

        }
        else  //--- ถ้ามีรายการในออเดอร์อยู่แล้ว
        {
          $detail 	= $order->getDetail($order->id, $id);
          $qty			= $qty + $detail->qty;

          $discount['discount'] = $order->gp.' %';
          $discount['amount']   = ($pd->price * ($order->gp * 0.01)) * $qty;


          $arr = array(
                  'id_product'      => $id,
                  'qty'             => $qty,
                  'discount'	      => $discount['discount'],
                  'discount_amount'	=> $discount['amount'],
                  'total_amount'	  => ($pd->price * $qty) - $discount['amount'],
                  'valid'           => 0,
                  'isSaved'	        => 0, //--- ย้อนกลับมาเป็นยังไม่ได้บันทึกอีกครั้ง
                  'gp'              => $order->gp
                  );

          if( $order->updateDetail($detail->id, $arr) === FALSE )
          {
            $result = FALSE;
            $error = 'Error : Update Fail';
            $err_qty++;
          }

        }	//--- end if isExistsDetail
      }
      else 	// if getStock
      {
        $error = 'Error : สินค้าไม่เพียงพอ';
      } 	//--- if getStock
    }	//--- if qty > 0
  }	//-- foreach items
}	//--- foreach ds
 ?>
