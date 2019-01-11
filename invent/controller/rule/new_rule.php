<?php
$sc = TRUE;
$cs = new discount_rule();
$name = $_POST['name'];
$reference = $cs->getNewReference();

$arr = array(
  'code' => $reference,
  'name' => $name,
  'date_add' => date('Y-m-d'),
  'id_emp' => getCookie('user_id')
);

//---	เพิ่มนโยบายใหม่ ถ้าสำเร็จจะได้ id กลับมา ถ้าไม่สำเร็จจะได้ FALSE
$id = $cs->add($arr);

if( $id === FALSE )
{
  $sc = FALSE;
  $message = $cs->error;
}

echo $sc === TRUE ? $id : $message;

 ?>
