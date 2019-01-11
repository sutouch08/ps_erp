<?php

/*
1. delete product_group rule;
2. insert new product_group rule;
*/


$ex = dropProductGroupRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการกลุ่มสินค้าเก่าไม่สำเร็จ';
}
else
{
  $productGroup = $_POST['productGroup'];
  if(!empty($productGroup))
  {
    startTransection();
    foreach($productGroup as $id_product_group)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_group (id_rule, id_product_group) ";
      $qr .= "VALUES (".$id_rule.", '".$id_product_group."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขกลุมสินค้าไม่สำเร็จ';
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
