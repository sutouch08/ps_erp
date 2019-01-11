<?php

function selectPaymentMethod($id = "" )
{
	$sc = '';
	$cs = new payment_method();
	$id = $id == "" ? $cs->getDefaultId() : $id;
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($id, $rs->id).'>'.$rs->name.'</option>';
		}
	}
	return $sc;
}


function selectPaymentMethodList($id="")
{
	$sc = '';
	$cs = new payment_method();
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($id, $rs->id).'>'.$rs->name.'</option>';
		}
	}
	return $sc;
}





function selectOnlinePaymentMethod($id = "" )
{
	$sc = '';
	$cs = new payment_method();
	$qs = $cs->getData();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			if( $rs->hasTerm == 0 )
			{
				$sc .= '<option value="'.$rs->id.'" '.isSelected($id, $rs->id).'>'.$rs->name.'</option>';
			}
		}
	}
	return $sc;
}





function getPaymentMethodIn($txt)
{
	$cs = new payment_method();
	$qs = $cs->searchId($txt);
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		$sc = "";
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= $i == 1 ? $rs->id : ", ".$rs->id;
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
