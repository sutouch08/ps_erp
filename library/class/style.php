<?php
class style
{
	public $id;
	public $code;
	public $name;
	public $active;
	public $show_in_sale;
	public $show_in_customer;
	public $show_in_online;
	public $can_sell;
	public $is_deleted;
	public $error = '';

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_product_style WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchArray($qs);
				foreach($rs as $key => $value)
				{
					$this->$key = $value;
				}
			}
		}
	}


	public function add(array $ds )
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_product_style (".$fields.") VALUES (".$values.")");

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
			$sc = dbQuery("UPDATE tbl_product_style SET " . $set . " WHERE id = '".$id."'");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}


	public function delete($id)
	{
		$sc = FALSE;
		if( $this->hasTransection($id) === FALSE )
		{
			$sc = dbQuery("DELETE FROM tbl_product_style WHERE id = '".$id."'");
		}
		else
		{
			$this->error = "Transection Exists";
		}
		return $sc;
	}




	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_style WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}


	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_product_style WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_product_style WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getStyleId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_style WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function getStyleCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT code FROM tbl_product_style WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getStyleName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_product_style WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getName($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT name FROM tbl_product_style WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function hasTransection($id)
	{
		$sc = FALSE;
		//--- Stock
		$stock	= dbNumRows( dbQuery("SELECT id_style FROM tbl_stock AS s JOIN tbl_product AS p ON s.id_product = p.id_product WHERE id_style = '".$id."'") );
		//--- Receive
		$receive	= dbNumRows( dbQuery("SELECT id_style FROM tbl_receive_detail AS r JOIN tbl_product AS p ON r.id_product = p.id_product WHERE id_style = '".$id."'") );
		//--- Order
		$order 	= dbNumRows( dbQuery("SELECT id_style FROM tbl_order_detail AS o JOIN tbl_product AS p ON o.id_product = p.id_product WHERE id_style = '".$id."'") );
		//--- PO
		$po		= dbNumRows( dbQuery("SELECT id_style FROM tbl_po AS po JOIN tbl_product AS pd ON po.id_product = pd.id_product WHERE id_style = '".$id."'") );

		$transection		= $stock + $receive + $order + $po;

		if( $transection > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}


	public function search($txt, $fields="")
	{
		if( $fields == "" )
		{
			return dbQuery("SELECT * FROM tbl_product_style WHERE active = 1 AND code LIKE '%".$txt."%'");
		}
		else
		{
			return dbQuery("SELECT ".$fields." FROM tbl_product_style WHERE active = 1 AND code LIKE '%".$txt."%'");
		}

	}



	public function autocomplete($txt, $fields = "", $limit = 50)
	{
		if($fields == '')
		{
			if( $txt == '*')
			{
				$sc = dbQuery("SELECT * FROM tbl_product_style LIMIT ".$limit);
			}
			else
			{
				$sc = dbQuery("SELECT * FROM tbl_product_style WHERE code LIKE '%".$txt."%' LIMIT ".$limit);
			}
		}
		else
		{
			if( $txt != '*')
			{
				$sc = dbQuery("SELECT ".$fields." FROM tbl_product_style WHERE code LIKE '%".$txt."%' LIMIT ".$limit);
			}
			else
			{
				$sc = dbQuery("SELECT ".$fields." FROM tbl_product_style LIMIT ".$limit);
			}

		}

		return $sc;
	}


	public function isShowInSale($id_style)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_style WHERE id_style = '".$id_style."' AND show_in_sale = 1");
		if(dbNumRows($qs) == 1)
		{
			$sc = TRUE;
		}

		return $sc;
	}

	public function isShowInCustomer($id_style)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_style WHERE id_style = '".$id_style."' AND show_in_customer = 1");
		if(dbNumRows($qs) == 1)
		{
			$sc = TRUE;
		}

		return $sc;
	}




}//--- End class

?>
