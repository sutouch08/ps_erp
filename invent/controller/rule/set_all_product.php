<?php

/*
1. set all product = 1
2. delete product rule
3. delete product_group rule;
4. delete product_type rule;
5. delete product_kind rule;
6. delete product_area rule;
7. delete product_class rule;
*/

startTransection();
//--- 1.
$qs = dbQuery("UPDATE tbl_discount_rule SET all_product = 1 WHERE id = ".$id_rule);
if($qs !== TRUE)
{
  $sc = FALSE;
  $message = 'กำหนดสินค้าทั้งหมดไม่สำเร็จ';
}
else
{
  //--- 2.
  if(dropProductStyleRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบรายชื่อสินค้าไม่สำเร็จ';
  }

  //--- 3.
  if(dropProductGroupRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขกลุ่มสินค้าไม่สำเร็จ';
  }

  //--- 4.
  if(dropProductSubGroupRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขกลุ่มย่อยสินค้าไม่สำเร็จ';
  }


  //--- 5.
  if(dropProductCategoryRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขกลุ่มย่อยสินค้าไม่สำเร็จ';
  }

  //--- 6.
  if(dropProductTypeRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขชนิดสินค้าไม่สำเร็จ';
  }

  //--- 7.
  if(dropProductKindRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขประเภทสินค้าไม่สำเร็จ';
  }

  //--- 8.
  if(dropProductBrandRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขยี่ห้อสินค้าไม่สำเร็จ';
  }

  //--- 9.
  if(dropProductYearRule($id_rule) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเงื่อนไขปีสินค้าไม่สำเร็จ';
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
