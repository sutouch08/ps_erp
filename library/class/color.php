<?php
class color
{
	public $id;
	public $code;
	public $name;
	public $id_group;
	public $error;
	public function __construct($id = "" )
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_color WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->code	= $rs->code;
				$this->name	= $rs->name;
				$this->id_group = $rs->id_group;
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
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_color (".$fields.") VALUES (".$values.")");
		}

		if( $sc === FALSE )
		{
			$this->error = dbError();
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
			$sc = dbQuery("UPDATE tbl_color SET " . $set . " WHERE id = '".$id."'");
			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}





	public function delete($id)
	{
		$sc = dbQuery("DELETE FROM tbl_color WHERE id = '".$id."'");
		if( $sc === FALSE )
		{
			$this->error = 'ลบรายการไม่สำเร็จ';
		}
		return $sc;
	}




	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_color WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function getGroupName($id_group)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_color_group WHERE id = '".$id_group."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function changeColorGroup($id, $id_group)
	{
		$sc = dbQuery("UPDATE tbl_color SET id_group = '".$id_group."' WHERE id = '".$id."'");
		if( 	$sc === FALSE )
		{
			$this->error = 	'เปลี่ยนกลุ่มไม่สำเร็จ';
		}
		return $sc;
	}



	public function getColorId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_color WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getColorCode($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT code FROM tbl_color WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getColorName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_color WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_color WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getCode($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT code FROM tbl_color WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_color WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getGroupCode($id_color)
	{
		$sc = '';
		$qs = dbQuery("SELECT g.code FROM tbl_color AS c JOIN tbl_color_group AS g ON c.id_group = g.id WHERE c.id = '".$id_color."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}


}////

?>
