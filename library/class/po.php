<?php
class po
{
	public $id;
	public $bookcode;
	public $code;
	public $reference;
	public $id_supplier;
	public $id_warehouse;
	public $credit_term;
	public $vat_type;
	public $vat_is_out;
	public $vat_amount;
	public $amount_ex;
	public $bill_discount;
	public $date_add;
	public $date_need;
	public $due_date;
	public $isCancle;
	public $status;
	public $error;

	public function __construct($reference = '')
	{
		if( $reference != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_po WHERE reference = '".$reference."' LIMIT 1");
			if( dbNumRows($qs) > 0 )
			{
				$rs = dbFetchArray($qs);
				foreach($rs as $key => $value)
				{
					$this->$key = $value;
				}
			}
		}
	}



	public function add( array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields 	.= $i == 1 ? $field : ", ".$field ;
				$values	.= $i == 1 ? "'".$value."'" : ", '".$value."'" ;
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_po (" . $fields.") VALUES (".$values.")");
		}
		return $sc;
	}



	public function update($bookcode, $reference, $id_pd, array $ds)
	{
		$sc = FALSE;
		if( !empty($ds) )
		{
			$set 	= "";
			$i 		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '".$value."'" : ", ". $field ." = '".$value."'";
				$i++;
			}

			$sc = dbQuery("UPDATE tbl_po SET ". $set ." WHERE bookcode = '".$bookcode."' AND reference = '".$reference."' AND id_product = '".$id_pd."'");
			$this->error = $sc === TRUE ? '' : dbError();
		}

		return $sc;
	}



	public function isExists($bookcode, $reference, $id_pd)
	{
		$sc  = FALSE;

		$qr  = "SELECT bookcode FROM tbl_po ";
		$qr .= "WHERE bookcode = '".$bookcode."' ";
		$qr .= "AND reference = '".$reference."' ";
		$qr .= "AND id_product = '".$id_pd."'";

		$qs = dbQuery($qr);

		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}

		return $sc;
	}




	public function isChanged($bookcode, $reference, $id_pd, $qty, $vat_amount, $amount)
	{
		$sc = FALSE;
		$qr  = "SELECT bookcode FROM tbl_po ";
		$qr .= "WHERE bookcode = '".$bookcode."' ";
		$qr .= "AND reference = '".$reference."' ";
		$qr .= "AND id_product = '".$id_pd."' ";
		$qr .= "AND (qty != '".$qty."' OR vat_amount != '".$vat_amount."' OR amount_ex != '".$amount."') ";
		$qs = dbQuery($qr);

		if(dbNumRows($qs) > 0)
		{
			$sc = TRUE;
		}

		return $sc;

	}





	public function close($bookcode, $reference)
	{
		//--- status 1 = not receive yet,  2 = received some,  3 = closed;
		return dbQuery("UPDATE tbl_po SET status = 3 WHERE bookcode = '".$bookcode."' AND reference = '".$reference."'");
	}




	public function unClose($bookcode, $reference)
	{
		$status = $this->makeStatus($bookcode, $reference);
		return dbQuery("UPDATE tbl_po SET status = ".$status." WHERE bookcode = '".$bookcode."' AND reference = '".$reference."'");
	}




	public function delete($reference)
	{
		return dbQuery("DELETE FROM tbl_po WHERE reference = '".$reference."'");
	}





	public function makeStatus($bookcode, $reference)
	{
		$sc = 1;
		$qs = dbQuery("SELECT received FROM tbl_po WHERE bookcode = '".$bookcode."' AND reference = '".$reference."' AND received > 0");
		if( dbNumRows($qs) > 0 )
		{
			$sc = 2;
		}
		return $sc;
	}






	public function setStatus($reference, $status = "")
	{
		$sc = FALSE;
		if( $reference != "" )
		{
			if( $status == "" )
			{
				$qs = dbQuery("SELECT received FROM tbl_po WHERE reference = '".$reference."' AND received > 0");
				if( dbNumRows($qs) > 0 )
				{
					$status = 2;
				}
				else
				{
					$status = 1;
				}
			}

			$sc = dbQuery("UPDATE tbl_po SET status = ".$status." WHERE reference = '".$reference."'");

		}

		return $sc;
	}




	public function getDetail($reference)
	{
		return dbQuery("SELECT * FROM tbl_po WHERE reference = '".$reference."' AND isCancle = 0 AND status != 3");
	}




	public function getPoDetail($reference)
	{
		return dbQuery("SELECT * FROM tbl_po WHERE reference = '".$reference."'");
	}




	public function getSupplierId($reference)
	{
		$sc = "";
		$qs = dbQuery("SELECT DISTINCT id_supplier FROM tbl_po WHERE reference = '".$reference."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function hasPO($reference)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_po WHERE reference = '".$reference."' AND status != 3 AND isCancle = 0");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function search($txt, $id_sup = '')
	{
		if($id_sup != '' && $id_sup != FALSE)
		{
			$qr  = "SELECT DISTINCT reference ";
			$qr .= "FROM tbl_po WHERE id_supplier = '".$id_sup."' ";
			$qr .= $txt == '*' ? '' : "AND reference LIKE '%".$txt."%' ";
			$qr .= "AND status != 3 AND isCancle = 0";
		}
		else
		{
			$qr  = "SELECT DISTINCT reference ";
			$qr .= "FROM tbl_po WHERE ";
			$qr .= "status != 3 AND isCancle = 0 ";
			$qr .= $txt == '*' ? '' : "AND reference LIKE '%".$txt."%' ";
		}

		return dbQuery($qr);
	}





	public function received($reference, $id_pd, $qty)
	{
		return dbQuery("UPDATE tbl_po SET received = received + ".$qty.", status = 2 WHERE reference = '".$reference."' AND id_product = '".$id_pd."'");
	}





	public function unReceived($reference, $id_pd, $qty)
	{
		$cQty = $this->getReceived($reference, $id_pd);
		$received = $cQty - $qty < 0 ? 0 : $cQty - $qty;
		return dbQuery("UPDATE tbl_po SET received = '".$received."' WHERE reference = '".$reference."' AND id_product = '".$id_pd."'");
	}




	public function getReceived($reference, $id_pd)
	{
		$sc = 0;
		$qs = dbQuery("SELECT received FROM tbl_po WHERE reference = '".$reference."' AND id_product = '".$id_pd."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	//--- ราคาสินค้า
	public function getPrice($reference, $id_pd)
	{
		$sc = 0;
		$qs = dbQuery("SELECT price FROM tbl_po WHERE reference = '".$reference."' AND id_product = '".$id_pd."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	//--- ส่วนลดระดับรายการสินค้า เป็นเงินบาท เช่น 10% หรือ 100
	public function getDiscount($reference, $id_pd)
	{
		$sc = 0;
		$qs = dbQuery("SELECT discount FROM tbl_po WHERE reference = '".$reference."' AND id_product = '".$id_pd."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getProductPrice($reference, $id_product)
	{
		$sc = 0;
		$qs = dbQuery("SELECT price FROM tbl_po WHERE reference = '".$reference."' AND id_product = '".$id_product."'");
		if( dbNumRows($qs) > 0)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}



	//---	รับสินค้าครบแล้วหรือยัง
	public function isCompleted($reference)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_po WHERE reference = '".$reference."' AND qty > received");
		if(dbNumRows($qs) == 0)
		{
			$sc = TRUE;
		}

		return $sc;
	}


}///---- end class


?>
