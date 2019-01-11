<?php
//--- ไว้ตรวจสอบความถูกต้อง
$sc = TRUE;

//--- ไอดีเอกสาร
$id_consign  = $_POST['id_consign'];

//--- ข้อมูลเอกสาร
$cs = new consign($id_consign);


//--- สถานของเอกสารต้องยังไม่มีการบันทึก หรือ ไม่ได้ยกเลิก
if($cs->isCancle == 0 && $cs->isSaved == 0)
{
  //--- ดึงรายการที่จะบันทึก
  $qs = $cs->getUnsaveDetails($id_consign);

  startTransection();

  if(dbNumRows($qs) > 0)
  {
    $employee = new employee($cs->id_employee);
    $role     = new order_role(8);
    $channels = new channels($cs->id_channels);
    $vat      = getConfig('VAT');

    //--- ข้อมูลลูกค้า
    $customer       = new customer($cs->id_customer);
    $customer_type  = new customer_type($customer->id_type);
    $customer_kind  = new customer_kind($customer->id_kind);
    $customer_class = new customer_class($customer->id_class);
    $customer_group = new customer_group($customer->id_group);
    $customer_area  = new customer_area($customer->id_area);



    //--- ข้อมูลสินค้า
    $pd       = new product();
    $color    = new color();
    $size     = new size();
    $pd_group = new product_group();
    $style    = new style();
    $category = new category();
    $kind     = new kind();
    $type     = new type();
    $brand    = new brand();

    //---	สำหรับจัดการต้นทุนสินค้า (tbl_product_cost)
    $pdCost   = new product_cost();

    //--- ไว้จัดการสต็อก
    $stock    = new stock();
    $movement = new movement();
    $zone     = new zone($cs->id_zone);
    //---
    $order = new order();

    while($rs = dbFetchObject($qs))
    {
      if($sc === FALSE)
      {
        break;
      }

      //--- ดึงข้อมูลสินค้า
      $pd->getData($rs->id_product);

      //----  ต้นทุนสินค้าแบบ average
      $cost_ex = $pdCost->getCost($rs->id_product);

      $cost_inc = addVAT($cost_ex, $vat);

      //--- จำนวนที่จะตัดยอด
      $qty = $rs->qty;

      //--- ราคาที่จะบันทึกขาย
      $price = $rs->price;

      //--- ส่วนลดที่ระบุมา (เป็นเปอร์เซ็นหรือยอดเงิน เช่น 10 % หรือ 10)
      $discLabel = $rs->discount;

      //--- ส่วนลดเป็นมูลค่าเงิน x จำนวนที่ระบุมา
      $discount_amount = $rs->discount_amount;

      //--- มูลค่ารวม
      $total_amount = $rs->total_amount;

      //--- ข้อมูลสำหรับบันทึกยอดขาย
      $ds = array(
        'id_order'        => 0,
        'reference'       => $cs->reference,
        'id_role'         => $role->id,
        'role_name'       => $role->name,
        'id_payment'      => 0,
        'payment'         => $role->name,
        'id_channels'     => $cs->id_channels,
        'channels'        => $channels->name,
        'id_product'      => $pd->id,
        'product_code'    => $pd->code,
        'product_name'    => $pd->name,
        'id_color'        => $pd->id_color,
        'color'           => $color->getCode($pd->id_color),
        'color_group'     => $color->getGroupCode($pd->id_color),
        'id_size'         => $pd->id_size,
        'size'            => $size->getCode($pd->id_size),
        'size_group'      => $size->getGroupCode($pd->id_size),
        'id_product_style'=> $pd->id_style,
        'product_style'   => $style->getCode($pd->id_style),
        'id_product_group'=> $pd->id_group,
        'product_group'   => $pd_group->getName($pd->id_group),
        'id_product_category' => $pd->id_category,
        'product_category'=> $category->getName($pd->id_category),
        'id_product_kind' => $pd->id_kind,
        'product_kind'    => $kind->getName($pd->id_kind),
        'id_product_type' => $pd->id_type,
        'product_type'    => $type->getName($pd->id_type),
        'id_brand'        => $pd->id_brand,
        'brand'           => $brand->getName($pd->id_brand),
        'year'            => $pd->year,
        'cost_ex'         => $cost_ex,
        'cost_inc'        => $cost_inc,
        'price_ex'        => removeVAT($price, $vat),
        'price_inc'       => $price,
        'sell_ex'         => removeVAT( ($total_amount/$qty), $vat),
        'sell_inc'        => $total_amount / $qty,
        'qty'             => $qty,
        'discount_label'  => $discLabel,
        'discount_amount' => $discount_amount,
        'total_amount_ex' => removeVAT($total_amount , $vat),
        'total_amount_inc'=> $total_amount,
        'total_cost_ex'   => $cost_ex * $qty,
        'total_cost_inc'  => $cost_inc * $qty,
        'margin_ex'       => removeVAT($total_amount, $vat) - ($cost_ex * $qty),
        'margin_inc'      => $total_amount - ($cost_inc * $qty),
        'id_customer'     => $customer->id,
        'customer_code'   => $customer->code,
        'customer_name'   => $customer->name,
        'customer_group'  => $customer_group->name,
        'customer_type'   => $customer_type->name,
        'customer_kind'   => $customer_kind->name,
        'customer_class'  => $customer_class->name,
        'customer_area'   => $customer_area->name,
        'province'        => $customer->province,
        'id_employee'     => $employee->id_employee,
        'employee_name'   => $employee->full_name,
        'date_add'        => dbDate($cs->date_add, TRUE),
        'id_zone'         => $zone->id,
        'id_warehouse'    => $zone->id_warehouse,
        'count_stock'     => $pd->count_stock
      );

      //--- บันทึกขาย
      if($order->sold($ds) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึกขายไม่สำเร็จ';
      }

      //--- ถ้าไม่นับสต็อก ไม่ต้องตัดยอด ไม่ต้องบันทึก movement
      if($pd->count_stock == 1)
      {

        //-------- ตัดยอดสต็อกจากโซน
        //--- ตรวจสอบจำนวนคงเหลือในโซนว่าพอให้ตัดหรือไม่
        $isEnough = $stock->isEnough($zone->id, $pd->id, $qty);

        //--- ตรวจสอบว่าโซนอนุญาติให้ติดลบหรือไม่
        $allowUnderZero = $zone->allowUnderZero;

        if( $isEnough === FALSE && $allowUnderZero === FALSE)
        {
          $sc = FALSE;
          $message = $pd->code.' : จำนวนคงเหลือไม่เพียงพอ';
        }
        else
        {
          if( $stock->updateStockZone($zone->id, $pd->id, ($qty * -1)) !== TRUE )
          {
            $sc = FALSE;
            $message = 'ตัดยอดในโซนไม่สำเร็จ';
          }
        }

        //--- บันทึก movement
        if( $movement->move_out($cs->reference, $zone->id_warehouse, $zone->id, $pd->id, $qty, $cs->date_add) !== TRUE)
        {
          $sc = FALSE;
          $message = 'บันทึก movement ไม่สำเร็จ';
        }

      } //--- if count_stock

      //--- เปลี่ยนสถนาะรายการเป็น บันทึกแล้ว
      if($cs->updateDetail($rs->id, array('status' => 1)) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
      }

    } //--- endwhile

  } //--- end if dbNumRows


  if($sc === TRUE)
  {
    //--- เปลี่ยนสถานะเป็นบันทึกแล้ว
    if($cs->update($cs->id, array('isSaved' => 1)) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะเอกสารไม่สำเร็จ';
    }
  }


  if($sc === TRUE)
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

} //--- end if iscancle


echo $sc === TRUE ? 'success' : $message;





 ?>
