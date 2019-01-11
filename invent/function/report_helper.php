<?php


function setToken($token)
{
	setcookie("file_download_token", $token, time() +3600,"/");
}


function selectBudgetYear($se = '')
{
	$sc = '';
	$se = $se == '' ? date('Y') : $se;
	$qs = dbQuery("SELECT DISTINCT tbl_sponsor_budget.year FROM tbl_sponsor_budget ORDER BY year ASC");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc .= '<option value="'.$rs->year.'" '.isSelected($se, $rs->year).'>'.$rs->year.'</option>';
		}

	}

	return $sc;
}


?>
