<?php
require '../../../library/config.php';
require '../../../library/functions.php';
require '../../function/tools.php';
require '../../function/date_helper.php';



	if( isset( $_GET['doLogin']))
	{
		$user = $_POST['user'];
		$password = $_POST['password'];

		$login = new validate_credentials();

		echo $login->sale_login($user, $password);

	}


	if( isset( $_GET['doLogout']))
	{
		deleteCookie('user_id');
		deleteCookie('sale_id');
		deleteCookie('UserName');
		echo 'done';
	}

?>
