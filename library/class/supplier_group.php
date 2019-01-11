<?php
class supplier_group
{
	public $id;
	public $code;
	public $name;
	public $error;

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_supplier_group WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchArray($qs);
				$this->id		= $rs->id;
				$this->code	= $rs->code;
				$this->name	= $rs->name;
			}
		}
	}



	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields 	= "";
			$values 	= "";
			$i 			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ', '.$field;
				$values	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_supplier_group (".$fields.") VALUES (".$values.")");

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
		if( count($ds) > 0 )
		{
			$set 	= "";
			$i 		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;
			}

			$sc = dbQuery("UPDATE tbl_supplier_group SET ".$set." WHERE id = '".$id."'");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}



	public function delete($id)
	{
		$sc = dbQuery("DELETE FROM tbl_supplier_group WHERE id = '".$id."'");
		if( $sc === FALSE)
		{
			$this->error = dbError();
		}

		return $sc;
	}


	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_supplier_group WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function hasMember($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_supplier WHERE id_group = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function countMember($id)
	{
		$qs = dbQuery("SELECT COUNT(*) FROM tbl_supplier WHERE id_group = '".$id."'");
		list( $sc ) = dbFetchArray($qs);
		return $sc;
	}



	public function getGroupCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_supplier_group WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getGroupId($code)
	{
		$sc = '0000';
		$qs = dbQuery("SELECT id FROM tbl_supplier_group WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray( $qs );
		}
		return $sc;
	}

}//----- End class


?>
