<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//--- ดึงข้อมูลรหัสและชื่อลูกค้า
if(isset($_GET['getCustomerCodeAndName']))
{
	include 'autocomplete/get_customer_code_and_name.php';
}

if(isset($_GET['getCustomerIdCodeAndName']))
{
	include 'autocomplete/get_customer_id_code_and_name.php';
}

//-- ดึงรายชื่อสปอนเซอร์
if(isset($_GET['getSponsorCodeAndName']))
{
	include 'autocomplete/get_sponsor_code_and_name.php';
}


//--- ดึงรายชื่ออภินันท์
if(isset($_GET['getSupportCodeAndName']))
{
	include 'autocomplete/get_support_code_and_name.php';
}

//---	รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getStyleCode']))
{
	include 'autocomplete/get_style_code.php';
}




if(isset($_GET['getStyleCodeAndId']))
{
	include 'autocomplete/get_style_code_id.php';
}




if(isset($_GET['getItemCode']))
{
	include 'autocomplete/get_item_code.php';
}



if(isset($_GET['getItemCodeAndId']))
{
	include 'autocomplete/get_item_code_id.php';
}




//---	รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getWarehouse']))
{
	include 'autocomplete/get_warehouse.php';
}




if(isset($_GET['getWarehouseCode']))
{
	include 'autocomplete/get_warehouse_code.php';
}




//---	รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getZone']))
{
	include 'autocomplete/get_zone.php';
}



//---	รายงานใบสั่งซื้อค้างรับ
if(isset($_GET['getSupplier']))
{
	include 'autocomplete/get_supplier.php';
}



//---- รายงานรายละเอียดใบสั่งซื้อ
if(isset($_GET['getSupplierCodeAndName']))
{
	include 'autocomplete/get_supplier_code_and_name.php';
}


//---
if(isset($_GET['getEmployeeIdAndName']))
{
	include 'autocomplete/get_employee_id_and_name.php';
}

?>
