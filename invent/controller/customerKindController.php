<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//------- Add new customer_kind
if( isset( $_GET['addKind'] ) )
{
	$sc = 'success';
	$code = $_POST['code'];
	$name = $_POST['name'];
	$cs = new customer_kind();
	$nameExists = $cs->isExists('name', $name);
	$codeExists	 = $cs->isExists('code', $code);
	if( $nameExists === TRUE )
	{
		$sc = 'nameError';
	}
	if( $codeExists === TRUE )
	{
		$sc = 'codeError';
	}
	
	if( $codeExists === FALSE && $nameExists === FALSE )
	{
		$arr = array('code' => $code, 'name' => $name);
		if( $cs->add($arr) === FALSE )
		{
			$sc = 'เพิ่มรายการไม่สำเร็จ';
		}
	}
	echo $sc;
}



if( isset( $_GET['saveEditKind'] ) )
{
	$sc = 'success';
	$id			= $_POST['id'];
	$code	= $_POST['code'];
	$name	= $_POST['name'];	
	$cs = new customer_kind();
	$nameExists = $cs->isExists('name', $name, $id);
	$codeExists	 = $cs->isExists('code', $code, $id);
	if( $nameExists === TRUE )
	{
		$sc = 'nameError';
	}
	if( $codeExists === TRUE )
	{
		$sc = 'codeError';
	}
	
	if( $codeExists === FALSE && $nameExists === FALSE )
	{
		$arr = array('code' => $code, 'name' => $name);
		if( $cs->update($id, $arr) === FALSE )
		{
			$sc = 'บันทึกรายการไม่สำเร็จ';
		}
	}
	echo $sc;
	
}


if( isset( $_GET['deleteKind'] ) )
{
	$sc = 'fail';
	$id = $_GET['id'];
	$cs = new customer_kind();	
	if( $cs->delete($id) === TRUE )
	{
		//----- เปลียน id_type ในสินค้าที่อ้างถึง ให้เป็น 0
		$cs->removeMember($id);	
		$sc = 'success';
	}
	echo $sc;
}


//----------- Get type data for edit
if( isset( $_GET['getData'] ) )
{
	$sc = "";
	$id = $_GET['id'];
	$cs = new customer_kind($id);
	if( $cs->id != '' )
	{
		$sc = $cs->id .' | ' . $cs->code . ' | ' . $cs->name;	
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sKindCode');
	deleteCookie('sKindName');
	echo 'done';	
}

?>