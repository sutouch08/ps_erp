<?php

/*
1. delete customer_group rule;
2. insert new customer_group rule;
*/


$ex = dropCustomerGroupRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการกลุ่มลูกค้าเก่าไม่สำเร็จ';
}
else
{
  $customerGroup = $_POST['customerGroup'];
  if(!empty($customerGroup))
  {
    startTransection();
    foreach($customerGroup as $id_customer_group)
    {
      $qr  = "INSERT INTO tbl_discount_rule_customer_group (id_rule, id_customer_group) ";
      $qr .= "VALUES (".$id_rule.", '".$id_customer_group."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขกลุมลูกค้าไม่สำเร็จ';
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
