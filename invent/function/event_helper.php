<?php
function getEventIn($txt)
{
	$sc = "";
	$cs = new event();
	$qs = $cs->search($txt);
	if( dbNumRows($qs) > 0)
	{
		$i = 1;
		while($rs = dbFetchObject($qs))
		{
			$sc .= $i == 1 ? "'".$rs->id."'" : ", '".$rs->id."'";
			$i++;
		}
	}
	else
	{
		$sc .= "'0'";
	}

	return $sc;
}


function eventName($id)
{
	$cs = new event($id);
	return $cs->name;
}
?>
