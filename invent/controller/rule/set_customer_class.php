<?php

/*
1. delete customer_group rule;
2. insert new customer_group rule;
*/


$ex = dropCustomerClassRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการเกรดลูกค้าเก่าไม่สำเร็จ';
}
else
{
  $customerClass = $_POST['customerClass'];
  if(!empty($customerClass))
  {
    startTransection();
    foreach($customerClass as $id_customer_class)
    {
      $qr  = "INSERT INTO tbl_discount_rule_customer_class (id_rule, id_customer_class) ";
      $qr .= "VALUES (".$id_rule.", '".$id_customer_class."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขเกรดลูกค้าไม่สำเร็จ';
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
}





 ?>
