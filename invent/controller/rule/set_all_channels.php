<?php

/*
1. set all channels = 1
2. delete channels rule
*/

startTransection();
//--- 1.
$qs = dbQuery("UPDATE tbl_discount_rule SET all_channels = 1 WHERE id = ".$id_rule);
if($qs !== TRUE)
{
  $sc = FALSE;
  $message = 'กำหนดช่องทางขายไม่สำเร็จ';
}
else
{
  //--- 2.
  if(dropChannelsRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบช่องทางขายไม่สำเร็จ';
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


echo $sc === TRUE ? 'success' : $message;

 ?>
