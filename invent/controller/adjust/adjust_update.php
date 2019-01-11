<?php

//--- ไอดีเอกสาร
$id = $_POST['id_adjust'];

//--- วันที่เอกสาร
$date_add = dbDate($_POST['date_add'], TRUE);

//--- อ้างถึงเอกสารอื่นๆ
$refer = $_POST['refer'];

//--- ชื่อผู้ขอปรับยอด
$requester = $_POST['requester'];

//--- หมายเหตุเอกสาร
$remark = $_POST['remark'];

//---
$is_so = isset($_POST['is_so']) ? $_POST['is_so'] : 1;

$cs = new adjust();

$arr = array(
  'date_add' => $date_add,
  'refer' => $refer,
  'requester' => $requester,
  'remark'  => $remark,
  'is_so' => $is_so,
  'emp_upd' => getCookie('user_id')
);

$sc = $cs->update($id, $arr);

echo $sc === TRUE ? 'success' : 'แก้ไขข้อมูลไม่สำเร็จ';

 ?>
