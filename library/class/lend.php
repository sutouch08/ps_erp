<?php
class lend
{

	public $id_order;
	public $id_zone;

	public function __construct($id = '')
	{
		if( $id != '')
		{
			$this->getData($id);
		}
	}



	public function getData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_order_lend WHERE id_order = '".$id."'");
		if( dbNumRows($qs) == 1)
		{
			$rs = dbFetchArray($qs);
			foreach ($rs as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}


	public function getDetails($id_order)
	{
		return dbQuery("SELECT * FROM tbl_order_lend_detail WHERE id_order = '".$id_order."'");
	}


	public function add(array $ds = array())
	{
		$sc = FALSE;
		if( !empty($ds))
		{
			$fields = "";
			$values = "";
			$i      = 1;
			foreach($ds as $field => $value)
			{
				$fields .= $i == 1 ? $field : ", " .$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_order_lend (".$fields.") VALUES (".$values.")");
		}

		return $sc;
	}



	public function update($id, array $ds = array())
	{
		$sc = FALSE;
		if( !empty($ds))
		{
			$set = "";
			$i   = 1;
			foreach($ds as $field => $value)
			{
				$set .= $i == 1 ? $field . " = '".$value."'" : ", ".$field ." = '".$value."'";
				$i++;
			}

			$sc = dbQuery("UPDATE tbl_order_lend SET ".$set." WHERE id_order = '".$id."'");
		}

		return $sc;
	}



	public function addDetail($id_order, $id_product, array $ds = array())
	{
		$sc = FALSE;
		if( !empty($ds))
		{
			if( $this->isExists($id_order, $id_product) === TRUE)
			{
				$sc = $this->updateDetail($id_order, $id_product, $ds['qty']);
			}
			else
			{
				$sc = $this->insertDetail($ds);
			}
		}

		return $sc;
	}



	public function getDetail($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_order_lend_detail WHERE id = '".$id."'");
		if(dbNumRows($qs) == 1)
		{
			$sc = dbFetchObject($qs);
		}

		return $sc;
	}



	public function insertDetail(array $ds = array())
	{
		$sc = FALSE;
		if( !empty($ds))
		{
			$fields = "";
			$values = "";
			$i      = 1;
			foreach($ds as $field => $value)
			{
				$fields .= $i == 1 ? $field : ", " .$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_order_lend_detail (".$fields.") VALUES (".$values.")");
		}

		return $sc;
	}





	public function updateDetail($id_order, $id_product, $qty)
	{
	 	return dbQuery("UPDATE tbl_order_lend_detail SET qty = qty + ". $qty." WHERE id_order = '".$id_order."' AND id_product = '".$id_product."'");
	}




	public function deleteDetail($id_order, $id_product)
	{
		return dbQuery("DELETE FROM tbl_order_lend_detail WHERE id_order = '".$id_order."' AND id_product = '".$id_product."'");
	}




	public function isExists($id_order, $id_product)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_order_lend_detail WHERE id_order = ".$id_order." AND id_product = '".$id_product."'");
		if( dbNumRows($qs) > 0)
		{
			$sc = TRUE;
		}

		return $sc;
	}





	public function getLendQty($id_order, $id_product)
	{
		$sc = 0;
		$qs = dbQuery("SELECT qty FROM tbl_order_lend_detail WHERE id_order = '".$id_order."' AND id_product = '".$id_product."'");
		if( dbNumRows($qs) == 1)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}





	public function getReturnedQty($id_order, $id_product)
	{
		$sc = 0;
		$qs = dbQuery("SELECT received FROM tbl_order_lend_detail WHERE id_order = '".$id_order."' AND id_product = '".$id_product."'");
		if( dbNumRows($qs) == 1)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}



	//---	มีการคืนสินค้ามาบ้างแล้วหรือยัง
	public function isReceived($id_order)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_order_lend_detail WHERE id_order = '".$id_order."' AND received > 0");
		if( dbNumRows($qs) > 0)
		{
			$sc = TRUE;
		}

		return $sc;
	}


	//---	ปิดเอกสาร (รับคืนครบแล้ว หรือ ยกเลิกเอกสาร)
	public function closed($id_order)
	{
		return dbQuery("UPDATE tbl_order_lend SET isClosed = 1 WHERE id_order = ".$id_order);
	}



	//---
	public function unClose($id_order)
	{
		return dbQuery("UPDATE tbl_order_lend SET isClosed = 0 WHERE id_order = ".$id_order);
	}


	public function updateReceived($id, $qty)
	{
		$emp = getCookie('user_id');

		$qs = dbQuery("UPDATE tbl_order_lend_detail SET received = received + ".$qty.", emp_upd = '".$emp."' WHERE id = ".$id);
		if($qs)
		{
			$this->validDetail($id);
		}

		return $qs;
	}



	public function unReceived($id_order, $id_pd, $qty)
	{
		$emp = getCookie('user_id');

		$qr  = "UPDATE tbl_order_lend_detail ";
		$qr .= "SET received = received - ".$qty.", ";
		$qr .= "valid = 0, ";
		$qr .= "emp_upd = '".$emp."' ";
		$qr .= "WHERE id_order = '".$id_order."' ";
		$qr .= "AND id_product = '".$id_pd."'";

		return dbQuery($qr);
	}



	public function validDetail($id)
	{
		return dbQuery("UPDATE tbl_order_lend_detail SET valid = 1 WHERE id = ".$id." AND qty = received");
	}





	//--- ตรวจสอบว่าคืนสินค้าครบทุกรายการแล้วหรือยัง
	public function isCompleted($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_order_lend_detail WHERE id_order = '".$id."' AND valid = 0");
		if(dbNumRows($qs) == 0)
		{
			$sc = TRUE;
		}

		return $sc;
	}





}	//---	End class

 ?>
