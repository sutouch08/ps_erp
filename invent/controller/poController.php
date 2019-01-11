<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if( isset( $_GET['closePO'] ) )
{
	$reference 	= $_GET['reference'];
	$bookcode	= $_GET['bookcode'];
	$po = new po();
	$sc = $po->close($bookcode, $reference) === TRUE ? 'success' : 'ปิดใบสั่งซื้อไม่สำเร็จ';
	echo $sc;
}


if( isset( $_GET['unClosePO'] ) )
{
	$reference 	= $_GET['reference'];
	$bookcode	= $_GET['bookcode'];
	$po = new po();
	$sc = $po->unClose($bookcode, $reference) === TRUE ? 'success' : 'ยกเลิกการปิดใบสั่งซื้อไม่สำเร็จ';
	echo $sc;
}



if(isset($_GET['deletePo']))
{
	$sc = TRUE;
	$reference = $_POST['reference'];
	$po = new po($reference);
	if( $po->status == 1 )
	{
		if( $po->delete($reference) !== TRUE )
		{
			$sc = FALSE;
			$message = $po->error;
		}
	}
	else if($po->status == 2 )
	{
		$sc = FALSE;
		$message = 'ไม่สามารถลบใบสั่งซื้อได้เนื่องจากมีการรับสินค้าแล้วบางส่วน';
	}
	else if( $po->status == 3)
	{
		$sc = FALSE;
		$message = 'ไม่สามารถลบใบสั่งซื้อที่ปิดแล้วได้';
	}

	echo $sc === TRUE ? 'success' : $message;

}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sPoCode');
	deleteCookie('sPoName');
	deleteCookie('sFrom');
	deleteCookie('sTo');
	deleteCookie('sPoStatus');
	echo 'done';
}

?>
