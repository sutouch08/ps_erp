<?php
//---	ไว้ตรวจสอบผลลัพภ์
$sc	= TRUE;

//---
$id = $_POST['id_receive_product'];

//---	เลขที่ใบสั่งซื้อ
$poCode = trim($_POST['poCode'] );

$po = new po($poCode);

$product	 = new product();
$zone      = new zone();
$st			   = new stock();
$mv			   = new movement();
$cost      = new product_cost();

//---	เลขที่ใบรับสินค้า (ไม่บังคับ)
$invoice	= trim(addslashes($_POST['invoice'] ));

//--- id zone
$id_zone = $_POST['id_zone'];

//--- approve key สำหรับอนุมัติรับเกินใบสั่งซื้อ
$approvKey = $_POST['approvKey'];

//--- id emp คนอนุมัติ
$id_emp = $_POST['id_emp'];

$data = $_POST['receive'];


$cs = new receive_product($id);

//--- ตรวจสอบเอกสารว่าบันทึกไปแล้วหรือยัง
if($cs->isSaved == 1)
{
  $sc = FALSE;
  $message = 'เอกสารถูกบันทึกไปแล้วโดยผู้อื่น';
}

//--- ตรวจสอบข้อมูลว่ามีข้อมูลมาหรือไม่
if(count($data) == 0)
{
  $sc = FALSE;
  $message = "ไม่พบรายการรับเข้า";
}


//--- ตรวจสอบใบสั่งซื้อว่าปิดไปแล้วหรือยัง
if($po->status == 3)
{
  $sc = FALSE;
  $message = "ใบสั่งซื้อไม่ถูกต้อง ถูกปิด หรือ ถูกยกเลิก";
}



if($sc === TRUE)
{

  startTransection();
  //--- update Document
  $arr = array(
    'id_supplier' => $po->id_supplier,
    'po' => $poCode,
    'invoice' => $invoice,
    'approver' => $id_emp,
    'approvKey' => $approvKey,
    'isSaved' => 1
  );

  //--- ปรับปรุงเอกสารก่อน
  if($cs->update($cs->id, $arr))
  {
    commitTransection();
  }
  else
  {
    $sc = FALSE;
    $message = 'ปรับปรุงข้อมูลเอกสารไม่สำเร็จ';
  }


  if($sc === TRUE)
  {
    //---	ไอดีของคลังที่จะรับเข้า
    $id_wh		= $zone->getWarehouseId($id_zone);

    //---	รายการที่มีการคีย์ยอดรับเข้ามา
    $data				= $_POST['receive'];

    //--- วันที่เอกสาร
    $date_add = $cs->date_add;

    //--- เลขที่เอกสาร
    $reference = $cs->reference;


    foreach( $data as $id_pd => $qty )
    {
      $pdCost = $po->getPrice($poCode, $id_pd);
      $arr = array(
              "id_receive_product"	=> $id,
              "id_style"	=> $product->getStyleId($id_pd),
              "id_product" => $id_pd,
              "qty"	=> $qty,
              "cost" => $pdCost,
              "id_warehouse" => $id_wh,
              "id_zone"	=> $id_zone
            );

      //------ เพิ่มรายการรับเข้า
      if(!$cs->insertDetail($arr))
      {
        $sc = FALSE;
        $message = 'เพิ่มรายการรับเข้าไม่สำเร็จ';
      }

      //---	บันทึกยอดสต็อกเข้าโซนที่รับสินค้าเข้า
      if(!$st->updateStockZone($id_zone, $id_pd, $qty))
      {
        $sc = FALSE;
        $message = 'บันทึกยอดสต็อกเข้าโซนไม่สำเร็จ';
      }

      //---	บันทึก movement เข้าโซนที่รับสินคาเข้า
      if(!$mv->move_in( $reference, $id_wh, $id_zone, $id_pd, $qty, $date_add ))
      {
        $sc = FALSE;
        $message = 'บันทึก movement ไม่สำเร็จ';
      }

      //--- บันทึกยอดรับใน PO
      if(!$po->received($poCode, $id_pd, $qty))
      {
        $sc = FALSE;
        $message = 'ปรับปรุงยอดรับแล้วในใบสั่งซื้อไม่สำเร็จ';
      }

      //--- บันทึกต้นทุนสินค้า
      if(!$cost->addCostList($id_pd, $pdCost, $qty, $date_add))
      {
        $sc = FALSE;
        $message = 'บันทึกต้นทุนสินค้าไม่สำเร็จ';
      }

      //---- ถ้าทุกอย่างไม่ผิดพลาด
      if($sc === TRUE)
      {
        commitTransection();
      }
      else
      {
        dbRollback();
        break;
      }

    }	//--- foreach data
  }

  endTransection();


  //--- ถ้ารับเข้าครบ PO แล้ว
  if($po->isCompleted($poCode) === TRUE && getConfig('PO_AUTO_CLOSE') == 1)
  {
    $po->close($po->bookcode, $poCode);
  }


} //-- end if


echo $sc === TRUE ? 'success' : $message;

 ?>
