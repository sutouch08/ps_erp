<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';

if(isset($_GET['poBacklogs']) && isset($_GET['report']))
{
  include 'report/purchase/report_po_backlogs.php';
}


if(isset($_GET['poBacklogs']) && isset($_GET['export']))
{
  if($_GET['showItem'] == 0)
  {
    include 'report/purchase/export_po_backlogs_by_style.php';
  }
  else
  {
    include 'report/purchase/export_po_backlogs_by_item.php';
  }

}


//--- รายงานประวัติการสั่งซื้อ แยกตามรุ่นสินค้า
if(isset($_GET['poHistoryByProduct']) && isset($_GET['report']))
{
  include 'report/purchase/report_po_history_by_product.php';
}


if(isset($_GET['getPoList']))
{
  include 'report/purchase/report_get_po_list.php';
}


//--- รายงานประวัติการสั่งซื้อ แยกตามรุ่นสินค้า
if(isset($_GET['poHistoryByProduct']) && isset($_GET['export']))
{
  include 'report/purchase/export_po_history_by_product.php';
}



//---- รายงานรายละเอียดใบสั่งซื้อ
if(isset($_GET['getPoDetail']) && isset($_GET['report']))
{
  include 'report/purchase/report_po_detail.php';
}


if(isset($_GET['getPoDetail']) && isset($_GET['export']))
{
  include 'report/purchase/export_po_detail.php';
}




 ?>
