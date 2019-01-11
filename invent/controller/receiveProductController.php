<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../function/receive_product_helper.php";


if(isset($_GET['addDetail']))
{
	include 'receive_product/add_detail.php';
}




if( isset( $_GET['getPoData'] ) )
{
	$reference = $_GET['reference']; // po
	$po 			= new po();
	$qs 			= $po->getDetail($reference);
	if( dbNumRows($qs) > 0 )
	{
		$ds = array();
		$no = 1;
		$totalQty	= 0;
		$totalBacklog = 0;
		$pd = new product();
		$bc = new barcode();
		$limit = getConfig('RECEIVE_OVER_PO');
		while( $rs = dbFetchObject($qs) )
		{
			$backlog = $rs->qty - $rs->received < 0 ? 0 : $rs->qty - $rs->received;
			$arr = array(
								"no"	=> $no,
								"barcode"	=> trim($bc->getBarcode($rs->id_product)),
								"id_pd"		=> $rs->id_product,
								"pdCode"		=> $pd->getCode($rs->id_product),
								"pdName"		=> $pd->getName($rs->id_product),
								"qty"			=> number_format($rs->qty),
								"limit"			=> ($rs->qty + ($rs->qty* ( $limit * 0.01 ) ) ) - $rs->received,
								"backlog"		=> number_format($backlog)
							);
			array_push($ds, $arr);
			$totalQty += $rs->qty;
			$totalBacklog += $backlog;
			$no++;
		}
		$arr = array(
							"qty"			=> number_format($totalQty),
							"backlog"		=> number_format($totalBacklog)
						);
		array_push($ds, $arr);
		$sc = json_encode($ds);
	}
	else
	{
		$sc = 'ใบสั่งซื้อไม่ถูกต้อง ใบสั่งซื้ออาจถูกปิด หรือ ถูกยกเลิกไปแล้ว';
	}
	echo $sc;
}





if( isset( $_GET['addNew'] ) )
{
	include 'receive_product/receive_add.php';
}




if( isset( $_GET['cancleReceived'] ) )
{
	include 'receive_product/receive_cancle.php';
}



if( isset( $_GET['getApprove'] ) )
{
	$sKey = $_GET['sKey'];
	$id_tab = 49; //---- อนุมัติรับสินค้าเกินใบสั่งซื้อ
	$valid = new validate();
	$rs = $valid->getApproveCode($id_tab, $sKey);

	if( $rs !== FALSE )
	{
		echo $rs;
	}
	else
	{
		echo 'fail';
	}
}





if( isset($_GET['search_supplier'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$sp = new supplier();
	$qs = $sp->search($_REQUEST['term']);
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc[] = $rs->code.' : ' . $rs->name .' | '. $rs->id;
		}
	}
	echo json_encode($sc);
}


if( isset( $_GET['search_po'] ) && isset( $_REQUEST['term'] ) )
{
	$id_sup = isset($_GET['id_supplier']) ? $_GET['id_supplier'] : FALSE;
	$sc = array();
	$po = new po();
	$qs = $po->search($_REQUEST['term'], $id_sup);
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->reference;
	}
	echo json_encode($sc);
}





if( isset( $_GET['search_zone'] ) && isset( $_REQUEST['term'] ) )
{
	$sc 	= array();
	$zone = new zone();
	$role = '5';
	$qs 	= $zone->searchReceiveZone($_REQUEST['term']);

	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc[] = $rs->zone_name.' | '.$rs->id_zone;
		}
	}
	else
	{
		$sc[] = "ไม่พบโซน";
	}
	echo json_encode($sc);
}





if(isset($_GET['update']))
{
	$sc = TRUE;
	$id = $_POST['id_receive_product'];
	$remark = $_POST['remark'];
	$date_add = dbDate($_POST['date_add'], TRUE);

	$arr = array(
		'date_add' => $date_add,
		'remark' => addslashes($remark)
	);

	$cs = new receive_product();
	if($cs->update($id, $arr) !== TRUE)
	{
		$sc = FALSE;
		$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}


if(isset($_GET['updateDocument']))
{
	$sc = TRUE;
	$id = $_POST['id_receive_product'];
	$date_add = dbDate($_POST['date_add'], TRUE);
	$invoice = $_POST['invoice'];
	$id_supplier = $_POST['id_supplier'];
	$remark = $_POST['remark'];

	$arr = array(
		'date_add' => $date_add,
		'invoice' => addslashes($invoice),
		'id_supplier' => $id_supplier,
		'remark' => addslashes($remark)
	);

	$cs = new receive_product();
	if($cs->update($id, $arr) !== TRUE)
	{
		$sc = FALSE;
		$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;

}



if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sReceiveCode');
	deleteCookie('sReceivePo');
	deleteCookie('sReceiveInv');
	deleteCookie('sReceiveSup');
	deleteCookie('sFrom');
	deleteCookie('sTo');
	deleteCookie('sReceiveStatus');
	echo 'done';
}

?>
