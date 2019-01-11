<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มเอกสารโอนคลังใหม่
if( isset( $_GET['addNew']))
{
	include 'transfer/add_new.php';
}



//---- แสดงตารางโอนย้ายสินค้า
if( isset( $_GET['getTransferTable'] ) )
{
	include 'transfer/transfer_table.php';
}



//---	Update เอกสาร
if( isset( $_GET['update'])){

	$sc = 'success';
	$cs = new transfer();
	$arr = array(
		'date_add' => dbDate($_POST['date_add'], TRUE),
		'from_warehouse'	=> $_POST['from_warehouse'],
		'to_warehouse'	=> $_POST['to_warehouse'],
		'remark' 	=> $_POST['remark']
	);

	$rs = $cs->update($_POST['id_transfer'], $arr);

	if( $rs === FALSE )
	{
		$sc = 'แก้ไขข้อมูลไม่สำเร็จ';
	}

	echo $sc;
}






//---	auto complete zone
if( isset( $_GET['getTransferZone']))
{
	$sc   = array();
	$zone = new zone();
	$qs   = $zone->searchWarehouseZone($_REQUEST['term'], $_GET['id_warehouse']);
	if( dbNumRows($qs) > 0)
	{
		while( $rs = dbFetchObject($qs))
		{
			$sc[] = $rs->zone_name.' | '.$rs->id_zone.' | '.$rs->allow_under_zero;
		}
	}
	else
	{
		$sc[] = 'ไม่พบโซน';
	}

	echo json_encode($sc);
}




//---	แสดงสินค้าที่อยู่ในโซน
if( isset( $_GET['getProductInZone'] ) )
{
	include 'transfer/product_in_zone.php';
}




//---	ย้ายสินค้าออกจากโซน
//--- เพิ่มรายการลงใน transfer detail
//---	เพิ่มลงใน transfer_temp
//---	update stock ตามรายการที่ใส่ตัวเลข
if( isset( $_GET['addToTransfer'] ) )
{
	include 'transfer/add_detail.php';
}






//--------- เพิ่มสินค้าทั้งหมดในโซนเข้าเอกสาร แล้ว ย้ายสินค้าทั้งหมดในโซนเข้า temp
if( isset( $_GET['addAllToTransfer'] ) )
{
	include 'transfer/add_all.php';
}






//---- เพิ่มรายการโอนด้วยบาร์โค้ด
if( isset( $_GET['addBarcodeToTransfer'] ) )
{
	include 'transfer/add_by_barcode.php';
}



//---	ย้ายสินค้าเข้าโซนปลายทาง ทีละรายการ
//---	ลบรายการออกจาก temp-table
//---	เพิ่มรายยอดสินค้าเข้าโซนปลายทาง
if( isset( $_GET['moveToZone'] ) )
{
	include 'transfer/move_to_zone.php';
}





//---	ย้ายสินค้าเข้าโซนปลายทาง ทั้งหมด
//---	ลบรายการออกจาก temp-table
//---	เพิ่มรายยอดสินค้าเข้าโซนปลายทาง
if( isset( $_GET['moveAllToZone'] ) )
{
	include 'transfer/move_all_to_zone.php';
}



//---	ย้ายสินค้าเข้าโซนปลายทาง ทีละรายการ โดยใช้การยิงบาร์โค้ด
//---	ลบรายการออกจาก temp-table
//---	เพิ่มรายยอดสินค้าเข้าโซนปลายทาง
if( isset( $_GET['moveBarcodeToZone'] ) )
{
	include 'transfer/move_by_barcode.php';
}




if( isset($_GET['getMovedQty']))
{
	$cs = new transfer();

	//---	ดึงข้อมูลรายการโอน
	$rs = $cs->getDetail($_POST['id_transfer_detail']);

	//---	ดึงยอดใน temp ถ้าไม่มีแสดงว่าโอนเข้าปลายทางหมดแล้ว
	$tmp_qty = $cs->getTempQty($rs->id);

	echo $rs->qty.' / '. ($rs->qty - $tmp_qty);
}

//---	ยิงบาร์โค้ดโซน
//---	ดึงข้อมูลโซนตามบาร์โค้ดโซนที่ยิงมา
if( isset( $_GET['getZone'] ) )
{
	$barcodeZone = $_GET['txt'];
	$id_wh = $_GET['id_warehouse'];
	$zone  = new zone();

	$rs   = $zone->getZoneDetailByBarcode($barcodeZone, $id_wh);

	if( $rs !== FALSE )
	{
		$sc = array(
			'id_zone' => $rs->id_zone,
			'zone_name' => $rs->zone_name,
			'isAllowUnderZero' => $rs->allow_under_zero
		);

		echo json_encode($sc);
	}
	else
	{
		echo 'ไม่พบโซน';
	}

}



//---	แสดงตารางสินค้าใน temp
if( isset( $_GET['getTempTable'] ) )
{
	include 'transfer/temp_table.php';
}


//--- บันทึกเอกสาร
//---	ส่งข้อมูลออกไป formula
if( isset( $_GET['saveTransfer'] ))
{
	$sc = TRUE;
	$id = $_POST['id_transfer'];
	$cs = new transfer();

	//---	ตรวจสอบรายการที่ยังไม่สมบูรณ์

	if( $cs->isCompleted($id) === TRUE )
	{
		//---	บันทึกเอกสาร
		if( $cs->save($id) !== TRUE )
		{
			$sc = FALSE;
			$message = 'บันทึกเอกสารไม่สำเร็จ';
		}
	}
	else
	{
		$sc = FALSE;
		$message = 'พบรายการที่ยังไม่สมบูรณ์';
	}

	echo $sc === TRUE ? 'success' : $message;
}




if( isset( $_GET['deleteTransfer'] ) )
{
	include 'transfer/delete_transfer.php';
}



if( isset( $_GET['deletetransferDetail'] ) )
{
	include 'transfer/delete_detail.php';
}



if( isset( $_GET['isHasDetail']))
{
	$sc = 'no_detail';
	$id = $_GET['id_transfer'];
	$cs = new transfer();
	if( $cs->hasDetail($id) === TRUE)
	{
		$sc = 'has_detail';
	}

	echo $sc;
}


//---	clear search filter
if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sTransferCode');
	deleteCookie('sEmp');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	deleteCookie('sStatus');
	echo 'done';
}
?>
