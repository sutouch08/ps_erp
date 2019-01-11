<?php

//---	ถ้าเป็นลูกค้าออไลน์ ให้ใส่ชื่อลูกค้าออนไลน์ไปด้วย
function orderCustomerName($id_customer, $online_code)
{
	$customer = new customer();
	$name = $customer->getName($id_customer);
	return $online_code != '' ? '[ '.$online_code.'] '.$name : $name;
}




function stateColor($state, $status, $isExpired=0)
{
	$sc = '';
	$st  = new state();
	$state = $isExpired == 1 ? 11 : $state;

	if( $status == 1 && $isExpired == 0)
	{
		$sc = $st->stateColor($state);
	}
	else if($isExpired == 1)
	{
		$sc = 'style="color:#CCC; background-color:#000;"';
	}
	return $sc;
}


function stateName($state, $status, $isExpired = 0)
{
	$sc = "ยังไม่บันทึก";
	$st = new state();
	if( $status == 1 && $isExpired == 0)
	{
		$sc = $st->getName($state);
	}
	else if($isExpired == 1)
	{
		$sc = 'หมดอายุ';
	}
	return $sc;
}



//---	ชื่อของ role
function roleName($role)
{
	$sc = "";
	$order = new order();
	$qs = $order->roleName($role);
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}




//--- แสดงป้ายส่วนลด
function discountLabel($disc)
{
	$arr = explode('%', $disc);
	if( count($arr) > 1)
	{
		return number_format(trim($arr[0]),2).' %';
	}
	else
	{
		return number_format($arr[0],2);
	}
}


function getSpace($amount, $length)
{
	$sc = '';
	$i	= strlen($amount);
	$m	= $length - $i;
	while($m > 0 )
	{
		$sc .= '&nbsp;';
		$m--;
	}
	return $sc.$amount;
}


function selectHour($se = '')
{
	$sc	= '';
	$hour = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
	foreach($hour as $rs)
	{
		$sc .= '<option value="'.$rs.'" '.isSelected($rs, $se).'>'.$rs.'</option>';
	}
	return $sc;
}



function selectMin($se = '' )
{
	$sc = '<option value="00">00</option>';
	$m = 59;
	$i 	= 1;
	while( $i <= $m )
	{
		$ix = $i < 10 ? '0'.$i : $i;
		$sc .= '<option value="'.$ix.'" '.isSelected($se, $ix).'>'.$ix.'</option>';
		$i++;
	}
	return $sc;
}



function selectTime($time='')
{
	$sc = '';
	$times = array('00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00',
						'09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30',
						'20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30');
	foreach($times as $hrs)
	{
		$sc .= '<option value="'.$hrs.'" '.isSelected($time, $hrs).'>'.$hrs.'</option>';
	}
	return $sc;
}



function getOrderStateChangeIn($state, $fromDate, $toDate, $startTime, $endTime)
{
	$sc = 0;
	$qr = "SELECT id_order FROM tbl_order_state ";
	$qr .= "WHERE id_state = '".$state."' ";
	$qr .= "AND date_upd >= '".$fromDate."' ";
	$qr .= "AND date_upd <= '".$toDate."' ";
	$qr .= "AND time_upd >= '".$startTime."' ";
	$qr .= "AND time_upd <= '".$endTime."' ";
	$qs = dbQuery($qr);
	if(dbNumRows($qs) > 0)
	{
		$sc = '';
		$i = 1;
		while($rs = dbFetchObject($qs))
		{
			$sc .= $i == 1 ? $rs->id_order : ", ".$rs->id_order;
			$i++;
		}
	}

	return $sc;
}



?>
