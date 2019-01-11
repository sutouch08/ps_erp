<?php

class product
{
	public $id;
	public $code;
	public $name;
	public $id_style;
	public $id_color;
	public $id_size;
	public $id_kind;
	public $id_type;
	public $id_group;
	public $id_category;
	public $id_brand;
	public $year;
	public $cost;
	public $price;
	public $id_unit;
	public $weight;
	public $width;
	public $length;
	public $height;
	public $count_stock;
	public $show_in_sale;
	public $show_in_customer;
	public $show_in_online;
	public $can_sell;
	public $active;
	public $is_deleted;
	public $emp;
	public $date_upd;
	public $error;
	public $barcode;

	public function __construct($id = '' )
	{
		if( $id != '' )
		{
			$this->getData($id);
		}
	}





	public function getData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchArray($qs);
			foreach( $rs as $key => $value)
			{
				$this->$key = $value;
			}
			$bc = new barcode();
			$this->barcode = $bc->getBarcode($id);
		}
	}


public function getDataByCode($code)
{
	$qs = dbQuery("SELECT * FROM tbl_product WHERE code = '".$code."'");
	if(dbNumRows($qs) == 1)
	{
		return dbFetchObject($qs);
	}

	return FALSE;
}




	public function add(array $ds)
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
			$sc = dbQuery("INSERT INTO tbl_product (".$fields.") VALUES (".$values.")");
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
			$sc = dbQuery("UPDATE tbl_product SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}






	public function updateProducts($id_style, array $ds)
	{
		$sc = TRUE;
		if( count( $ds ) > 0 )
		{
			$qs = $this->getProductsByStyle($id_style); ///-- get all products in this style
			if( dbNumRows($qs) > 0 )
			{
				startTransection();
				while( $rs = dbFetchObject($qs) )
				{
					if( ! $this->update($rs->id, $ds) )
					{
						$sc = FALSE;
					}
				}
				if( $sc === TRUE )
				{
					commitTransection();
				}
				else
				{
					dbRollback();
				}
				endTransection();
			}
		}

		return $sc;
	}





	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_product WHERE id = '".$id."'");
	}






	public function updateDescription($id_style, $desc)
	{
		if( $this->hasDescription($id_style) === TRUE )
		{
			return dbQuery("UPDATE tbl_product_detail SET description = '".$desc."' WHERE id_style = '".$id_style."'");
		}
		else
		{
			return dbQuery("INSERT INTO tbl_product_detail ( id_style, description ) VALUES ('".$id_style."', '".$desc."')");
		}
	}






	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function getDescription($id_style)
	{
		$sc = '';
		$qs = dbQuery("SELECT description FROM tbl_product_detail WHERE id_style = '".$id_style."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	public function hasDescription($id_style)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_detail WHERE id_style = '".$id_style."'");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function isDisactiveAll($id_style)
	{
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND active = 1");
		return dbNumRows($qs) > 0 ? FALSE : TRUE;
	}






	public function getProductsByStyle($id_style)
	{
		$qr = "SELECT p.* FROM tbl_product AS p ";
		$qr .= "LEFT JOIN tbl_color AS c ON p.id_color = c.id ";
		$qr .= "LEFT JOIN tbl_size AS s ON p.id_size = s.id ";
		$qr .= "WHERE p.id_style = '".$id_style."' ";
		$qr .= "ORDER BY c.code ASC, s.position ASC";
		return dbQuery($qr);
	}





	public function hasImage($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_image FROM tbl_product_image WHERE id_product = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}






	public function addImage($id, $id_image)
	{
		return dbQuery("INSERT INTO tbl_product_image (id_product, id_image) VALUES ('".$id."', '".$id_image."')");
	}






	public function updateImage($id, $id_image)
	{
		return dbQuery("UPDATE tbl_product_image SET id_image = '".$id_image."' WHERE id_product = '".$id."'");
	}






	public function getImageId($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT id_image FROM tbl_product_image WHERE id_product = '".$id."' LIMIT 1");
		if( dbNumRows($qs) > 0 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	///------- get images list of this style
	public function getProductImages($id_style)
	{
		return dbQuery("SELECT * FROM tbl_image WHERE id_style = '".$id_style."'");
	}






	//---- get Status of specific field
	public function getStatus($id, $field)
	{
		$sc = 0;
		$qs = dbQuery("SELECT ".$field." FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	//----- set status of specific field
	public function setStatus($id, $field, $val)
	{
		return dbQuery("UPDATE tbl_product SET ".$field." = '".$val."' WHERE id = '".$id."'");
	}





	public function getStyleId($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id_style FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray( $qs);
		}
		return $sc;
	}


	public function getStyleCode($id)
	{
		$sc  = '';
		$qr  = "SELECT s.code FROM tbl_product AS p JOIN tbl_product_style AS s ON p.id_style = s.id ";
		$qr .= "WHERE s.id = '".$id."'";
		$qs = dbQuery($qr);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}





	public function getAllColors($id_style)
	{
		$sc = array();
		$qs = dbQuery("SELECT p.id_color, c.code, c.name FROM tbl_product AS p JOIN tbl_color AS c ON p.id_color = c.id WHERE p.id_style = '".$id_style."' GROUP BY p.id_color ORDER BY c.code ASC");
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$sc[$rs->id_color]		= array("code" => $rs->code, "name" => $rs->name);
			}
		}
		return $sc;
	}






	public function getAllSizes($id_style)
	{
		$sc = array();
		$qs = dbQuery("SELECT p.id_size, s.code, s.name FROM tbl_product AS p JOIN tbl_size AS s ON p.id_size = s.id WHERE p.id_style = '".$id_style."' GROUP BY p.id_size ORDER BY s.position ASC");
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$sc[$rs->id_size]		= array("code" => $rs->code, "name" => $rs->name);
			}
		}
		return $sc;
	}





	public function countAttribute($id_style)
	{
		$color = dbNumRows(dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND id_color != '0' AND id_color != '' GROUP BY id_style"));
		$size = dbNumRows(dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND id_size != '0' AND id_size != '' GROUP BY id_style"));
		return $color + $size;
	}






	public function getNameByCode($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_product WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product WHERE code = '".$code."'");
		if( dbNumRows($qs ) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getCode($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT code FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getUnitCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT u.code FROM tbl_product AS p JOIN tbl_unit AS u ON p.id_unit = u.id WHERE p.id = '".$id."'");

		if(dbNumRows($qs) == 1 )
		{
			list($sc) = dbFetchArray($qs);
		}

		return $sc;
	}






	public function getStylePrice($id_style)
	{
		$sc = 0;
		$qs = dbQuery("SELECT MIN( price ) AS price FROM tbl_product WHERE id_style = '".$id_style."'");
		list( $price ) = dbFetchArray($qs);
		if( ! is_null( $price ) )
		{
			$sc = $price;
		}
		return $sc;
	}






	public function getPrice($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT price FROM tbl_product WHERE id_product = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function isCountStock($id_style)
	{
		$sc = TRUE;
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND count_stock = 0");
		if( dbNumRows($qs) > 0 )
		{
			$sc = FALSE;
		}
		return $sc;
	}





	//--- ยอดรวมทุกคลังทุกโซนทั้งขายได้และไม่ได้
	public function getStock($id, $id_branch = 0)
	{
		$stock = new stock();
		return $stock->getStock($id, $id_branch);
	}






	//---- ยอดรวมสินค้าที่สั่งได้
	public function getSellStock($id, $id_branch = 0)
	{
		$order = new order();
		$stock = new stock();
		$cancle = new cancle_zone();
		$buffer = new buffer();
		$sellStock = $stock->getSellStock($id, $id_branch);
		$reservStock = $order->getReservQty($id, $id_branch);
		//$cancleQty = 0; //$cancle->getCancleQty($id);
		//$bufferStock = $id_order == '' ? 0 : $buffer->getSumQty($id_order, $id);
		$availableStock = $sellStock - $reservStock;
		//return $availableStock < 0 ? 0 : $availableStock;
		return $availableStock < 0 ? 0 : $availableStock;
	}





	//---- ยอดรวมของรุ่นสินค้าที่สั่งได้
	public function getStyleSellStock($id_style, $id_branch = 0)
	{
		$order = new order();
		$stock = new stock();
		//$cancle = new cancle_zone();
		$sellStock = $stock->getStyleSellStock($id_style, $id_branch);
		$reservStock = $order->getStyleReservQty($id_style, $id_branch);
		//$cancleQty = 0; //$cancle->getStyleCancleQty($id_style);

		$availableStock = $sellStock - $reservStock;

		//return $availableStock < 0 ? 0 : $availableStock;
		return $availableStock < 0 ? 0 : $availableStock;
	}





	public function getCost($id)
	{
		$sc = 0.00;
		$qs = dbQuery("SELECT cost FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}


	public function getMinCode($code)
	{
		$style = new style();
		$id_style = $style->getId($code);
		$qs = dbQuery("SELECT MIN(code) FROM tbl_product WHERE id_style = '".$id_style."'");
		list($sc) = dbFetchArray($qs);

		return is_null($sc) ? $code : $sc;
	}


	public function getMaxCode($code)
	{
		$style = new style();
		$id_style = $style->getId($code);
		$qs = dbQuery("SELECT MAX(code) FROM tbl_product WHERE id_style = '".$id_style."'");
		list($sc) = dbFetchArray($qs);

		return is_null($sc) ? $code : $sc;
	}



	public function search($txt, $fields, $limit= 50)
	{
		if($txt == '*')
		{
			return dbQuery("SELECT $fields FROM tbl_product ORDER BY code ASC LIMIT ".$limit);
		}
		else
		{
			return dbQuery("SELECT $fields FROM tbl_product WHERE code LIKE '%".$txt."%' ORDER BY code ASC LIMIT ".$limit);
		}

	}




}//จบ class
?>
