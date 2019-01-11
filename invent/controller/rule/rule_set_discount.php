<?php
$sc = TRUE;

$id_rule = $_POST['id_rule'];
$setPrice = trim($_POST['set_price']);
$price = $_POST['price'];
$disc = trim($_POST['disc']);
$unit = $_POST['disc_unit'];
$minQty = $_POST['min_qty'];
$minAmount = $_POST['min_amount'];
$canGroup = trim($_POST['can_group']);

$minQty = $minQty > 0 ? $minQty : 0; //-- ต้องไม่ติดลบ
$minAmount = $minAmount > 0 ? $minAmount : 0; //--- ต้องไม่ติดลบ
$canGroup = $canGroup == 'Y' ? 1 : 0;
$discUnit = $unit == 'P' ? 'percent' : ($unit == 'A' ? 'amount' : 'percent');

if($setPrice == 'Y')
{
  $disc = 0;
  $price = $price > 0 ? $price : 0;
}


if($setPrice == 'N')
{
  $price = 0;
  $disc = $disc >= 0 ? $disc : 0;
}

//--- ถ้าไม่ได้กำหนดราคาขาย และส่วนลดเป็น % ส่วนลดต้องไม่เกิน 100%
if($setPrice == 'N' && $unit == 'P' && $disc > 100)
{
  $sc = FALSE;
  $message = 'ส่วนลดต้องไม่เกิน 100%';
}


//---- ถ้าไม่มีอะไรผิดพลาด
if($sc === TRUE)
{
  $cs = new discount_rule();
  $arr = array(
    'qty' => $minQty,
    'amount' => $minAmount,
    'canGroup' => $canGroup,
    'item_price' => $price,
    'item_disc' => $disc,
    'item_disc_unit' => $discUnit,
    'emp_upd' => getCookie('user_id')
  );

  if($cs->update($id_rule, $arr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'บันทีกเงื่อนไขไม่สำเร็จ : '.$cs->error;
  }
}

echo $sc === TRUE ? 'success' : $message;
 ?>
