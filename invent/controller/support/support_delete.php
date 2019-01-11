<?php

$id = $_POST['id_support'];
$sp = new support($id);
$bd = new support_budget();
$order = new order();


//---	ตรวจสอบทรานเซ็คชั้นก่อนลบ
$rs = $order->isExitsTransection($sp->id_customer, 3);

//---	ถ้าไม่มีทรานเซ็คชั่น
if( $rs === FALSE )
{
  startTransection();
  //---	ลบงบประมาณ
  $br = $bd->deleteSupportBudget($id);

  //---	ลบผู้รับ
  $sc = $sp->delete($id);

  if( $br === TRUE && $sc === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

  if( $sc === FALSE)
  {
    $message = 'ลบรายการไม่สำเร็จ';
  }
}
else
{
  $message = 'ไม่สามารถลบได้เนื่องจากมี transection เกิดขึ้นในระบบแล้ว';
  $sc = FALSE;
}

echo $sc === TRUE ? 'success' : $message;

 ?>
