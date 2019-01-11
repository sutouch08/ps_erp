<?php

/*
1. set all customer = 1
2. delete customer rule
3. delete customer_group rule;
4. delete customer_type rule;
5. delete customer_kind rule;
6. delete customer_area rule;
7. delete customer_class rule;
*/

startTransection();
//--- 1.
$qs = dbQuery("UPDATE tbl_discount_rule SET all_customer = 1 WHERE id = ".$id_rule);
if($qs !== TRUE)
{
  $sc = FALSE;
  $message = 'กำหนดลูกค้าทั้งหมดไม่สำเร็จ';
}
else
{
  //--- 2.
  if(dropCustomerListRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบรายชื่อลูกค้าไม่สำเร็จ';
  }

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
