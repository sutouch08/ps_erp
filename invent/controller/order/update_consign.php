<?php
	$consign = new order_consign();
	$logs = new logs();

	//---	data for logs discount
	$id_emp = getCookie('user_id');
	$approver = '';
	$token = '';

	//--- id_zone in tbl_order_consign
	$id_zone = $_POST['id_zone'];

	$gp = floatval($_POST['gp']);

	$id_branch = isset($_POST['id_branch']) ? $_POST['id_branch'] : 0;

	//---	เตรียมข้อมูลสำหรับ update
	$arr = array(
						"date_add"	=> dbDate($_POST['date_add']),
						"id_customer" => $_POST['id_customer'],
						"status"		=> 0, //--- เปลี่ยนกลับ ให้กดบันทึกใหม่
						"emp_upd"		=> getCookie('user_id'),
						"remark"		=> $_POST['remark'],
						"id_branch" => $id_branch,
						"gp"				=> $gp,
						"is_so"			=> $_POST['is_so']
						);

		$sc = TRUE;
		startTransection();
		//----- ถ้ายังไม่มีรายการ ไม่ต้องคำนวณใหม่
		if( $order->hasDetails($order->id) === TRUE )
		{
			//----- ยกเลิกการบันทึกรายการ เพื่อจะได้คำนวณใหม่อีกที
			$order->unSaveDetails($order->id);
		}

		//--- update order header
		$ra = $order->update($order->id, $arr);
		if( $ra === FALSE )
		{
			$sc = FALSE;
		}

		//--- update zone
		$rb = $consign->update($order->id, $id_zone);
		if( $rb === FALSE )
		{
			$sc = FALSE;
		}

		//--- update gp and discount for each detail row
		$qs = $order->getDetails($order->id);

		if( dbNumRows($qs) > 0)
		{
			while( $rc = dbFetchObject($qs))
			{
				$discLabel = $gp.' %';
				$discAmount= ($rc->price * $rc->qty) * ($gp * 0.01);

				//---	prepare data for update detail
				$arr = array(
								"discount"        => $discLabel,
								"discount_amount" => $discAmount,
								"total_amount"    => ($rc->price * $rc->qty) - $discAmount,
								"gp"              => $gp
				      );

				//---	update detail row
				$rd = $order->updateDetail($rc->id, $arr);

				//---	prepare data for discount logs
				$log_data = array(
												"reference"		=> $order->reference,
												"product_code"	=> $rc->product_code,
												"old_discount"	=> $rc->discount,
												"new_discount"	=> $discLabel,
												"id_employee"	=> $id_emp,
												"approver"		=> $approver,
												"token"			=> $token
											);

				//---		insert discount logs
				$re = $logs->logs_discount($log_data);

				if( $rd === FALSE OR $re === FALSE)
				{
					$sc = FALSE;
				}

			}	//--- end while

		}	//---	end if dbNumRows

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
