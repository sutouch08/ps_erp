<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../function/order_helper.php';
include '../function/customer_helper.php';


//---- เพิ่มเลขที่เอกสารใหม่
if( isset( $_GET['addNew'] ) )
{
	include 'order/new_order.php';
}




//----- 	บันทึกเอกสารเปลี่ยนสถานะ เป็นบันทึกแล้ว
//-----	ถ้ามีเครดิตเทอม จะตัดยอดเครดิตด้วย
if( isset( $_GET['saveOrder'] ) )
{
	$order 		= new order($_POST['id_order']);

	//---	ถ้าเป็นการขาย
	if( $order->role == 1)
	{
		include 'order/save_order.php';
	}

	//---	ฝากขาย
	if( $order->role == 2)
	{
		include 'order/save_consign.php';
	}

	//--- ถ้าเป็นการเบิกอภินันท์
	if( $order->role == 3)
	{
		include 'order/save_support.php';
	}

	//---	ถ้าเป็นสปอนเซอร์
	if( $order->role == 4)
	{
		include 'order/save_sponsor.php';
	}

	//---	ถ้าเป็นเบิกแปรสภาพ
	if( $order->role == 5)
	{
		include 'order/save_transform.php';
	}

	//--- ถ้าเป็นยืมสินค้า
	if( $order->role == 6)
	{
		include 'order/save_lend.php';
	}

}




if(isset($_GET['getBalanceCredit']))
{
	$id_customer = $_GET['id_customer'];
	$cs = new customer_credit($id_customer);
	echo $cs->balance;
}



//----- 	แก้ไขหัวเอกสาร
//------ 	ถ้ามีการเปลี่ยนวันที่ /ชื่อลูกค้า /ช่องทางการชำระเงิน /ช่องทางการขาย จะทำการคำนวณส่วนลดใหม่
//------	ถ้าการชำระเงินมีเครดิตเทอม จะคืนยอดใช้ไปก่อน แล้วค่อยบันทึกเพื่อตัดยอดเครดิตอีกที
if( isset( $_GET['updateOrder'] ) )
{
	$order = new order($_POST['id_order']);

	//---	ขาย
	if( $order->role == 1)
	{
		include 'order/update_order.php';
	}

	//---	ฝากขาย
	if( $order->role == 2)
	{
		include 'order/update_consign.php';
	}

	//---	เบิกอภินันท์
	if( $order->role == 3)
	{
		include 'order/update_support.php';
	}

	//---	สปอนเซอร์
	if( $order->role == 4)
	{
		include 'order/update_sponsor.php';
	}

	//---	เบิกแปรสภาพ
	if( $order->role == 5)
	{
		include 'order/update_transform.php';
	}

	//---	ยืมสินค้า
	if( $order->role == 6)
	{
		include 'order/update_lend.php';
	}


}





//----- แก้ไขส่วนลดโดยพนักงาน และมีผู้อนุมัติการแก้ไข
if( isset( $_GET['updateEditDiscount'] ) )
{
	$order = new order($_POST['id_order']);
	if( $order->role == 2)
	{
		include 'order/edit_consign_gp.php';
	}

	if( $order->role == 1)
	{
		include 'order/edit_discount.php';
	}

}




//---- แก้ไขราคาสินค้า โดยพนักงาน และมีผู้อนุมัติการแก้ไข
if( isset( $_GET['updateEditPrice'] ) )
{
	$order 		= new order($_POST['id_order']);

	//---	ขาย
	if( $order->role == 1)
	{
		include 'order/edit_order_price.php';
	}

	//---	ฝากขาย
	if( $order->role == 2)
	{
		include 'order/edit_consign_price.php';
	}

	//--- เบิกอภินันท์
	if( $order->role == 3)
	{
		include 'order/edit_support_price.php';
	}

	//---	เบิกสปอนเซอร์
	if( $order->role == 4)
	{
		include 'order/edit_sponsor_price.php';
	}

}



//---- แก้ไขราคาสินค้าที่ไม่มีการนับสต็อก
if( isset($_GET['updateNonCountPrice']) )
{
	$order = new order($_POST['id_order']);
	if($order->state > 7)
	{
		echo 'ไม่สามารถแก้ไขราคาได้ เนื่องจากออเดอร์ถูกเปิดบิลไปแล้ว';
	}
	else
	{
		include 'order/edit_order_non_count_price.php';
	}
}


//---- แก้ไขราคาทุนสินค้า โดยพนักงาน และมีผู้อนุมัติการแก้ไข
if( isset( $_GET['updateEditCost'] ) )
{
	include 'order/edit_cost.php';
}




//----		เพิ่มรายการสินค้าเข้าออเดอร์พร้อมคำนวณส่วนลดจากนโยบายส่วนลด
if( isset( $_GET['addToOrder'] ) )
{
	include 'order/add_detail.php';
}





//----- Delete detail row
if( isset( $_GET['removeDetail'] ) )
{
	$id 	= $_POST['id_order_detail'];
	$id_order = $_POST['id_order'];
	$order = new order($id_order);

	//---	ขาย
	if( $order->role == 1)
	{
		include 'order/delete_order_detail.php';
	}

	//---	ฝากขาย
	if( $order->role == 2)
	{
		include 'order/delete_consign_detail.php';
	}

	//---	เบิกอภินันท์
	if( $order->role == 3)
	{
		include 'order/delete_support_detail.php';
	}

	//---	เบิกสปอนเซอร์
	if( $order->role == 4 )
	{
		include 'order/delete_sponsor_detail.php';
	}

	//---	เบิกแปรสภาพ
	if( $order->role == 5)
	{
		include 'order/delete_transform_detail.php';
	}

}





//----		Attribute Grid By Search box
if( isset( $_GET['getProductGrid'] ) && isset( $_GET['pdCode'] ) )
{
	$sc = 'not exists';
	$pdCode = trim($_GET['pdCode']);
	//$id_order = isset($_GET['id_order']) ? $_GET['id_order'] : '';
	$id_branch = isset($_GET['id_branch']) ? $_GET['id_branch'] : 0;

	$qr = "SELECT code FROM tbl_product_style WHERE code = '".$pdCode."' AND active = 1 AND can_sell = 1 AND is_deleted = 0";
	$qs = dbQuery($qr);
	if( dbNumRows($qs) > 0 )
	{
		$pd = new product();
		$grid = new product_grid();
		$style = new style();
		$id_style = $style->getId($pdCode);
		$view = FALSE; //----- View stock ? TRUE = view stock only FALSE = order
		$sc = $grid->getOrderGrid($id_style, $view, $id_branch);
		$tableWidth	= $pd->countAttribute($id_style) == 1 ? 800 : $grid->getOrderTableWidth($id_style);
		$sc .= ' | ' . $tableWidth;
		$sc .= ' | ' . $id_style;
	}
	echo $sc;
}




//----		Attribute Grid By Search box in sale page
if( isset( $_GET['getSaleProductGrid'] ) && isset( $_GET['pdCode'] ) )
{
	$sc = 'not exists';
	$pdCode = trim($_GET['pdCode']);
	$id_branch = isset($_GET['id_branch']) ? $_GET['id_branch'] : 0;
	$view = FALSE;

	$qr  = "SELECT code FROM tbl_product_style ";
	$qr .= "WHERE code = '".$pdCode."' ";
	$qr .= "AND active = 1 ";
	$qr .= "AND can_sell = 1 ";
	$qr .= "AND is_deleted = 0 ";
	$qr .= "AND show_in_sale = 1";

	$qs = dbQuery($qr);

	if( dbNumRows($qs) > 0 )
	{
		$pd = new product();
		$grid = new product_grid();
		$style = new style();
		$id_style = $style->getId($pdCode);
		$sc = $grid->getOrderGrid($id_style, $view, $id_branch);
		$tableWidth	= $pd->countAttribute($id_style) == 1 ? 800 : $grid->getOrderTableWidth($id_style);
		$sc .= ' | ' . $tableWidth;
		$sc .= ' | ' . $id_style;
	}
	echo $sc;
}




//----- Attribute Grid By Clicking image
if( isset( $_GET['getOrderGrid'] ) && isset( $_GET['id_style'] ) )
{
	$sc = 'not exists';
	$id_style = $_GET['id_style'];
	$id_branch = isset($_GET['id_branch']) ? $_GET['id_branch'] : 0;

	$pd = new product();
	$grid = new product_grid();
	$style = new style();
	$view = FALSE; //----- View stock ? TRUE = view stock only FALSE = order
	$sc = $grid->getOrderGrid($id_style, $view, $id_branch);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	$sc .= ' | ' . $id_style;
	echo $sc;
}




//----- Attribute Grid By Clicking image
if( isset( $_GET['getStockGrid'] ) && isset( $_GET['id_style'] ) )
{
	$sc = 'not exists';
	$id_style = $_GET['id_style'];
	$id_branch = isset($_GET['id_branch']) ? $_GET['id_branch'] : 0;
	$pd = new product();
	$grid = new product_grid();
	$style = new style();
	$view = TRUE;  //--- view stock
	$sc = $grid->getOrderGrid($id_style, $view, $id_branch);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	$sc .= ' | ' . $id_style;
	echo $sc;
}



//----- Attribute Grid By Clicking image
if( isset( $_GET['getSaleStockGrid'] ) && isset( $_GET['id_style'] ) )
{
	$sc = 'not exists';
	$id_style = $_GET['id_style'];
	$style = new style($id_style);
	$id_branch = isset($_GET['id_branch']) ? $_GET['id_branch'] : 0;

	if($style->show_in_sale == 1 && $style->can_sell == 1 && $style->active == 1 && $style->is_deleted == 0)
	{
		$pd = new product();
		$grid = new product_grid();
		$view = TRUE;  //--- view stock
		$sc = $grid->getOrderGrid($id_style, $view, $id_branch);
		$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
		$sc .= ' | '.$tableWidth;
		$sc .= ' | ' . $style->getCode($id_style);
		$sc .= ' | ' . $id_style;
	}

	echo $sc;
}


//----- Echo product style list in tab
if( isset( $_GET['getProductsInOrderTab'] ) )
{
	include 'order/product_tab.php';
}




//----- Echo product style list in tab
if( isset( $_GET['getProductsInViewTab'] ) )
{
	include 'order/stock_tab.php';
}



//----- Echo product style list in tab Use in sale order
if( isset( $_GET['getSaleProductsInOrderTab'] ) )
{
	include 'order/sale_product_tab.php';
}





//----- Echo Order detail list
if( isset( $_GET['getDetailTable'] ) )
{
	include 'order/detail_table.php';
}





if( isset( $_GET['getCustomer'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$cs = new customer();
	$qs = $cs->search($_REQUEST['term'], "id, code, name");
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->code .' | '. $rs->name .' | '. $rs->id;
	}
	echo json_encode($sc);
}










if( isset( $_GET['getCustomerOnline'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$cs = new order();
	$qs = $cs->searchOnlineCode($_REQUEST['term'] );
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->online_code;
	}
	echo json_encode($sc);
}



if( isset( $_GET['getSaleCustomer'] ) && isset( $_REQUEST['term'] ) )
{
	$id_sale = $_GET['id_sale'];
	$sc = array();
	$cs = new customer();
	$qs = $cs->search_sale_customer($_REQUEST['term'], $id_sale, 50 );
	if(dbNumRows($qs) > 0)
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc[] = $rs->name.' ['.$rs->province.']' .' | '. $rs->id;
		}
	}
	else
	{
		$sc[] = 'ไม่พบข้อมูล';
	}

	echo json_encode($sc);
}



//---	autocomplete for search box in order page
if( isset( $_GET['searchProducts'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$qr  = "SELECT code FROM tbl_product_style WHERE code LIKE '%".$_REQUEST['term']."%' ";
	$qr .= "AND active = 1 AND can_sell = 1 AND is_deleted = 0 ORDER BY code ASC";
	$qs = dbQuery($qr);
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->code;
	}
	echo json_encode($sc);
}




//---	autocomplete for search box in sale page
if( isset( $_GET['searchSaleProducts'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$qr  = "SELECT code FROM tbl_product_style ";
	$qr .= "WHERE (code LIKE '%".$_REQUEST['term']."%' OR name LIKE '%".$_REQUEST['term']."%') ";
	$qr .= "AND active = 1 AND can_sell = 1 AND is_deleted = 0 AND show_in_sale = 1 ";
	$qr .= "ORDER BY code ASC LIMIT 20";

	$qs = dbQuery($qr);
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->code;
	}
	echo json_encode($sc);
}



//------------------------- แจ้งโอนเงินพร้อมแนบไฟล์หลักฐาน  ------------//
if( isset( $_GET['confirmPayment'] ) )
{
	include 'order/confirm_payment.php';
}



if( isset( $_GET['updateShippingFee'] ) )
{
	$sc 		= 'fail';
	$amount 	= $_POST['fee'];
	$id			= $_POST['id_order'];
	$order	= new order();
	$arr 		= array("shipping_fee" => $amount);
	if( $order->update($id, $arr) )
	{
		$sc = 'success';
	}
	echo $sc;
}



if(isset($_GET['updateDeliveryNo']))
{
	$sc = 'fail';
	$id = $_POST['id_order'];
	$deliveryNo = $_POST['deliveryNo'];
	$order = new order();
	$arr = array('shipping_code' => $deliveryNo);
	if($order->update($id, $arr))
	{
		$sc = 'success';
	}
	echo $sc;
}






if( isset( $_GET['updateServiceFee'] ) )
{
	$sc = 'fail';
	$amount	= $_POST['fee'];
	$id			= $_POST['id_order'];
	$order	= new order();
	$arr		= array("service_fee" => $amount);
	if( $order->update($id, $arr) )
	{
		$sc = 'success';
	}
	echo $sc;
}




if( isset( $_GET['getSummary'] ) )
{
	include 'order/order_summary.php';
}




if( isset( $_GET['getPayAmount'] ) )
{
	$order = new order($_GET['id_order']);
	$amount = $order->getTotalAmount($order->id);
	$sc = ( $amount + $order->shipping_fee + $order->service_fee ) - $order->bDiscAmount;
	echo $sc;
}





//------------------ return address Table  ---------------//
if( isset( $_GET['getAddressTable'] ) )
{
	$sc 			= 'fail';
	$id_order	= $_POST['id_order'];
	$order 		= new order();
	$code 		= $order->getOnlineCode($id_order);
	if( $code )
	{
		$ds = array();
		$ad = new online_address();
		$qs = $ad->getAddressByCode($code);
		if( dbNumRows($qs) > 0 )
		{
			while( $data = dbFetchArray($qs) )
			{
				$arr	= array(
							'id'			=> $data['id'],
							'name'		=> $data['first_name'].' '.$data['last_name'],
							'address'	=> $data['address1'].' '.$data['address2'].' '.$data['province'].' '.$data['postcode'],
							'phone'	=> $data['phone'],
							'email'		=> $data['email'],
							'alias'		=> $data['alias'],
							'default'	=> $data['is_default'] == 1 ? 1 : ''
							);
				array_push($ds, $arr);
			}
			$sc = json_encode($ds);
		}
	}
	echo $sc;
}






if( isset( $_GET['getAddressDetail']) )
{
	$sc = 'fail';
	$id = $_POST['id_address'];
	$ad = new online_address();
	$qs = $ad->getAddress($id);
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchObject($qs);
		$ds = array(
					"id"		=> $rs->id,
					"customer_code"	=> $rs->customer_code,
					"first_name"	=> $rs->first_name,
					"last_name"	=> $rs->last_name,
					"address1"	=> $rs->address1,
					"address2"	=> $rs->address2,
					"district" => $rs->district,
					"province"	=> $rs->province,
					"postcode"	=> $rs->postcode,
					"phone"		=> $rs->phone,
					"email"			=> $rs->email,
					"alias"			=> $rs->alias,
					"is_default"	=> $rs->is_default
				);

		$sc = json_encode($ds);
	}
	echo $sc;
}





if( isset( $_GET['saveAddress'] ) )
{
	$id = $_POST['id_address'];
	$ad = new online_address();
	if( $id != "" )
	{
		$arr = array(
					"first_name"	=> $_POST['first_name'],
					"last_name"	=> $_POST['last_name'],
					"address1"	=> $_POST['address1'],
					"address2"	=> $_POST['address2'],
					"district"	=> $_POST['district'],
					"province"	=> $_POST['province'],
					"postcode"	=> $_POST['postcode'],
					"phone"		=> $_POST['phone'],
					"email"			=> $_POST['email'],
					"alias"			=> $_POST['alias']
				);
		$rs = $ad->update($id, $arr);
	}
	else
	{
		$arr = array(
					"customer_code"	=> $_POST['online_code'],
					"first_name"	=> $_POST['first_name'],
					"last_name"	=> $_POST['last_name'],
					"address1"	=> $_POST['address1'],
					"address2"	=> $_POST['address2'],
					"district"  => $_POST['district'],
					"province"	=> $_POST['province'],
					"postcode"	=> $_POST['postcode'],
					"phone"		=> $_POST['phone'],
					"email"			=> $_POST['email'],
					"alias"			=> $_POST['alias']
				);
		$rs = $ad->add($arr);
	}
	echo $rs === TRUE ? 'success' : 'fail';
}



//--------------------- Delete Online Address  ---------------//
if( isset( $_GET['removeAddress'] ) )
{
	$id = $_POST['id_address'];
	$ad	= new online_address();
	echo $ad->delete($id) === TRUE ? 'success' : 'fail';
}




if( isset( $_GET['setDefaultAddress'] ) )
{
	$id = $_POST['id_address'];
	$add = new online_address();
	$sc = $add->setDefault($id);
	echo $sc === TRUE ? 'success' : 'fail';
}






//--- เปลี่ยนสถานะของออเดอร์โดยคน
if( isset( $_GET['stateChange']))
{
	include 'order/order_state_change.php';
}


//---- เปลี่ยนสถานะเป็นจัดส่งแล้ว
if( isset($_GET['setDelivered']))
{
	$sc = TRUE;
	$id_order = $_POST['id_order'];
	$state = 10; //--- จัดส่งแล้ว
	$order = new order($id_order);

	if($order->state == 8 OR $order->state == 9)
	{
		if( $order->stateChange($order->id, $state) !== TRUE)
		{
			$sc = FALSE;
			$message = 'เปลี่ยนสถานะไม่สำเร็จ';
		}
	}

	echo $sc === TRUE ? 'success' : $message;
}


//---- เปลี่ยนสถานะกลับมาเป็นรอจัดส่งเหมือนเดิม
if(isset($_GET['setNotDelivery']))
{
	$sc = TRUE;
	$id_order = $_POST['id_order'];
	$state = 8; //---- รอจัดส่ง
	$order  = new order($id_order);

	if($order->state == 10 OR $order->state == 9)
	{
		if( $order->stateChange($order->id, $state) !== TRUE)
		{
			$sc = FALSE;
			$message = 'เปลี่ยนสถานะไม่สำเร็จ';
		}
	}

	echo $sc === TRUE ? 'success' : $message;
}


if(isset($_GET['setExpired']))
{
	include 'order/set_order_expired.php';
}


if(isset($_GET['setNotExpire']))
{
	$id = $_POST['id_order'];
	$option = $_POST['option'];
	$sc = TRUE;

	$order = new order();
	$arr = array(
		'never_expire' => $option
	);

	if($order->update($id, $arr) !== TRUE)
	{
		$sc = FALSE;
		$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['searchRefCode']) && isset($_REQUEST['term']))
{
	$txt = trim($_REQUEST['term']);
	$field = 'ref_code';
	$limit = 50; //---- limit result

	$sc = array();

	$qr  = "SELECT ref_code
						FROM  tbl_order
					WHERE
						ref_code LIKE '%".$txt."%'
					ORDER BY ref_code ASC
					LIMIT 50";

	$qs = dbQuery($qr);

	if(dbNumRows($qs) > 0)
	{
	  while($rs = dbFetchObject($qs))
	  {
	    $sc[] = $rs->ref_code;
	  }
	}
	else
	{
	  $sc[] = 'ไม่พบข้อมูล';
	}

	echo json_encode($sc);
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sOrderCode');
	deleteCookie('sOrderCus');
	deleteCookie('sOrderEmp');
	deleteCookie('sOrderPaymentMethod');
	deleteCookie('sOrderChannels');
	deleteCookie('sOrderZone');
	deleteCookie('sOrderUser');
	deleteCookie('sBranch');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	deleteCookie('state_1');
	deleteCookie('state_2');
	deleteCookie('state_3');
	deleteCookie('state_4');
	deleteCookie('state_5');
	deleteCookie('state_6');
	deleteCookie('state_7');
	deleteCookie('state_8');
	deleteCookie('state_9');
	deleteCookie('state_10');
	deleteCookie('state_11');
	deleteCookie('notSave');
	deleteCookie('onlyMe');
	deleteCookie('isExpire');
	deleteCookie('sRefCode');
	deleteCookie('sDeliveryCode');
	deleteCookie('selectState');
	deleteCookie('startTime');
	deleteCookie('endTime');
	echo "done";
}


?>
