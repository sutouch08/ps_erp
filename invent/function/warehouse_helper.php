<?php
/////////////////////////////////
///////   Warehouse Helper //////
////////////////////////////////
function getWarehouseIn($txt)
{
	$sc = '1234567890';
	$qs = dbQuery("SELECT id FROM tbl_warehouse WHERE code LIKE'%".$txt."%' OR name LIKE'%".$txt."%'");
	if(dbNumRows($qs) > 0)
	{
		$sc = '';
		$i = 1;
		while($rs = dbFetchObject($qs))
		{
			$sc .= $i == 1? "'".$rs->id."'" : ", '".$rs->id."'";
			$i++;
		}
	}

	return $sc;
}


function selectWarehouse($se = 0 )
{
	$warehouse = new warehouse();
	$sc = '<option value="0">โปรดเลือก</option>';
	$qs = $warehouse->getDatas();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $se).'>'.$rs->code .' | ' .$rs->name .'</option>';
		}
	}
	return $sc;
}



function selectBranch($se = 0)
{
	$sc = '';
	$branch = new branch();
	$qs = $branch->getData();
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $se).'>'.$rs->code.' : '.$rs->name.'</option>';
		}
	}

	return $sc;
}



function selectWarehouseRole($se = 0)
{
	$warehouse = new warehouse();
	$qs = $warehouse->getRoleDatas();
	$sc = '<option value="0">โปรดเลือก</option>';

	if(dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $se).'>'.$rs->name.'</option>';
		}
	}
	return $sc;
}





function getWarehouseRoleName($id)
{
	$warehouse = new warehouse();
	return $warehouse->getRoleName($id);
}





function isExistsWarehouseCode($code, $id = "")
{
	$warehouse = new warehouse();
	return $warehouse->isExistsWarehouseCode($code, $id);
}





function isExistsWarehouseName($name, $id="")
{
	$wh = new warehouse();
	return $wh->isExistsWarehouseName($name, $id);
}





function getWarehouseDetail($id)
{
	$wh = new warehouse();
	return $wh->getWarehouseDetail($id);
}






function isEmptyWarehouse($id)
{
	$wh = new warehouse();
	return $wh->isEmptyWarehouse($id);
}




function getWarehouseCode($id)
{
	$wh = new warehouse();
	return $wh->getCode($id);
}





//-------------- คลังนี้สามารถติดลบได้หรือไม่
function isAllowUnderZero($id)
{
	$wh = new warehouse();
	return $wh->isAllowUnderZero($id);
}





function isAllowPrepare($id)
{
	$wh = new warehouse();
	return $wh->isAllowPrepare($id);
}





function isAllowSell($id)
{
	$wh = new warehouse();
	return $wh->isAllowSell($id);
}




function isWarehouseActive($id)
{
	$wh = new warehouse();
	return $wh->isWarehouseActive($id);
}


function warehouseName($id)
{
	$wh = new warehouse();
	return $wh->getName($id);
}


?>
