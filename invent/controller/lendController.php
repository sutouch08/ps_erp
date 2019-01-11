<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require "../function/lend_helper.php";


//--- ค้นหาโซนที่อยู่ในคลังระหว่างทำเท่านั้น
if( isset( $_GET['getZone']))
{
  $sc = array();
  $zone = new zone();
  $qs = $zone->searchLendZone(trim($_REQUEST['term']));
  if( dbNumRows($qs) > 0 )
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->zone_name.' | '.$rs->id_zone;
    }
  }
  else
  {
    $sc[] = 'ไม่พบรายการ';
  }

  echo json_encode($sc);
}







//--- ค้นหาพนักงาน
if( isset( $_GET['getEmployee']))
{
  $sc = array();
  $emp = new employee();
  $qs = $emp->search('id_employee, first_name, last_name', trim($_REQUEST['term']));
  if( dbNumRows($qs) > 0)
  {
    while( $rs = dbFetchObject($qs))
    {
      $sc[] = $rs->first_name.' '.$rs->last_name.' | '. $rs->id_employee;
    }
  }
  else
  {
    $sc[] = 'ไม่พบรายการ';
  }

  echo json_encode($sc);
}



if( isset($_GET['getDetailTable']))
{
  include 'lend/detail_table.php';
}



if( isset($_GET['getProduct']))
{
  $sc = array();
  $pd = new product();
  $qs = $pd->search(trim($_REQUEST['term']), 'id, code');
  if( dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->id;
    }
  }
  else
  {
    $sc[] = 'ไม่พบรายการ';
  }

  echo json_encode($sc);
}
 ?>
