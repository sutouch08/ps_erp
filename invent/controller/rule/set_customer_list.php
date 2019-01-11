<?php

/*
1. set all customer = 0
2. delete customers rule;
2.1 set customer rule;
3. delete customer_group rule;
4. delete customer_type rule;
5. delete customer_kind rule;
6. delete customer_area rule;
7. delete customer_class rule;
*/

startTransection();
//--- 1.
$qs = dbQuery("UPDATE tbl_discount_rule SET all_customer = 0 WHERE id = ".$id_rule);

if($qs !== TRUE)
{
  $sc = FALSE;
  $message = 'ปรับปรุงรายการไม่สำเร็จ';
}
else
{
  //--- 2.
  if(dropCustomerListRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบรายการเก่าไม่สำเร็จ';
  }
  else
  {
    //--- 2.1.
    $cusList = $_POST['custId'];

    if(!empty($cusList))
    {
      foreach($cusList as $id_customer)
      {
        $qc = dbQuery("INSERT INTO tbl_discount_rule_customers (id_rule, id_customer) VALUES (".$id_rule.", '".$id_customer."')");
      }
    }
    else
    {
      $sc = FALSE;
      $message = 'ไม่พบรายชื่อลูกค้า';
    }
  }

  if($sc === TRUE)
  {
    //--- 3.
    if(dropCustomerGroupRule($id_rule) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบเงื่อนไขกลุ่มลูกค้าไม่สำเร็จ';
    }

    //--- 4.
    if(dropCustomerTypeRule($id_rule) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบเงื่อนไขชนิดลูกค้าไม่สำเร็จ';
    }

    //--- 5.
    if(dropCustomerKindRule($id_rule) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบเงื่อนไขประเภทลูกค้าไม่สำเร็จ';
    }

    //--- 6.
    if(dropCustomerAreaRule($id_rule) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบเงื่อนไขเขตลูกค้าไม่สำเร็จ';
    }

    //--- 7.
    if(dropCustomerClassRule($id_rule) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบเงื่อนไขเกรดลูกค้าไม่สำเร็จ';
    }
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
