<?php
$sc = TRUE;

$id = $_POST['id']; //---- id approver not id_employee

$ap = new approver();

if($ap->delete($id) === FALSE)
{
  $sc = FALSE;
  $message = 'ลบผู้อนุมัติไม่สำเร็จ';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
