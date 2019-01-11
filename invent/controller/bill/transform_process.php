<?php
  $bill = new bill();
  $transform = new transform($order->id);
  $zone = new zone($transform->id_zone);

  //--- ใช้งาน ทรานเซ็คชั่น
  startTransection();

  //---	เปลี่ยนสถานะออเดอร์เป็นเปิดบิลแล้ว
  $ra = $order->stateChange($order->id, 8);

  //--- รายละเอียดการเปิดบิล เปรียบเทียบกันระหว่างสั่งซื้อ กับ ตรวจสินค้า
  $qs = $bill->getBillDetail($order->id);

  //--- ถ้ามีรายการสั่งและรายการตรวจสินค้า
  if( dbNumRows($qs) > 0)
  {
    $customer = new customer($order->id_customer);
    $employee = new employee($order->id_employee);

    $role     = new order_role($order->role);
    $vat      = getConfig('VAT');

    $customer_type  = new customer_type($customer->id_type);
    $customer_kind  = new customer_kind($customer->id_kind);
    $customer_class = new customer_class($customer->id_class);
    $customer_group = new customer_group($customer->id_group);
    $customer_area  = new customer_area($customer->id_area);

    $stock    = new stock();
    $movement = new movement();
    $buffer   = new buffer();
    $prepare  = new prepare();

    $product  = new product();
    $color    = new color();
    $size     = new size();
    $pd_group = new product_group();
    $style    = new style();
    $category = new category();
    $kind     = new kind();
    $type     = new type();
    $brand    = new brand();



    while( $rs = dbFetchObject($qs))
    {
      if($sc == FALSE)
      {
        break;
      }
      //--- ถ้ายอดตรวจ น้อยกว่า หรือ เท่ากับ ยอดสั่ง ใช้ยอดตรวจในการตัด buffer
      //--- ถ้ายอดตวจ มากกว่า ยอดสั่ง ให้ใช้ยอดสั่งในการตัด buffer (บางทีอาจมีการแก้ไขออเดอร์หลังจากมีการตรวจสินค้าแล้ว)
      $sell_qty = ($rs->order_qty >= $rs->qc) ? $rs->qc : $rs->order_qty;


      //--- ดึงข้อมูลสินค้าที่จัดไปแล้วตามสินค้า
      $qa = $buffer->getDetails($order->id, $rs->id_product);


      if( dbNumRows($qa) > 0)
      {
        while( $rm = dbFetchObject($qa) )
        {
          if($sell_qty > 0)
          {

          //--- ถ้ายอดใน buffer น้อยกว่าหรือเท่ากับยอดสั่งซื้อ (แยกแต่ละโซน น้อยกว่าหรือเท่ากับยอดสั่ง (ซึ่งควรเป็นแบบนี้))
            $buffer_qty = $rm->qty <= $sell_qty ? $rm->qty : $sell_qty;

            //--- ทำยอดให้เป็นลบเพื่อตัดยอดออก เพราะใน function  ใช้การบวก
            $qty = $buffer_qty * (-1);

            //--- 1. ตัดยอดออกจาก buffer
            //--- นำจำนวนติดลบบวกกลับเข้าไปใน buffer เพื่อตัดยอดให้น้อยลง
            if($buffer->update($rm->id_order, $rm->id_product, $rm->id_zone, $qty) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ปรับยอดใน buffer ไม่สำเร็จ';
              break;
            }

            //--- ลดยอด sell qty ลงตามยอด buffer ทีลดลงไป
            $sell_qty += $qty;

            //--- 2. update movement out
            if($movement->move_out($order->reference, $rm->id_warehouse, $rm->id_zone, $rm->id_product, $buffer_qty, dbDate($order->date_add, TRUE)) !== TRUE)
            {
              $sc = FALSE;
              $message = 'บันทึก movement ขาออกไม่สำเร็จ';
              break;
            }


            $product->getData($rm->id_product);

            $ds = $order->getDetail($order->id, $product->id); //---  return as object of order_detail row

            //----  ต้นทุนสินค้าแบบ average
            $cost_ex = $pdCost->getCost($rm->id_product);
            $cost_inc = addVAT($cost_ex, $vat);

            //--- ข้อมูลสำหรับบันทึกยอดขาย
            $arr = array(
                    'id_order'  => $order->id,
                    'reference' => $order->reference,
                    'id_role'   => $order->role,
                    'role_name' => $role->name,
                    'payment'   => 'transform',
                    'channels'  => 'transform',
                    'id_product'  => $product->id,
                    'product_code'  => $product->code,
                    'product_name'  => $product->name,
                    'id_color'      => $product->id_color,
                    'color'         => $color->getCode($product->id_color),
                    'color_group'   => $color->getGroupCode($product->id_color),
                    'id_size'       => $product->id_size,
                    'size'          => $size->getCode($product->id_size),
                    'size_group'    => $size->getGroupCode($product->id_size),
                    'id_product_style'  => $product->id_style,
                    'product_style' => $style->getCode($product->id_style),
                    'id_product_group'  => $product->id_group,
                    'product_group' => $pd_group->getName($product->id_group),
                    'id_product_category' => $product->id_category,
                    'product_category'  => $category->getName($product->id_category),
                    'id_product_kind'   => $product->id_kind,
                    'product_kind'  => $kind->getName($product->id_kind),
                    'id_product_type'   => $product->id_type,
                    'product_type'  => $type->getName($product->id_type),
                    'id_brand'      => $product->id_brand,
                    'brand'         => $brand->getName($product->id_brand),
                    'year'          => $product->year,
                    'cost_ex' => $cost_ex,
                    'cost_inc'  => $cost_inc,
                    'price_ex'  => removeVAT($ds->price, $vat),
                    'price_inc' => $ds->price,
                    'sell_ex'   => removeVAT( ($ds->total_amount/$ds->qty), $vat),
                    'sell_inc'  => $ds->total_amount / $ds->qty,
                    'qty'       => $buffer_qty,
                    'total_amount_ex' => removeVAT( ($ds->total_amount / $ds->qty) * $buffer_qty, $vat),
                    'total_amount_inc'  => ($ds->total_amount / $ds->qty) * $buffer_qty,
                    'total_cost_ex'   => $cost_ex * $buffer_qty,
                    'total_cost_inc'  => $cost_inc * $buffer_qty,
                    'margin_ex'   => removeVAT((($ds->total_amount / $ds->qty) * $buffer_qty), $vat) - ($cost_ex * $buffer_qty),
                    'margin_inc'  => (($ds->total_amount / $ds->qty) * $buffer_qty) - ($cost_inc * $buffer_qty),
                    'id_customer' => $customer->id,
                    'customer_code' => $customer->code,
                    'customer_name' => $customer->name,
                    'customer_group'  => $customer_group->name,
                    'customer_type'   => $customer_type->name,
                    'customer_kind'   => $customer_kind->name,
                    'customer_class'  => $customer_class->name,
                    'customer_area'   => $customer_area->name,
                    'id_employee' => $employee->id_employee,
                    'employee_name' => $employee->full_name,
                    'date_add'  => dbDate($order->date_add, TRUE),
                    'id_zone' => $rm->id_zone,
                    'id_warehouse'  => $rm->id_warehouse
            );

            //--- 3. บันทึกยอดขาย
            if($order->sold($arr) !== TRUE)
            {
              $sc = FALSE;
              $sc = 'บันทึกขายไม่สำเร็จ';
              break;
            }

            //--- 4. ปรับปรุงจำนวนคงเหลือในตารางต้นทุน
            if( $pdCost->removeCostList($product->id, $buffer_qty) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ปรับปรุงต้นทุนสินค้าไม่สำเร็จ';
              break;
            }
          } //--- end if sell_qty > 0
        } //--  end while
      } //--- end if

      //-------------------- ส่วนนี้สำหรับโอนเข้าคลังระหว่างทำ
      //--- ตัวเลขที่มีการเปิดบิล
      $sold_qty = ($rs->order_qty >= $rs->qc) ? $rs->qc : $rs->order_qty;

      //--- ยอดสินค้าที่มีการเชื่อมโยงไว้ในตาราง tbl_order_transform_detail (เอาไว้โอนเข้าคลังระหว่างทำ รอรับเข้า)
      //--- ถ้ามีการเชื่อมโยงไว้ ยอดต้องมากกว่า 0 ถ้ายอดเป็น 0 แสดงว่าไม่ได้เชื่อมโยงไว้
      $trans_list = $transform->getTransformProducts($rs->id);

      //--- ถ้าไม่มีการเชื่อมโยงไว้
      while( $ts = dbFetchObject($trans_list))
      {
        //--- ถ้าจำนวนที่เชื่อมโยงไว้ น้อยกว่า หรือ เท่ากับ จำนวนที่ตรวจได้ (ไม่เกินที่สั่งไป)
        //--- แสดงว่าได้ของครบตามที่ผูกไว้ ให้ใช้ตัวเลขที่ผูกไว้ได้เลย
        //--- แต่ถ้าได้จำนวนที่ผูกไว้มากกว่าที่ตรวจได้ แสดงว่า ได้สินค้าไม่ครบ ให้ใช้จำนวนที่ตรวจได้แทน
        $move_qty = $ts->qty <= $sold_qty ? $ts->qty : $sold_qty;

        if( $move_qty > 0)
        {
          //--- update ยอดเปิดบิลใน tbl_order_transform_detail field sold_qty
          if( $transform->updateSoldQty($ts->id, $move_qty) === TRUE )
          {
            $sold_qty -= $move_qty;
          }
          else
          {
            $sc = FALSE;
            $message = 'ปรับปรุงยอดรายการค้างรับไม่สำเร็จ';
          }
        }
      }

    } //--- End while

    //--- เคลียร์ยอดค้างที่จัดเกินมาไปที่ cancle หรือ เคลียร์ยอดที่เป็น 0
    //--  function/bill_helper.php
    if( clearBuffer($order->id) == FALSE)
    {
      $sc = FALSE;
      $message = 'เคลียร์ buffer ไม่สำเร็จ';
    }

  }
  else
  {
    $sc = FALSE;
    $message = 'ไม่พบรายการตรวจสินค้า';
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




 ?>
