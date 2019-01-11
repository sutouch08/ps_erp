<?php
$sc = TRUE;
$id_consign = $_POST['id_consign'];
$id_consign_check = $_POST['id_consign_check'];

$cs = new consign($id_consign);
$ck = new consign_check();

//--- จะลบได้เอกสารต้องยังไม่ได้บันทึกและไม่ได้ถูกยกเลิก
if($cs->isSaved == 0 && $cs->isCancle == 0 && $cs->id_consign_check == $id_consign_check)
{
  startTransection();
  //--- ลบรายการนำเข้า

  if($cs->deleteImportDetails($cs->id) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบรายการไม่สำเร็จ';
  }

  if($sc === TRUE)
  {
    //--- ลบการอ้างอิงเอกสาร
    if($cs->update($cs->id, array('id_consign_check' => 0)) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบการอ้างอิงเอกสารไม่สำเร็จ';
    }

    //--- เปลี่ยนสถานะเอกสารกระทบยอดกลับไปเป็นยังไม่ถูกดึงรายการ
    $arr = array('valid' => 0 , 'id_consign' => 0);
    if($ck->update($id_consign_check, $arr) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะเอกสารกระทบอยดไม่สำเร็จ';
    }
  }

  if($sc === TRUE)
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

}
else
{
  $sc = FALSE;

  if($cs->isSaved == 1)
  {
    $message = 'เอกสารถูกบันทึกไปแล้ว ไม่สามารถลบได้';
  }
  else if($cs->isCancle == 1)
  {
    $message = 'เอกสารถูกยกเลิกไปแล้ว ไม่สามารถลบได้';
  }
  else if($cs->id_consign_check != $id_consign_check)
  {
    $message = 'เลขที่เอกสารไม่ตรงกัน ไม่สามารถลบได้';
  }
}

echo $sc === TRUE ? 'success' : $message;


 ?>
