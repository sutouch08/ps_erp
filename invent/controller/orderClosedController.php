<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


//---	พิมพ์ใบนำส่งสำหรับแปะหน้ากล่องส่งไปรษณีย์
if( isset( $_GET['printAddressSheet']))
{
	include '../function/transport_helper.php';
	include '../print/address/print_address_sheet.php';
}




//----------------  พิมพ์ใบปะหน้ากล่อง ขาย Online  ---------------------//
if( isset( $_GET['printOnlineAddressSheet'] ) && isset( $_GET['id_address'] ) )
{
	include '../print/address/print_address_online_sheet.php';
}





//---	พิมพ์ packing list แบบไม่มีบาร์โค้ด
if( isset( $_GET['printOrder']))
{
	include '../print/order/print_order.php';
}



//---	พิมพ์ packing list แบบมีบาร์โค้ด
if( isset( $_GET['printOrderBarcode']))
{
	include '../print/order/print_order_barcode.php';
}


if( isset( $_GET['getOnlineAddress'] ) )
{

	$code 	= $_GET['online_code'];
	$online = new online_address();
	$id 		= $online->getDefaultId($code);

	echo $id == '' ? 'noaddress' : $id;
}






if( isset( $_GET['clearFilter']))
{
	deleteCookie('sOrderCode');
	deleteCookie('sOrderCustomer');
	deleteCookie('sOrderEmployee');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	deleteCookie('viewDate');
	deleteCookie('sOrder');
	deleteCookie('sConsign');
	deleteCookie('sSupport');
	deleteCookie('sSponsor');
	deleteCookie('sTransform');
	deleteCookie('sLend');
	deleteCookie('sRequisition');
	deleteCookie('sOnline');
	deleteCookie('sBranch');
	deleteCookie('sDelivered');
	deleteCookie('sNotDelivery');
	echo 'done';
}



?>
