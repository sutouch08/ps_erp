<?php
class customer_kind
{
	public $id;
	public $code;
	public $name;
	public function __construct($id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_customer_kind WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs 				= dbFetchObject($qs);
				$this->id 		= $rs->id;
				$this->code 	= $rs->code;
				$this->name	 	= $rs->name;	
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
			$sc = dbQuery("INSERT INTO tbl_customer_kind (".$fields.") VALUES (".$values.")");
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
			$sc = dbQuery("UPDATE tbl_customer_kind SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	
	public function isExists($field, $value, $id='')
	{
		$sc = FALSE;
		if( $id != '' )
		{
			$qs = dbQuery("SELECT id FROM tbl_customer_kind WHERE ".$field." = '".$value."' AND id != '".$id."'");
		}
		else
		{
			$qs = dbQuery("SELECT id FROM tbl_customer_kind WHERE ".$field." = '".$value."'");
		}
		
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_customer_kind WHERE id = '".$id."'");
	}
	
	public function hasMember($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_customer WHERE id_kind = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
	public function countMember($id)
	{
		$qs = dbQuery("SELECT COUNT(*) FROM tbl_customer WHERE id_kind = '".$id."'");
		list( $sc ) = dbFetchArray($qs);
		return  $sc;			
	}
	
	
	public function removeMember($id)
	{
		return dbQuery("UPDATE tbl_customer SET id_kind = 0 WHERE id_kind = ".$id);	
	}	
	
	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_customer_kind WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	
	
	public function getId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_customer_kind WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;	
	}
	
	
	public function getNameByCode($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_customer_kind WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	public function getName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_customer_kind WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	public function getData()
	{
		return dbQuery("SELECT * FROM tbl_customer_kind");	
	}
}

?>