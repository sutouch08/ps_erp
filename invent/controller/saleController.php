<?php
require "../../library/config.php";
require "../function/tools.php";

//------------------------- Add Login
if( isset( $_GET['addUser'] ) )
{
	$sc 	= "success";
	$id 	= $_POST['id'];
	$userName	= trim( $_POST['userName'] );
	$password	= md5( trim( $_POST['password'] ) );
	$active 	= $_POST['active'];
	$arr = array("user_name" => $userName, "password" => $password, "active" => $active);
	$sale = new sale();
	$rs 	= $sale->update($id, $arr);
	if( $rs === FALSE )
	{
		$sc = "fail";
	}
	echo $sc;
}




//------------------------- Update login
if( isset( $_GET['updateUser'] ) )
{
	$sc 	= "success";
	$id 	= $_POST['id'];
	$userName	= trim( $_POST['userName'] );
	$password	= "";
	$active 	= $_POST['active'];
	if( $userName != "" )
	{
		$arr = array("user_name" => $userName, "active" => $active);
	}
	else
	{
		$arr = array("user_name" => $userName, "password" => $password, "active" => $active);
	}
	$sale = new sale();
	$rs 	= $sale->update($id, $arr);
	if( $rs === FALSE )
	{
		$sc = "fail";
	}
	echo $sc;
}




//---------------------------- Delete Sale
if( isset( $_GET['deleteSale'] ) )
{
	$sc = "success";
	$id = $_POST['id'];
	$emp	= getCookie('user_id');
	$sale = new sale();
	$rs = $sale->delete($id, $emp);
	if( $rs === FALSE )
	{
		$sc = "fail";
	}
	echo $sc;
}




//----------------------------- Undelete sale
if( isset( $_GET['unDeleteSale'] ) )
{
	$sc = "success";
	$id  = $_POST['id'];
	$sale = new sale();
	$emp	= getCookie("user_id");
	$rs = $sale->unDelete($id, $emp);
	if( $rs === FALSE )
	{
		$sc = "fail";
	}
	echo $sc;
}



if( isset( $_GET['checkUserName'] ) )
{
	$sc = "duplicate";
	$id = $_POST['id'];
	$userName = $_POST['userName'];
	$sale = new sale();
	if( $sale->validUserName($id, $userName) === TRUE )
	{
		$sc = "ok";
	}
	echo $sc;
}

if( isset( $_GET['deleteSaleGroup'] ) )
{
	$sc = 'fail';
	$code = $_POST['code'];
	$qs = dbQuery("DELETE FROM tbl_sale_group WHERE code = '".$code."'");
	if( $qs === TRUE )
	{
		$sc = 'success';
	}
	echo $sc;
}


if(isset($_GET['resetPwd']) && isset($_POST['id_sale']))
{
	$id = $_POST['id_sale'];
	$pwd = $_POST['pwd'];

	$sale = new sale();
	$arr = array('password' => $pwd);
	$sc = $sale->update($id, $arr);

	echo $sc === TRUE ? 'success' : 'เปลี่ยนรหัสผ่านไม่สำเร็จ';
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sCode');
	deleteCookie('sName');
	deleteCookie('stCode');
	deleteCookie('stName');
	echo 'success';
}


?>
