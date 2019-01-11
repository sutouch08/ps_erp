<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require "../function/bill_helper.php";
//require "../function/support_helper.php";
//require "../function/sponsor_helper.php";
//require "../function/lend_helper.php";
include '../function/vat_helper.php';

if(isset( $_GET['checkState']))
{
	$order = new order();
	$state = $order->getState($_GET['id_order']);
	if( $state != 7)
	{
		echo 'state changed';
	}
}



if( isset( $_GET['confirmOrder']))
{
	$sc = TRUE;	//---
	$order = new order($_POST['id_order']);

	if( $order->state == 7)
	{
		//---	สำหรับจัดการต้นทุนสินค้า (tbl_product_cost)
		$pdCost = new product_cost();

		//---	ดำเนินการตามเงื่อนไขต่างๆ
		switch($order->role)
		{
			//---	ถ้าเป็นการขาย หรือ ขาย ออนไลน์
			case 1 :
				include 'bill/order_process.php';
			break;

				//---	ถ้าเป็นการฝากขาย
			case 2 :
				include 'bill/consign_process.php';
			break;

			//---	ถ้าเป็นการเบิกอภินันท์
			case 3 :
				include 'bill/support_process.php';
			break;

			//---	ถ้าเป็นการเบิกสปอนเซอร์
			case 4 :
				include 'bill/sponsor_process.php';
			break;

			//---	ถ้าเป็นการเบิกแปรสภาพสินค้า
			case 5 :
				include 'bill/transform_process.php';
			break;

			//---	ถ้าเป็นการยืมสินค้า
			case 6 :
				include 'bill/lend_process.php';
			break;

			//---	ถ้าเป็นการเบิกใช้งานอื่นๆ
			case 7 :
				include 'bill/requisition_process.php';
			break;

			default :
				include 'bill/order_process.php';
			break;
		}
	}
	else
	{
		$sc = FALSE;
		$message = 'เปิดบิลไม่สำเร็จ เนื่องจากสถานะออเดอร์ถูกเปลี่ยนไปแล้ว';
	}

	echo $sc === TRUE ? 'success' : $message;

}





if( isset( $_GET['clearFilter']))
{
	deleteCookie('sOrderCode');
	deleteCookie('sCustomerName');
	deleteCookie('sOrderEmp');
	deleteCookie('sOrderRole');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	deleteCookie('sBranch');
	echo 'done';
}



?>
