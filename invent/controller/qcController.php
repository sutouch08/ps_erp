<?php
include "../../library/config.php";
include "../../library/functions.php";
include "../function/tools.php";
include "../function/qc_helper.php";

if( isset( $_GET['closeOrder'])){
	$order = new order($_POST['id_order']);
	if( $order->state == 6){
		$sc = $order->stateChange($order->id, 7);
	}else {
		$sc = 'ไม่สามารถปิดออเดอร์ได้ เนื่องจากสถานะออเดอร์ได้ถูกเปลี่ยนไปแล้ว';
	}

	echo $sc === TRUE ? 'success' : ($sc === FALSE ? 'ปิดออเดอร์ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง' : $sc);
}



//----	บันทึกรายการที่ตรวจ
if( isset( $_GET['saveQc']))
{
	include 'qc/qc_save_qc.php';
}




if( isset( $_GET['getBox']))
{
	$box = new box();
	$id_box = $box->getBox($_GET['barcode'], $_GET['id_order']);
	echo $id_box === FALSE ? 'ไม่พบกล่อง' : $id_box;
}





if( isset( $_GET['getBoxList']))
{
	include 'qc/qc_box_list.php';
}



if(isset($_GET['getCheckedTable']))
{
	include 'qc/qc_checked_table.php';
}



if(isset($_GET['decreaseCheckedQty']))
{
	$sc = TRUE;
	$id_qc = $_POST['id_qc'];
	$remove_qty = $_POST['remove_qty'];

	$qr = dbQuery("SELECT qty FROM tbl_qc WHERE id = '".$id_qc."'");
	if(dbNumRows($qr) == 1 )
	{
		list($qty) = dbFetchArray($qr);
		if($remove_qty <= $qty)
		{
			if($remove_qty == $qty)
			{
				$qs = dbQuery("DELETE FROM tbl_qc WHERE id = ".$id_qc);
			}
			else
			{
				$qs = dbQuery("UPDATE tbl_qc SET qty = qty - ".$remove_qty." WHERE id = ".$id_qc);
			}

			if(!$qs)
			{
				$sc = FALSE;
				$message = 'ลบยอดตรวจนับไม่สำเร็จ';
			}
		}
		else
		{
			$sc = FALSE;
			$message = 'ยอดที่เอาออกต้องไม่มากกว่ายอดตรวจนับ';
		}
	}
	else
	{
		$sc = FALSE;
		$message = 'ไม่พบรายการตรวจนับ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



if( isset( $_GET['printBox']))
{
	include '../print/packing/print_box.php';
}


if(isset($_GET['clearFilter']))
{
	deleteCookie('sOrderCode');
	deleteCookie('sOrderCus');
	deleteCookie('sOrderEmp');
	deleteCookie('sBranch');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}

?>
