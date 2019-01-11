<?php

function tis($text)
{
	return iconv('utf-8', 'tis-620', $text);
}



function goBackButton()
{
		return '<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>';
}


//----ใช้สำหรับรับค่าจาก searchForm ต่างๆ
function getFilter($postName, $cookieName, $defaultValue = "")
{
	$sc = isset( $_POST[$postName] ) ? trim( $_POST[$postName] ) : ( getCookie($cookieName) ? getCookie($cookieName) : $defaultValue );
	return $sc;
}



//-------------------------  สร้าง cookie
function createCookie($name, $value, $time = 3600, $path = '/')
{
	return setcookie($name, $value, intval(time()+$time), $path);
}




//----------------------------  ลบ cookie
function deleteCookie($name, $path = '/')
{
	return setcookie($name, '', time()-3600, $path);
}




//--------------------------  ใช้งาน cookie
function getCookie($name)
{
	if( isset( $_COOKIE[$name] ) )
	{
		return $_COOKIE[$name];
	}
	else
	{
		return FALSE;
	}
}


//---	ตัดข้อความแล้วเติม ... ข้างหลัง
function limitText($str, $length)
{
	$txt = '...';
	if( strlen($str) >= $length)
	{
		return mb_substr($str, 0, $length).$txt;
	}
	else
	{
		return $str;
	}
}




//-------------------------  ส่งกลับ id_profile
function getProfile($id_employee)
{
	$id_profile = FALSE;
	$qs = dbQuery("SELECT id_profile FROM tbl_employee WHERE id_employee = ".$id_employee);
	if( dbNumRows($qs) == 1 )
	{
		list($id_profile) = dbFetchArray($qs);
	}
	return $id_profile;
}




function setError($name, $value)
{
	session_start();
     $_SESSION[$name] = $value;
     session_write_close();
}





function getError($name)
{
	 session_start();
     $var = isset( $_SESSION[$name] ) ? $_SESSION[$name] : FALSE;
     session_write_close();
	 unset($_SESSION[$name]);
     return $var;
}





function checkError()
{
	session_start();
	foreach( array('error', 'warning', 'message') as $name)
	{
     $var = isset( $_SESSION[$name] ) ? $_SESSION[$name] : FALSE;
	 if( $var !== FALSE )
	 {
		 echo '<input type="hidden" id="'.$name.'" value="'.$var.'" />';
	 }
	 unset($_SESSION[$name]);
	}
	session_write_close();
}


//-----------------------  AC format
function ac_format($number)
{
	if($number == 0)
	{
		$number = "-";
	}
	return $number;
}




function number($number, $digit = 0)
{
	return number_format($number, $digit);
}


function DateDiff($strDate1,$strDate2)
{
	return ceil((strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 ));  // 1 day = 60*60*24
 }




function thaiDate($date='', $d="-"){
	if( $date == '' )
	{
		$date = date('Y-m-d');
	}
	return date("d".$d."m".$d."Y",strtotime($date));
}





function thaiDateTime($date='')
{
	if( $date == '' )
	{
		$date = date('Y-m-d');
	}
	return date("d-m-Y H:i:s", strtotime($date));
}

 function state_color($current_state){
		$sql = dbQuery("SELECT color FROM tbl_order_state WHERE id_order_state = $current_state");
		list($color) = dbFetchArray($sql);
		return $color;
	}




function selectEmployeeGroup($selected="", $title = "------ เลือก ------"){
	$profile = $_COOKIE['profile_id'];
		echo "<option value='' "; if($selected == ""){ echo"selected='selected'";} echo">$title</option>";
		$sql = dbQuery("SELECT * FROM tbl_profile where id_profile >= '$profile'");
		$row = dbNumRows($sql);
		$i = 0;
		while($i<$row){
			list($id_profile, $profile_name) = dbFetchArray($sql);
			echo"<option value='$id_profile' "; if($id_profile == $selected){ echo"selected='selected'";} echo">$profile_name</option>";
			$i++;
		}
}















function employeeList($selected=""){
	$sql = dbQuery("SELECT id_employee, first_name, last_name FROM tbl_employee");
	echo "<option value='' "; if($selected == ""){ echo"selected='selected'";} echo">------ เลือก ------</option>";
	$row = dbNumRows($sql);
	$i=0;
	while($i<$row){
		list($id_employee, $first_name, $last_name) = dbFetchArray($sql);
		echo"<option value='$id_employee'"; if($selected==$id_employee){ echo"selected='selected'";} echo"> $first_name  $last_name</option>";
		$i++;
	}
}



function orderStateList($id_order)
{
	$id_tab 	= 14;
	$id_profile = getCookie('profile_id');
    $pm 		= checkAccess($id_profile, $id_tab);
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	$sc 		= '<option value="0"> ---- สถานะ ---- </option>';
	$sc 		.= $edit == 1 ? '<option value="1">รอการชำระเงิน</option>' : '';
	$sc 		.= $edit == 1 ? '<option value="3">รอจัดสินค้า</option>' : '';
	$sc 		.= $delete == 1 ? '<option value="8">ยกเลิก</option>' : '';

	return $sc;
}



function reorder($p_from, $p_to){
			if($p_to < $p_from){
				$from = $p_to;
				$to = $p_from;
			}else{
				$from = $p_from;
				$to = $p_to;
			}
			$arr['from'] = $from;
			$arr['to'] = $to;
			return $arr;
}


function employee_name($id_emp)
{
	$emp = new employee();
	return $emp->getFullName($id_emp);
}


function employeeName($id_emp)
{
	$emp = new employee();
	return $emp->getName($id_emp);
}





//-----------------  คืนยอดงบประมาณคงเหลือ  ---------------//
function return_budget($id_order)
{
	$sc 		= TRUE;
	$order	= new order($id_order);
	if($order->current_state == 9 )
	{
		$amount = $order->qc_amount($id_order);
	}
	else
	{
		$amount = $order->getCurrentOrderAmount($id_order);
	}

	if($order->role == 7 )
	{
		require_once 'support_helper.php';
		$id_budget	= get_id_support_budget_by_order($id_order);
		$balance 	= get_support_balance($id_budget);
		$balance 	+= $amount;

		//-----  ปรับปรุงยอดคงเหลือในงบประมาณ
		$ra	= update_support_balance($id_budget, $balance);

		//----- ปรับปรุงยอดออเดอร์ใน order_sponsor
		$rb	= update_order_support_amount($id_order, 0.00 );

		if( ! $ra OR ! $rb ){ $sc = FALSE; }
	}

	if($order->role == 4 )
	{
		require_once "sponsor_helper.php";
		$id_budget	= get_id_sponsor_budget_by_order($id_order);
		$balance 	= get_sponsor_balance($id_budget);
		$balance 	+= $amount;

		//-----  ปรับปรุงงบคงเหลือ
		$ra	= update_sponsor_balance($id_budget, $balance);

		//-----  ปรับปรุงยอดออเดอร์ใน order_sponsor
		$rb	= update_order_sponsor_amount($id_order, 0.00 );

		if( ! $ra OR ! $rb ){ $sc = FALSE; }
	}
	return $sc;
}





//----------------------  ตัดยอดงบประมาณตามออเดอร์ที่เปลี่ยนสถานะจากยกเลิกเป็นสถานะ รอชำระเงิน หรือ รอจัดสินค้า
function apply_budget($id_order)
{
	$sc 		= TRUE;
	$order	 = new order($id_order);
	if($order->role == 7 )
	{
		$id_budget	= get_id_support_budget_by_order($id_order);
		$balance 	= get_support_balance($id_budget);
		$amount 		= $order->getCurrentOrderAmount($id_order);
		$balance 	+= $amount * -1;
		$sc 			= update_support_balance($id_budget, $balance);
	}

	if($order->role == 4 )
	{
		$id_budget 	= get_id_sponsor_budget_by_order($id_order);
		$balance 	= get_sponsor_balance($id_budget);
		$amount 		= $order->getCurrentOrderAmount($id_order);
		$balance 	+= $amount * -1;
		$sc 			= update_sponsor_balance($id_budget, $balance);
	}
	return $sc;
}






function profile_name($id_profile)
{
	$name = "";
	$qs = dbQuery("SELECT profile_name FROM tbl_profile WHERE id_profile = ".$id_profile);
	if(dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);
		$name = $rs['profile_name'];
	}
	return $name;
}




function clearToken($token)
{
	setcookie("file_download_token", $token, time() +3600,"/");
}

?>
