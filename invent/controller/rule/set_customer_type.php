<?php

/*
1. delete customer_type rule;
2. insert new customer_type rule;
*/


$ex = dropCustomerTypeRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการชนิดลูกค้าเก่าไม่สำเร็จ';
}
else
{
  $customerType = $_POST['customerType'];
  if(!empty($customerType))
  {
    startTransection();
    foreach($customerType as $id_customer_type)
    {
      $qr  = "INSERT INTO tbl_discount_rule_customer_type (id_rule, id_customer_type) ";
      $qr .= "VALUES (".$id_rule.", '".$id_customer_type."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขชนิดลูกค้าไม่สำเร็จ';
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
