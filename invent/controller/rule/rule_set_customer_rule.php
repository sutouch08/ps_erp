<?php
$sc = TRUE;

$id_rule = $_POST['id_rule'];

//--- all customer ?
$all = $_POST['all_customer'] == 'Y' ? TRUE : FALSE;

//--- customer name ?
$custId = $_POST['customer_id'] == 'Y' ? TRUE : FALSE;

//--- customer group ?
$group = $_POST['customer_group'] == 'Y' ? TRUE : FALSE;

//--- customer type ?
$type = $_POST['customer_type'] == 'Y' ? TRUE : FALSE;

//--- customer kind ?
$kind = $_POST['customer_kind'] == 'Y' ? TRUE : FALSE;

//--- customer area ?
$area = $_POST['customer_area'] == 'Y' ? TRUE : FALSE;

//--- customer class ?
$class = $_POST['customer_class'] == 'Y' ? TRUE : FALSE;

if($all === TRUE)
{
  include 'rule/set_all_customer.php';
  exit;
}

if($all === FALSE)
{
  //--- เปลี่ยนเงื่อนไข set all_customer = 0
  if(setAllCustomer($id_rule, 0) !== TRUE)
  {
    echo 'ยกเลิกเงื่อนไขลูกค้าทั้งหมดไม่สำเร็จ';
    exit;
  }


  //--- กรณีระบุชื่อลูกค้า
  if($custId === TRUE)
  {
    include 'rule/set_customer_list.php';
    exit;
  }

  //--- กรณีไม่ระบุชื่อลูกค้า
  if($custId === FALSE)
  {
    //--- ลบรายชื่อลูกค้าออกก่อน
    if(dropCustomerListRule($id_rule) == FALSE)
    {
      echo 'ยกเลิกรายชื่อลูกค้าไม่สำเร็จ';
      exit;
    }

    
    //---- Set customer group
    if($group === TRUE)
    {
      include 'rule/set_customer_group.php';
    }

    //--- set customer type
    if($type === TRUE)
    {
      include 'rule/set_customer_type.php';
    }

    //--- set customer kind
    if($kind === TRUE)
    {
      include 'rule/set_customer_kind.php';
    }

    //--- set customer area
    if($area === TRUE)
    {
      include 'rule/set_customer_area.php';
    }

    //--- set customer class
    if($class === TRUE)
    {
      include 'rule/set_customer_class.php';
    }
  } //--- end if custId == false
} //--- end if $all === false

echo $sc === TRUE ? 'success' : $message;



 ?>
