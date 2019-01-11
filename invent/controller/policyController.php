<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มนโยบายใหม่
if(isset($_GET['addNew']))
{
	include 'policy/policy_add.php';
}


if(isset($_GET['updatePolicy']))
{
	include 'policy/policy_update.php';
}



if(isset($_GET['deletePolicy']))
{
	$sc = TRUE;
	$id = $_POST['id_policy'];
	$cs = new discount_policy();
	$option = $cs->countOrderSold($id) > 0 ? 'HIDE' : 'DELETE';
	if($cs->deletePolicy($id, $option) == FALSE)
	{
		$sc = FALSE;
		$message = 'ลบรายการไม่สำเร็จ';
	}
	else
	{
		dbQuery("UPDATE tbl_discount_rule SET id_discount_policy = 0 WHERE id_discount_policy = ".$id);
	}

	echo $sc === TRUE ? 'success' : $message;
}


if(isset($_GET['clearFilter']))
{
	deleteCookie('policyCode');
	deleteCookie('policyName');
	deleteCookie('isActive');
	deleteCookie('startDate');
	deleteCookie('endDate');
	echo 'done';
}
?>
