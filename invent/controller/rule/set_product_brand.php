<?php

/*
1. delete product_brand rule;
2. insert new product_brand rule;
*/


$ex = dropProductBrandRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการยี่ห้อสินค้าเก่าไม่สำเร็จ';
}
else
{
  $productBrand = $_POST['productBrand'];
  if(!empty($productBrand))
  {
    startTransection();
    foreach($productBrand as $id_product_brand)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_brand (id_rule, id_product_brand) ";
      $qr .= "VALUES (".$id_rule.", '".$id_product_brand."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขยี่ห้อสินค้าไม่สำเร็จ';
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
