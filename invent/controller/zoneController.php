<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require '../function/warehouse_helper.php';
require '../function/zone_helper.php';



if( isset( $_GET['deleteZone'] ) )
{
	$sc = 'success';
	$id_zone = $_POST['id_zone'];
	$zone = new zone();
	$rs = $zone->deleteZone($id_zone);
	if( $rs === FALSE )
	{
		$sc = 'ไม่สามารถลบได้ เนื่องจากมีทรานเซ็คชั่นเกิดขึ้นแล้ว';
	}
	echo $sc;
}




if( isset( $_GET['updateZone'] ) )
{
	$sc = 'fail';
	$id_zone	= $_POST['id_zone'];
	if( ! isExistsZoneCode($_POST['code'], $id_zone) && ! isExistsZoneName($_POST['name'], $id_zone) )
	{

		$ds = array(
						'id_warehouse'	=> $_POST['id_warehouse'],
						'barcode_zone'		=> $_POST['code'],
						'zone_name'		=> $_POST['name']
						);
		$zone = new zone();
		$rs = $zone->update($id_zone, $ds);
		if( $rs === TRUE )
		{
			$sc = 'success';
		}
	}

	echo $sc;
}


if(isset($_GET['addCustomerZone']))
{
	$id_zone = $_POST['id_zone'];
	$id_customer = $_POST['id_customer'];
	$zone = new zone();

	$qs = dbQuery("INSERT INTO tbl_zone_customer (id_zone, id_customer) VALUES (".$id_zone.", '".$id_customer."')");
	if($qs === TRUE)
	{
		$id = dbInsertId();
		$customer = new customer($id_customer);
		$sc = array(
			'id' => $id,
			'code' => $customer->code,
			'name' => $customer->name
		);

		echo json_encode($sc);
	}
	else
	{
		echo dbError();
	}
}



if(isset($_GET['removeCustomerZone']))
{
	$id = $_POST['id'];

	$qs = dbQuery("DELETE FROM tbl_zone_customer WHERE id = ".$id);
	if($qs)
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}



if( isset( $_GET['addNewZone'] ) )
{
	$sc = 'fail';

	if( ! isExistsZoneCode($_POST['code']) && ! isExistsZoneName($_POST['name']) )
	{
		$ds = array(
						'id_warehouse'	=> $_POST['id_warehouse'],
						'barcode_zone'		=> $_POST['code'],
						'zone_name'		=> $_POST['name']
						);

		$zone		= new zone();

		$rs	= $zone->add($ds);

		if( $rs !== FALSE )	 //-- It's mean id_zone returned
		{
			$rd = getZoneDetail($rs);
			if( $rd !== FALSE )
			{
				$arr = array(
									'barcode'	=> $rd->barcode_zone,
									'zone_name'	=> $rd->zone_name,
									'warehouse_name'	=> getWarehouseCode($rd->id_warehouse) . ' | ' . warehouseName($rd->id_warehouse)
									);
				$sc = json_encode($arr);
			}
		}
	}
	echo $sc;
}




if( isset( $_GET['checkBarcode'] ) )
{
	$sc = 'ok';
	$barcode = $_GET['barcode'];
	$id_zone = isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : '';
	if( isExistsZoneCode($barcode, $id_zone) === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}




if( isset($_GET['getCustomer']))
{
	$sc = array();
	$cs = new customer();
	$qs = $cs->search(trim($_REQUEST['term']), 'id, code, name');
	if( dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[] = $rs->code.' | '. $rs->name. ' | ' .$rs->id;
		}
	}
	else
	{
		$sc[] = 'ไม่พบรายการ';
	}

	echo json_encode($sc);
}




if( isset( $_GET['checkName'] ) )
{
	$sc = 'ok';
	$name	= $_GET['name'];
	$id_zone		= isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : '';
	if( isExistsZoneName($name, $id_zone) === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}




if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('zCode');
	deleteCookie('zName');
	deleteCookie('zWH');
	deleteCookie('zCus');
	echo 'done';
}

?>
