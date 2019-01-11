<?php

class cancle_zone
{
	public $id;
	public $id_order;
	public $id_style;
	public $id_product;
	public $qty;
	public $id_zone;
	public $id_warehouse;
	public $id_employee;
	public $date_upd;

	public function __construct($id = '')
	{
		if($id != '')
		{
			$qs = dbQuery("SELECT * FROM tbl_cancle WHERE id = '".$id."'");
			if(dbNumRows($qs) == 1)
			{
				$rs = dbFetchArray($qs);
				foreach($rs as $key => $value)
				{
					$this->$key = $value;
				}
			}
		}
	}


	public function updateCancle($id_order, $id_style, $id_product, $id_zone, $id_warehouse, $qty)
	{
		if( $this->isExists($id_order, $id_product, $id_zone))
		{
			return $this->update($id_order, $id_product, $id_zone, $qty);
		}
		else
		{
			return $this->add($id_order, $id_style, $id_product, $id_zone, $id_warehouse, $qty);
		}
	}



	public function add($id_order, $id_style, $id_product, $id_zone, $id_warehouse, $qty)
	{
		$qr  = "INSERT INTO tbl_cancle ";
		$qr .= "(id_order, id_style, id_product, qty, id_zone, id_warehouse) ";
		$qr .= "VALUES ";
		$qr .= "('".$id_order."', '".$id_style."', '".$id_product."', '".$qty."', '".$id_zone."', '".$id_warehouse."')";

		return dbQuery($qr);
	}





	public function update($id_order, $id_product, $id_zone, $qty)
	{
		$qr  = "UPDATE tbl_cancle SET qty = qty + ".$qty." ";
		$qr .= "WHERE id_order = ".$id_order." ";
		$qr .= "AND id_product = '".$id_product."' ";
		$qr .= "AND id_zone = ".$id_zone;

		return dbQuery($qr);
	}




	public function getCancleQty($id_pd)
	{
		$qr = "SELECT SUM(qty) AS qty FROM tbl_cancle AS c ";
		$qr .= "JOIN tbl_warehouse AS w ON c.id_warehouse = w.id ";
		$qr .= "WHERE c.id_product = '".$id_pd."' AND w.sell = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);

		return is_null( $qty ) ? 0 : $qty;
	}




	public function getStyleCancleQty($id_style)
	{
		$qr = "SELECT SUM(qty) AS qty FROM tbl_cancle AS c ";
		$qr .= "JOIN tbl_warehouse AS w ON c.id_warehouse = w.id ";
		$qr .= "WHERE c.id_style = '".$id_style."' AND w.sell = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;
	}



	public function dropZero($id_order ='')
	{
		if( $id_order == '')
		{
			return dbQuery("DELETE FROM tbl_cancle WHERE qty <= 0");
		}
		else
		{
			return dbQuery("DELETE FROM tbl_cancle WHERE id_order = ".$id_order." AND qty = 0");
		}
	}




	public function isExists($id_order, $id_product, $id_zone)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_cancle WHERE id_order = ".$id_order." AND id_product = '".$id_product."' AND id_zone = '".$id_zone."'");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}

		return $sc;
	}


	public function getDetailsByWarehouse($id_warehouse)
	{
		$qr  = "SELECT c.*, p.code FROM tbl_cancle AS c JOIN tbl_product AS p ON c.id_product = p.id ";
		$qr .= "WHERE id_warehouse = '".$id_warehouse."'";
		return dbQuery($qr);
	}


	public function getDetails(){
		$qr = "SELECT * FROM tbl_cancle";
		return dbQuery($qr);
	}


	public function move_out($id_cancle, $qty)
	{
		return dbQuery("UPDATE tbl_cancle SET qty = qty - ".$qty." WHERE id = '".$id_cancle."'");
	}


	public function isEnough($id_cancle, $qty)
	{
		$sc = 0;
		$qs = dbQuery("SELECT qty FROM tbl_cancle WHERE id = '".$id_cancle."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc >= $qty ? TRUE : FALSE;
	}



	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_cancle WHERE id = '".$id."'");
	}



	public function getStockInCancle($id_pd)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_cancle WHERE id_product = '".$id_pd."'");
    list($qty) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }




}
?>
