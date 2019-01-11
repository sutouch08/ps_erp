<?php

/*
1. delete customer_group rule;
2. insert new customer_group rule;
*/


$ex = dropCustomerAreaRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการเขตลูกค้าเก่าไม่สำเร็จ';
}
else
{
  $customerArea = $_POST['customerArea'];
  if(!empty($customerArea))
  {
    startTransection();
    foreach($customerArea as $id_customer_area)
    {
      $qr  = "INSERT INTO tbl_discount_rule_customer_area (id_rule, id_customer_area) ";
      $qr .= "VALUES (".$id_rule.", '".$id_customer_area."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขเขตลูกค้าไม่สำเร็จ';
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
