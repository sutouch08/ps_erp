<?php
class employee
{
	public $id_employee;
	public $id_profile;
	public $first_name;
	public $last_name;
	public $full_name;
	public $email;
	public $password;
	public $last_login;
	public $date_register;
	public $s_key;
	public $active;
	public $id_division;
	public $error_message;


	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_employee WHERE id_employee = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				while( $rs = dbFetchObject($qs) )
				{
					$this->id_employee	= $rs->id_employee;
					$this->id_profile		= $rs->id_profile;
					$this->first_name		= $rs->first_name;
					$this->last_name		= $rs->last_name;
					$this->full_name		= $rs->first_name.' '.$rs->last_name;
					$this->email				= $rs->email;
					$this->password		= $rs->password;
					$this->date_register	= $rs->date_register;
					$this->last_login		= $rs->last_login;
					$this->s_key			= $rs->s_key;
					$this->active			= $rs->active;
					$this->id_division		= $rs->id_division;
				}
			}
		}
	}


	////เพิ่มพนักงานใหม่
	public function addEmployee(array $data){
		$qs = "INSERT INTO tbl_employee ";
		$qs .= "(id_profile, first_name, last_name, email, password, last_login, date_register, s_key, active) ";
		$qs .= "VALUES (".$data['id_profile'].", '".$data['first_name']."', '".$data['last_name']."', ";
		$qs .= "'".$data['email']."', '".$data['password']."', NOW(), NOW(), '".$data['s_key']."', ".$data['active'].")";
		$qs = dbQuery($qs);
		if($qs)
		{
			return dbInsertId();
		}else{
			return false;
		}
	}


	public function editEmployee(array $data)
	{
		$qs = dbQuery("UPDATE tbl_employee SET first_name = '".$data['first_name']."', last_name = '".$data['last_name']."', email = '".$data['email']."', id_profile = ".$data['id_profile'].", active = ".$data['active']." WHERE id_employee = ".$data['id_employee']);
		return $qs;
	}


public function deleteEmployee($id_employee){
	//***** ตรวจสอบทรานเซ็คชั่นที่เกี่ยวข้อง ถ้ามีพนักงานเข้าไปเกี่ยวข้องแล้ว ไม่อนุญาติให้ลบ ******************//
	$tr = 0; // เก็บจำนวนตารางที่มีพนักงานเข้าไปเกี่ยวข้อง
	$order 		= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_order WHERE id_employee = $id_employee"));
	$pos 			= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_pos_order WHERE id_employee = $id_employee"));
	$prepare 	= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_prepare WHERE id_employee = $id_employee"));
	$qc 			= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_qc WHERE id_employee = $id_employee"));
	$recieved 	= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_recieved_product WHERE id_employee = $id_employee"));
	$return 		= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_return_order WHERE id_employee = $id_employee"));
	$sale 			= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_sale WHERE id_employee = $id_employee"));
	$temp 		= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_temp WHERE id_employee = $id_employee"));
	$tranfer 		= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_tranfer WHERE id_employee = $id_employee"));
	$adj 			= dbNumRows(dbQuery("SELECT DISTINCT id_employee FROM tbl_adjust WHERE id_employee = $id_employee"));
	$tr = $tr + $order + $pos + $prepare + $qc + $recieved + $return + $sale + $temp + $tranfer + $adj;
	if($tr>0){
		$this->error_message = "ไม่สามารถลบพนักงานได้ เนื่องจากมี transection ที่เกี่ยวข้องกับพนักงานคนนี้เกิดขึ้นแล้ว";
		return false;
	}else{
		$sql = dbQuery("DELETE FROM tbl_employee WHERE id_employee = $id_employee");
		if($sql){
			return true;
		}else{
			$this->error_message = "ลบพนักงานไม่สำเร็จ";
			return false;
		}
	}
}

public function reset_password(array $data){
	list($id_employee, $password) = $data;
		$sql = dbQuery("UPDATE tbl_employee SET password= '$password' WHERE id_employee = $id_employee");
		if($sql){
			return true;
		}else{
			$this->error_message = "เปลี่ยนรหัสผ่านไม่สำเร็จ";
			return false;
		}
}

public function reset_s_key(array $data)
{
	$rs = $this->check_s_key($data['s_key'], $data['id_employee']);
	if($rs)
	{
		return dbQuery("UPDATE tbl_employee SET s_key = '".md5($data['s_key'])."' WHERE id_employee = ".$data['id_employee']);
	}else{
		return false;
	}
}

public function check_email($email, $id_employee =""){
	if($id_employee !=""){
		$row = dbNumRows(dbQuery("SELECT email FROM tbl_employee WHERE email = '$email' AND id_employee != $id_employee"));
	}else{
	$row = dbNumRows(dbQuery("SELECT email FROM tbl_employee WHERE email = '$email'"));
	}
	if($row>0){ $result = false; }else{ $result = true; }
	return $result;
}

public function check_s_key($s_key, $id_employee = ""){
	if($s_key !=""){ $s_key = md5($s_key); }
	if($id_employee !=""){
	$row = dbNumRows(dbQuery("SELECT s_key FROM tbl_employee WHERE s_key = '$s_key' AND s_key !='' AND id_employee != $id_employee"));
	}else{
	$row = dbNumRows(dbQuery("SELECT s_key FROM tbl_employee WHERE s_key = '$s_key' AND s_key !='' "));
	}
	if($row>0){ $rs = false; }else{ $rs = true; }
	return $rs;
}

public function check_password($password, $id_employee = ""){
	if($id_employee != ""){
		$row = dbNumRows(dbQuery("SELECT password FROM tbl_employee WHERE password = '$password' AND id_employee != $id_employee"));
	}else{
		$row = dbNumRows(dbQuery("SELECT password FROM tbl_employee WHERE password = '$password'"));
	}
	if($row>0){ $rs = false; }else{ $rs = true; }
	return $rs;
}

//**************************  Active / Disactive Employee  *************************//
public function change_status($id_employee, $active){
	if($active == 0){ $active = 1; }else{ $active = 0; }
	$sql = dbQuery("UPDATE tbl_employee SET active = $active WHERE id_employee = $id_employee");
	if($sql){
		return true;
	}else{
		$this->error_message = "เปลี่ยนสถานะพนักงานไม่สำเร็จ";
		return false;
	}
}


//----------------------------------------------------- New Code ----------------------------//
public function getDivisionCode($id_employee)
{
	$sc = getConfig('DEFAULT_DIVISION_CODE');
	$qs = dbQuery("SELECT code FROM tbl_employee AS e JOIN tbl_division AS d ON e.id_division = d.id WHERE id_employee = ".$id_employee);
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}


public function getName($id)
{
	$sc = "";
	$qs = dbQuery("SELECT first_name FROM tbl_employee WHERE id_employee = '".$id."'");
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}


public function getFullName($id)
{
	$sc = "";
	$qs = dbQuery("SELECT first_name, last_name FROM tbl_employee WHERE id_employee = '".$id."'");
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchObject($qs);
		$sc = $rs->first_name.' '.$rs->last_name;
	}
	return $sc;
}

public function getSignature($id)
{
	$sc = $this->getName($id);
	$imgPath = WEB_ROOT."img/employee/signature/".$id.".png";
	$path = DOC_ROOT.$imgPath;
	//echo $path;

	if( file_exists( $path ) ){
		$sc = '<img src="'.$imgPath.'" style="max-width:180px; max-height:80px;" />';
	}
	return $sc;
}


public function searchId($txt)
{
	return dbQuery("SELECT id_employee FROM tbl_employee WHERE first_name LIKE '%".$txt."%' OR last_name LIKE '%".$txt."%'");
}




public function search($fields, $txt)
{
	return dbQuery("SELECT ".$fields." FROM tbl_employee WHERE (first_name LIKE '%".$txt."%' OR last_name LIKE '%".$txt."%') AND active = 1");
}


//--- will be return id_employee and id_profile
public function whoUseThisKey($s_key)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT id_employee, id_profile FROM tbl_employee WHERE s_key = '".$s_key."'");
	if( dbNumRows($qs) == 1 )
	{
		$sc = dbFetchObject($qs);
	}
	return $sc;
}



//---
public function getKey($id)
{
	$qs = dbQuery("SELECT s_key FROM tbl_employee WHERE id_employee = '".$id."'");
	if(dbNumRows($qs) == 1)
	{
		list($s_key) = dbFetchArray($qs);

		$s_key = $s_key == '' ? md5($id) : $s_key;
		return $s_key;
	}

	return FALSE;
}






}//--- Enc classs

?>
