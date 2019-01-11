<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';

//--- บันทึกเอกสาร
if( isset($_GET['saveReturn']))
{
  include 'return_order/return_order_save.php';
}



//--- ลบเอกสารลดหนี้
if( isset($_GET['deleteReturn']))
{
  include 'return_order/return_order_delete.php';
}



//--- ปิดเอกสารโดยไม่รับสินค้า
if(isset($_GET['setValid']))
{
  $reference = $_POST['reference'];
  $cs = new return_order();
  $sc = $cs->setValid($reference, 1);

  echo $sc === TRUE ? 'success' : 'เปลี่ยนสถานะไม่สำเร็จ';
}



//--- ยกเลิกการปิดเอกสารโดยไม่รับเข้า
if(isset($_GET['disValid']))
{
  $reference = $_POST['reference'];
  $cs = new return_order();
  $sc = $cs->setValid($reference, 0);

  echo $sc === TRUE ? 'success' : 'เปลี่ยนสถานะไม่สำเร็จ';
}



//--- auto complete zone name
if( isset( $_GET['getZone']) )
{
  $sc = array();
  $id_warehouse = $_GET['id_warehouse'];
  $zone = new zone();
  $qs = $zone->searchWarehouseZone(trim($_REQUEST['term']), $id_warehouse);
  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->zone_name.' | '.$rs->id_zone;
    }
  }
  else
  {
    $sc[] = 'ไม่พบโซน';
  }

  echo json_encode($sc);
}



//--- บันทึกโซนรับเข้า (ทั้งเอกสาร)
if( isset( $_GET['setZone'] ) )
{
  $sc = TRUE;
  $reference = $_POST['reference'];
  $id_zone = $_POST['id_zone'];
  $cs = new return_order();

  if( $cs->setZone($reference, $id_zone) === FALSE )
  {
    $sc = FALSE;
    $message = 'เปลี่ยนโซนไม่สำเร็จ';
  }

  echo $sc === TRUE ? 'success' : $message;

}



if( isset($_GET['clearFilter']))
{
  deleteCookie('SMsCode');
  deleteCookie('SMsInv');
  deleteCookie('SMsCus');
  deleteCookie('Return');
  deleteCookie('notReturn');
  deleteCookie('Valid');
  deleteCookie('notValid');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}

 ?>
