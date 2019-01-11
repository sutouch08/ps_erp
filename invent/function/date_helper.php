<?php
function dateSelect($se = '')
{
	$h 	= 0;
	$i 		= 0;
	$H		= 23;
	$option = '';
	while( $h <= $H )
	{
		$time = ($h < 10 ? '0'.$h : $h).':'.($i == 30 ? '30' : '00');
		$option .= '<option value="'.$time.':00" '.isSelected($time.':00', $se).'>'.$time.'</option>';
		$i += 30;
		if( $i == 60 )
		{
			$i = 0;
			$h++;
		}
	}
	return $option;
}




function thaiDateFormat($date, $time = FALSE , $sep = '-')
{
	if($date != "" && $date != "0000-00-00")
	{
		$y 	= date("Y", strtotime($date));
		if($y < 2200){ $y += 543; }
		if($time)
		{
			$date = date("d".$sep."m".$sep.$y." H:i:s", strtotime($date));
		}
		else
		{
			$date = date("d".$sep."m".$sep.$y, strtotime($date));
		}
	}
	return $date;
}





function thaiTextDateFormat($date, $time = FALSE)
{
	$Y 	= date('Y', strtotime($date));
	$m 	= date('m', strtotime($date));
	$d 	= date('d', strtotime($date));

	$Y 	= $Y < 2200 ? $Y+543 : $Y+0;  //----- เปลี่ยน ค.ศ. เป็น พ.ศ. ---//
	$t 		= date('H:i', strtotime($date));

	switch( $m )
	{
		case "01": $m 	= "ม.ค."; break;
		case "02": $m 	= "ก.พ."; break;
		case "03": $m 	= "มี.ค."; break;
		case "04": $m 	= "เม.ย."; break;
		case "05": $m 	= "พ.ค."; break;
		case "06": $m	 = "มิ.ย."; break;
		case "07": $m 	= "ก.ค."; break;
		case "08": $m 	= "ส.ค."; break;
		case "09": $m 	= "ก.ย."; break;
		case "10": $m 	= "ต.ค."; break;
		case "11": $m 	= "พ.ย."; break;
		case "12": $m 	= "ธ.ค."; break;
	}
	$newDate 	= $time === TRUE ? $d.' '.$m.' '.$Y.' '.$t : $d.' '.$m.' '.$Y;
	return $newDate;
}





//------- แปลงวันที่ใน excel เป็นวันที่ใน php
function excelDateToPhpDate($excelDate)
{
	if( $excelDate < 25569 )
	{
		return FALSE;
	}
	else
	{
		$days = $excelDate - 2 ; // ตัดหัวตัดท้าย
		$base = date('1900-01-01');
		return date('Y-m-d', strtotime("+ $days day $base"));
	}
}





function selectYears($se="0000")
{
	$sc 		= '';
	$length	= 5;
	$startYear = getConfig('START_YEAR');
	//$se 		= ($se == '' OR $se == '0000') ? $startYear : $se;
	$year = ($se - $length) < $startYear ? $startYear : $se - $length;
	$lastYear = date('Y') + $length;
	while( $year <= $lastYear )
	{
		$sc .= '<option value="'.$year.'" '.isSelected($year, $se).'>'.$year.'</option>';
		$year++;
	}
	return $sc;
}




function dbYear($year = '')
{
	$Y = $year == '' ? date('Y') : $year;
	$Y = $Y > 2500 ? $Y - 543 : $Y;
	return $Y;
}


function fmDate($date)
{
	$sp = substr($date,2,1);
	$ds = explode($sp, $date);
	if( count($ds) == 3)
	{
		$date = $ds[1].'-'.$ds[0].'-20'.$ds[2];
	}
	return date('Y-m-d', strtotime($date));
}



?>
