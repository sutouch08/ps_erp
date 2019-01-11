<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';
require "../function/transport_helper.php";
require "../function/order_helper.php";
require "../function/date_helper.php";

/****** add new *****/
if( isset( $_GET['insertAddress'] ) && isset( $_POST['id_customer'] ) )
{
	$sc = 'fail';
	$data = array(
						'id_customer'	=> $_POST['id_customer'],
						'alias'				=> $_POST['alias'],
						'company'		=> $_POST['company'],
						'first_name'		=> $_POST['fname'],
						'last_name'		=> $_POST['lname'],
						'address1'		=> $_POST['address1'],
						'address2'		=> $_POST['address2'],
						'city'				=> $_POST['city'],
						'postcode'		=> $_POST['postcode'],
						'phone'			=> $_POST['phone'],
						'remark'			=> $_POST['remark']
						);
	$ad 	= new address();
	$rs = $ad->add($data);
	if( $rs )
	{
		$sc = 'success';
	}
	echo $sc;
}



if( isset( $_GET['updateAddress'] ) && isset( $_GET['id_address'] ) )
{
	$sc = 'fail';
	$id = $_GET['id_address'];
	$data = array(
						'id_customer'	=> $_POST['id_customer'],
						'alias'				=> $_POST['alias'],
						'company'		=> $_POST['company'],
						'first_name'		=> $_POST['fname'],
						'last_name'		=> $_POST['lname'],
						'address1'		=> $_POST['address1'],
						'address2'		=> $_POST['address2'],
						'city'				=> $_POST['city'],
						'postcode'		=> $_POST['postcode'],
						'phone'			=> $_POST['phone'],
						'remark'			=> $_POST['remark']
						);
	$ad 	= new address();
	$rs = $ad->update($id, $data);
	if( $rs === TRUE )
	{
		$sc = 'success';
	}
	echo $sc;
}





if( isset( $_GET['deleteAddress'] ) && isset( $_GET['id_address'] ) )
{
	$sc = 'fail';
	$ad = new address();
	$rs = $ad->delete($_GET['id_address']);
	if( $rs === TRUE )
	{
		$sc = 'success';
	}
	echo $sc;
}





if( isset( $_GET['getAddress'] ) )
{
	include "../function/customer_helper.php";
	$sc = "";
	$ds = array();
	$id_address = $_POST['id_address'];
	$qs = dbQuery("SELECT * FROM tbl_address WHERE id_address = ".$id_address);
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchObject($qs);
		$arr = array(
							'id_address'		=> $rs->id_address,
							'id_customer'	=> $rs->id_customer,
							'customer_name'		=> customerName($rs->id_customer),
							'Fname'			=> $rs->first_name,
							'Lname'			=> $rs->last_name,
							'company'		=> $rs->company,
							'address1'		=> $rs->address1,
							'address2'		=> $rs->address2,
							'province'		=> $rs->city,
							'postcode'		=> $rs->postcode,
							'phone'			=> $rs->phone,
							'alias'				=> $rs->alias,
							'remark'			=> $rs->remark
							);
	}
	else
	{
		$arr = array("nodata" => "nodata");
	}
	echo json_encode($arr);
}




if( isset( $_GET['getAddressTable'] ) )
{
	$sc = "";
	$ds = array();
	$id_customer = $_POST['id_customer'];
	$qs = dbQuery("SELECT * FROM tbl_address WHERE id_customer = '".$id_customer."'");
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$arr = array(
								'id_address'		=> $rs->id_address,
								'alias'				=> $rs->alias,
								'name'				=> $rs->company == "" ? $rs->first_name.' '.$rs->last_name : $rs->company,
								'address'		=> $rs->address1.' '.$rs->address2.' '.$rs->city.' '.$rs->postcode,
								'phone'			=> $rs->phone
								);
			array_push($ds, $arr);
		}
	}
	else
	{
		$arr = array( "nodata" => "nodata");
		array_push($ds, $arr);
	}

	$sc = json_encode($ds);
	echo $sc;

}






if( isset( $_GET['getAddressInfo'] ) )
{
	$ad = new address($_GET['id_address']);
	$data = array(
					"alias" 		=> $ad->alias,
					"company"	=> $ad->company,
					"customer"	=> $ad->first_name.' '.$ad->last_name,
					"address"	=> $ad->address1.' '.$ad->address2,
					"city"			=> $ad->city,
					"postcode"	=> $ad->postcode,
					"phone"		=> $ad->phone,
					"remark"		=> $ad->remark
					);
	echo json_encode($data);
}






if( isset( $_GET['check_sender'] ) && isset( $_GET['sender_name'] ) )
{
	$name = trim($_GET['sender_name']);
	if( isset( $_GET['id_sender'] ) )
	{
		$id = $_GET['id_sender'];
		$qs = dbQuery("SELECT name FROM tbl_sender WHERE id_sender != ".$id." AND name = '".$name."'");
	}
	else
	{
		$qs = dbQuery("SELECT name FROM tbl_sender WHERE name = '".$name."'");
	}
	echo dbNumRows($qs);
}






if( isset( $_GET['addNewSender'] ) && isset( $_POST['type'] ) )
{
	$name 		= $_POST['name'];
	$address1 	= $_POST['address1'];
	$address2 	= $_POST['address2'];
	$phone 		= $_POST['phone'];
	$open			= $_POST['open'];
	$close		= $_POST['close'];
	$type 		= $_POST['type'] == 'เก็บเงินปลายทาง' ? $_POST['type'] : 'เก็บเงินต้นทาง';
	$qs = dbQuery("INSERT INTO tbl_sender ( name, address1, address2, phone, open, close, type ) VALUES ( '".$name."', '".$address1."', '".$address2."', '".$phone."', '".$open."', '".$close."', '".$type."')");
	if( $qs )
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}






if( isset( $_GET['updateSender'] ) && isset( $_GET['id_sender'] ) )
{
	$id 		 	= $_GET['id_sender'];
	$name 		= $_POST['name'];
	$address1 	= $_POST['address1'];
	$address2 	= $_POST['address2'];
	$phone 		= $_POST['phone'];
	$open			= $_POST['open'];
	$close		= $_POST['close'];
	$type 		= $_POST['type'] == 'เก็บเงินปลายทาง' ? $_POST['type'] : 'เก็บเงินต้นทาง';
	$qs = dbQuery("UPDATE tbl_sender SET name = '".$name."', address1 = '".$address1."', address2 = '".$address2."', phone = '".$phone."', open = '".$open."', close = '".$close."', type = '".$type."' WHERE id_sender = ".$id);
	if( $qs )
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}






if( isset( $_GET['deleteSender'] ) && isset( $_GET['id_sender'] ) )
{
	$id = $_GET['id_sender'];
	$qs = dbQuery("DELETE FROM tbl_sender WHERE id_sender = ".$id);
	if( $qs )
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}






if( isset( $_GET['deleteTransportCustomer'] ) && isset( $_GET['id_transport'] ) )
{
	$id = $_GET['id_transport'];
	$qs = dbQuery("DELETE FROM tbl_transport WHERE id_transport = ".$id);
	if( $qs )
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}





if( isset( $_GET['isTransportCustomerExists'] ) && isset( $_GET['id_customer'] ) )
{
	$qs = dbQuery("SELECT id_customer FROM tbl_transport WHERE id_customer = '".$_GET['id_customer']."'");
	echo dbNumRows($qs);
}





if( isset( $_GET['insertTransportCustomer'] ) && isset( $_POST['id_customer'] ) )
{
	$id_customer 		= $_POST['id_customer'];
	$main_sender 		= $_POST['main_sender'];
	$second_sender	= $_POST['second_sender'] == '' ? 0 : $_POST['second_sender'];
	$third_sender 		= $_POST['third_sender'] == '' ? 0 : $_POST['third_sender'];
	$qs = dbQuery("INSERT INTO tbl_transport (id_customer, main_sender, second_sender, third_sender) VALUES ('".$id_customer."', '".$main_sender."', '".$second_sender."', '".$third_sender."')");
	if( $qs )
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}





if( isset( $_GET['updateTransportCustomer'] ) && isset( $_GET['id_transport'] ) )
{
	$id = $_GET['id_transport'];
	$main = $_POST['main_sender'];
	$sec = $_POST['second_sender'] == '' ? 0 : $_POST['second_sender'];
	$third = $_POST['third_sender'] == '' ? 0 : $_POST['third_sender'];
	$qs = dbQuery("UPDATE tbl_transport SET main_sender = '".$main."', second_sender = '".$sec."', third_sender = '".$third."' WHERE id_transport = ".$id);
	if( $qs )
	{
		echo 'success';
	}
	else
	{
		echo 'fail';
	}
}





if( isset( $_GET['getSenderInfo'] ) && isset( $_GET['id_sender'] ) )
{
	$id = $_GET['id_sender'];
	$data = array();
	$qs = dbQuery("SELECT * FROM tbl_sender WHERE id_sender = '".$id."'");
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);
		$data = array(
							"sender_name"	=> $rs['name'],
							"address" 		=> $rs['address1'].' '.$rs['address2'],
							"phone" 			=> $rs['phone'],
							"opentime" 		=> date("H:i", strtotime($rs['open'])).' - '.date("H:i", strtotime($rs['close'])),
							"type" 			=> $rs['type']
							);
	}
	echo json_encode($data);
}





if( isset( $_GET['countAddress'] ) )
{
	$adds = 0;
	if( isset( $_POST['id_customer'] ) )
	{
		$id_customer = $_POST['id_customer'];
		$adds = countAddress($id_customer);
	}
	echo $adds;
}





if( isset( $_GET['getAddressForm'] ) )
{
	include 'address/address_form.php';
}





if( isset($_GET['clearFilter']) )
{
	deleteCookie('sCustomer');
	deleteCookie('sAddress');
	deleteCookie('sProvince');
	echo 'success';
}



?>
