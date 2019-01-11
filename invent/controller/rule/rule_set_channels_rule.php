<?php
$sc = TRUE;

$id_rule = $_POST['id_rule'];

//--- all channels ?
$all = $_POST['all_channels'] == 'Y' ? TRUE : FALSE;

if($all === TRUE)
{
  include 'rule/set_all_channels.php';
  exit;
}

if($all === FALSE)
{
  startTransection();
  //--- เปลี่ยนเงื่อนไข set all_channels = 0
  if(setAllChannels($id_rule, 0) !== TRUE)
  {
    exit;
  }

  //--- ลบเงื่อนไขเก่าออก
  if(dropChannelsRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ยกเลิกเงื่อนไขเก่าไม่สำเร็จ';
  }
  else
  {
    //--- เพิ่มเงื่อนไขใหม่
    $channels = isset($_POST['channels']) ? $_POST['channels'] : FALSE;
    if(!empty($channels))
    {
      foreach($channels as $id_channels)
      {
        $qr  = "INSERT INTO tbl_discount_rule_channels (id_rule, id_channels) ";
        $qr .= "VALUES ";
        $qr .= "(".$id_rule.", ".$id_channels.")";

        if(dbQuery($qr) !== TRUE)
        {
          $sc = FALSE;
          $message = 'เพิ่มเงื่อนไขช่องทางการขายไม่สำเร็จ';
        }
      } //--- end foreach
    } //--- end if !empty
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

} //--- end if $all === false

echo $sc === TRUE ? 'success' : $message;



 ?>
