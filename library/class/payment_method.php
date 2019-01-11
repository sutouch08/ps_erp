
<?php
class payment_method
{
	public $id;
	public $code;
	public $name;
	public $isDefault;
	public $hasTerm;
	
	public function __construct($id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_payment_method WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs 				= dbFetchObject($qs);
				$this->id 		= $rs->id;
				$this->code 	= $rs->code;
				$this->name	 	= $rs->name;	
				$this->isDefault = $rs->isDefault;
				$this->hasTerm = $rs->hasTerm;
			}
		}
	}
	
	
	public function add(array $ds )
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
			$sc = dbQuery("INSERT INTO tbl_payment_method (".$fields.") VALUES (".$values.")");
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
			$sc = dbQuery("UPDATE tbl_payment_method SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	
	
	
	
	public function setDefault($id)
	{
		$sc = FALSE;
		if( $this->clearDefault() )
		{
			$sc = dbQuery("UPDATE tbl_payment_method SET isDefault = 1 WHERE id = ".$id);
		}
		return $sc;
	}
	
	
	
	
	
	
	
	public function setTerm($id, $val)
	{
		return dbQuery("UPDATE tbl_payment_method SET hasTerm = ".$val." WHERE id = ".$id);
	}
	
	
	
	
	
	
	public function clearDefault()
	{
		return dbQuery("UPDATE tbl_payment_method SET isDefault = 0");	
	}
	
	
	
	
	public function isExists($field, $value, $id='')
	{
		$sc = FALSE;
		if( $id != '' )
		{
			$qs = dbQuery("SELECT id FROM tbl_payment_method WHERE ".$field." = '".$value."' AND id != '".$id."'");
		}
		else
		{
			$qs = dbQuery("SELECT id FROM tbl_payment_method WHERE ".$field." = '".$value."'");
		}
		
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	
	
	public function hasTerm($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_payment_method WHERE id = ".$id." AND hasTerm = 1");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_payment_method WHERE id = '".$id."'");
	}

	



	
	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_payment_method WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	
	
	
	
	public function getId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_payment_method WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;	
	}
	
	
	
	
	
	public function getNameByCode($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_payment_method WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	
	
	
	public function getName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_payment_method WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	
	
	public function getData()
	{
		return dbQuery("SELECT * FROM tbl_payment_method");	
	}
	
	
	
	
	public function getDefaultId()
	{
		$sc = "";
		$qs = dbQuery("SELECT id FROM tbl_payment_method WHERE isDefault = 1");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	
	public function searchId($txt)
	{
		return dbQuery("SELECT id FROM tbl_payment_method WHERE name LIKE '%".$txt."%' OR code LIKE '%".$txt."%'");
	}	
}

?>