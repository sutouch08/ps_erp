<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';


if( isset( $_GET['deleteBrand'] ) )
{
	$id = $_POST['id'];
	$cs = new brand();
	$sc = 'success';
	if( $cs->delete($id) === FALSE )
	{
		$sc = $cs->error;
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('bCode');
	deleteCookie('bName');
	echo 'success';
}

?>
