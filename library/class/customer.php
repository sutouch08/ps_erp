<?php
class customer {
	public $id; 				//-- id customer
	public $code;			//-- Customer code
	public $name;			//-- Customer's name
	public $address1;
	public $address2;
	public $address3;
	public $tel;				//-- Phone No.
	public $fax;				//-- Fax No.
	public $m_id;			//-- Citizen ID
	public $tax_id;
	public $contact;			//-- Contact Person Name
	public $email;
	public $id_kind;
	public $id_type;
	public $id_class;
	public $id_group;		//-- Group of customer
	public $id_area;		//-- Area of customer
	public $id_sale;		//-- Sale of Customer
	public $credit;			//-- Credti Amount
	public $term;			//-- Credit Term
	public $address_no;	//-- address no like 75/65,
	public $room_no;		//-- Room no.
	public $floor_no;		//-- ชั้นที่
	public $building;
	public $soi;				//-- ซอย
	public $village_no;		//-- Moo
	public $road;
	public $tambon;			//-- ตำบล
	public $amphur;			//-- อำเภอ
	public $province;		//-- จังหวัด
	public $zip;				//-- Post code , zip code
	public $active;			//-- status 1 = Active,  0 = Inactive
	public $is_deleted;
	public $emp;				//--- employee who delete or restore
	public $date_upd;
	public $error;

	public function __construct( $id = '')
	{
		if( $id != '' )
		{
			$this->getData($id);
		}
	}




	public function getData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchArray($qs);
			foreach($rs as $key => $value)
			{
				$this->$key = $value;
			}

		}
	}


	public function getDataByCode($code)
	{
		$qs = dbQuery("SELECT * FROM tbl_customer WHERE code = '".$code."'");
		if(dbNumRows($qs) == 1)
		{
			return dbFetchObject($qs);
		}

		return FALSE;
	}


	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_customer (".$fields.") VALUES (".$values.")");
			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}






	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_customer SET " . $set . " WHERE id = '".$id."'");
			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}





	public function delete($id, $emp)
	{
		$sc = FALSE;
		if( $this->hasTransection($id) === FALSE )
		{
			$sc = dbQuery("DELETE FROM tbl_customer WHERE id = '".$id."'");
		}
		else
		{
			$sc = dbQuery("UPDATE tbl_customer SET is_deleted = 1, emp = ".$emp." WHERE id = '". $id ."'");
		}

		if( $sc === FALSE)
		{
			$this->error = dbError();
		}

		return $sc;
	}






	public function unDelete($id, $emp)
	{
		return dbQuery("UPDATE tbl_customer  SET is_deleted = 0, emp = ".$emp." WHERE id = '".$id."'");
	}






	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}






	public function getCustomerCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}







	public function getId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_customer WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function hasTransection($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_order WHERE id_customer = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}






	public function getName($id)
	{
		$cs = '';
		$qs = dbQuery("SELECT name FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $cs ) = dbFetchArray($qs);
		}
		return $cs;
	}






	public function getSale($id)
	{
		$sc = "0000";
		$qs = dbQuery("SELECT id_sale FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}







	public function getTerm($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT term FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}





	public function getCredit($id)
	{
		$sc = 0.00;
		$qs = dbQuery("SELECT credit FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}



	public function getProvince($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT province FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}







	public function searchId($txt)
	{
		return dbQuery("SELECT id FROM tbl_customer WHERE name LIKE '%".$txt."%'");
	}





	public function search($txt, $fields = "", $limit = 50)
	{
		if( $fields == "" )
		{
			return dbQuery("SELECT * FROM tbl_customer WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%' AND active = 1 LIMIT ".$limit);
		}
		else
		{
			return dbQuery("SELECT ".$fields." FROM tbl_customer WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%' AND active = 1 LIMIT ".$limit);
		}
	}



	public function search_sale_customer($txt, $id_sale, $limit=50)
	{
		$qr  = "SELECT * FROM tbl_customer ";
		$qr .= "WHERE (name LIKE '%".$txt."%' OR code LIKE '%".$txt."%') ";
		$qr .= "AND active = 1 AND id_sale = '".$id_sale."' LIMIT ".$limit;
		return dbQuery($qr);
	}




}//--- end class


?>
