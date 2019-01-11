<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';


if( isset( $_GET['deleteProductGroup'] ) )
{
	$id = $_POST['id'];
	$cs = new product_group();
	$sc = 'success';
	if( $cs->delete($id) === FALSE )
	{
		$sc = $cs->error;
	}
	echo $sc;
}


if(isset($_GET['setActive']))
{
	$sc = TRUE;
	$id = $_POST['id'];
	$active = $_POST['active'];
	$active = $active == 0 ? 1 : 0;
	$cs = new product_group();
	if( $cs->setActive($id, $active) === FALSE )
	{
		$sc = FALSE;
		$message = $cs->error;
	}

	echo $sc === TRUE ? $active : $message;
}

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sGroupCode');
	deleteCookie('sGroupName');
	echo 'done';
}

?>
