<?php

class product_group {

	public $id;
	public $code;
	public $name;
	public $error;

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs =dbQuery("SELECT * FROM tbl_product_group WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id 	= $rs->id;
				$this->code	= $rs->code;
				$this->name = $rs->name;
			}
		}
	}



	public function add(array $ds)
	{
		$sc 		= FALSE;
		$fields 	= '';
		$values	= '';
		$n			= count($ds);
		$i			= 1;
		if( $n > 0 )
		{
			foreach( $ds as $field => $value )
			{
				$fields 	.= $i == 1 ? $field : ', '.$field;
				$values  .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_product_group (".$fields.") VALUES (".$values.")");
			if( $sc === FALSE )
			{
				$this->error = dbError();
			}
		}

		return $sc;
	}




	public function update( $id, array $ds)
	{
		$sc 	= FALSE;
		$set 	= '';
		$n		= count($ds);
		$i		= 1;
		if( $n > 0 )
		{
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_product_group SET ". $set ." WHERE id = '".$id."'");
			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}





	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_product_group WHERE id = '".$id."'");
	}




	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_product_group WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}

		return $sc;
	}




	public function getProductGroupId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_group WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getProductGroupCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_product_group WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getProductGroupName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_product_group WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_group WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_product_group WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_product_group WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getProductGroup()
	{
		return dbQuery("SELECT * FROM tbl_product_group WHERE active = 1");
	}


	public function countMember($id)
	{
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id_group = '".$id."' GROUP BY id_style");
		return dbNumRows($qs);
	}



	public function setActive($id, $active)
	{
		$sc = dbQuery("UPDATE tbl_product_group SET active = ".$active." WHERE id = '".$id."'");
		if($sc === FALSE)
		{
			$this->error = dbError();
		}

		return $sc;
	}

}


?>
