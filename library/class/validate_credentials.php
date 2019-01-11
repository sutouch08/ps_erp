<?php

class validate_credentials
{
	public function __construct()
	{

	}


	//---	ตรวจสอบสิทธิ์ในการอนุมัติต่างๆ
	public function validatePermission($id_tab, $id_profile, $fields="")
	{
		$sc =  FALSE;
		if( $fields == "")
		{
			$qs = dbQuery("SELECT a.add, a.edit, a.delete FROM tbl_access AS a WHERE a.id_profile = ".$id_profile." AND id_tab = ".$id_tab);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$sc = ($rs->add == 1 OR $rs->edit == 1 OR $rs->delete == 1 ) ? TRUE : FALSE;
			}

		}
		else
		{
			$qs = dbQuery("SELECT a.".$fields." AS value FROM tbl_access AS a WHERE a.id_profile = ".$id_profile." AND id_tab = ".$id_tab);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$sc = $rs->value == 1 ? TRUE : FALSE;
			}
		}
		return $sc;
	}




	public function sale_login($user, $psd)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_sale WHERE user_name = '".$user."' AND password = '".$psd."'");

		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchObject($qs);

			//---	ถ้าบัญชีไม่ได้ถูกปิด หรือ ถูกลบ
			if($rs->active == 1 && $rs->is_deleted == 0)
			{
				$time = 3600*24*30; //---	1 เดือน
				createCookie('sale_id', $rs->id,$time );
				createCookie('saleName', $rs->name, $time);
				createCookie('user_id', $rs->id_employee, $time);
				$sc = TRUE;
			}
			else
			{
				$message = 'บัญชีของคุณถูกระงับ';
			}
		}
		else
		{
			$message = 'ชื่อผู้ใช้งานหรือรหัสไม่ถูกต้อง';
		}

		return $sc === TRUE ? 'success' : $message;
	}

}//--- end class
?>
