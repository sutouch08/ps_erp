<?php
$sc = TRUE;
$cs = new receive_product();
$date_add = dbDate($_POST['date_add'], TRUE);
$remark = $_POST['remark'] == '' ? '' : addslashes(trim($_POST['remark']));

$arr = array(
  'bookcode' => getConfig('BOOKCODE_BI'),
  'reference' => $cs->getNewReference($date_add),
  'id_employee' => getCookie('user_id'),
  'date_add' => $date_add,
  'remark' => $remark
);

$id = $cs->add($arr);

if($id === FALSE OR !is_numeric($id))
{
  $sc = FALSE;
  $message = 'เพิ่มเอกสารไม่สำเร็จ';
}

echo $sc === TRUE ? $id : $message;

 ?>
