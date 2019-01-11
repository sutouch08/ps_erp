<?php 
require "../../library/config.php";
require "../../library/functions.php";
include '../function/tools.php';

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('caCode');
	deleteCookie('caName');
	echo 'success';	
}


if( isset( $_GET['deleteCustomerArea'] ) )
{
	$sc 	= 'success';
	$id		= $_POST['id'];
	$cg	= new customer_area();
	$code = $cg->getAreaCode($id);
	if( $code !== FALSE )
	{
		if($cg->hasMember($code) === FALSE )
		{
			if( $cg->delete($id) === FALSE )
			{ 
				$sc = 'ลบเขตลูกค้าไม่สำเร็จ'; 
			}
		}
		else
		{
			$sc = 'ไม่สามารถลบเขตลูกค้าได้เนื่องจากมีสมาชิกอยู่ในกลุ่ม';
		}
	}
	else
	{
		$sc = 'ไม่พบเขตลูกค้าที่ต้องการลบ';	
	}
	echo $sc;	
}

?>