<?php
$sc = TRUE;
$date_add = dbDate($_POST['date_add'], TRUE);
$id = $_POST['id_consign'];
$cs = new consign();
$arr = array(
  'id_customer' => $_POST['id_customer'],
  'id_zone' => $_POST['id_zone'],
  'id_channels' => $_POST['id_channels'],
  'remark' => $_POST['remark'],
  'date_add'  => $date_add,
  'is_so'   => $_POST['is_so'],
  'emp_upd' => getCookie('user_id')
);

if( $cs->update($id, $arr) !== TRUE)
{
  $sc = FALSE;
  $message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
