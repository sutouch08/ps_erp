<?php
function imageUrl($reference)
{
	$link	= WEB_ROOT.'img/payment/'.$reference.'.jpg';
	$file 	= realpath(DOC_ROOT.$link);
	if( ! file_exists($file) )
	{
		$link = FALSE;
	}
	return $link;
}



function validPayment($id_order)
{
	$payment = new payment();
	return $payment->valid($id_order);
}



function paymentLabel($id_order, $isExists, $isPaid)
{
	$sc = "";
	if( $isExists === TRUE )
	{
		
        if( $isPaid == 1 )
		{
			$sc .= '<button type="button" class="btn btn-sm btn-success" onClick="viewPaymentDetail('. $id_order .')">';
			$sc .= 'จ่ายเงินแล้ว | ดูรายละเอียด';
			$sc .= '</button>';
		}
		else
		{
			$sc .= '<button type="button" class="btn btn-sm btn-primary" onClick="viewPaymentDetail('. $id_order .')">';
			$sc .= 'แจ้งชำระแล้ว | ดูรายละเอียด';
			$sc .= '</button>';
		}
	}
	
	return $sc;
}

?>