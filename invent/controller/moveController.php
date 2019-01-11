<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มเอกสารโอนคลังใหม่
if( isset( $_GET['addNew']))
{
	include 'move/add_new.php';
}



//---- แสดงตารางโอนย้ายสินค้า
if( isset( $_GET['getMoveTable'] ) )
{
	include 'move/move_table.php';
}



//---	Update เอกสาร
if( isset( $_GET['update']))
{
	include 'move/update_move.php';
}






//---	auto complete zone
if( isset( $_GET['getMoveZone']))
{
	$sc   = array();
	$zone = new zone();
	$id_warehouse = $_GET['id_warehouse'];
	$qs   = $zone->searchWarehouseZone($_REQUEST['term'], $id_warehouse);
	if( dbNumRows($qs) > 0)
	{
		while( $rs = dbFetchObject($qs))
		{
			$sc[] = $rs->zone_name.' | '.$rs->id_zone;
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
	include 'move/product_in_zone.php';
}




if( isset( $_GET['getProductInCancle'] ) )
{
	include 'move/product_in_cancle.php';
}




//---	ย้ายสินค้าออกจากโซน
//--- เพิ่มรายการลงใน move detail
//---	เพิ่มลงใน move_temp
//---	update stock ตามรายการที่ใส่ตัวเลข
if( isset( $_GET['addToMove'] ) )
{
	include 'move/add_detail.php';
}



//---	ย้ายสินค้าออกจาก tbl_cancle
//--- เพิ่มรายการลงใน move detail
//---	เพิ่มลงใน move_temp
//---	update cancle zone ตามรายการที่ใส่ตัวเลข
if( isset( $_GET['addCancleToMove'] ) )
{
	include 'move/add_cancle_detail.php';
}






//--------- เพิ่มสินค้าทั้งหมดในโซนเข้าเอกสาร แล้ว ย้ายสินค้าทั้งหมดในโซนเข้า temp
if( isset( $_GET['addAllToMove'] ) )
{
	include 'move/add_all.php';
}






//---- เพิ่มรายการโอนด้วยบาร์โค้ด
if( isset( $_GET['addBarcodeToMove'] ) )
{
	include 'move/add_by_barcode.php';
}



//---	ย้ายสินค้าเข้าโซนปลายทาง ทีละรายการ
//---	ลบรายการออกจาก temp-table
//---	เพิ่มรายยอดสินค้าเข้าโซนปลายทาง
if( isset( $_GET['moveToZone'] ) )
{
	include 'move/move_to_zone.php';
}





//---	ย้ายสินค้าเข้าโซนปลายทาง ทั้งหมด
//---	ลบรายการออกจาก temp-table
//---	เพิ่มรายยอดสินค้าเข้าโซนปลายทาง
if( isset( $_GET['moveAllToZone'] ) )
{
	include 'move/move_all_to_zone.php';
}



//---	ย้ายสินค้าเข้าโซนปลายทาง ทีละรายการ โดยใช้การยิงบาร์โค้ด
//---	ลบรายการออกจาก temp-table
//---	เพิ่มรายยอดสินค้าเข้าโซนปลายทาง
if( isset( $_GET['moveBarcodeToZone'] ) )
{
	include 'move/move_by_barcode.php';
}




if( isset($_GET['getMovedQty']))
{
	$cs = new move();

	//---	ดึงข้อมูลรายการโอน
	$rs = $cs->getDetail($_POST['id_move_detail']);

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



if( isset( $_GET['getWarehouse']) )
{
	$sc = array();
	$wh = new warehouse();
	$qs = $wh->searchWarehouse(trim($_REQUEST['term']));
	if( dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[] = $rs->code.' | '.$rs->name.' | '.$rs->id;
		}
	}
	else
	{
		$sc[] = 'ไม่พบคลัง';
	}

	echo json_encode($sc);
}



//---	แสดงตารางสินค้าใน temp
if( isset( $_GET['getTempTable'] ) )
{
	include 'move/temp_table.php';
}


//--- บันทึกเอกสาร
//---	ส่งข้อมูลออกไป formula
if( isset( $_GET['saveMove'] ))
{
	$sc = TRUE;
	$id = $_POST['id_move'];
	$cs = new move();

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




if(isset($_GET['unSaveMove']))
{
	$id = $_POST['id_move'];
	$cs = new move();
	$sc = TRUE;
	if($cs->unSave($id) !== TRUE)
	{
		$sc = FALSE;
		$message = 'เปลี่ยนสถานะไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



if( isset( $_GET['deleteMove'] ) )
{
	include 'move/delete_move.php';
}



if( isset( $_GET['deleteMoveDetail'] ) )
{
	include 'move/delete_detail.php';
}



if( isset( $_GET['isHasDetail']))
{
	$sc = 'no_detail';
	$id = $_GET['id_move'];
	$cs = new move();
	if( $cs->hasDetail($id) === TRUE)
	{
		$sc = 'has_detail';
	}

	echo $sc;
}


//---	clear search filter
if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sMoveCode');
	deleteCookie('sEmp');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	deleteCookie('sStatus');
	echo 'done';
}
?>
