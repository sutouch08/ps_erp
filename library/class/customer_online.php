<?php
class customer_online
{
	public $id;
	public $code;
	public $name;

	public function __construct($id="")
	{
		if( $id !="")
		{
			$qs = dbQuery("SELECT * FROM tbl_customer_online WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->code	= $rs->code;
				$this->name = $rs->lname;
			}
		}
	}




	public function add(array $ds = array() )
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
			$sc = dbQuery("INSERT INTO tbl_customer_online (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}



	public function getName($code)
	{
		$qs = dbQuery("SELECT first_name, last_name FROM tbl_address_online WHERE customer_code = '".$code."'");
		if(dbNumRows($qs) > 0)
		{
			list($sc) = dbFetchArray($qs);
		}
		else
		{
			$sc = $code;
		}

		return $sc;
	}


	public function isExists($code)
	{
		$qs = dbQuery("SELECT id FROM tbl_customer_online WHERE code = '".$code."'");
		if( dbNumRows($qs) > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}






	public function search($txt, $fields = "")
	{
		if( $fields == "" )
		{
			return dbQuery("SELECT * FROM tbl_customer_online WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
		}
		else
		{
			return dbQuery("SELECT ".$fields." FROM tbl_customer_online WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
		}
	}


}

?>
