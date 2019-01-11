<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../function/print_helper.php';




if(isset($_GET['printOrderSheet']))
{
  include '../function/order_helper.php';
  include '../function/customer_helper.php';

  include '../print/order/print_order_sheet.php';
}


//--- ใบปะหน้ากล่อง
if(isset($_GET['printConsignBox']))
{
  include '../print/packing/print_consign_box.php';
}


//--- ตัดยอดฝากขาย
if(isset($_GET['printConsign']))
{
  include '../print/consign/print_consign.php';
}



//--- ส่งคืนสินค้า (ลดหนี้ซื้อ)
if(isset($_GET['printReturnReceived']))
{
  include '../print/return_received/print_return_received.php';
}



//--- คืนสินค้าจากการยืม
if(isset($_GET['printReturnLend']))
{
  include '../print/return_lend/print_return_lend.php';
}


 ?>
