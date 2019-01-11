<?php
class barcode
{

public $id;
public $barcode;
public $id_product;
public $reference;
public $unit_code;
public $unit_qty;
public $error;

public function __construct($id='')
{
	if($id !='')
	{
		$qs = dbQuery("SELECT * FROM tbl_barcode WHERE id= '".$id."'");
		if(dbNumRows($qs) == 1)
		{
			$rs = dbFetchObject($qs);
			$this->id = $rs->id;
			$this->barcode = $rs->barcode;
			$this->id_product = $rs->id_product;
			$this->reference = $rs->reference;
			$this->unit_code = $rs->unit_code;
			$this->unit_qty = $rs->unit_qty;
		}
	}
}



public function add(array $ds)
{
	$sc = FALSE;
	if( count($ds) > 0 )
	{
		$fields = "";
		$values = "";
		$i = 1;
		foreach($ds as $field => $value )
		{
			$fields .= $i == 1 ? $field : ", ".$field;
			$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
			$i++;
		}
		$sc = dbQuery("INSERT INTO tbl_barcode (".$fields.") VALUES (".$values.")");
		$this->error = $sc === FALSE ? dbError() : '';
	}

	return $sc;
}

/*
public function update($id, array $ds)
{
	$sc = FALSE;
	if( count( $ds ) > 0 )
	{
		$set = "";
		$i = 1;
		foreach( $ds as $field => $value )
		{
			$set .= $i== 1 ? $field ." = '".$value."'" : ", ".$field . " = '".$value."'";
			$i++;
		}
		$sc = dbQuery("UPDATE tbl_barcode SET ".$set." WHERE id = '".$id."'");
		$this->error = $sc === FALSE ? dbError() : '';
	}

	return $sc;
}
*/


public function update($barcode, array $ds)
{
	$sc = FALSE;
	if( count( $ds ) > 0 )
	{
		$set = "";
		$i = 1;
		foreach( $ds as $field => $value )
		{
			$set .= $i== 1 ? $field ." = '".$value."'" : ", ".$field . " = '".$value."'";
			$i++;
		}
		$sc = dbQuery("UPDATE tbl_barcode SET ".$set." WHERE barcode = '".$barcode."'");
		$this->error = $sc === FALSE ? dbError() : '';
	}

	return $sc;
}



public function delete($barcode)
{
	return dbQuery("DELETE FROM tbl_barcode WHERE barcode = '" .$barcode."'");
}




public function isExists($barcode)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT barcode FROM tbl_barcode WHERE barcode = '".$barcode."'");
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;
}


public function isAllExists($id, $barcode)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT barcode FROM tbl_barcode WHERE barcode = '".$barcode."' OR id = '".$id."'");
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;
}


public function isDuplicate($id, $barcode)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT * FROM tbl_barcode WHERE barcode = '".$barcode."' AND id != '".$id."'");
	if(dbNumRows($qs) > 0)
	{
		$sc = TRUE;
	}

	return $sc;
}



public function getBarcode($id_pd)
{
	$sc = "";
	$qs = dbQuery("SELECT barcode FROM tbl_barcode WHERE id_product = '".$id_pd."' AND unit_qty = 1 LIMIT 1")	;
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}



public function getBarcodes($id_pd)
{
	return dbQuery("SELECT * FROM tbl_barcode WHERE id_product = '".$id_pd."'");
}


public function getDetail($barcode)
{
	$qs = dbQuery("SELECT * FROM tbl_barcode WHERE barcode = '".$barcode."'");
	if( dbNumRows($qs) == 1 )
	{
		return dbFetchObject($qs);
	}

	return FALSE;
}




//---	เอาเฉพาะไอดีสินค้า
public function getProductId($barcode)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT id_product FROM tbl_barcode WHERE barcode = '".$barcode."'");
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}

	return $sc;
}

}//// end class



?>
