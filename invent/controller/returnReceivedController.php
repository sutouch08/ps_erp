<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';

//--- บันทึกเอกสาร
if( isset($_GET['saveReturn']))
{
  include 'return_received/return_received_save.php';
}



//--- ลบเอกสารลดหนี้
if( isset($_GET['deleteReturn']))
{
  include 'return_received/return_received_delete.php';
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
  $cs = new return_received();

  if( $cs->setZone($reference, $id_zone) === FALSE )
  {
    $sc = FALSE;
    $message = 'เปลี่ยนโซนไม่สำเร็จ';
  }

  echo $sc === TRUE ? 'success' : $message;

}



if( isset($_GET['clearFilter']))
{
  deleteCookie('BMCode');
  deleteCookie('BMInv');
  deleteCookie('sSup');
  deleteCookie('Valid');
  deleteCookie('notValid');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}

 ?>
