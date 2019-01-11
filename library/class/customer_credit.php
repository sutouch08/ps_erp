<?php
class customer_credit
{
	public $id_customer;
	public $code;
	public $name;
	public $credit;
	public $used;
	public $balance;
	public $date_upd;
	public $error;
	public $isLimit = TRUE;

	public function __construct($id = "")
	{
		$this->isLimit = getConfig('USE_CREDIT_LIMIT') == 1 ? TRUE : FALSE;
		if( $id != "" )
		{
			$this->getData($id);
		}
	}


	public function getData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_customer_credit WHERE id_customer = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchObject($qs);
			$this->id_customer	= $rs->id_customer;
			$this->code		= $rs->code;
			$this->name  	= $rs->name;
			$this->credit	= $rs->credit;
			$this->used	 	= $rs->used;
			$this->balance	= $rs->balance;
		}
	}





	public function add(array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty($ds) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .=	 $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'". $value."'" : ", '".$value."'";
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_customer_credit (".$fields.") VALUES (".$values.")");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}





	public function update($id, array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty($ds) )
		{
			$set  = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field ." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;
			}

			$sc = dbQuery("UPDATE tbl_customer_credit SET ".$set." WHERE id_customer = '".$id."'");

			if( $sc === FALSE)
			{
				$this->error = dbError();
			}
		}
		return $sc;
	}



	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_customer_credit WHERE id_customer = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}






	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_customer_credit WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function getBalance($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT balance FROM tbl_customer_credit WHERE id_customer = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function increaseUsed( $id, $amount = 0 )
	{
		$sc = dbQuery("UPDATE tbl_customer_credit SET used = used + ".$amount." WHERE id_customer = '".$id."'");
		if( $sc )
		{
			$sc = $this->calculate($id);
		}
		return $sc;
	}





	public function decreaseUsed( $id, $amount = 0 )
	{
		$sc = dbQuery("UPDATE tbl_customer_credit SET used = used - ".$amount." WHERE id_customer = '".$id."'");
		if( $sc )
		{
			$sc = $this->calculate($id);
		}
		return $sc;
	}





	public function isEnough($id, $amount)
	{
		if($this->isLimit)
		{
			$balance = $this->getBalance($id);
			if( $amount > $balance )
			{
				return FALSE;
			}
		}

		return TRUE;
	}





	public function calculate($id)
	{
		return dbQuery("UPDATE tbl_customer_credit SET balance = credit - used WHERE id_customer = '".$id."'");
	}

}

?>
