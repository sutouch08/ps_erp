<?php

function getEmployeeIn($txt)
{
	$cs = new employee();
	$qs = $cs->searchId($txt);
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		$sc = "";
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= $i == 1 ? $rs->id_employee : ", ".$rs->id_employee;
			$i++;
		}
	}
	else
	{
		$sc = "0";	
	}
	return $sc;
}

?>