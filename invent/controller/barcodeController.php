<?php
require '../../library/config.php';
require '../../library/functions.php';
require "../function/tools.php";


if( isset( $_GET['deleteBarcode'] ) )
{
	$sc = TRUE;
	$barcode = $_POST['barcode'];
	$bc	= new barcode();

	if( $bc->delete($barcode) !== TRUE)
	{
		$sc = FALSE;
		$message = 'ลบรายการไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['getData']))
{
	$sc = FALSE;
	$id = $_GET['id_barcode'];
	$bc = new barcode($id);
	if($bc->id != '')
	{
		$arr = array(
			'id_barcode' => $bc->id,
			'barcode' => $bc->barcode,
			'pdCode' => $bc->reference
		);

		$sc = json_encode($arr);
	}

	echo $sc === FALSE ? 'ไม่พบข้อมูล' : $sc;
}



if(isset($_GET['isDuplicate']))
{
	$id = $_GET['id_barcode'];
	$barcode = $_GET['barcode'];
	$sc = 'ok';
	$bc = new barcode();
	if($bc->isDuplicate($id, $barcode) === TRUE)
	{
		$sc = 'duplicate';
	}

	echo $sc;
}


if(isset($_GET['updateBarcode']))
{
		$id = $_POST['id_barcode'];
		$barcode = $_POST['barcode'];

		$bc = new barcode();

		echo $bc->update($id, array('barcode' => $barcode)) === TRUE ? 'success' : $bc->error;
}



if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sProduct');
	deleteCookie('sBarcode');
	deleteCookie('sUnit');
	echo "success";
}

?>
