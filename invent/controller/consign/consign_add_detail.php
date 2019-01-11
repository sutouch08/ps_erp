<?php
$sc    = TRUE;

$id    = $_POST['id_consign'];
$id_pd = $_POST['id_product'];
$qty   = $_POST['qty'];
$price = $_POST['price'];
$pDisc = $_POST['pDisc'];
$aDisc = $_POST['aDisc'];
$id_zone = $_POST['id_zone'];

$cs    = new consign();
$pd    = new product();
$bc    = new barcode();
$st    = new stock();
$zone  = new zone($id_zone);
$pd->getData($id_pd);

$discount = $pDisc > 0 ? $pDisc.' %' : $aDisc;

//---- ดึงรายการที่มีอยู่แล้ว
$qs = $cs->getExistsDetail($id, $id_pd, $price, $discount);

if(dbNumRows($qs) == 1)
{
  //--- update exists detail
  $rs = dbFetchObject($qs);

  //---- เอายอดที่คีย์มา รวมกับยอดในเอกสารที่มีอยู่แล้ว
  $max_qty = $qty + $cs->getSumProductQty($id, $id_pd);

  $qty = $rs->qty + $qty;

  if($st->isEnough($id_zone, $id_pd, $max_qty) === FALSE && $zone->allowUnderZero === FALSE && $pd->count_stock == 1)
  {
    $sc = FALSE;
    $message = 'สต็อกในโซนไม่เพียงพอ';
  }
  else
  {
    $discountAmount = $pDisc > 0 ? (($price * ($pDisc * 0.01)) * $qty) : ($aDisc * $qty);
    $totalAmount = ($qty * $price) - $discountAmount;
    $arr = array(
      'qty'  => $qty,
      'discount_amount' => $discountAmount,
      'total_amount' => $totalAmount
    );

    //--- ถ้า update ข้อมูลไม่สำเร็จ
    if($cs->updateDetail($rs->id, $arr) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ปรับปรุงข้อมูลตัดยอดไม่สำเร็จ';
    }
    else
    {
      //---- ถ้า update ข้อมูลสำเร็จ
      //---- เตรียมข้อมูลส่งกลับไป update ที่ client
      $pd->getData($rs->id_product);
      $barcode = $bc->getBarcode($rs->id_product);
      $ds = array(
        'id' => $rs->id,
        'barcode' => $barcode,
        'product' => $rs->product_code.' : '.$rs->product_name,
        'price' => $rs->price,
        'qty' => $qty,
        'p_disc' => $pDisc,
        'a_disc' => $aDisc,
        'amount' => $totalAmount
      );

    }

  }//--- end if isEnough

}
else
{
  //----- ถ้ายังไม่มีข้อมูล
  //---- ถ้าสินค้าในโซนไม่พอตัด
  if($st->isEnough($id_zone, $id_pd, $qty) === FALSE && $zone->allowUnderZero === FALSE && $pd->count_stock == 1)
  {
    $sc = FALSE;
    $message = 'สต็อกในโซนไม่เพียงพอ';
  }
  else
  {
    //---- Insert if not exists
    $discountAmount = $pDisc > 0 ? (($price * ($pDisc * 0.01)) * $qty) : ($aDisc * $qty);
    $totalAmount = ($qty * $price) - $discountAmount;
    $pd->getData($id_pd);
    $barcode = $bc->getBarcode($id_pd);
    $arr = array(
      'id_consign' => $id,
      'id_style' => $pd->id_style,
      'id_product' => $pd->id,
      'product_code' => $pd->code,
      'product_name' => $pd->name,
      'cost' => $pd->cost,
      'price' => $price,
      'qty' => $qty,
      'discount' => $discount,
      'discount_amount' => $discountAmount,
      'total_amount' => $totalAmount
    );


    $idc = $cs->addDetail($arr);

    if($idc == FALSE)
    {
      $sc = FALSE;
      $message = 'เพิ่มรายการไม่สำเร็จ';
    }
    else
    {
      $ds = $cs->getDetail($idc);
      if(dbNumRows($ds) == 1 )
      {
        $rs = dbFetchObject($ds);
        //---- เตรียมข้อมูลส่งกลับไป update ที่ client
        $pd->getData($rs->id_product);
        $barcode = $bc->getBarcode($rs->id_product);
        $ds = array(
          'id' => $rs->id,
          'barcode' => $barcode,
          'product' => $rs->product_code.' : '.$rs->product_name,
          'price' => $rs->price,
          'qty' => $rs->qty,
          'p_disc' => $pDisc,
          'a_disc' => $aDisc,
          'amount' => $totalAmount
        );
      }
    }
  } //--- end if isEnough
}

echo $sc === TRUE ? json_encode($ds) : $message;

 ?>
