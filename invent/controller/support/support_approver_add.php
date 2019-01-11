<?php
$sc = TRUE;
$id_emp = $_POST['id_employee'];
$doc_type = 'SU-BUDGET';
$ap = new approver();
$emp = new employee();
if($ap->isExistsApprover($doc_type, $id_emp) === TRUE)
{
  $sc = FALSE;
  $message = 'มีผู้อนุมัติในระบบแล้ว';
}
else
{
  $sKey = $emp->getKey($id_emp);

  if($sKey === FALSE)
  {
    $sc = FALSE;
    $message = 'ไม่พบพนักงานในระบบ กรุณาตรวจสอบชื่อว่าถูกต้องหรือไม่';
  }
  else
  {
    $arr = array(
      'doc_type' => $doc_type,
      'id_employee' => $id_emp,
      'approve_key' => $sKey
    );

    $rs = $ap->add($arr);
    if($rs === FALSE)
    {
      $sc = FALSE;
      $message = 'เพิ่มผู้อนุมัติไม่สำเร็จ';
    }
  }
}

echo $sc === TRUE ? 'success' : $message;
?>
