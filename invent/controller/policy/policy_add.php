<?php
$sc = TRUE;
$cs = new discount_policy();
$name = $_POST['name'];
$fromDate = dbDate($_POST['fromDate']);
$toDate = dbDate($_POST['toDate']);
$reference = $cs->getNewReference();

$arr = array(
  'reference' => $reference,
  'name' => $name,
  'date_start' => $fromDate,
  'date_end' => $toDate,
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
