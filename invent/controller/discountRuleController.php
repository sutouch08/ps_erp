<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มนเงื่อนไขใหม่
if(isset($_GET['addNew']))
{
	include 'rule/new_rule.php';
}


if(isset($_GET['updateRule']))
{
	include 'rule/rule_update.php';
}


//------ กำหนดเงื่อนไขส่วนลดในกฏ
if(isset($_GET['setDiscount']))
{
	include 'rule/rule_set_discount.php';
}


//------ กำหนดเงื่อนไขลูกค้า
if(isset($_GET['setCustomerRule']))
{
	include '../function/discount_rule_helper.php';
	include 'rule/rule_set_customer_rule.php';
}


//----- กำหนดเงื่อนไขสินค้า
if(isset($_GET['setProductRule']))
{
	include '../function/discount_rule_helper.php';
	include 'rule/rule_set_product_rule.php';
}


//--- กำหนดเงื่อนไขช่องทางขาย
if(isset($_GET['setChannelsRule']))
{
	include '../function/discount_rule_helper.php';
	include 'rule/rule_set_channels_rule.php';
}


//--- กำหนดเงื่อนไขช่องทางการชำระเงิน
if(isset($_GET['setPaymentRule']))
{
	include '../function/discount_rule_helper.php';
	include 'rule/rule_set_payment_rule.php';
}



if(isset($_GET['getActiveRuleList']))
{
	$id = $_GET['id_policy'];
	$cs = new discount_rule();
	$qs = $cs->getActiveRuleList($id);
	$ds = array();
	if(dbNumRows($qs))
	{
		while($rs = dbFetchObject($qs))
		{
			$arr = array(
				'id_rule' => $rs->id,
				'ruleCode' => $rs->code,
				'ruleName' => $rs->name,
				'date_upd' => thaiDate($rs->date_upd)
			);

			array_push($ds, $arr);
		}
	}
	else
	{
		$arr = array(
			'nodata' => 'nodata'
		);

		array_push($ds, $arr);
	}

	echo json_encode($ds);
}




if(isset($_GET['addPolicyRule']))
{
	$id_policy = $_POST['id_policy'];
	$rule = $_POST['rule'];
	$cs = new discount_rule();
	$sc = TRUE;
	if(!empty($rule))
	{
		foreach($rule as $id_rule)
		{
			if($cs->setPolicy($id_rule, $id_policy) == FALSE)
			{
				$sc = FALSE;
				$message = 'เพิ่มกฏไม่สำเร็จ';
			}
		}	//--- end foreach
	}	//--- end if empty

	echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['unlinkRule']))
{
	$sc = TRUE;
	$id = $_POST['id_rule'];
	$cs = new discount_rule();
	if($cs->unsetPolicy($id) !== TRUE)
	{
		$sc = FALSE;
		$message = 'ลบกฎไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['deleteRule']))
{
	$sc = TRUE;
	$id = $_POST['id_rule'];
	$cs = new discount_rule($id);
	if($cs->id_discount_policy != 0)
	{
		$sc = FALSE;
		$message = 'ไม่สามารถลบกฎที่ถูกเชื่อมโยงได้';
	}
	else
	{
		$countOrder = $cs->countOrder($id);
		$countSold = $cs->countOrderSold($id);
		if($countOrder > 0 OR $countSold > 0)
		{
			$option = 'HIDE';  //--- just change is_delete = 1
			if($cs->deleteRule($id, $option) == FALSE)
			{
				$sc = FALSE;
				$message = 'ลบรายการไม่สำเร็จ';
			}
		}
		else
		{
			$option = 'DELETE';
			if($cs->deleteRule($id, $option) == FALSE)
			{
				$sc = FALSE;
				$message = 'ลบรายการไม่สำเร็จ';
			}
		}	//--- end if countOrder
	}	//--- end if id_discount_policy;

	echo $sc === TRUE ? 'success' : $message;
}




if(isset($_GET['clearFilter']))
{
	deleteCookie('ruleCode');
	deleteCookie('ruleName');
	deleteCookie('isActive');
	deleteCookie('sPolicy');
	deleteCookie('sDisc');
	echo 'done';
}
?>
