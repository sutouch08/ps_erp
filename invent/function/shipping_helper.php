<?php

function shippingLabel($code)
{
	$sc = "";
	if( $code != "" )
	{
		$sc = '<label class="btn btn-sm btn-info">จัดส่งแล้ว | '.$code.'</label>';
	}
	return $sc;
}
?>