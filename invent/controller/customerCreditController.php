<?php
require "../../library/config.php";
require "../../library/functions.php";
include '../function/tools.php';

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sCreditCode');
	deleteCookie('sCreditName');
	echo 'success';
}


?>
