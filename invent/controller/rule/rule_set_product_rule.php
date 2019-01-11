<?php
$sc = TRUE;

$id_rule = $_POST['id_rule'];

//--- all product ?
$all = $_POST['all_product'] == 'Y' ? TRUE : FALSE;

//--- product name ?
$style = $_POST['product_style'] == 'Y' ? TRUE : FALSE;

//--- product group ?
$group = $_POST['product_group'] == 'Y' ? TRUE : FALSE;

//--- product sub group ?
$sub = $_POST['product_sub_group'] == 'Y' ? TRUE : FALSE;

//--- product category ?
$category = $_POST['product_category'] == 'Y' ? TRUE : FALSE;

//--- product type ?
$type = $_POST['product_type'] == 'Y' ? TRUE : FALSE;

//--- product kind ?
$kind = $_POST['product_kind'] == 'Y' ? TRUE : FALSE;

//--- product brand ?
$brand = $_POST['product_brand'] == 'Y' ? TRUE : FALSE;

//--- product year ?
$year = $_POST['product_year'] == 'Y' ? TRUE : FALSE;

if($all === TRUE)
{
  include 'rule/set_all_product.php';
  exit;
}

if($all === FALSE)
{
  //--- เปลี่ยนเงื่อนไข set all_product = 0
  if(setAllProduct($id_rule, 0) !== TRUE)
  {
    echo 'ยกเลิกเงื่อนไขสินค้าทั้งหมดไม่สำเร็จ';
    exit;
  }


  //--- กรณีระบุรุ่นสินค้า
  if($style === TRUE)
  {
    include 'rule/set_product_style.php';
    exit;
  }

  //--- กรณีไม่ระบุชื่อสินค้า
  if($style === FALSE)
  {
    //--- ลบรายชื่อสินค้าออกก่อน
    if(dropProductStyleRule($id_rule) == FALSE)
    {
      echo 'ยกเลิกรายการสินค้าไม่สำเร็จ';
      exit;
    }


    //---- Set product group
    if($group === TRUE)
    {
      include 'rule/set_product_group.php';
    }

    //---- Set product sub group
    if($sub === TRUE)
    {
      include 'rule/set_product_sub_group.php';
    }

    //---- Set product category
    if($category === TRUE)
    {
      include 'rule/set_product_category.php';
    }

    //--- set product type
    if($type === TRUE)
    {
      include 'rule/set_product_type.php';
    }

    //--- set product kind
    if($kind === TRUE)
    {
      include 'rule/set_product_kind.php';
    }

    //--- set product brand
    if($brand === TRUE)
    {
      include 'rule/set_product_brand.php';
    }

    //--- set product class
    if($year === TRUE)
    {
      include 'rule/set_product_year.php';
    }
  } //--- end if styleId == false
} //--- end if $all === false

echo $sc === TRUE ? 'success' : $message;



 ?>
