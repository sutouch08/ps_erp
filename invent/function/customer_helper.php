<?php
function getCustomerIn($txt)
{
	$cs = new customer();
	$qs = $cs->searchId($txt);
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		$sc = "";
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= $i == 1 ? "'".$rs->id."'" : ", '".$rs->id."'";
			$i++;
		}
	}
	else
	{
		$sc = "'0'";
	}
	return $sc;
}


function customerGroupIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_customer_group WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc 	.= $i == 1 ? "'".$rs['code']."'" : ", '".$rs['code']."'";
			$i++;
		}
	}
	else
	{
		$sc = "'".$txt."'";
	}
	return $sc;
}


function customerAreaIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT id FROM tbl_customer_area WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc .= $i == 1 ? "'".$rs['id']."'" : ", '".$rs['id']."'";
			$i++;
		}
	}
	else
	{
		$sc = "'".$txt."'";
	}
	return $sc;
}

function customerKindIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_customer_kind WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc 	.= $i == 1 ? "'".$rs['code']."'" : ", '".$rs['code']."'";
			$i++;
		}
	}
	else
	{
		$sc = "'".$txt."'";
	}
	return $sc;
}


function customerTypeIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_customer_type WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc 	.= $i == 1 ? "'".$rs['code']."'" : ", '".$rs['code']."'";
			$i++;
		}
	}
	else
	{
		$sc = "'".$txt."'";
	}
	return $sc;
}



function customerClassIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_customer_class WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc 	.= $i == 1 ? "'".$rs['code']."'" : ", '".$rs['code']."'";
			$i++;
		}
	}
	else
	{
		$sc = "'".$txt."'";
	}
	return $sc;
}



function customerName($id)
{
	$cs = new customer();
	return $cs->getName($id);
}



function selectCustomerGroup($id = "")
{
	$sc = '<option value="0">โปรดเลือก</option>';
	$cs = new customer_group();
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $id).'>'.$rs->name.'</option>';
		}
	}

	return $sc;
}



function selectCustomerKind($id = "")
{
	$sc = '<option value="0">โปรดเลือก</option>';
	$cs = new customer_kind();
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $id).'>'.$rs->name.'</option>';
		}
	}

	return $sc;
}

function selectCustomerType($id = "")
{
	$sc = '<option value="0">โปรดเลือก</option>';
	$cs = new customer_type();
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $id).'>'.$rs->name.'</option>';
		}
	}

	return $sc;
}

function selectCustomerClass($id = "")
{
	$sc = '<option value="0">โปรดเลือก</option>';
	$cs = new customer_class();
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $id).'>'.$rs->name.'</option>';
		}
	}

	return $sc;
}



?>
