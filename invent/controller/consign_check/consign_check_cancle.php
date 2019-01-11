<?php
$id = $_POST['id_consign_check'];
$cs = new consign_check($id);

if($cs->valid == 0)
{

  $sc = TRUE;
  startTransection();
  //--- Step 1 delete box detail
  $qs = $cs->getAllConsignBox($id);

  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      if($sc == FALSE)
      {
        break;
      }
      
      //---- Let's delete box detail box by box
      if($cs->deleteAllBoxDetails($rs->id) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบรายการในกล่องที่ '.$rs->box_no.' ไม่สำเร็จ';
      }

      //---- now delete box
      if($cs->deleteConsignBox($rs->id) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบกล่องที่ '.$rs->box_no.' ไม่สำเร็จ';
      }

    }//--- end while
  }//--- endif dbNumRows

  //--- now delete all consign_check detail
  if($sc === TRUE)
  {
    if($cs->deleteAllDetails($id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบรายการทั้งหมดไม่สำเร็จ';
    }
  }


  if($sc === TRUE)
  {
    if($cs->cancleConsignCheck($id) !== TRUE)
    {
      $sc = FALSE;
      $sc = 'เปลี่ยนสถานะเอกสารไม่สำเร็จ';
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
  $message = 'ไม่สามารถลบข้อมูลได้เนื่องจากมีการดึงไปตัดยอดฝากขายแล้ว';
}

echo $sc === TRUE ? 'success' : $message;

 ?>
