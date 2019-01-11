<?php

function colorGroupIn($txt)
{
	$sc = "-1";
	if( $txt == "0" || $txt == "00" )
	{
		$sc = 0;
	}
	else
	{
		$qs = dbQuery("SELECT id FROM tbl_color_group WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = "";
			$i = 1;
			while( $rs = dbFetchObject($qs) )
			{
				$sc .= $i == 1 ? $rs->id : ", ".$rs->id;
				$i++;
			}
		}
	}
	return $sc;
}


function selectColorGroup($id=0)
{
	$sc = '<option value="0">ไม่มีกลุ่ม</option>';
	$qs = dbQuery("SELECT * FROM tbl_color_group");
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '. isSelected($rs->id, $id).' >'.$rs->name.'</option>';
		}
	}
	return $sc;
}


?>