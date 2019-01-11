<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';


//--- รายงานวิเคราะห์ขายแบบละเอียด
if(isset($_GET['sale_deep_analyz']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_deep_analyz.php';
}


//--- รายงานยอดขายแยกตามช่องทางการขายแสดงเลขที่เอกสาร
if(isset($_GET['saleByChannelsAndReference']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sale_by_channels_show_reference.php';
}


if(isset($_GET['saleByChannelsAndReference']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_by_channels_show_reference.php';
}


//--- รายงานยอดขายออนไลน์
if(isset($_GET['saleOnlineByChannelsAndRefCode']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sale_online_channels_details.php';
}


if(isset($_GET['saleOnlineByChannelsAndRefCode']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_online_channels_details.php';
}




if(isset($_GET['saleByCustomerOrder']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sale_by_customer_order.php';
}


if(isset($_GET['saleByCustomerOrder']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_by_customer_order.php';
}



if(isset($_GET['saleByCustomerItems']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sale_by_customer_items.php';
}


if(isset($_GET['saleByCustomerItems']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_by_customer_items.php';
}


//--- รายงานสรุปยอดสปอนเซอร์
if(isset($_GET['sponsorByBudget']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sponsor_by_budget.php';
}


if(isset($_GET['sponsorByBudget']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sponsor_by_budget.php';
}


if(isset($_GET['sponsorByCustomerOrder']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sponsor_by_customer_order.php';
}


if(isset($_GET['sponsorByCustomerOrder']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sponsor_by_customer_order.php';
}


if(isset($_GET['sponsorByCustomerItems']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sponsor_by_customer_items.php';
}


if(isset($_GET['sponsorByCustomerItems']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sponsor_by_customer_items.php';
}



//---- รายงานสรุปยอดอภินันท์
if(isset($_GET['supportByBudget']) && isset($_GET['report']))
{
  include 'report/saleReport/report_support_by_budget.php';
}


if(isset($_GET['supportByBudget']) && isset($_GET['export']))
{
  include 'report/saleReport/export_support_by_budget.php';
}


if(isset($_GET['supportByCustomerOrder']) && isset($_GET['report']))
{
  include 'report/saleReport/report_support_by_customer_order.php';
}


if(isset($_GET['supportByCustomerOrder']) && isset($_GET['export']))
{
  include 'report/saleReport/export_support_by_customer_order.php';
}


if(isset($_GET['supportByCustomerItems']) && isset($_GET['report']))
{
  include 'report/saleReport/report_support_by_customer_items.php';
}


if(isset($_GET['supportByCustomerItems']) && isset($_GET['export']))
{
  include 'report/saleReport/export_support_by_customer_items.php';
}




 ?>
