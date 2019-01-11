<?php

/*
1. delete product_kind rule;
2. insert new product_kind rule;
*/


$ex = dropProductKindRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการชนิดสินค้าเก่าไม่สำเร็จ';
}
else
{
  $productKind = $_POST['productKind'];
  if(!empty($productKind))
  {
    startTransection();
    foreach($productKind as $id_product_kind)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_kind (id_rule, id_product_kind) ";
      $qr .= "VALUES (".$id_rule.", '".$id_product_kind."')";

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
