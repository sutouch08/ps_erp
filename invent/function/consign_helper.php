<?php
function consignStatusLabel($is_so = 1, $isExport = 0, $isSaved = 0,$isCancle = 0)
{
	$sc = '';

	if( $isSaved == 0)
	{
		$sc = 'NC';
	}
	else if( $isExport == 0 && $is_so == 1)
	{
		$sc = 'NE';
	}

	if( $isCancle == 1)
	{
		$sc = 'CN';
	}

	$sc = '<span class="red">'.$sc.'</span>';
	return $sc;
}
 ?>
