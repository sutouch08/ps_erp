<?php

function selectBudgetYear($id, $id_budget)
{
  $sc = '';

  $bd = new support_budget();
  $qs = $bd->getSupportBudgetYear($id);
  while( $rs = dbFetchObject($qs))
  {
    $sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $id_budget).'>'.$rs->year.'</option>';
  }

  return $sc;
}



//--- for add budget
function selectSupportYears()
{
  $sc 		= '';
	$length	= 5;
	$cYear = date('Y');
  $year = $cYear - $length;
	$lYear = $cYear + $length;
	while( $year <= $lYear )
	{
		$sc .= '<option value="'.$year.'" '.isSelected($year, $cYear).'>'.$year.'</option>';
		$year++;
	}

	return $sc;
}
?>
