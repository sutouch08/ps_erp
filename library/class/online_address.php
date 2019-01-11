<?php
class online_address
{
	public $id;
	public $customer_code;
	public $first_name;
	public $last_name;
	public $address1;
	public $address2;
	public $district;
	public $province;
	public $postcode;
	public $phone;
	public $email;
	public $alias;
	public $is_default;


	public function __construct($id="")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_address_online WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchArray($qs);
				foreach( $rs as $key => $value )
				{
					$this->$key = $value;
				}
			}
		}
	}



	public function add( array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty( $ds ) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_address_online (".$fields.") VALUES (".$values.")");

			if($sc === TRUE)
			{
				$id = dbInsertId();
				$this->setDefault($id);
			}
		}
		return $sc;
	}






	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field ." = '".$value."'" : ", ". $field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_address_online SET ". $set ." WHERE id = ".$id);
		}
		return $sc;
	}






	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_address_online WHERE id = ".$id);
	}





	public function getDefaultId($code)
	{
		$sc = '';
		$qs = dbQuery("SELECT id FROM tbl_address_online WHERE customer_code = '".$code."' AND is_default = 1");

		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		else  //---	เผื่อว่ามีที่อยู่แต่ไม่ได้ตั้งเป็น default ไว้
		{
			$qs = dbQuery("SELECT id FROM tbl_address_online WHERE customer_code = '".$code."'");
			if( dbNumRows($qs) > 0)
			{
				list( $sc ) = dbFetchArray($qs);
			}
		}

		return $sc;
	}




	public function getCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT customer_code FROM tbl_address_online WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function setDefault($id)
	{
		$code = $this->getCode($id);
		$qs = dbQuery("UPDATE tbl_address_online SET is_default = 1 WHERE id = ".$id);
		$qr = dbQuery("UPDATE tbl_address_online SET is_default = 0 WHERE id != ".$id." AND customer_code = '".$code."'");
		return ( $qs === TRUE && $qr === TRUE ) ? TRUE : FALSE;
	}



	public function getAddress($id)
	{
		return dbQuery("SELECT * FROM tbl_address_online WHERE id = ".$id);
	}


	public function getOnlineAddressByCustomerCode($customer_code)
	{
		$qs = dbQuery("SELECT * FROM tbl_address_online WHERE customer_code = '".$customer_code."' ORDER BY is_default DESC LIMIT 1");
		if(dbNumRows($qs) == 1)
		{
			$result = dbFetchArray($qs);
			foreach($result as $key => $val)
			{
				$this->$key = $val;
			}
		}
	}
	


	public function getAddressByCode(	$online_code)
	{
		return dbQuery("SELECT * FROM tbl_address_online WHERE customer_code = '".$online_code."'");
	}




	public function getProvince($online_code)
	{
		$sc = '';
		$qs = dbQuery("SELECT province FROM tbl_address_online WHERE customer_code = '".$online_code."' LIMIT 1");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}


	public function isExists($customer_code, $address)
	{
		$qs = dbQuery("SELECT id FROM tbl_address_online WHERE customer_code = '".$customer_code."' AND address1 = '".$address."'");
		if(dbNumRows($qs) > 0)
		{
			return TRUE;
		}

		return FALSE;
	}



	public function clearProperties()
	{
		$this->id = '';
		$this->customer_code = '';
		$this->first_name = '';
		$this->last_name = '';
		$this->address1 = '';
		$this->address2 = '';
		$this->district = '';
		$this->province = '';
		$this->postcode = '';
		$this->phone = '';
		$this->email = '';
		$this->alias = '';
		$this->is_default = '';
	}


}

?>
