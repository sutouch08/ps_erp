<?php
class kind
{
	public $id;
	public $code;
	public $name;

	public function __construct($id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_product_kind WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->code	= $rs->code;
				$this->name	= $rs->name;
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
			$sc = dbQuery("INSERT INTO tbl_product_kind (".$fields.") VALUES (".$values.")");
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
			$sc = dbQuery("UPDATE tbl_product_kind SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}


	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_product_kind WHERE id = '".$id."'");
	}


	public function removeMember($id)
	{
		return dbQuery("UPDATE tbl_product SET id_kind = '0' WHERE id_kind = '".$id."'");
	}


	public function isExists($field, $value, $id='')
	{
		$sc = FALSE;
		if( $id != '' )
		{
			$qs = dbQuery("SELECT id FROM tbl_product_kind WHERE ".$field." = '".$value."' AND id != '".$id."'");
		}
		else
		{
			$qs = dbQuery("SELECT id FROM tbl_product_kind WHERE ".$field." = '".$value."'");
		}

		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function getCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_product_kind WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}


	public function getName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_product_kind WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}




	public function getProductKind()
	{
		return dbQuery("SELECT * FROM tbl_product_kind");
	}




	public function countMember($id)
	{
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id_kind = '".$id."' GROUP BY id_style");
		return dbNumRows($qs);
	}


}//--- end class
?>
