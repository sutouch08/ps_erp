<?php
class unit {
	public $id;
	public $code;
	public $name;
	public $error;

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_unit WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$ths->id		= $rs->id;
				$this->code = $rs->code;
				$this->name = $rs->name;
			}
		}
	}






	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields = '';
			$values = '';
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields 	.= $i == 1 ? $field : ", ".$field;
				$values 	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			
			$sc = dbQuery("INSERT INTO tbl_unit (".$fields.") VALUES (".$values.")");

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
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '".$value."'" : ", ".$field . " = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_unit SET ".$set." WHERE id = '".$id."'");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}






	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_unit WHERE id = '".$id."'");
	}





	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_unit WHERE id = '". $id. "'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function getName($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_unit WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc 	) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getUnitId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_unit WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getUnitCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_unit WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getUnitName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_unit WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





}// end class

?>
