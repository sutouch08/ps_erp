<?php
//--- ไว้ตรวจสอบความถูกต้อง
$sc = TRUE;

//--- ไอดีเอกสาร
$id = $_POST['id_adjust'];

//--- โซนที่จะปรับยอด
$id_zone = $_POST['id_zone'];

//--- สินค้าที่จะปรับ
$id_product = $_POST['id_product'];

//--- จำนวนที่จะปรับ
$qty = $_POST['up_qty'] - $_POST['down_qty'];

if( $qty != 0 )
{
  $cs = new adjust();
  $zone = new zone($id_zone);

  //--- ถ้ามีรายการปรับยอดอยู่ก่อนแล้ว จะได้ id กลับมา
  $rs = $cs->getDetailId($id, $id_zone, $id_product);

  if( $rs !== FALSE )
  {
    //--- ตรวจสอบว่า บันทึกรายการไปแล้วหรือยัง
    if( $cs->isValidDetail($rs) === FALSE)
    {
      //--- ถ้ายังไม่ได้บันทึก
      //--- update รายการที่มีอยู่
      if( $cs->updateDetail($rs, $qty) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ปรับปรุงรายการไม่สำเร็จ';
      }
    }
    else
    {
      $sc = FALSE;
      $message = 'ไม่สามารถปรับปรุงรายการได้เนื่องจากรายการถูกปรับยอดไปแล้ว';
    }

  }
  else
  {
    //--- ถ้ายังไม่มีรายการปรับยอดอยู่ เพิ่มใหม่
    $arr = array(
      'id_adjust' => $id,
      'id_product' => $id_product,
      'id_warehouse'  => $zone->id_warehouse,
      'id_zone' => $id_zone,
      'qty' => $qty
    );

    //--- ถ้าสำเร็จจะได้ id กลับมา
    $rs = $cs->addDetail($arr);
    if( $rs === FALSE )
    {
      $sc = FALSE;
      $message = 'เพิ่มรายการไม่สำเร็จ';
    }
  }

  //--- เพิ่มหรือ update เสร็จแล้ว
  if( $rs !== FALSE )
  {
    $ds = $cs->getDetail($rs);
    if( $ds === FALSE )
    {
      $sc = FALSE;
      $message = 'ไม่พบข้อมูลรายการปรับยอด';
    }
    else
    {
      $barcode = new barcode();
      $product = new product();
      $arr = array(
        'id'  => $ds->id,
        'barcode' => $barcode->getBarcode($ds->id_product),
        'pdCode'  => $product->getCode($ds->id_product),
        'pdName'  => $product->getName($ds->id_product),
        'zoneName'  => $zone->name,
        'up'  => $ds->qty > 0 ? number($ds->qty) : 0,
        'down'  => $ds->qty < 0 ? number($ds->qty * -1) : 0,
        'valid' => $ds->valid
      );
    }
  }
}
else
{
  $sc = FALSE;
  $message = 'ไม่สามารถเพิ่มรายการได้เนื่องจากยอดที่ปรับเป็น 0';
}

echo $sc === TRUE ? json_encode($arr) : $message;


 ?>
