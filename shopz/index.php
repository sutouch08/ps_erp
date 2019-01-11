<?php
require_once '../library/config.php';
require_once '../library/functions.php';
require '../invent/function/tools.php';

$open = shop_open();
if(!$open)
{ 
	$content = getConfig("MAINTENACE_MESSAGE");
	require_once("maintenance.php");
}
else
{
	$content = '';
	if( !isset( $_COOKIE['id_customer'] ) )
	{
		$id_customer = uniqid();
		setcookie("id_customer", $id_customer, time()+(3600*24*7), "/");  /// set cookie for 7 days
	}
	else
	{
		$id_customer = $_COOKIE['id_customer'];	
	}
	$page = (isset($_GET['content'])&& $_GET['content'] !='')?$_GET['content']:'';
	switch($page){
		case 'category':
			$content = 'category.php';
			$pageTitle = 'สินค้า';
			break;
		case 'product':
			$content = 'product.php';
			$pageTitle = 'สินค้า';
			break;
		case 'cart':
			$content = 'cart.php';
			$pageTitle = 'ตะกร้าสินค้า';
			break;
		case 'account':
			$content = 'account.php';
			$pageTitle = 'บัญชีของฉัน';
			break;
		case 'order':
			$content = 'order_list.php';
			$pageTitle = 'บัญชีของฉัน';
			break;
		case 'my-address':
			$content = 'my_address.php';
			$pageTitle = 'ที่อยู่ของฉัน';
			break;
		case 'user-information':
			$content = 'user_information.php';
			$pageTitle = 'ที่อยู่ของฉัน';
			break;
		case 'credit':
			$content = 'my_credit.php';
			$pageTitle = 'เครดิต';
			break;
		case "forgot_password":
			$content = "forget_password.php";
			$pageTitle = "ลืมรหัสผ่าน";
			break;
		case "reset_password":
			$content = "reset_password.php";
			$pageTitle = "เปลี่ยนรหัสผ่าน";
			break;
		default:
			$content = 'home.php';
			$pageTitle = "Welcome";
			break;
	}
	require_once 'template.php';
}
?>

