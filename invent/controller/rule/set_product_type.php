<?php

/*
1. delete product_type rule;
2. insert new product_type rule;
*/


$ex = dropProductTypeRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการชนิดสินค้าเก่าไม่สำเร็จ';
}
else
{
  $productType = $_POST['productType'];
  if(!empty($productType))
  {
    startTransection();
    foreach($productType as $id_product_type)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_type (id_rule, id_product_type) ";
      $qr .= "VALUES (".$id_rule.", '".$id_product_type."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขชนิดสินค้าไม่สำเร็จ';
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
