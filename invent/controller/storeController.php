<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if(isset($_GET['deleteBuffer']))
{

	$sc = TRUE;

	$id = $_POST['id_buffer'];
	$buffer = new buffer($id);
	$prepare = new prepare();
	$order = new order();

	if($buffer->id_order != '' && $buffer->id_product != '' && $buffer->id_zone != '')
	{
		startTransection();
		$stock = new stock();

		if($stock->updateStockZone($buffer->id_zone, $buffer->id_product, $buffer->qty) !== TRUE)
		{
			$sc = FALSE;
			$message = 'ย้ายสินค้ากลับโซนไม่สำเร็จ';
		}

		if($prepare->deletePrepared($buffer->id_order, $buffer->id_product, $buffer->id_zone) !== TRUE)
		{
			$sc = FALSE;
			$message = 'ลบรายการจัดสินค้าไม่สำเร็จ';
		}

		if($order->unValidDetail($buffer->id_order, $buffer->id_product) !== TRUE)
		{
			$sc = FALSE;
			$message = 'เปลี่ยนสถานะรายการสั่งซื้อไม่สำเร็จ';
		}

		if($buffer->delete($id) !== TRUE)
		{
			$sc = FALSE;
			$message = 'ลบ Buffer ไม่สำเร็จ';
		}



		if($sc === TRUE)
		{
			commitTransection();
		}
		else
		{
			dbRollback();
		}

		endTransection();
	}
	else
	{
		$sc = FALSE;
		$message = 'ไม่พบข้อมูล หรือ Buffer อาจถูกลบไปแล้ว';
	}

	echo $sc === TRUE ? 'success' : $message;

}




//----- ย้ายสินค้าจาก Cancle zone กลับโซนเดิม
if(isset($_GET['deleteCancle']))
{

	$sc = TRUE;

	$id = $_POST['id_cancle'];
	$cancle = new cancle_zone($id);

	if($cancle->id_order != '' && $cancle->id_product != '' && $cancle->id_zone != '')
	{
		startTransection();
		$stock = new stock();

		if($stock->updateStockZone($cancle->id_zone, $cancle->id_product, $cancle->qty) !== TRUE)
		{
			$sc = FALSE;
			$message = 'ย้ายสินค้ากลับโซนไม่สำเร็จ';
		}

		if($cancle->delete($id) !== TRUE)
		{
			$sc = FALSE;
			$message = 'ลบ Cancle ไม่สำเร็จ';
		}

		if($sc === TRUE)
		{
			commitTransection();
		}
		else
		{
			dbRollback();
		}

		endTransection();
	}
	else
	{
		$sc = FALSE;
		$message = 'ไม่พบข้อมูล หรือ Cancle อาจถูกลบไปแล้ว';
	}

	echo $sc === TRUE ? 'success' : $message;

}




if(isset($_GET['clearFilter']) && isset($_GET['stock_movement']))
{
	deleteCookie('reference');
	deleteCookie('pdCode');
	deleteCookie('zoneCode');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}


if(isset($_GET['clearFilter']) && isset($_GET['buffer_zone']))
{
	deleteCookie('reference');
	deleteCookie('pdCode');
	deleteCookie('zoneCode');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}



if(isset($_GET['clearFilter']) && isset($_GET['cancle_zone']))
{
	deleteCookie('reference');
	deleteCookie('pdCode');
	deleteCookie('zoneCode');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}



?>
