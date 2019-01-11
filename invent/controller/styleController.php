<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';

if( isset( $_GET['updateShowInSale']))
{
	$id_style = $_POST['id_style'];
	$active 	= $_POST['show_in_sale'];
	$style 		= new style();
	$rs 			= $style->update($id_style, array('show_in_sale' => $active));
	echo $rs === TRUE ? 'success' : 'fail';
}


if( isset( $_GET['updateShowInCustomer']))
{
	$id_style = $_POST['id_style'];
	$active 	= $_POST['show_in_customer'];
	$style 		= new style();
	$rs 			= $style->update($id_style, array('show_in_customer' => $active));
	echo $rs === TRUE ? 'success' : 'fail';
}



if( isset( $_GET['updateShowInOnline']))
{
	$id_style = $_POST['id_style'];
	$active 	= $_POST['show_in_online'];
	$style 		= new style();
	$rs 			= $style->update($id_style, array('show_in_online' => $active));
	echo $rs === TRUE ? 'success' : 'fail';
}


if( isset( $_GET['updateCanSell']))
{
	$id_style = $_POST['id_style'];
	$active 	= $_POST['can_sell'];
	$style 		= new style();
	$rs 			= $style->update($id_style, array('can_sell' => $active));
	echo $rs === TRUE ? 'success' : 'fail';
}





if( isset( $_GET['updateActive']))
{
	$id_style = $_POST['id_style'];
	$active 	= $_POST['active'];
	$style 		= new style();
	$rs 			= $style->update($id_style, array('active' => $active));
	echo $rs === TRUE ? 'success' : 'fail';
}






if( isset( $_GET['deleteStyle'] ) )
{
	$id = $_POST['id'];
	$st = new style();
	$sc = 'success';
	if( $st->delete($id) === FALSE )
	{
		$sc = $st->error;
	}
	echo $sc;
}


if(isset($_GET['getStyleId']))
{
	$code = $_GET['style_code'];
	$cs = new style();
	$sc = $cs->getId($code);
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('stCode');
	deleteCookie('stName');
	echo 'success';
}

?>
