<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';


if( isset($_GET['addNew']) )
{
  include 'adjust/adjust_add_new.php';
}



if( isset( $_GET['update']))
{
  include 'adjust/adjust_update.php';
}


if( isset( $_GET['addDetail']))
{
  include 'adjust/adjust_add_detail.php';
}


if( isset( $_GET['deleteDetail']))
{
  include 'adjust/adjust_delete_detail.php';
}


if( isset($_GET['deleteAdjust']))
{
  include 'adjust/adjust_delete.php';
}





//--- ปรับยอดในโซน ตามรายการที่คีย์
//--- บันทึก dropMovement
//--- บันทึกการปรับยอดในตารางรายการ tbl_adjust_detail.valid = 1
//--- บันทึกการปรับยอดที่เอกสาร tbl_adjust.isSaved = 1
if( isset($_GET['saveAdjust']))
{
  include 'adjust/adjust_save.php';
}



if( isset( $_GET['getZone']))
{
  $sc   = array();
  $zone = new zone();
  $qs   = $zone->search(trim($_REQUEST['term']));
  if( dbNumRows($qs) > 0)
  {
    while( $rs = dbFetchObject($qs))
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



if( isset($_GET['getProduct']))
{
  $sc = array();
  $pd = new product();
  $qs = $pd->search(trim($_REQUEST['term']), 'id, code');
  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->id;
    }
  }
  else
  {
    $sc[] = 'ไม่พบสินค้า';
  }

  echo json_encode($sc);
}



if( isset($_GET['clearFilter']))
{
  deleteCookie('sAdjustCode');
  deleteCookie('sAdjustRefer');
  deleteCookie('sEmp');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}

 ?>
