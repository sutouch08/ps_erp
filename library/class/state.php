<?php
class state
{
	public function __construct(){}

	//--- 1 = รอชำระเงิน
	//--- 2 = แจ้งชำระเงิน
	//--- 3 = รอจัดสินค้า
	//--- 4 = กำลังจัดสินค้า
	//--- 5 = รอแพ็ค
	//--- 6 = กำลังแพ็ค
	//--- 7 = รอเปิดบิล
	//--- 8 = รอการจัดส่ง
	//--- 9 = กำลังจัดส่ง
	//--- 10 = จัดส่งแล้ว
	//--- 11 = ยกเลิก
	public function add($id_order, $id_state, $id_employee)
	{
		return dbQuery("INSERT INTO tbl_order_state (id_order, id_state, id_employee, time_upd) VALUES (".$id_order.", ".$id_state.", ".$id_employee.", '".date('H:i:s')."')");
	}





	public function getName($id)
	{
		$sc = 1;
		$qs = dbQuery("SELECT name FROM tbl_state WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function getColor($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT color FROM tbl_state WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}






	public function stateColor($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT color, font FROM tbl_state WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchObject($qs);
			$sc = 'style="color:'.$rs->font.'; background-color:'.$rs->color.';"';
		}
		return $sc;
	}






	public function getOrderStateList($id_order)
	{
		$qr = "SELECT o.id_employee, o.date_upd, s.name, s.color, s.font FROM tbl_order_state AS o ";
		$qr .= "JOIN tbl_state AS s ON o.id_state = s.id WHERE o.id_order = ".$id_order." ORDER BY o.date_upd ASC, s.id ASC";
		return dbQuery($qr);
	}





	public function hasState($id_order)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_order_state WHERE id_order = ".$id_order);
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function hasEmployeeState($id_order, $id_state, $id_emp)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_order_state WHERE id_order = ".$id_order." AND id_state = ".$id_state." AND id_employee = ".$id_emp);
		if( dbNumRows($qs) > 0)
		{
			$sc = TRUE;
		}

		return $sc;
	}



	public function getCurrentStateLabel($id_order)
	{
		$qr = "SELECT o.id_employee, o.date_upd, s.name, s.color, s.font FROM tbl_order AS o ";
		$qr .= "JOIN tbl_state AS s ON o.state = s.id WHERE o.id = ".$id_order;
		return dbQuery($qr);
	}


	public function getLastStateEmployee($id_order, $id_state)
	{
		$sc  = 0;
		$qr  = "SELECT id_employee FROM tbl_order_state ";
		$qr .= "WHERE id_order = '".$id_order."' AND id_state = '".$id_state."' ";
		$qr .= "ORDER BY date_upd DESC LIMIT 1";
		$qs = dbQuery($qr);

		if(dbNumRows($qs) == 1)
		{
			list($sc) = dbFetchArray($qs);
		}

		return $sc;
	}


}//--- end class
