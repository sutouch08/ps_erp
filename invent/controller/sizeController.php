<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';

if( isset( $_GET['movePositionUp'] ) )
{
	$sc 	= 'success';
	$id 	= $_POST['id'];
	$pos 	= $_POST['position'];	
	$cs 	= new size();
	if( $cs->decresePosition($id, $pos) === FALSE )
	{
		$sc = 'ย้ายตำแหน่งไม่สำเร็จ';
	}
	echo $sc;
}



if( isset( $_GET['movePositionDown'] ) )
{
	$sc 	= 'success';
	$id 	= $_POST['id'];
	$pos 	= $_POST['position'];	
	$cs 	= new size();
	if( $cs->incresePosition($id, $pos) === FALSE )
	{
		$sc = 'ย้ายตำแหน่งไม่สำเร็จ';
	}
	echo $sc;
}



if( isset( $_GET['deleteSize'] ) )
{
	$id = $_POST['id'];
	$cs = new size();
	$sc = 'success';
	if( $cs->delete($id) === FALSE )
	{
		$sc = $cs->error;
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sCode');
	deleteCookie('sName');
	echo 'success';	
}

?>