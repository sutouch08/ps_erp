<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//------- Add new channels
if( isset( $_GET['addChannels'] ) )
{
	$sc = 'success';
	$code = $_POST['code'];
	$name = $_POST['name'];
	$isDefault = $_POST['isDefault'];
	$isOnline = $_POST['isOnline'];
	$cs = new channels();
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
		$arr = array(
					'code' 	=> $code, 
					'name' 	=> $name, 
					'isOnline' => $isOnline
				);
				
		if( $cs->add($arr) === FALSE )
		{
			$sc = 'เพิ่มรายการไม่สำเร็จ';
		}
		else
		{
			if( $isDefault == 1 )
			{
				$cs->setDefault($cs->getId($code));	
			}
		}
	}
	echo $sc;
}



if( isset( $_GET['saveEditChannels'] ) )
{
	$sc = 'success';
	$id			= $_POST['id'];
	$code	= $_POST['code'];
	$name	= $_POST['name'];	
	$isDefault = $_POST['isDefault'];
	$isOnline		= $_POST['isOnline'];
	$cs = new channels();
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
		$arr = array(
					'code' 	=> $code, 
					'name' 	=> $name,
					'isOnline'	=> $isOnline
				);
		if( $cs->update($id, $arr) === FALSE )
		{
			$sc = 'บันทึกรายการไม่สำเร็จ';
		}
		else
		{
			if( $isDefault == 1 )
			{
				$cs->setDefault($id);	
			}
		}
	}
	echo $sc;
	
}


if( isset( $_GET['deleteChannels'] ) )
{
	$sc = 'fail';
	$id = $_GET['id'];
	$cs = new channels();	
	if( $cs->delete($id) === TRUE )
	{
		$sc = 'success';
	}
	echo $sc;
}


//----------- Get type data for edit
if( isset( $_GET['getData'] ) )
{
	$sc = "";
	$id = $_GET['id'];
	$cs = new channels($id);
	if( $cs->id != '' )
	{
		$sc = $cs->id .' | ' . $cs->code . ' | ' . $cs->name . ' | ' .$cs->isDefault . ' | ' .$cs->isOnline;	
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sChannelsCode');
	deleteCookie('sChannelsName');
	echo 'done';	
}

?>