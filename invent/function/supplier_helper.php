<?php
function supplier_in($txt)
{
	$sp = new supplier();
	$qs = $sp->search($txt);
	$sc	= "'0000'";
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= ", '".$rs->id."'";			
		}			
	}
	return $sc;
}

?>