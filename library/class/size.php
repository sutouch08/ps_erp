<?php
class size
{
	public $id;
	public $code;
	public $name;
	public $position;
	public $error;

	public function __construct($id = "" )
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_size WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->code	= $rs->code;
				$this->name	= $rs->name;
				$this->position	= $rs->position;
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
			$sc = dbQuery("INSERT INTO tbl_size (".$fields.") VALUES (".$values.")");

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
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_size SET " . $set . " WHERE id = '".$id."'");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}





	public function delete($id)
	{
		$pos = $this->getPosition($id);
		$sc = dbQuery("DELETE FROM tbl_size WHERE id = '".$id."'");
		if( $sc === FALSE )
		{
			$this->error = 'ลบรายการไม่สำเร็จ';
		}
		else
		{
			$this->updateDeletePosition($pos);
		}
		return $sc;
	}





	public function updateDeletePosition($pos)
	{
		return dbQuery("UPDATE tbl_size SET position = position - 1 WHERE position > ".$pos);
	}




	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_size WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function getNextPosition()
	{
		$sc = 0;
		$qs = dbQuery("SELECT MAX( position ) AS max FROM tbl_size");
		list( $max ) = dbFetchArray($qs);
		if( ! is_null( $max ) )
		{
			$sc = $max;
		}
		return $sc + 1;
	}




	public function getMaxPosition()
	{
		$sc = 1;
		$qs = dbQuery("SELECT MAX( position ) AS max FROM tbl_size");
		list( $max ) = dbFetchArray($qs);
		if( ! is_null( $max ) )
		{
			$sc = $max;
		}
		return $sc;
	}





	public function getMinPosition()
	{
		$sc = 1;
		$qs = dbQuery("SELECT MIN( position ) AS min FROM tbl_size");
		list( $min ) = dbFetchArray($qs);
		if( ! is_null( $min ) )
		{
			$sc = $min;
		}
		return $sc;
	}




	public function decresePosition($id, $pos)
	{
		$sc = FALSE;
		$target = $pos - 1;
		$id_a		= $this->getIdByPos($target);
		if(dbQuery("UPDATE tbl_size SET position = ".$target." WHERE id = '".$id."'") === TRUE )
		{
			$sc = dbQuery("UPDATE tbl_size SET position = ".$pos." WHERE id = '".$id_a."'");
		}
		return $sc;
	}




	public function incresePosition($id, $pos)
	{
		$sc = FALSE;
		$target = $pos + 1;
		$id_a		= $this->getIdByPos($target);
		if(dbQuery("UPDATE tbl_size SET position = ".$target." WHERE id = '".$id."'") === TRUE )
		{
			$sc = dbQuery("UPDATE tbl_size SET position = ".$pos." WHERE id = '".$id_a."'");
		}
		return $sc;
	}




	public function getIdByPos($pos)
	{
		$id = 1;
		$qs = dbQuery("SELECT id FROM tbl_size WHERE position = ".$pos);
		if( dbNumRows($qs) > 0 )
		{
			list( $id ) = dbFetchArray($qs);
		}

		return $id;
	}




	public function getPosition($id)
	{
		$sc = 1;
		$qs = dbQuery("SELECT position FROM tbl_size WHERE id = '".$id."'");
		if( dbNumRows( $qs ) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getSizeId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_size WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getSizeCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_size WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getSizeName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_size WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_size WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_size WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_size WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getGroupCode($id_size)
	{
		$sc = '';
		$qs = dbQuery("SELECT g.code FROM tbl_size AS s JOIN tbl_size_group AS g ON s.id_group = g.id WHERE s.id = '".$id_size."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}


	public function getGroupName($id_size)
	{
		$sc = '';
		$qs = dbQuery("SELECT g.name FROM tbl_size AS s JOIN tbl_size_group AS g ON s.id_group = g.id WHERE s.id = '".$id_size."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}



}////

?>
