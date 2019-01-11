<?php
	$cs = new transform();
	$id_branch = isset($_POST['id_branch']) ? $_POST['id_branch'] : 0;

	//---	เตรียมข้อมูลสำหรับ update order
	$arr = array(
						"date_add"	=> dbDate($_POST['date_add'],TRUE),
						"id_customer" => $_POST['id_customer'],
						"id_employee"	=> $_POST['id_employee'],
						"status"		=> 0, //--- เปลี่ยนกลับ ให้กดบันทึกใหม่
						"emp_upd"		=> getCookie('user_id'),
						"remark"		=> $_POST['remark'],
						"id_branch" => $id_branch
					);

	//---	เตรียมข้อมูลสำหรับ update order_transform
	$ds = array(
					"id_zone" => $_POST['id_zone'],
					"role"	=> $_POST['transRole']
				);

	$sc = TRUE;
	startTransection();

	//--- update order header
	if( $order->update($order->id, $arr) === FALSE )
	{
		$sc = FALSE;
	}

	//--- update zone
	if( $cs->update($order->id, $ds) === FALSE )
	{
		$sc = FALSE;
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


	echo $sc === TRUE ? 'success' : 'fail';

?>
