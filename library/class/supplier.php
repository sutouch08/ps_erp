<?php
class supplier
{
	public $id;
	public $code;
	public $name;
	public $id_group;
	public $credit_term;
	public $active;
	public $is_deleted;
	public $emp;
	public $date_upd;
	public $error;

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_supplier WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id			= $rs->id;
				$this->code	 	= $rs->code;
				$this->name		= $rs->name;
				$this->id_group = $rs->id_group;
				$this->credit_term		= $rs->credit_term;
				$this->active	= $rs->active;
				$this->is_deleted	= $rs->is_deleted;
				$this->emp		= $rs->emp;
				$this->date_upd	= $rs->date_upd;
			}
		}
	}



	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_supplier (".$fields.") VALUES (".$values.")");

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
			$i 		= 1;
			foreach( $ds as $field => $value )
			{
				$set	.= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;
			}

			$sc = dbQuery("UPDATE tbl_supplier SET ".$set." WHERE id = '".$id."'");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}





	public function delete($id, $emp)
	{
		if( $this->hasTransection($id) === FALSE )
		{
			return dbQuery("DELETE FROM tbl_supplier WHERE id = '".$id."'");
		}
		else
		{
			return dbQuery("UPDATE tbl_supplier SET is_deleted = 1, emp = ".$emp." WHERE id = '".$id."'");
		}
	}




	public function unDelete($id, $emp)
	{
		return dbQuery("UPDATE tbl_supplier SET is_deleted = 0, emp = ".$emp." WHERE id = '".$id."'");
	}




	public function hasTransection($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_po WHERE id_supplier = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}




	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_supplier WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_supplier WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getId($code)
	{
		$sc = '';
		$qs = dbQuery("SELECT id FROM tbl_supplier WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}

	public function getName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_supplier WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getNameByCode($code)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_supplier WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function search($txt)
	{
		return dbQuery("SELECT id, code, name FROM tbl_supplier WHERE name LIKE '%".$txt."%' OR code LIKE '%".$txt."%'");
	}


	public function autocomplete($txt, $fields, $limit = 50)
	{
		if($txt == '*')
		{
			return dbQuery("SELECT ".$fields." FROM tbl_supplier ORDER BY code ASC LIMIT ".$limit);
		}
		else
		{
			return dbQuery("SELECT ".$fields." FROM tbl_supplier WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%' ORDER BY code ASC LIMIT ".$limit);
		}
	}
}//----end class
?>
