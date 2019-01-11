<?php
class receive_transform
{
	public $id;

	//---	รหัสเล่มเอกสารใน formula
	public $bookcode;

	//---	เลขที่เอกสาร ใน Smart invent
	public $reference;

	//---	ไอดี ของออเดอร์เบิกแปรสภาพ
	public $id_order;

	//---	เลขที่ออเดอร์เบิกแปรสภาพ
	public $order_code;

	//---	เลขที่ใบส่งสินค้า
	public $invoice;

	//---	พนักงานที่รับสินค้า
	public $id_employee;

	//---	วันที่เอกสาร
	public $date_add;

	//---	วันที่ปรับปรุงเอกสาร
	public $date_upd;

	//---	พนักงานที่ทำการปรับปรุง
	public $emp_upd;

	//---	ยกเลิกหรือไม่
	public $isCancle;

	//---	หมายเหตุ
	public $remark;

	//---	ส่งออกไป formula แล้วหรือยัง
	public $isExported;

	//---	ใครอนุมัติรับสินค้าเกิน (กรณีสินค้าเกินกว่าที่เบิกไปแปร)
	public $approver;

	//---	รหัสการอนุมัติ(เข้ารหัสไว้)
	public $approvKey;

	//--- บันทึกเอกสารไปแล้วหรือยัง
	public $isSaved = 0;

	public $error;


	public function __construct($id = "")
	{
		if( $id != "" && $id != FALSE )
		{
			$this->getData($id);
		}
	}




	public function getData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_receive_transform WHERE id = ".$id);

		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchArray($qs);
			foreach($rs as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}




	public function add(array $ds = array())
	{
		$sc = FALSE;
		if(!empty($ds))
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

			$qs = dbQuery("INSERT INTO tbl_receive_transform (".$fields.") VALUES (".$values.")");
			if($qs === TRUE)
			{
				$sc = dbInsertId();
			}
			else
			{
				$this->error = dbError();
			}
		}

		return $sc;
	}


public function update($id, array $ds = array())
{
	if(!empty($ds))
	{
		$set = "";
		$i = 1;
		foreach($ds as $field => $value)
		{
			$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
			$i++;
		}

		return dbQuery("UPDATE tbl_receive_transform SET ".$set." WHERE id = ".$id);
	}

	return FALSE;
}


	public function getDetail($id)
	{
		return dbQuery("SELECT * FROM tbl_receive_transform_detail WHERE id_receive_transform = ".$id);
	}





	public function get_id($reference)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_receive_transform WHERE reference = '".$reference."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getTotalQty($id_receive_transform)
	{
		$sc = 0;
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_receive_transform_detail WHERE id_receive_transform = ".$id_receive_transform);
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}





	public function hasDetails($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_receive_transform_detail WHERE id_receive_transform = ".$id);
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function insertDetail(array $ds)
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
			$sc = dbQuery("INSERT INTO tbl_receive_transform_detail (".$fields.") VALUES (".$values.")");
		}

		return $sc;
	}





	public function cancleDetail($id)
	{
		return dbQuery("UPDATE tbl_receive_transform_detail SET is_cancle = 1 WHERE id = ".$id);
	}






	public function cancleReceived($id, $emp)
	{
		return dbQuery("UPDATE tbl_receive_transform SET isCancle = 1, emp_upd = ".$emp." WHERE id = ".$id);
	}






	public function exported($id)
	{
		return dbQuery("UPDATE tbl_receive_transform SET isExported = 1 WHERE id = ".$id);
	}






	//-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date     = $date == '' ? date('Y-m-d') : $date;
		$Y		    = date('y', strtotime($date));
		$M		    = date('m', strtotime($date));
		$runDigit = getConfig('RUN_DIGIT_TRANSFORM');
		$prefix   = getConfig('PREFIX_RECEIVE_TRANSFORM');
		$preRef   = $prefix . '-' . $Y . $M;

		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_receive_transform WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");

		list( $ref ) = dbFetchArray($qs);

		if( ! is_null( $ref ) )
		{
			$runNo = mb_substr($ref, ($runDigit*(-1)), NULL, 'UTF-8') + 1;
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
		}
		else
		{
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
		}

		return $reference;
	}






	//-- มูลค่าสินค้ารามทั้งบิล
	public function getTotalAmount($id, $poCode)
	{
		$sc = 0;
		$qs = $this->getDetail($id);
		if( dbNumRows($qs) > 0 )
		{
			$po = new po();
			while( $rs = dbFetchObject($qs) )
			{
				$price = $po->getPrice($poCode, $rs->id_product);
				$sc += $rs->qty * $price;
			}
		}
		return $sc;
	}






	public function getNotExportData()
	{
		return dbQuery("SELECT id FROM tbl_receive_transform WHERE isExported = 0 AND isCancle = 0");
	}








}//--end class

?>
