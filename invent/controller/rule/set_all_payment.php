<?php

/*
1. set all payment = 1
2. delete payment rule
*/

startTransection();
//--- 1.
$qs = dbQuery("UPDATE tbl_discount_rule SET all_payment = 1 WHERE id = ".$id_rule);
if($qs !== TRUE)
{
  $sc = FALSE;
  $message = 'กำหนดช่องทางการชำระเงินไม่สำเร็จ';
}
else
{
  //--- 2.
  if(dropPaymentRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบช่องทางการชำระเงินไม่สำเร็จ';
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
