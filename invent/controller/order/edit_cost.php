<?php
	$order 		= new order($_POST['id_order']);
	$ds 			= $_POST['cost'];
	$approver	= $_POST['approver'];
	$token		= $_POST['token'];
	$id_emp		= getCookie('user_id');
	$logs 			= new logs();
	$count = 0;
	foreach( $ds as $id => $value )
	{
		//----- ข้ามรายการที่ไม่ได้กำหนดค่ามา
		if( $value != "" )
		{
			//--- ได้ Obj มา
			$detail = $order->getDetailData($id);

			//--- ถ้ารายการนี้มีอยู่
			if( $detail !== FALSE )
			{

				$arr = array(	"cost" => $value);

				$cs = $order->updateDetail($id, $arr);

				$log_data = array(
											"reference"		=> $order->reference,
											"product_code"	=> $detail->product_code,
											"old_cost"	=> $detail->cost,
											"new_cost"	=> $value,
											"id_employee"	=> $id_emp,
											"approver"		=> $approver,
											"token"			=> $token
											);
				$logs->logs_cost($log_data);
			}	//--- end if detail
		} //--- End if value
	}	//--- end foreach

	echo 'success';

?>
