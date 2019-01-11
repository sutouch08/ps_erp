<?php

function zoneName($id)
{
	$zone = new zone($id);
	return $zone->name;
}

function isExistsZoneCode($code, $id = '')
{
	$zone = new zone();
	return $zone->isExistsZoneCode($code, $id);
}





function isExistsZoneName($name, $id = '')
{
	$zone = new zone();
	return $zone->isExistsZoneName($name, $id);
}




function getZoneDetail($id)
{
	$zone = new zone();
	return $zone->getZoneDetail($id);
}



function getZoneIn($txt)
{
	$cs = new zone();
	$qs = $cs->searchId($txt);
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		$sc = "";
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= $i == 1 ? "'".$rs->id_zone."'" : ", '".$rs->id_zone."'";
			$i++;
		}
	}
	else
	{
		$sc = "'0'";
	}
	return $sc;
}




function getConsignZoneIn($txt)
{
	$cs = new zone();
	$qs = $cs->searchConsignZoneId($txt);
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		$sc = "";
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= $i == 1 ? "'".$rs->id_zone."'" : ", '".$rs->id_zone."'";
			$i++;
		}
	}
	else
	{
		$sc = "'0'";
	}
	return $sc;
}

?>
