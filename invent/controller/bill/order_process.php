<?php
  $bill = new bill();
  $payment  = new payment_method($order->id_payment);
  $credit = new customer_credit();
  $wh = new warehouse();
  $customer = new customer($order->id_customer);
  $employee = new employee($order->id_employee);
  $channels = new channels($order->id_channels);
  $role     = new order_role($order->role);
  $vat      = getConfig('VAT');

  $customer_type  = new customer_type($customer->id_type);
  $customer_kind  = new customer_kind($customer->id_kind);
  $customer_class = new customer_class($customer->id_class);
  $customer_group = new customer_group($customer->id_group);
  $customer_area  = new customer_area($customer->id_area);

  $sale = new sale($order->id_sale);

  $online_address = new online_address();

  $stock    = new stock();

  $movement = new movement();
  $buffer   = new buffer();
  $prepare  = new prepare();
  $zone     = new zone();

  $product  = new product();
  $color    = new color();
  $size     = new size();
  $pd_group = new product_group();
  $style    = new style();
  $category = new category();
  $kind     = new kind();
  $type     = new type();
  $brand    = new brand();

  $rule     = new discount_rule();
  $policy   = new discount_policy();


  //--- ใช้งาน ทรานเซ็คชั่น
  startTransection();

  //---	เปลี่ยนสถานะออเดอร์เป็นเปิดบิลแล้ว
  $ra = $order->stateChange($order->id, 8);

  //--- มีเครดิตหรือไม่
  $term = $payment->hasTerm($order->id_payment);

  //--- ถ้าเป็นเครดิต ดึงเครดิตที่ถูกใช้ไปของออเดอร์นี้มาตั้งไว้ก่อน บั
  //--- นทึกขายแล้วค่อยตัดออก ส่วนที่เหลือให้คืนกลับให้ลูกค้า
  $useCredit = $term === TRUE ? $order->getTotalAmount($order->id) : 0;

  //--- รายละเอียดการเปิดบิล เปรียบเทียบกันระหว่างสั่งซื้อ กับ ตรวจสินค้า
  $qs = $bill->getBillDetail($order->id);

  if( dbNumRows($qs) > 0)
  {

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


            //--- 2. update movement
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

            $rule->getData($ds->id_rule);

            $policy->getData($rule->id_discount_policy);


            //--- ข้อมูลสำหรับบันทึกยอดขาย
            $arr = array(
                    'id_order'  => $order->id,
                    'reference' => $order->reference,
                    'id_role'   => $order->role,
                    'role_name' => $role->name,
                    'id_payment'  => $payment->id,
                    'payment'   => $payment->name,
                    'id_channels' => $channels->id,
                    'channels'  => $channels->name,
                    'id_product'  => $product->id,
                    'product_code'  => $product->code,
                    'product_name'  => $product->name,
                    'id_color'  => $product->id_color,
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
                    'id_product_kind' => $product->id_kind,
                    'product_kind'  => $kind->getName($product->id_kind),
                    'id_product_type' => $product->id_type,
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
                    'discount_label'  => $ds->discount,
                    'discount_amount' => ($ds->discount_amount / $ds->qty) * $buffer_qty,
                    'total_amount_ex' => removeVAT( ($ds->total_amount / $ds->qty) * $buffer_qty, $vat),
                    'total_amount_inc'  => ($ds->total_amount / $ds->qty) * $buffer_qty,
                    'total_cost_ex'   => $cost_ex * $buffer_qty,
                    'total_cost_inc'  => $cost_inc * $buffer_qty,
                    'margin_ex'   => removeVAT((($ds->total_amount / $ds->qty) * $buffer_qty), $vat) - ($cost_ex * $buffer_qty),
                    'margin_inc'  => (($ds->total_amount / $ds->qty) * $buffer_qty) - ($cost_inc * $buffer_qty),
                    'id_policy'   => $policy->id,
                    'policy_code' => $policy->reference,
                    'policy_name' => $policy->name,
                    'id_rule'     => $rule->id,
                    'rule_code'   => $rule->code,
                    'rule_name'   => $rule->name,
                    'id_customer' => $customer->id,
                    'customer_code' => $customer->code,
                    'customer_name' => $customer->name,
                    'customer_group'  => $customer_group->name,
                    'customer_type'   => $customer_type->name,
                    'customer_kind'   => $customer_kind->name,
                    'customer_class'  => $customer_class->name,
                    'customer_area'   => $customer_area->name,
                    'province'        => $order->isOnline == 1 ? $online_address->getProvince($order->online_code) : $customer->province,
                    'id_sale'    => $sale->id,
                    'sale_code'   => $sale->code,
                    'sale_name'   => $sale->name,
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
              $message = 'บันทึกขายไม่สำเร็จ';
              break;
            }

            //--- 4. ปรับปรุงจำนวนคงเหลือในตารางต้นทุน
            if( $pdCost->removeCostList($product->id, $buffer_qty) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ปรับปรุงต้นทุนสินค้าไม่สำเร็จ';
              break;
            }

            //--- ถ้ามีการใช้เครดิต ตัดยอดเครดิตใช้ไป
            if( $term === TRUE && $useCredit > 0 )
            {
              $useCredit -= $arr['total_amount_inc'];
            }
          } //--- end if sell_qty > 0
        } //--  end while
      } //--- end if
    } //--- End while

    //--- เคลียร์ยอดค้างที่จัดเกินมาไปที่ cancle หรือ เคลียร์ยอดที่เป็น 0
    //--  function/bill_helper.php
    if( clearBuffer($order->id) == FALSE)
    {
      $sc = FALSE;
      $message = 'เคลียร์ buffer ไม่สำเร็จ';
    }

  }
  /*
  else
  {
    $sc = FALSE;
    $message = 'ไม่พบรายการตรวจสินค้า';
  }
  */

  //--- บันทึกขายรายการที่ไม่นับสต็อก
  $qn = $bill->getNonCountBillDetail($order->id);
  if(dbNumRows($qn) > 0)
  {
    $defaultWhId = $wh->getDefaultId();

    while( $rm = dbFetchObject($qn) )
    {
      if($rm->order_qty > 0)
      {

        $product->getData($rm->id_product);

        $ds = $order->getDetail($order->id, $product->id); //---  return as object of order_detail row

        //----  ต้นทุนสินค้าแบบ average
        $cost_ex = 0;
        $cost_inc = 0;

        $rule->getData($ds->id_rule);

        $policy->getData($rule->id_discount_policy);


        //--- ข้อมูลสำหรับบันทึกยอดขาย
        $arr = array(
                'id_order'  => $order->id,
                'reference' => $order->reference,
                'id_role'   => $order->role,
                'role_name' => $role->name,
                'id_payment'  => $payment->id,
                'payment'   => $payment->name,
                'id_channels' => $channels->id,
                'channels'  => $channels->name,
                'id_product'  => $product->id,
                'product_code'  => $product->code,
                'product_name'  => $product->name,
                'id_color'  => $product->id_color,
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
                'id_product_kind' => $product->id_kind,
                'product_kind'  => $kind->getName($product->id_kind),
                'id_product_type' => $product->id_type,
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
                'qty'       => $ds->qty,
                'discount_label'  => $ds->discount,
                'discount_amount' => $ds->discount_amount,
                'total_amount_ex' => removeVAT($ds->total_amount, $vat),
                'total_amount_inc'  => $ds->total_amount,
                'total_cost_ex'   => $cost_ex * $ds->qty,
                'total_cost_inc'  => $cost_inc * $ds->qty,
                'margin_ex'   => removeVAT($ds->total_amount, $vat) - ($cost_ex * $ds->qty),
                'margin_inc'  => $ds->total_amount - ($cost_inc * $ds->qty),
                'id_policy'   => $policy->id,
                'policy_code' => $policy->reference,
                'policy_name' => $policy->name,
                'id_rule'     => $rule->id,
                'rule_code'   => $rule->code,
                'rule_name'   => $rule->name,
                'id_customer' => $customer->id,
                'customer_code' => $customer->code,
                'customer_name' => $customer->name,
                'customer_group'  => $customer_group->name,
                'customer_type'   => $customer_type->name,
                'customer_kind'   => $customer_kind->name,
                'customer_class'  => $customer_class->name,
                'customer_area'   => $customer_area->name,
                'province'        => $order->isOnline == 1 ? $online_address->getProvince($order->online_code) : $customer->province,
                'id_sale'    => $sale->id,
                'sale_code'   => $sale->code,
                'sale_name'   => $sale->name,
                'id_employee' => $employee->id_employee,
                'employee_name' => $employee->full_name,
                'date_add'  => dbDate($order->date_add, TRUE),
                'id_warehouse'  => $defaultWhId,
                'count_stock' => 0
        );

        //--- 3. บันทึกยอดขาย
        if($order->sold($arr) !== TRUE)
        {
          $sc = FALSE;
          $message = 'บันทึกขายไม่สำเร็จ';
          break;
        }

        //--- ถ้ามีการใช้เครดิต ตัดยอดเครดิตใช้ไป
        /*
        if( $term === TRUE && $useCredit > 0 )
        {
          $useCredit -= $arr['total_amount_inc'];
        }
        */
      } //--- end if sell_qty > 0
    } //--  end while
  }


  if(dbNumRows($qs) == 0 && dbNumRows($qn) == 0)
  {
    $sc = FALSE;
    $message = 'ไม่พบรายการตรวจสินค้า';
  }

  //--- log การคืนยอดเครดิตหรืองบประมาณ
  //--- (ไว้ตรวจสอบเวลามีการย้อนขั้นตอนและบันทึกขายอีกครับ (ทำให้มีการคืนเครดิตในสินค้าที่ไม่ได้บันทึกขายอีกรอบ))
  $clog      = new return_credit_log();

  //--- ดึงยอดทีเครดิตที่เคยคืนไปแล้วครั้งก่อน (ถ้ามี)
  $logAmount = $clog->getReturnCreditLogAmount($order->id, $order->id_customer);
  $useCredit -= $logAmount;

  //--- ถ้าเป็นออเดอร์ที่มีการใช้เครดิต และ ออเดอร์ได้ของไม่ครบตามที่สั่ง
  //--- ให้คืนยอดใช้ไปให้ลูกค้า
  if( $term === TRUE && $useCredit > 0)
  {
    $credit->decreaseUsed($order->id_customer, $useCredit);

    //--- เก็บ log การคืนยอดเครดิตหรืองบประมาณ
    //--- (ไว้ตรวจสอบเวลามีการย้อนขั้นตอนและบันทึกขายอีกครับ (ทำให้มีการคืนเครดิตในสินค้าที่ไม่ได้บันทึกขายอีกรอบ))
    $clog     = new return_credit_log();
    $clog->add_log($order->id, $order->id_customer, $useCredit );
  }


  if( $sc === TRUE )
  {
    commitTransection();
    //dbRollback();
  }
  else
  {
    dbRollback();
  }

  endTransection();




 ?>
