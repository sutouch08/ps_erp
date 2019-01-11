<?php
function customerProvince($id_customer)
{
	$sc = "";
	$qs = dbQuery("SELECT city FROM tbl_address WHERE id_customer = ".$id_customer);
	if( dbNumRows($qs) > 0 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}


function getOnlineAddress($customer_code)
{
	$qs = dbQuery("SELECT * FROM tbl_address_online WHERE customer_code = '".$customer_code."' ORDER BY is_default DESC LIMIT 1");

	if(dbNumRows($qs) == 1)
	{
		return dbFetchObject($qs);
	}

	return FALSE;
}

?>
