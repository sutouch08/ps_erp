<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if( isset( $_GET['deleteGroup'] ) )
{
	$sc = "success";
	$id = $_POST['id'];
	$sp = new supplier_group();
	$code = $sp->getGroupCode($id);
	if( $code !== FALSE )
	{
		if( $sp->delete($id) === FALSE )
		{
			$sc = "ลบกลุ่มไม่สำเร็จ";	
		}
	}
	else
	{
		$sc = "ไม่พบกลุ่มที่ต้องการลบ";	
	}
	echo $sc;	
}




if( isset( $_GET['deleteSupplier'] ) )
{
	$sc = 'success';
	$id = $_POST['id'];
	$emp	= getCookie('user_id');
	$sp = new supplier();
	$rs = $sp->delete($id, $emp);
	if( $rs === FALSE )
	{
		$sc = 'ลบผู้ขายไม่สำเร็จ';
	}
	echo $sc;
}



if( isset( $_GET['unDelete'] ) )
{
	$sc = "success";
	$id = $_POST['id'];
	$emp = getCookie('user_id');
	$sp = new supplier();
	if( $sp->unDelete($id, $emp) === FALSE )
	{
		$sc = "ยกเลิกการลบไม่สำเร็จ";
	}
	echo $sc;
}




if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('spCode');
	deleteCookie('spName');
	deleteCookie('spGroup');
	echo "success";	
}



?>
