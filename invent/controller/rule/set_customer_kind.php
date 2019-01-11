<?php

/*
1. delete customer_group rule;
2. insert new customer_group rule;
*/


$ex = dropCustomerKindRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการประเภทลูกค้าเก่าไม่สำเร็จ';
}
else
{
  $customerKind = $_POST['customerKind'];
  if(!empty($customerKind))
  {
    startTransection();
    foreach($customerKind as $id_customer_kind)
    {
      $qr  = "INSERT INTO tbl_discount_rule_customer_kind (id_rule, id_customer_kind) ";
      $qr .= "VALUES (".$id_rule.", '".$id_customer_kind."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขประเภทลูกค้าไม่สำเร็จ';
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
