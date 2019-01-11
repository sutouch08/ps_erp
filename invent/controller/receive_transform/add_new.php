<?php
//---	ไว้ตรวจสอบผลลัพภ์
$sc		 = TRUE;

$cs = new receive_transform();

$date_add = dbDate($_POST['date_add'], TRUE);
$remark = addslashes($_POST['remark']);

//---	รหัสเล่มเอกสารรับเข้าจากการผลิด (FR)
$bookcode = getConfig('BOOKCODE_RECEIVE_TRANSFORM');

//--- เลขที่เอกสารใหม่
$reference	= $cs->getNewReference($date_add);

$arr = array(
  'bookcode'  => $bookcode,
  'reference' => $reference,
  'date_add'  => $date_add,
  'id_employee' => getCookie('user_id'),
  'remark'    => $remark
);

$rs = $cs->add($arr);

if($rs === FALSE OR ! is_numeric($rs))
{
  $sc = FALSE;
  $message = 'เพิ่มเอกสารไม่สำเร็จ '.$cs->error;
}

echo $sc === TRUE ? $rs : $message;

 ?>
