<?php

/*
1. delete product_category rule;
2. insert new product_category rule;
*/


$ex = dropProductCategoryRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการหมวดหมู่สินค้าเก่าไม่สำเร็จ';
}
else
{
  $productCategory = $_POST['productCategory'];
  if(!empty($productCategory))
  {
    startTransection();
    foreach($productCategory as $id_product_category)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_category (id_rule, id_product_category) ";
      $qr .= "VALUES (".$id_rule.", '".$id_product_category."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขหมวดหมู่สินค้าไม่สำเร็จ';
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
