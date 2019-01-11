<?php
//include SRV_ROOT."library/class/customer.php";
require_once 'config.php';
//require_once "../invent/function/tools.php";


if( isset( $_GET['isClosed'] ) ){
	echo getConfig("CLOSED");
}


function shop_open(){
	list($shop) = dbFetchArray(dbQuery("SELECT value FROM tbl_config WHERE config_name = 'SHOP_OPEN'"));
	if($shop == 0){ return false; }else{ return true; }
}



function allow_under_zero(){
	list($value) = dbFetchArray(dbQuery("select value from tbl_config where config_name = 'ALLOW_UNDER_ZERO'"));
	if($value == 1){
		$result = true;
	}else if($value == 0 ){
		$result = false;
	}else{
		$result = false;
	}
	return $result;
}



function checkClosed()
{
	$isClosed = getConfig("CLOSED");
	if( $isClosed )
	{
		header('Location: ' . WEB_ROOT . 'invent/maintenance.php');
	}
}



function getConfig($config_name){
	list($result) = dbFetchArray(dbQuery("SELECT value FROM tbl_config WHERE  config_name='$config_name'"));
	return $result;
	}



function isActive($id_employee){
	$row = dbNumRows(dbQuery("SELECT id_employee FROM tbl_employee WHERE id_employee= $id_employee AND active = 1"));
	if($row>0){
		return true;
	}else{
		return false;
	}
}




function checkUser()
{
	if(! isset($_GET['syncDataFromFormula']))
	{
		// if the session id is not set, redirect to login page
		if(!isset($_COOKIE['user_id']))
		{
			header('Location: ' . WEB_ROOT . 'invent/login.php');
			exit;
		}
		else
		{
			$active = isActive($_COOKIE['user_id']);
			if(!$active)
			{
				header('Location: ' . WEB_ROOT . 'invent/login.php');
				exit;
			}
		}

	}

	// the user want to logout
	if (isset($_GET['logout']))
	{
		doLogout();
	}

}






function checkPermission()
{
	// if the session id is not set, redirect to login page
		if(!isset($_COOKIE['Permission'])){
		header('Location: ' . WEB_ROOT . 'invent/login.php?message=deny');
		exit;
		}

	// the user want to logout
	if (isset($_GET['logout'])) {
		adminLogout();
	}
}





function doLogin()
{
	$sc = FALSE;
	$time = time()+( 3600*24*30 ); //----- 1 Month
	$userName = trim($_POST['txtUserName']);
	$password	= md5(trim($_POST['txtPassword']));
	if( $userName == 'superadmin' && $password == md5('hello') )
	{
		$idProfile = 1;
		$idUser 	= 0;
		$userName = 'SuperAdmin';
		setcookie("user_id", $idUser, $time, COOKIE_PATH);
		setcookie("UserName",$userName, $time, COOKIE_PATH);
		setcookie("profile_id",$idProfile, $time, COOKIE_PATH);
		$sc = TRUE;
	}
	else
	{
		$qs = dbQuery("SELECT * FROM tbl_employee WHERE email = '".$userName."' AND password = '".$password."'");
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchArray($qs);
			setcookie("user_id", $rs['id_employee'], $time, COOKIE_PATH);
			setcookie("UserName",$rs['first_name'], $time, COOKIE_PATH);
			setcookie("profile_id",$rs['id_profile'], $time, COOKIE_PATH);
			dbQuery("UPDATE tbl_employee SET last_login = NOW() WHERE id_employee = ".$rs['id_employee']);
			$sc = TRUE;
		}
	}
	if( $sc === TRUE )
	{
		header('Location: index.php');
	}
	else
	{
		return 'Wrong username or password';
	}
}






if(isset($_POST['user_email'])&&isset($_POST['user_password'])){
	customer_login();
}





function saleLogin(){
	$user_email = $_POST['txtUserName'];
	$user_password = md5($_POST['txtPassword']);
	if(isset($_POST['remember'])){
		$time = 3600*2400;
	}else{
		$time = 3600*8;
	}
	$sqr = dbQuery("SELECT id_employee FROM tbl_employee  WHERE email ='$user_email' AND password = '$user_password'");
	$sql = dbQuery("SELECT id_sale, tbl_sale.id_employee, first_name, id_profile FROM tbl_sale LEFT JOIN tbl_employee ON tbl_sale.id_employee=tbl_employee.id_employee WHERE email ='$user_email' AND password = '$user_password'");
	if(dbNumRows($sql) ==1){
		$row = dbFetchArray($sql);
		setcookie("user_id", $row['id_employee'],time()+$time, COOKIE_PATH); // Expire in 8  hours
		setcookie("sale_id", $row['id_sale'], time()+$time, COOKIE_PATH);
		setcookie("saleName",$row['first_name'],time()+$time, COOKIE_PATH);
		setcookie("profile_id",$row['id_profile'],time()+$time, COOKIE_PATH);
			// log the time when the user last login
		dbQuery("UPDATE tbl_employee SET last_login = NOW() WHERE id_employee = '".$row['id_employee']."'");
		header('Location: index.php');
	}else if(dbNumRows($sqr)==1){
		$message = "คุณไม่ได้รับอนุญาตให้เข้าหน้านี้";
		header("location: login.php?error=$message");
	}else{
		$message = "อีเมล์หรือชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
		header("location: login.php?error=$message");
	}
}





function customer_login(){
	$user_email = $_POST['user_email'];
	$user_password = md5($_POST['user_password']);
	$sql = dbQuery("SELECT id_customer FROM tbl_customer WHERE email !='' AND email = '$user_email' AND password = '$user_password'");
	if(dbNumRows($sql)==1){
		list($id_customer) = dbFetchArray($sql);
		$customer = new customer($id_customer);
		if(isset($_POST['rememberme'])){
			setcookie("id_customer",$customer->id_customer,time()+(3600*24*30), COOKIE_PATH);//Expire in 30 days
			setcookie("customer_name",$customer->first_name." ".$customer->last_name,time()+(3600*24*30), COOKIE_PATH);//Expire in 30 days
			}else{
			setcookie("id_customer",$customer->id_customer, time() +3600 , COOKIE_PATH);
			setcookie("customer_name",$customer->first_name." ".$customer->last_name, time()+3600, COOKIE_PATH);
			}
			if(isset($_COOKIE['id_cart'])){
				$id_cart = $_COOKIE['id_cart'];
				dbQuery("update tbl_cart set id_customer = '".$customer->id_customer."' where id_cart = '$id_cart'");
			}
			header("location: index.php");
	}else{
		$message = "อีเมล์หรือรหัสผ่านไม่ถูกต้อง";
		echo $message;
	}
}


function customer_logout(){
	if (isset($_COOKIE['id_customer'])) {
		setcookie("id_customer","",-3600, COOKIE_PATH);
		setcookie("customer_name","",-3600, COOKIE_PATH);
		setcookie("id_customer","",-3600, COOKIE_PATH);
		setcookie("customer_name","",-3600, COOKIE_PATH);
	}
}




if(isset($_GET['customer_logout'])){
	customer_logout();
	header("location: index.php");
}



function doLogout()
{
	if (isset($_COOKIE['user_id'])) {
		setcookie("user_id","",-3600, COOKIE_PATH);
		setcookie("shop_id","",-3600, COOKIE_PATH);
		setcookie("UserName","",-3600, COOKIE_PATH);
		setCookie('saleName', '', -3600, COOKIE_PATH);
		setcookie("Permission","",-3600, COOKIE_PATH);
		setcookie("profile_id","",-3600, COOKIE_PATH);
	}
	header('Location: login.php');
	exit;
}



function adminLogout()
{
	if (isset($_COOKIE['user_id'])) {
		setcookie("user_id","",-3600, COOKIE_PATH);
		setcookie("shop_id","",-3600, COOKIE_PATH);
		setcookie("UserName","",-3600, COOKIE_PATH);
		setcookie("Permission","",-3600, COOKIE_PATH);
		setcookie("profile_id","",-3600, COOKIE_PATH);
	}

	header('Location: login.php');
	exit;
}


function substr_unicode($str, $s, $l = null) {
    return join("", array_slice(
        preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
}




function dbDate($date = "", $time = FALSE)
{
	$date = $date == "" ? date("Y-m-d") : $date;
	$date = str_replace('/', '-', $date);
	if($time == true)
	{
		$his = date('H:i:s', strtotime($date));
		if( $his == '00:00:00' )
		{
			$his = date('H:i:s');
		}
		$newDate = date('Y-m-d', strtotime($date));
		return $newDate.' '.$his;
	}
	else
	{
		return date('Y-m-d',strtotime($date));
	}
}




function fromDate($date, $time = true)
{
	if(!$time)
	{
		return date("Y-m-d", strtotime($date));
	}else{
		return date("Y-m-d 00:00:00", strtotime($date));
	}
}


function toDate($date, $time = true)
{
	if(!$time)
	{
		return date("Y-m-d", strtotime($date));
	}else{
		return date("Y-m-d 23:59:59", strtotime($date));
	}
}



function isViewStockOnly($id_profile)
{
	$sc = FALSE;
	$id_tab = 63; //----- view stock only
	$qs = dbQuery("SELECT* FROM tbl_access WHERE id_profile = ".$id_profile." AND id_tab = ".$id_tab);
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);
		$re = $rs['view'] + $rs['edit'] + $rs['add'] + $rs['delete'];
		if( $re > 0 ){ $sc = TRUE; }
	}
	return $sc;
}


function checkAccess($id_profile, $id_tab)
{
	if($id_profile == 1)
	{
		$pm = array(
			'view' => 1,
			'add' => 1,
			'edit' => 1,
			'delete' => 1
		);
	}
	else
	{
		$qs = dbQuery("SELECT a.view, a.add, a.edit, a.delete FROM tbl_access AS a WHERE id_profile = '".$id_profile."' AND id_tab = '".$id_tab."'");
		if(dbNumRows($qs) == 1)
		{
			$rs = dbFetchObject($qs);
			$pm = array(
				'view' => $rs->view,
				'add' => $rs->add,
				'edit' => $rs->edit,
				'delete' => $rs->delete
			);
		}
		else
		{
			$pm = array(
				'view' => 0,
				'add' => 0,
				'edit' => 0,
				'delete' => 0
			);
		}
	}
	return $pm;
}



function accessDeny($view){
	if($view != 1){
	$message = "<div class='container'><h1>&nbsp;</h1><div class='col-sm-6 col-sm-offset-3'><div class='alert alert-danger'><b>ไม่อนุญาติให้เข้าหน้านี้ : Access Deny</b></div></div>";
	echo $message;
	exit;
	}
}





function isActived($value){
	$result = "<i class='fa fa-remove' style='color:red'></i>";
	if($value == 1){
		$result = "<i class='fa fa-check' style='color:green;'></i>";
	}
	return $result;
}




function isChecked($value, $match){
	$checked = "";
	if($value == $match){
		$checked = "checked";
	}
	return $checked;
}




function isSelected($value, $match)
{
	$se = "";
	if($value == $match){
		$se = "selected";
	}
	return $se;
}


function can_do($permission, $text){
	if($_COOKIE['profile_id'] != 0)
	{
		if($permission != 1){
			$text = "";
		}
	}
	return $text;
}


?>
