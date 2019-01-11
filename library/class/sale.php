<?php
class sale
{
	public $id;
	public $code;
	public $name;
	public $id_group;
	public $user_name;
	public $active;
	public $is_delete;
	public $emp_delete;
	public $date_upd;
	public $error;



	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_sale WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id			= $rs->id;
				$this->code		= $rs->code;
				$this->name		= $rs->name;
				$this->id_group = $rs->id_group;
				$this->user_name 	= $rs->user_name;
				$this->active	= $rs->active;
				$this->is_delete	= $rs->is_deleted;
				$this->emp_delete	= $rs->emp_delete;
				$this->date_upd	= $rs->date_upd;
			}
		}
	}






	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i== 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_sale (".$fields.") VALUES (".$values.")");
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
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '".$value."'" : ", ". $field . " = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_sale SET ".$set." WHERE id = '".$id."'");
			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}

		return $sc;
	}








	public function delete( $id , $emp)
	{
		return  dbQuery("UPDATE tbl_sale SET is_deleted = 1, emp_delete = ".$emp." WHERE id = '".$id."'");
	}






	public function unDelete( $id, $emp )
	{
		return dbQuery("UPDATE tbl_sale SET is_deleted = 0, emp_delete = ".$emp." WHERE id = '".$id."'");
	}





	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_sale WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}

		return $sc;
	}






	public function validUserName($id, $userName)
	{
		$sc = TRUE;
		$qs = dbQuery("SELECT id FROM tbl_sale WHERE user_name = '".$userName."' AND id != '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = FALSE;
		}

		return $sc;
	}






	public function getNameByCode($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_sale WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray( $qs );
		}

		return  $sc;
	}






	public function getSaleName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_sale WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray( $qs );
		}

		return  $sc;
	}





	public function getSaleId($code)
	{
		$sc = '0000';
		$qs = dbQuery("SELECT id FROM tbl_sale WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}










	public function getName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_sale WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray( $qs );
		}

		return  $sc;
	}





	public function getId($code)
	{
		$sc = '0000';
		$qs = dbQuery("SELECT id FROM tbl_sale WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}





	public function getCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_sale WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}


}//--- end class


?>
