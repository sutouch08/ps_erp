<?php
class customer_area
{
	public $id;
	public $code;
	public $name;
	public $error;

	public function __construct($id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_customer_area WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs 				= dbFetchObject($qs);
				$this->id 		= $rs->id;
				$this->code 	= $rs->code;
				$this->name	 	= $rs->name;
			}
		}
	}



	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			if( $this->isExists($ds['id']) === FALSE )
			{
				$sc = dbQuery("INSERT INTO tbl_customer_area (id, code, name) VALUES ('".$ds['id']."', '".$ds['code']."', '".$ds['name']."')");

				if( $sc === FALSE)
				{
					$this->error = dbError();
				}

			}
		}

		return $sc;
	}


	public function update($id, array $ds)
	{
		$sc = dbQuery("UPDATE tbl_customer_area SET code = '".$ds['code']."', name = '".$ds['name']."' WHERE id = '".$id."'");
		if( $sc === FALSE)
		{
			$this->error = dbError();
		}

		return $sc;
	}


	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_customer_area WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}




	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_customer_area WHERE id = '".$id."'");
	}





	public function hasMember($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_customer WHERE id_area = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function countMember($id)
	{
		$qs = dbQuery("SELECT COUNT(*) FROM tbl_customer WHERE id_area = '".$id."'");
		list( $sc ) = dbFetchArray($qs);
		return  $sc;
	}




	public function getAreaCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_customer_area WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getAreaId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_customer_area WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getAreaNameByCode($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_customer_area WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getAreaName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_customer_area WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


}

?>
