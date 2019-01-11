<?php 
require "../../library/config.php";
require "../../library/functions.php";
include '../function/tools.php';
include_once '../function/group_helper.php';

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('cgCode');
	deleteCookie('cgName');
	echo 'success';	
}


if( isset( $_GET['deleteCustomerGroup'] ) )
{
	$sc 	= 'success';
	$id		= $_POST['id'];
	$cg	= new customer_group();
	$code = $cg->getGroupCode($id);
	if( $code !== FALSE )
	{
		if($cg->hasMember($code) === FALSE )
		{
			if( $cg->delete($id) === FALSE )
			{ 
				$sc = 'ลบกลุ่มไม่สำเร็จ'; 
			}
		}
		else
		{
			$sc = 'ไม่สามารถลบกลุ่มได้เนื่องจากมีสมาชิกอยู่ในกลุ่ม';
		}
	}
	else
	{
		$sc = 'ไม่พบกลุ่มที่ต้องการลบ';	
	}
	echo $sc;	
}

?>