<?php

/*
1. delete product_group rule;
2. insert new product_group rule;
*/


$ex = dropProductSubGroupRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการกลุ่มย่อยลูกค้าเก่าไม่สำเร็จ';
}
else
{
  $pd = $_POST['productSubGroup'];
  if(!empty($pd))
  {
    startTransection();
    foreach($pd as $id_sub)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_sub_group (id_rule, id_product_sub_group) ";
      $qr .= "VALUES (".$id_rule.", '".$id_sub."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขกลุมย่อยสินค้าไม่สำเร็จ';
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
