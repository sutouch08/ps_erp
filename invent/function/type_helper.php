<?php

function selectType($id = "")
{
	$option = '<option value="">เลือกชนิดสินค้า</option>';	
	$cs = new type();
	$qs = $cs->getProductType();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$option .= '<option value="'.$rs->id.'" '.isSelected($id, $rs->id).'>'.$rs->name.'</option>';	
		}
	}
	return $option;	
}

?>