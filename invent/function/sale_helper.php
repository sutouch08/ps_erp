<?php

function saleGroupCodeIn($txt)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT code FROM tbl_sale_group WHERE code LIKE '%" . trim( $txt ) ."%' OR name LIKE '%". trim( $txt ) ."%'");
	if( dbNumRows($qs) > 0 )
	{
		$sc = "";
		$i = 1;
		while( $rs = dbFetchObject($qs) )
		{
			$sc	.= $i == 1 ? "'".$rs->code."'" : ", '".$rs->code."'";
			$i++;
		}
	}
	return $sc;
}

?>