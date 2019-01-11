<?php
$sc = 'success';
$id = $_POST['id_move'];
$id_warehouse = $_POST['id_warehouse'];
$cs = new move($id);

if( $cs->id_warehouse !== $id_warehouse)
{
  if( $cs->hasDetail($id) === FALSE)
  {
    $arr = array(
      'id_warehouse' => $_POST['id_warehouse'],
      'remark' 	=> $_POST['remark'],
      'emp_upd'	=> getCookie('user_id')
    );
  }
  else
  {
    $sc = 'ไม่อนุญาติให้เปลี่ยนคลัง';
  }
}
else
{
  $arr = array(
    'remark' => $_POST['remark'],
    'emp_upd' => getCookie('user_id')
  );
}

$rs = $cs->update($_POST['id_move'], $arr);

if( $rs === FALSE )
{
  $sc = 'แก้ไขข้อมูลไม่สำเร็จ';
}

echo $sc;

 ?>
