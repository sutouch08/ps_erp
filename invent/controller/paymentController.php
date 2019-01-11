<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require '../function/order_helper.php';
require '../function/bank_helper.php';
require '../function/payment_helper.php';
require '../function/date_helper.php';
include '../function/customer_helper.php';


//------------------ ยืนยันการชำระเงิน  -----------------------//
if( isset( $_GET['confirmPayment'] ) )
{
	$sc			= 'success';
	$id_emp		= getCookie('user_id');
	$order	= new order($_POST['id_order']);
	startTransection();

	//----- update tbl_payment set valid = 1
	$ra = validPayment($order->id);

	//----- update tbl_order set isPaid = 1
	$rb = $order->paid($order->id);

	//----	ถ้า state น้อยกว่า 3 (1 = รอชำระงิน 2 = แจ้งชำระเงิน) update state -> 3 (รอการจัดสินค้า)
	//----- ถ้า state มากกว่า 2 ไม่ต้องทำอะไร
	$rc = $order->state < 3 ? $order->stateChange($order->id, 3) : TRUE;

	if( $ra === TRUE && $rb === TRUE && $rc === TRUE )
	{
		commitTransection();
	}
	else
	{
		dbRollback();
		$sc = 'fail';
	}
	echo $sc;
}




if( isset( $_GET['removePayment'] ) )
{
	$sc = 'fail';
	$payment = new payment();
	if( $payment->delete($_POST['id_order']) === TRUE )
	{
		$sc = 'success';
	}
	echo $sc;
}






if( isset( $_GET['removeValidPayment'] ) )
{
	$sc = 'fail';
	$payment = new payment();
	if( $payment->removeValidPayment($_POST['id_order']) === TRUE )
	{
		$sc = 'success';
	}
	echo $sc;
}



//------------------  ตารางรายการรอตรวจสอบยอดชำระ  ------------//
if( isset( $_GET['getOrderTable'] ) )
{
	$sc = 'fail';
	$payment 	= new payment();
	$channels = new channels();
	$qs 			= $payment->getData();
	if( dbNumRows($qs) > 0 )
	{
		$ds = array();
		$no = 1;
		$co = new customer_online();
		$emp = new employee();
		while( $rs = dbFetchObject($qs) )
		{
			$order 	= new order($rs->id_order);
			$bank	 	= getBankAccount($rs->id_account);
			$amount 	= ( $order->getTotalAmount($rs->id_order) - $order->bDiscAmount) + $order->shipping_fee + $order->service_fee;
			$shipFee	 = $order->shipping_fee;
			$servFee = $order->service_fee;
			$refCode = $order->ref_code == '' ? '' : ' ['.$order->ref_code.']';
			$arr			= array(
									"id"					=> $order->id,
									"no"					=> $no,
									"reference"		=> $order->reference.$refCode,
									"channels"		=> $channels->getName($order->id_channels),
									"customer"		=> $order->isOnline == 1 ? $co->getName($order->online_code) : customerName($order->id_customer),
									"employee"		=> $emp->getName($order->id_employee),
									"orderAmount"	=> number_format($amount, 2), //--- ยอดที่ต้องชำระ
									"payAmount"		=> number_format($rs->pay_amount, 2),   //--- ยอดโอน
									"accNo"				=> $bank['acc_no']
									);
			array_push($ds, $arr);
			$no++;
		}
		$sc = json_encode($ds);
	}
	echo $sc;
}




//--------------- ข้อมูลการชำระเงินเพื่อตรวจสอบ  ------------//
if( isset( $_GET['getPaymentDetail'] ) )
{
	$sc 			= 'fail';
	$id_order 	= $_POST['id_order'];
	$payment = new payment();
	$qs = $payment->getDetail($id_order);

	if( dbNumRows($qs) == 1 )
	{
		$rs		= dbFetchArray($qs);
		$order	= new order($id_order);
		$bank	 	= getBankAccount($rs['id_account']);
		$img		= imageUrl($order->reference);

		$ds 	= array(
						"id"			=> $id_order,
						"orderAmount"	=> number_format($rs['order_amount'], 2),
						"payAmount"		=> number_format($rs['pay_amount'], 2),
						"payDate"		=> thaiDateFormat($rs['paydate'], TRUE, '/'),
						"bankName"		=> $bank['bank_name'],
						"branch"			=> $bank['branch'],
						"accNo"			=> $bank['acc_no'],
						"accName"		=> $bank['acc_name'],
						"date_add"	=> thaiDateTime($rs['date_add']),
						"imageUrl"		=> $img === FALSE ? '' : $img,
						"valid"				=> "no"
						);
		$sc = json_encode($ds);
	}
	echo $sc;
}





//--------------- ข้อมูลการชำระเงินที่ยืนยันแล้ว  ------------//
if( isset( $_GET['getValidPaymentDetail'] ) )
{
	$id_order 	= $_POST['id_order'];
	$payment = new payment();
	$qs = $payment->getValidDetail($id_order);
	if( dbNumRows($qs) == 1 )
	{
		$rs		= dbFetchArray($qs);
		$order	= new order($id_order);
		$bank	 	= getBankAccount($rs['id_account']);
		$img		= imageUrl($order->reference);

		$ds 	= array(
						"id"			=> $id_order,
						"orderAmount"	=> number_format($rs['order_amount'], 2),
						"payAmount"		=> number_format($rs['pay_amount'], 2),
						"payDate"		=> thaiDateFormat($rs['paydate'], TRUE, '/'),
						"bankName"		=> $bank['bank_name'],
						"branch"			=> $bank['branch'],
						"accNo"			=> $bank['acc_no'],
						"accName"		=> $bank['acc_name'],
						"date_add"	=> thaiDateTime($rs['date_add']),
						"imageUrl"		=> $img === FALSE ? '' : $img
						);
		$sc = json_encode($ds);
	}
	echo $sc;
}








//--------------- ข้อมูลการชำระเงินเพื่อตรวจสอบ  ------------//
if( isset( $_GET['viewPaymentDetail'] ) )
{
	$sc 			= 'fail';
	$id_order 	= $_POST['id_order'];
	$qs 			= dbQuery("SELECT * FROM tbl_payment WHERE id_order = ".$id_order);
	if( dbNumRows($qs) == 1 )
	{
		$rs		= dbFetchArray($qs);
		$order	= new order($id_order);
		$bank	 	= getBankAccount($rs['id_account']);
		$img		= imageUrl($order->reference);

		$ds 	= array(
						"id"			=> $id_order,
						"orderAmount"	=> number_format($rs['order_amount'], 2),
						"payAmount"		=> number_format($rs['pay_amount'], 2),
						"payDate"		=> thaiDateFormat($rs['paydate'], TRUE, '/'),
						"bankName"		=> $bank['bank_name'],
						"branch"			=> $bank['branch'],
						"accNo"			=> $bank['acc_no'],
						"accName"		=> $bank['acc_name'],
						"date_add"	=> $rs['date_add'] == '0000-00-00 00:00:00' ? '' : thaiDateTime($rs['date_add']),
						"imageUrl"		=> $img === FALSE ? '' : $img,
						"valid"				=> "no"
						);
		$sc = json_encode($ds);
	}
	echo $sc;
}





if( isset( $_GET['searchBankAccount'] ) )
{
	$sc = array();
	$bank = new bank_account();
	$qs = $bank->search($_REQUEST['term'], "bank_name, branch, acc_name, acc_no");
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->bank_name .' | '. $rs->acc_no .' | '. $rs->branch .' | '. $rs->acc_name;
	}
	echo json_encode($sc);
}






if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sPaymentCode');
	deleteCookie('sRefCode');
	deleteCookie('sPaymentCus');
	deleteCookie('sAcc');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo "done";
}


?>
