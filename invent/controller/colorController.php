<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';

if( isset( $_GET['changeColorGroup'] ) )
{
	$sc = "success";
	$id_group = $_GET['id_group'];
	$id_color = $_GET['id_color'];
	$cs		= new color();
	if( $cs->changeColorGroup($id_color, $id_group) === FALSE )
	{
		$sc = $cs->error;	
	}
	echo $sc;
}

if( isset( $_GET['deleteColor'] ) )
{
	$id = $_POST['id'];
	$cs = new color();
	$sc = 'success';
	if( $cs->delete($id) === FALSE )
	{
		$sc = $cs->error;
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('cCode');
	deleteCookie('cName');
	deleteCookie('cGroup');
	echo 'success';	
}

?>