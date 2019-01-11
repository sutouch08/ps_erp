<?php

function selectKind($id = "")
{
	$option = '<option value="">เลือกประเภทสินค้า</option>';	
	$cs = new kind();
	$qs = $cs->getProductKind();
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