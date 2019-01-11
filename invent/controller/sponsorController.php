<?php

require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/sponsor_helper.php';
include '../function/date_helper.php';


//---	เพิ่มรายชื่อผู้รับสปอนเซอร์ใหม่พร้อมเพิ่มงบประมาณในตัว
//---	แต่งบประมาณยังไม่ถูกอนุมัตินะ
if( isset( $_GET['addNewSponsor']))
{
	include 'sponsor/sponsor_add.php';
}






//---	ตรวจสอบปีซ้ำกันมั้ย
if( isset($_GET['checkDuplicatedYear']))
{
	$id_budget = isset( $_GET['id_budget']) ? $_GET['id_budget'] : FALSE;
	$id_sponsor = $_GET['id_sponsor'];
	$year = $_GET['year'];
	$bd = new sponsor_budget();
	$rs = $bd->isExistsYear($id_sponsor, $year, $id_budget);
	echo $rs === FALSE ? 'ok' : 'Duplicated';
}







//---	เพิ่มงบประมาณใหม่
if( isset($_GET['addNewBudget']))
{
	$bd = new sponsor_budget();

	//---	ตรวจสอบก่อนว่าปีซ้ำกันมั้ย ถ้าไม่ซ้ำเพิ่มใหม่ได้เลย
	//---	(ไม่สามารถซ้ำกันได้เพราะ database ออกแบบให้เป็น unique)
	if( $bd->isExistsYear($_POST['id_sponsor'], $_POST['year']) === FALSE )
	{
		$arr = array(
						'reference' => trim($_POST['reference']),
						'id_sponsor' => $_POST['id_sponsor'],
						'id_customer' => $_POST['id_customer'],
						'start' => dbDate($_POST['fromDate']),
						'end'	=> dbDate($_POST['toDate']),
						'year' => dbYear($_POST['year']),
						'budget'	=> $_POST['budget'],
						'balance' => $_POST['budget'],
						'remark'	=> $_POST['remark']
					);
		$rs = $bd->add($arr);

		echo $rs === FALSE ? 'เพิ่มงบประมาณไม่สำเร็จ' : 'success';
	}
	else
	{
		echo 'ปีงบประมาณซ้ำ กรุณาเลือกใหม่';
	}

}





if( isset($_GET['updateBudget']))
{
	include 'sponsor/sponsor_budget_update.php';
}







//---	approve budget
if( isset( $_GET['approveBudget']))
{
	$bd = new sponsor_budget();
	$id = $_POST['id_budget'];
	$arr = array(
					'active' => $_POST['active'],
					'approver' => $_POST['id_emp'],
					'approve_key' => $_POST['approve_key']
				);


	$rs = $bd->update($id, $arr);

	echo $rs === TRUE ? 'success' : 'ไม่สำเร็จ';
}





//---	Delete sponsor
if( isset($_GET['deleteSponsor']))
{
	include 'sponsor/sponsor_delete.php';
}





//---	set using budget
if( isset( $_GET['setActiveBudget']))
{
	$id_sponsor = $_POST['id_sponsor'];
	$id_budget 	= $_POST['id_budget'];

	$sp = new sponsor();
	$rs = $sp->update($id_sponsor, array('id_budget' => $id_budget));

	echo $rs === TRUE ? 'success' : 'เปลี่ยนงบประมาณไม่สำเร็จ';
}







//---	รายละเอียดงบประมาณ (เปลียนปี)
if( isset( $_GET['getBudgetData']))
{
	$id = $_GET['id_budget'];
	$bd = new sponsor_budget($id);
	$arr = array(
					'id'	=> $bd->id,
					'reference' => $bd->reference,
					'budget'	=> $bd->budget,
					'start'	=> thaiDate($bd->start),
					'end'	=> thaiDate($bd->end),
					'year'	=> $bd->year,
					'remark' => $bd->remark
				);
	echo json_encode($arr);
}








//---	check transection before delete
if( isset($_GET['checkTransection']))
{
	$id_customer = $_GET['id_customer'];
	$role = 4; //---- สปอนเซอร์
	$order = new order();
	$rs = $order->isExitsTransection($id_customer, $role);

	echo $rs === TRUE ? 'transection_exists' : 'no_transection';
}







//---	ค้นหารายชื่อลูกค้าเพื่อเพิ่มผู้รับ
if( isset( $_GET['getCustomer']) && $_REQUEST['term'])
{
	$sc = array();
	$cs = new customer();
	$qs = $cs->search(trim($_REQUEST['term']), 'id, code, name');

	while( $rs = dbFetchObject($qs))
	{
		$sc[] = $rs->code .' | ' . $rs->name . ' | ' . $rs->id;
	}

	echo json_encode($sc);
}




//---	ค้นรายชื่อผู้รับสปอนเซอร์ ใช้ในการสั่งออเดอร์
if( isset($_GET['getSponsorCustomer']))
{
	$date = dbDate($_GET['date']);
	$sc = array();
	$cs = new sponsor();
	$qs = $cs->getSponsorAndBudgetBalance(trim($_REQUEST['term']), $date);
	if(dbNumRows($qs) > 0)
	{
		while( $rs = dbFetchObject($qs))
		{
			$sc[] = $rs->code.' | '. $rs->name. ' | '. $rs->id_customer . ' | '. $rs->id_budget;
		}

	}
	else
	{
		$sc[] = 'ไม่พบข้อมูล';
	}

	echo json_encode($sc);

}


//---	ค้นหาพนักงาน
if( isset( $_GET['getEmployee']))
{
	$sc = array();
	$emp = new employee();
	$qs = $emp->search('id_employee, first_name, last_name', trim($_REQUEST['term']));
	if( dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[] = $rs->first_name.' '.$rs->last_name. ' | ' .$rs->id_employee;
		}

	}
	else
	{
		$sc[] = 'ไม่พบข้อมูล';
	}

	echo json_encode($sc);
}



if( isset( $_GET['getBudgetBalance']))
{
	$id_customer = $_GET['id_customer'];
	$sp = new sponsor();
	echo number($sp->getBudgetBalanceByCustomer($id_customer), 2);
}



//---	ตรวจสอบรายชื่อซ้ำ
if( isset( $_GET['isExistsSponsor']))
{
	$id_customer = $_POST['id_customer'];
	$id_sponsor = isset($_POST['id_sponsor']) ? $_POST['id_sponsor'] : FALSE;
	$sponsor = new sponsor();

	$id = $sponsor->isSponsorExists($id_customer, $id_sponsor);

	echo $id == FALSE ? 'ok' : $id;
}



//----เปลี่ยนชือผู้ถืองบประมาณ
if(isset($_GET['changeSponsorCustomer']))
{
	$sc = TRUE;
	$id_sponsor = $_POST['id_sponsor'];
	$id_customer = $_POST['id_customer'];
	$customerName = $_POST['customerName'];
	$isUpdate = $_POST['isUpdate'] == 1 ? TRUE : FALSE;

	$sp = new sponsor();
	$bd = new sponsor_budget();


	if($isUpdate === TRUE)
	{
		$cs = new customer($id_customer);
		$cg = new customer_group($cs->id_group);
		$ck = new customer_kind($cs->id_kind);
		$ca = new customer_area($cs->id_area);
		$ct = new customer_type($cs->id_type);
		$cc = new customer_class($cs->id_class);
		$sale = new sale($cs->id_sale);
	}


	$arr = array(
		'id_customer' => $id_customer,
		'name' => $customerName
	);

	startTransection();

	//---- update sponsor
	if($sp->update($id_sponsor, $arr) !== TRUE)
	{
		$sc = FALSE;
		$message = 'เปลี่ยนผู้ถืองบประมาณไม่สำเร็จ';
	}

	//--- updte id_customer in budget
	if($sc === TRUE)
	{
		$qs = $bd->getBudgetList($id_sponsor);
		if(dbNumRows($qs) > 0)
		{
			while($rs = dbFetchObject($qs))
			{
				$id = $rs->id;
				$arr = array(
					'id_customer' => $id_customer
				);

				if($bd->update($id, $arr) !== TRUE)
				{
					$sc = FALSE;
					$message = 'เปลี่ยนแปลงงบประมาณไม่สำเร็จ';
				}
				else
				{
					if($isUpdate === TRUE)
					{
						$qo = dbQuery("UPDATE tbl_order SET id_customer = '".$id_customer."' WHERE role = 4 AND id_budget = '".$id."'");
						if($qo !== TRUE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงรายการสั่งซื้อย้อนหลังไม่สำเร็จ';
						}
						else
						{
							$qr  = "UPDATE tbl_order_sold ";
							$qr .= "SET id_customer = '".$id_customer."' " ;
							$qr .= ", customer_code = '".$cs->code."' ";
							$qr .= ", customer_name = '".$cs->name."' ";
							$qr .= ", customer_group = '".$cg->name."' ";
							$qr .= ", customer_type = '".$ct->name."' ";
							$qr .= ", customer_kind = '".$ck->name."' ";
							$qr .= ", customer_class = '".$cc->name."' ";
							$qr .= ", customer_area = '".$ca->name."' ";
							$qr .= ", province = '".$cs->province."' ";
							$qr .= ", id_sale = '".$sale->id."' ";
							$qr .= ", sale_code = '".$sale->code."' ";
							$qr .= ", sale_name = '".$sale->name."' ";

							$qr .= "WHERE id_role = 4 AND id_budget = '".$id."'";

							$qa = dbQuery($qr);

							if($qa !== TRUE)
							{
								$sc = FALSE;
								$message = 'ปรับปรุงรายการขายย้อนหลังไม่สำเร็จ';
							}
						}
					}
				}
			}
		}
	}


	if($sc === TRUE)
	{
		commitTransection();
	}
	else
	{
		dbRollback();
	}

	endTransection();

	echo $sc === TRUE ? 'success' : $message;
}


//----- เปิดสิทธิ์การอนุมัติงบประมาณ
if(isset($_GET['setActiveApprover']))
{
	$sc = TRUE;
	$id = $_POST['id'];
	$apv = new approver();
	$arr = array('active' => 1);

	if($apv->update($id, $arr) === FALSE)
	{
		$sc = FALSE;
		$message = 'เปิดใช้งานไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



//---- ปิดสิทธิ์การอนุมัติงบประมาณ
if(isset($_GET['disActiveApprover']))
{
	$sc = TRUE;
	$id = $_POST['id'];
	$apv = new approver();
	$arr = array('active' => 0);

	if($apv->update($id, $arr) === FALSE)
	{
		$sc = FALSE;
		$message = 'ปิดใช้งานไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}



//----- ตรวจสอบว่ามีรายชื่ออยู่แล้วหรือไม่
if(isset($_GET['isExistsApprover']))
{
	$id_emp = $_GET['id_employee'];
	$doc_type = 'SP-BUDGET';
	$ap = new approver();
	$sc = $ap->isExistsApprover($doc_type, $id_emp);

	echo $sc === TRUE ? 'exists' : 'ok';
}


//----- เพิ่มผู้อนุมัติงบประมาณ
if(isset($_GET['addApprover']))
{
	include 'sponsor/sponsor_approver_add.php';
}


//----- ลบผู้อนุมัติงบประมาณ
if(isset($_GET['removeApprover']))
{
	include 'sponsor/sponsor_approver_delete.php';
}


if( isset($_GET['clearFilter']))
{
	deleteCookie('sSponsorName');
	deleteCookie('sSponsorYear');
	deleteCookie('sName');
	deleteCookie('isActive');
	echo 'done';
}
?>
