<?php
function selectCategory($id = "")
{
	$option = '<option value="">ทั้งหมด</option>';
	$cs = new category();
	$qs = $cs->getCategory();
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
