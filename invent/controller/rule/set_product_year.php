<?php

/*
1. delete product_brand rule;
2. insert new product_brand rule;
*/


$ex = dropProductYearRule($id_rule);
if($ex == FALSE)
{
  $sc = FALSE;
  $message = 'ลบรายการยี่ห้อสินค้าเก่าไม่สำเร็จ';
}
else
{
  $productYear = $_POST['productYear'];
  if(!empty($productYear))
  {
    startTransection();
    foreach($productYear as $pd_year)
    {
      $qr  = "INSERT INTO tbl_discount_rule_product_year (id_rule, year) ";
      $qr .= "VALUES (".$id_rule.", '".$pd_year."')";

      if(dbQuery($qr) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เพิ่มเงื่อนไขปีสินค้าไม่สำเร็จ';
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
