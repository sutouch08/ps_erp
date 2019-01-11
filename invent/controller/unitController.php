<?php
require "../../library/config.php";
require "../../library/functions.php";
include '../function/tools.php';


if( isset( $_GET['deleteUnit'] ) )
{
	$sc = "success";
	$id = $_POST['id'];
	$unit = new unit();
	$rs = $unit->delete($id);
	if( $rs === FALSE )
	{
		$sc = "fail";
	}
	echo $sc;
}




if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('uCode');
	deleteCookie('uName');
	echo "success";	
}

?>