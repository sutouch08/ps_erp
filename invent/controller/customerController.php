<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if( isset( $_GET['saveGeneral'] ) )
{
	$sc = 'success';
	$id = $_POST['id_customer'];
	$cs = new customer();
	$arr = array(
						"id_kind"	=> $_POST['kind'],
						"id_type"	=> $_POST['type'],
						"id_class"	=> $_POST['class']
						);
	if( $cs->update($id, $arr) === FALSE )
	{
		$sc = 'บันทึกรายการไม่สำเร็จ';
	}
	echo $sc;
}



if( isset( $_GET['unDeleteCustomer'] ) )
{
	$sc 				= 'success';
	$id_customer	= $_POST['id_customer'];
	$emp				= getCookie('user_id');
	$cust				= new customer();
	if( $cust->unDelete($id_customer, $emp) === FALSE )
	{
		$sc = 'fail';
	}
	echo $sc;
}





if( isset( $_GET['deleteCustomer'] ) )
{
	$sc 				= 'success';
	$id_customer	= $_POST['id_customer'];
	$emp				= getCookie('user_id');
	$cust				= new customer();
	if( $cust->delete($id_customer, $emp) === FALSE )
	{
		$sc = 'fail';
	}
	echo $sc;
}





if( isset( $_GET['updateDiscount'] ) )
{
	$sc = TRUE;
	$id_customer	= $_POST['id_customer'];
	$group	= $_POST['group'];
	if( count( $group ) > 0 )
	{
		startTransection();
		$dc	= new discount();
		foreach( $group as $id_group => $discount )
		{
			if( $dc->isExistsGroupDiscount($id_customer, $id_group) === FALSE )
			{
				if( $discount != 0 )
				{
					$arr = array(
								'id_customer'		=> $id_customer,
								'id_product_group'	=> $id_group,
								'discount'				=> $discount,
								'emp'					=> getCookie('user_id')
								);
					if( $dc->add($arr) === FALSE )
					{
						$sc = FALSE;
					}
				}
			}
			else
			{
				$id_discount 	= $dc->getDiscountIdByGroup($id_customer, $id_group);
				if( $id_discount !== FALSE )
				{
					if( $discount != 0 )
					{
						$arr	= array(
											'discount'		=> $discount,
											'unit'			=> 'percentage',
											'emp'			=> getCookie('user_id')
											);
						if( $dc->update($id_discount, $arr) === FALSE )
						{
							$sc = FALSE;
						}
					}
					else
					{
						$dc->delete($id_discount);
					}

				}
				else
				{
					$sc = FALSE;
				}
			}
		}//---- end foreach

		if( $sc !== FALSE )
		{
			commitTransection();
		}
		else
		{
			dbRollback();
		}
		endTransection();
	}
	echo $sc === TRUE ? 'success' : 'fail';
}



if(isset($_GET['getCustomerId']))
{
	$code = trim($_GET['customerCode']);
	$customer = new customer();
	echo $customer->getId($code);
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('cName');
	deleteCookie('cCode');
	deleteCookie('cGroup');
	deleteCookie('cKind');
	deleteCookie('cType');
	deleteCookie('cClass');
	deleteCookie('cArea');
	deleteCookie('cProvince');
	echo "success";
}

?>
