<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require '../function/warehouse_helper.php';

if( isset( $_GET['deleteWarehouse'] ) && isset( $_POST['id_warehouse'] ) )
{
	$sc = 'success';
	$id_warehouse	= $_POST['id_warehouse'];
	$warehouse = new warehouse();
	if( $warehouse->deleteWarehouse($id_warehouse) === FALSE )
	{
		$sc = 'ข้อผิดพลาด ! | ลบคลังสินค้าไม่สำเร็จ หรือ คลังสินค้าไม่ว่าง';
	}
	echo $sc;
}



if( isset( $_POST['addNew'] ) && isset( $_POST['whCode'] ) )
{
	$sc = 'success';
	$ds	= array(
						'code'	 	=> $_POST['whCode'],
						'warehouse_name'		=> $_POST['whName'],
						'role'		=> $_POST['whRole'],
						'sell'		=> $_POST['sell'],
						'prepare'	=> $_POST['prepare'],
						'allow_under_zero'		=> $_POST['underZero'],
						'active'	=> $_POST['active']
						);
	$warehouse		= new warehouse();
	$rs 	= $warehouse->add($ds);
	if( $rs === FALSE )
	{
		$sc = 'เพิ่มคลังสินค้าไม่สำเร็จ';
	}
	echo $sc;
}


if( isset( $_POST['editWarehouse'] ) && isset( $_POST['id_warehouse'] ) )
{
	$sc = 'success';
	$id_warehouse	= $_POST['id_warehouse'];
	$ds	= array(
						'role'		=> $_POST['whRole'],
						'sell'		=> $_POST['sell'],
						'prepare'	=> $_POST['prepare'],
						'allow_under_zero'		=> $_POST['underZero'],
						'active'	=> $_POST['active'],
						'last_upd' => date('Y-m-d H:i:s'),
						'emp_upd' => getCookie('user_id'),
						'id_branch' => $_POST['id_branch']
						);
	$warehouse		= new warehouse();
	$rs 	= $warehouse->update($id_warehouse, $ds);
	if( $rs === FALSE )
	{
		$sc = 'ปรับปรุงข้อมูลไม่สำเร็จ';
	}
	echo $sc;
}


if( isset( $_POST['checkCode'] ) && isset( $_POST['whCode'] ) )
{
	$whCode = $_POST['whCode'];
	$id			= $_POST['id_warehouse']; /// May be blank
	$sc		= 'success';
	$rs 		= isExistsWarehouseCode($whCode, $id);
	if( $rs === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}

if( isset( $_POST['checkName'] ) && isset( $_POST['whName'] ) )
{
	$sc 			= 'success';
	$whName 	= $_POST['whName'];
	$id				= $_POST['id_warehouse'];
	$rs 			= isExistsWarehouseName($whName, $id);
	if( $rs === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}


if(isset($_GET['setSell']))
{
	$sc = TRUE;
	$id = $_GET['id'];
	$value = $_GET['value'] == 0 ? 1 : 0;
	$cs = new warehouse();
	$arr = array(
		'sell' => $value,
		'last_upd' => date('Y-m-d H:i:s'),
		'emp_upd' => getCookie('user_id')
	);

	if( $cs->update($id, $arr) === FALSE)
	{
		$sc = FALSE;
		$message = $cs->error;
	}

	echo $sc === TRUE ? $value : $message;
}


if(isset($_GET['setPrepare']))
{
	$sc = TRUE;
	$id = $_GET['id'];
	$value = $_GET['value'] == 0 ? 1 : 0;
	$cs = new warehouse();
	$arr = array(
		'prepare' => $value,
		'last_upd' => date('Y-m-d H:i:s'),
		'emp_upd' => getCookie('user_id')
	);
	if( $cs->update($id, $arr) === FALSE)
	{
		$sc = FALSE;
		$message = $cs->error;
	}

	echo $sc === TRUE ? $value : $message;
}


if(isset($_GET['setAuz']))
{
	$sc = TRUE;
	$id = $_GET['id'];
	$value = $_GET['value'] == 0 ? 1 : 0;
	$cs = new warehouse();
	$arr = array(
		'allow_under_zero' => $value,
		'last_upd' => date('Y-m-d H:i:s'),
		'emp_upd' => getCookie('user_id')
	);

	if( $cs->update($id, $arr) === FALSE)
	{
		$sc = FALSE;
		$message = $cs->error;
	}

	echo $sc === TRUE ? $value : $message;
}


if(isset($_GET['setActive']))
{
	$sc = TRUE;
	$id = $_GET['id'];
	$value = $_GET['value'] == 0 ? 1 : 0;
	$cs = new warehouse();
	$arr = array(
		'active' => $value,
		'last_upd' => date('Y-m-d H:i:s'),
		'emp_upd' => getCookie('user_id')
	);

	if( $cs->update($id, $arr) === FALSE)
	{
		$sc = FALSE;
		$message = $cs->error;
	}

	echo $sc === TRUE ? $value : $message;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('whCode');
	deleteCookie('whName');
	deleteCookie('whRole');
	deleteCookie('sBranch');
	deleteCookie('underZero');
	echo 'success';
}

?>
