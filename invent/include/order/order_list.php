<?php

//--- function getFilter in function/tools.php
$sCode 	= getFilter('sCode', 'sOrderCode', '');	//---	reference
$sCus	 	= getFilter('sCus', 'sOrderCus', '' );	//---	customer
$sEmp	= getFilter('sEmp', 'sOrderEmp', '' );	//---	Employee
$sPaymet	= getFilter('sPayment', 'sOrderPaymentMethod', '' ); //--- Payment Method
$sChannels	= getFilter('sChannels', 'sOrderChannels', '' ); 	//---	Sales Channels
$fromDate	= getFilter('fromDate', 'fromDate', '' );
$toDate	= getFilter('toDate', 'toDate', '' );
$isOnline = $_GET['content'] == 'order_online' ? TRUE : FALSE;
$sRefCode = getFilter('sRefCode', 'sRefCode', '');
$sDeliveryCode = getFilter('sDeliveryCode', 'sDeliveryCode', '');
$selectState = getFilter('selectState', 'selectState', '');
$startTime = getFilter('startTime', 'startTime', '');
$endTime = getFilter('endTime', 'endTime','');
$sBranch = getFilter('sBranch', 'sBranch', '');

$state_1 = getFilter('state_1', 'state_1', 0);
$state_2 = getFilter('state_2', 'state_2', 0);
$state_3 = getFilter('state_3', 'state_3', 0);
$state_4 = getFilter('state_4', 'state_4', 0);
$state_5 = getFilter('state_5', 'state_5', 0);
$state_6 = getFilter('state_6', 'state_6', 0);
$state_7 = getFilter('state_7', 'state_7', 0);
$state_8 = getFilter('state_8', 'state_8', 0);
$state_9 = getFilter('state_9', 'state_9', 0);
$state_10 = getFilter('state_10', 'state_10', 0);
$state_11 = getFilter('state_11', 'state_11', 0);
$notSave = getFilter('notSave', 'notSave', 0);
$onlyMe = getFilter('onlyMe', 'onlyMe', 0);
$isExpire = getFilter('isExpire', 'isExpire', 0);

$btn_1 = $state_1 == 0 ? '' : 'btn-info';
$btn_2 = $state_2 == 0 ? '' : 'btn-info';
$btn_3 = $state_3 == 0 ? '' : 'btn-info';
$btn_4 = $state_4 == 0 ? '' : 'btn-info';
$btn_5 = $state_5 == 0 ? '' : 'btn-info';
$btn_6 = $state_6 == 0 ? '' : 'btn-info';
$btn_7 = $state_7 == 0 ? '' : 'btn-info';
$btn_8 = $state_8 == 0 ? '' : 'btn-info';
$btn_9 = $state_9 == 0 ? '' : 'btn-info';
$btn_10 = $state_10 == 0 ? '' : 'btn-info';
$btn_11 = $state_11 == 0 ? '' : 'btn-info';
$btn_notSave = $notSave == 0 ? '' : 'btn-info';
$btn_onlyMe = $onlyMe == 0 ? '' : 'btn-info';
$btn_expire = $isExpire == 0 ? '' : 'btn-info';

$state = '';

$a = 1;
for($i =1; $i <= 11; $i++)
{
	if(${'state_'.$i} == 1)
	{
		$state .= $a == 1 ? $i : ', '.$i;
		createCookie('state_'.$i, 1);
		$a++;
	}
	else
	{
		deleteCookie('state_'.$i);
	}
}

?>
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
				<?php if(getConfig('ALLOW_IMPORT_ORDER') == 1) : ?>
					<button type="button" class="btn btn-sm btn-success" onclick="getUploadFile()">นำเข้าออเดอร์</button>
				<?php endif; ?>
        	<button type="button" class="btn btn-sm btn-info" onclick="goViewStock()"><i class="fa fa-search"></i> ดูสต็อกคงเหลือ</button>
        	<?php if( $add ) : ?>
						<?php if( $isOnline === TRUE ) : ?>
            <button type="button" class="btn btn-sm btn-primary" onclick="goAddOnline()"><i class="fa fa-plus"></i> เพิ่มใหม่ [ออนไลน์] </button>
						<?php else : ?>
						<button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่ [ปกติ] </button>
						<?php endif; ?>
          <?php endif; ?>
        </p>
    </div>
</div>
<hr class="margin-bottom-15" />
<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
    	<label>ลูกค้า</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
    </div>

    <div class="col-sm-2 padding-5">
    	<label>พนักงาน</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sEmp" id="sEmp" value="<?php echo $sEmp; ?>" />
    </div>

		<div class="col-sm-2 padding-5">
			<label class="display-block">เลขที่อ้างอิง</label>
			<input type="text" class="form-control input-sm text-center search-box" name="sRefCode" id="sRefCode" value="<?php echo $sRefCode; ?>" />
		</div>

		<div class="col-sm-2 padding-5">
			<label class="display-block">เลขที่จัดส่ง</label>
			<input type="text" class="form-control input-sm text-center search-box" name="sDeliveryCode" id="sDeliveryCode" value="<?php echo $sDeliveryCode; ?>" />
		</div>

		<div class="col-sm-1 padding-5">
      <label class="display-block not-show">Apply</label>
      <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>

		<div class="col-sm-1 padding-5 last">
    	<label class="display-block not-show">Apply</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>

		<div class="divider-hidden margin-top-5 margin-bottom-5"></div>

		<div class="col-sm-2 padding-5 first">
			<label class="display-block">ช่องทางขาย</label>
			<select class="form-control input-sm" name= "sChannels" id="sChannels" onchange="getSearch()">
				<option value="">ทั้งหมด</option>
				<?php echo selectOfflineChannels($sChannels); ?>
			</select>
		</div>

		<div class="col-sm-2 padding-5">
			<label class="display-block">การชำระเงิน</label>
			<select class="form-control input-sm" name="sPayment" id="sPayment" onchange="getSearch()">
				<option value="">ทั้งหมด</option>
				<?php echo selectPaymentMethodList($sPaymet); ?>
			</select>
		</div>

		<div class="col-sm-2 padding-5">
    	<label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm text-center input-discount" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" placeholder="เริ่มต้น" />
        <input type="text" class="form-control input-sm text-center input-unit" name="toDate" id="toDate" value="<?php echo $toDate; ?>" placeholder="สิ้นสุด" />
    </div>

		<div class="col-sm-2 padding-5">
			<label class="display-block">สถานะ</label>
			<select class="form-control input-sm" name="selectState">
				<option value="">เลือกสถานะ</option>
				<option value="3" <?php echo isSelected($selectState, 3); ?>>รอจัดสินค้า</option>
				<option value="4" <?php echo isSelected($selectState, 4); ?>>กำลังจัดสินค้า</option>
				<option value="5" <?php echo isSelected($selectState, 5); ?>>รอตรวจ</option>
				<option value="6" <?php echo isSelected($selectState, 6); ?>>กำลังตรวจ</option>
				<option value="7" <?php echo isSelected($selectState, 7); ?>>รอเปิดบิล</option>
			</select>
		</div>

		<div class="col-sm-1 padding-5">
			<label class="display-block">เริ่มต้น</label>
			<select class="form-control input-sm" name="startTime">
				<?php echo selectTime($startTime); ?>
			</select>
		</div>

		<div class="col-sm-1 padding-5">
			<label class="display-block">สิ้นสุด</label>
			<select class="form-control input-sm" name="endTime">
				<?php echo selectTime($endTime); ?>
			</select>
		</div>
		<div class="col-sm-2 padding-5 last">
			<label>สาขา</label>
			<select class="form-control input-sm search-select" id="sBranch" name="sBranch">
				<option value="">ทั้งหมด</option>
				<?php echo selectBranch($sBranch); ?>
			</select>
		</div>

		<div class="divider-hidden margin-top-5 margin-bottom-5"></div>

		<div class="col-sm-1 padding-5 first">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_1; ?>" id="btn-1" onclick="toggleState('btn-1', <?php echo $state_1; ?> )">รอชำระเงิน</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_2; ?>" id="btn-2" onclick="toggleState('btn-2', <?php echo $state_2; ?> )">แจ้งชำระเงิน</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_3; ?>" id="btn-3" onclick="toggleState('btn-3', <?php echo $state_3; ?> )">รอจัด</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_4; ?>" id="btn-4" onclick="toggleState('btn-4', <?php echo $state_4; ?> )">กำลังจัด</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_5; ?>" id="btn-5" onclick="toggleState('btn-5', <?php echo $state_5; ?> )">รอตรวจ</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_6; ?>" id="btn-6" onclick="toggleState('btn-6', <?php echo $state_6; ?> )">กำลังตรวจ</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_7; ?>" id="btn-7" onclick="toggleState('btn-7', <?php echo $state_7; ?> )">รอเปิดบิล</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_8; ?>" id="btn-8" onclick="toggleState('btn-8', <?php echo $state_8; ?> )">เปิดบิลแล้ว</button>
		</div>
		<!--
		<div class="col-sm-1 padding-5">

			<button type="button" class="btn btn-sm btn-block <?php echo $btn_9; ?>" id="btn-9" onclick="toggleState('btn-9', <?php echo $state_9; ?> )">กำลังจัดส่ง</button>
		</div>

			-->
		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_10; ?>" id="btn-10" onclick="toggleState('btn-10', <?php echo $state_10; ?> )">จัดส่งแล้ว</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_11; ?>" id="btn-11" onclick="toggleState('btn-11', <?php echo $state_11; ?> )">ยกเลิก</button>
		</div>

		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_notSave; ?>" id="btn-notSave" onclick="toggleState('btn-notSave', <?php echo $notSave; ?> )">ยังไม่บันทึก</button>
		</div>

		<div class="col-sm-1 padding-5 last">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_onlyMe; ?>" id="btn-onlyMe" onclick="toggleState('btn-onlyMe', <?php echo $onlyMe; ?> )">เฉพาะฉัน</button>
		</div>

		<!--
		<div class="col-sm-1 padding-5 last">
			<button type="button" class="btn btn-sm btn-block <?php echo $btn_expire; ?>" id="btn-expire" onclick="toggleState('btn-expire', <?php echo $isExpire; ?> )">หมดอายุ</button>
		</div>
	-->



		<input type="hidden" name="state_1" id="state_1" value="<?php echo $state_1; ?>" />
		<input type="hidden" name="state_2" id="state_2" value="<?php echo $state_2; ?>" />
		<input type="hidden" name="state_3" id="state_3" value="<?php echo $state_3; ?>" />
		<input type="hidden" name="state_4" id="state_4" value="<?php echo $state_4; ?>" />
		<input type="hidden" name="state_5" id="state_5" value="<?php echo $state_5; ?>" />
		<input type="hidden" name="state_6" id="state_6" value="<?php echo $state_6; ?>" />
		<input type="hidden" name="state_7" id="state_7" value="<?php echo $state_7; ?>" />
		<input type="hidden" name="state_8" id="state_8" value="<?php echo $state_8; ?>" />
		<input type="hidden" name="state_9" id="state_9" value="<?php echo $state_9; ?>" />
		<input type="hidden" name="state_10" id="state_10" value="<?php echo $state_10; ?>" />
		<input type="hidden" name="state_11" id="state_11" value="<?php echo $state_11; ?>" />
		<input type="hidden" name="notSave" id="state_notSave" value="<?php echo $notSave; ?>" />
		<input type="hidden" name="onlyMe" id="state_onlyMe" value="<?php echo $onlyMe; ?>" />
		<input type="hidden" name="isExpire" id="state_expire" value="<?php echo $isExpire; ?>" />
		<input type="hidden" name="id_branch" id="id_branch" value="<?php echo $sBranch; ?>" />

		<?php if($isOnline == TRUE) : ?>
		<input type="hidden" name="isOnline" id="isOnline" value="1" />
		<?php endif; ?>

</div>
</form>

<hr class="margin-top-10 margin-bottom-10"/>

<?php
	$where = "WHERE role = 1 ";

	if( $isOnline === TRUE )
	{
		$where .= "AND isOnline = 1 ";
	}

	//--- Reference
	if( $sCode != "" )
	{
		createCookie('sOrderCode', $sCode);
		$where .= "AND reference LIKE '%".$sCode."%' ";
	}

	//--- Customer
	if( $sCus != "" )
	{
		createCookie('sOrderCus', $sCus);
		$where .= "AND id_customer IN(".getCustomerIn($sCus).") "; //--- function/customer_helper.php
	}

	//--- Employee
	if( $sEmp != "" )
	{
		createCookie('sOrderEmp', $sEmp);
		$where .= "AND id_employee IN(".getEmployeeIn($sEmp).") "; //--- function/employee_helper.php
	}


	//--- สาขา
	if($sBranch != '')
	{
		createCookie('sBranch', $sBranch);
		$where .= "AND id_branch = '".$sBranch."' ";
	}


	if( $fromDate != "" && $toDate != "" )
	{
		createCookie('fromDate', $fromDate);
		createCookie('toDate', $toDate);
		if($selectState != '')
		{
			createCookie('selectState', $selectState);
			createCookie('startTime', $startTime);
			createCookie('endTime', $endTime);
			$where .= "AND id_order IN(".getOrderStateChangeIn($selectState, fromDate($fromDate), toDate($toDate), $startTime, $endTime).") ";
		}
		else
		{
			$where .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '". toDate($toDate)."' ";
		}

	}





	if($sPaymet != '')
	{
		createCookie('sOrderPaymentMethod', $sPaymet);
		$where .= "AND id_payment = '".$sPaymet."' ";
	}





	if($sChannels != '')
	{
		createCookie('sOrderChannels', $sChannels);
		$where .= "AND id_channels = '".$sChannels."' ";
	}





	if($sRefCode != '')
	{
		createCookie('sRefCode', $sRefCode);
		$where .= "AND ref_code LIKE '%".$sRefCode."%' ";
	}





	if($sDeliveryCode != '')
	{
		createCookie('sDeliveryCode', $sDeliveryCode);
		$where .= "AND shipping_code LIKE '%".$sDeliveryCode."%' ";
	}





	if($notSave == 1)
	{
		createCookie('notSave', $notSave);
		$where .= "AND status = 0 ";
	}
	else
	{
		if($state != '')
		{
			$where .= "AND state IN(".$state.") ";
		}

	}


	if($isExpire == 1)
	{
		createCookie('isExpire', 1);
		$where .= "AND isExpire = 1 ";
	}


	if($onlyMe == 1)
	{
		createCookie('onlyMe', 1);
		$where .= "AND id_user = '".getCookie('user_id')."' ";
	}

	$where .= "ORDER BY reference DESC";

	//echo $where;
	$table = 'tbl_order LEFT JOIN tbl_order_user ON tbl_order.id = tbl_order_user.id_order';
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page($table, $where, $get_rows);

	$qs = dbQuery("SELECT tbl_order.* FROM ".$table." " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>
<div class="row">
	<div class="col-sm-8 padding-5 first">
		<?php $paginator->display($get_rows, 'index.php?content=order'); ?>
	</div>
	<div class="col-sm-3 padding-5 margin-top-10">
		<input type="text" class="form-control input-sm text-center" id="pd-search-box" placeholder="ค้นหารหัสรุ่นสินค้า" />
	</div>
	<div class="col-sm-1 padding-5 last">
		<button type="button" class="btn btn-sm btn-block btn-primary margin-top-10" onclick="getStockGrid()">เช็คสต็อก</button>
	</div>

	<input type="hidden" id="id_style" />
</div>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-bordered">
        	<thead>
            	<tr class="font-size-10">
                	<th class="width-5 text-center">ลำดับ</th>
									<th class="width-8 text-center">วันที่</th>
                  <th class="text-center" style="width:17%;">เลขที่เอกสาร</th>
                  <th class="text-center">ลูกค้า</th>
                  <th class="width-8 text-center">จังหวัด</th>
                  <th class="width-8 text-center">ยอดเงิน</th>
                  <th class="width-8 text-center">ช่องทางขาย</th>
                  <th class="width-8 text-center">การชำระเงิน</th>
                  <th class="width-8 text-center">สถานะ</th>
                  <th class="width-5 text-center"></th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no 		= row_no();			?>
<?php	$cs 		= new customer(); ?>
<?php	$order 	= new order(); ?>
<?php	$ch 		= new channels(); ?>
<?php	$pm		  = new payment_method(); ?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>
<?php    $address = getOnlineAddress($rs->online_code); ?>
			<tr class="font-size-10" <?php echo stateColor($rs->state, $rs->status, $rs->isExpire); //--- order_help.php ?>>
            	<td class="middle text-cennter pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo $no; ?>
							</td>

							<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo thaiDate($rs->date_add); ?>
							</td>

							<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
									<?php echo $rs->reference; ?>
									<?php if($rs->ref_code != '') : ?>
											[ <?php echo $rs->ref_code; ?>]
									<?php endif; ?>
							</td>

              <td class="middle pointer" onclick="goEdit(<?php echo $rs->id; ?>)">

                  <?php echo customerName($rs->id_customer); ?>
                  <?php if( $rs->role == 1 && $pm->hasTerm($rs->id_payment) === FALSE && $rs->isPaid == 0 ) : ?>
                  	<span class="red font-size-14 padding-10" style="margin-left:10px; background-color:#FFF;">ยังไม่ชำระเงิน</span>
                  <?php endif; ?>
									<?php if( $rs->isOnline == 1 ) : ?>
                   		[  <?php echo $rs->online_code; ?>  ] &nbsp; &nbsp;
									<?php endif; ?>
              </td>

              <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo $cs->getProvince($rs->id_customer); ?>
							</td>

              <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo number_format($order->getTotalAmount($rs->id), 2); ?>
							</td>

              <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo $ch->getName($rs->id_channels); ?>
							</td>

              <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo $pm->getName($rs->id_payment); ?>
							</td>

              <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
								<?php echo stateName($rs->state, $rs->status, $rs->isExpire); ?>
							</td>

              <td class="middle text-center">
								<?php if($rs->isOnline == 1 && $address !== FALSE) : ?>
									<button type="button" class="btn btn-xs btn-primary" title="พิมพ์ใบปะหน้า" onclick="printOnlineAddress(<?php echo $address->id; ?>, <?php echo $rs->id; ?>)">
										<i class="fa fa-print"></i>
									</button>
								<?php else : ?>
                <button type="button"
                			class="btn btn-link btn-mini"
                            data-container="body"
                            data-toggle="popover"
														data-html="true"
                			data-placement="left"
                            data-trigger="focus"
                            data-content="

                            สาขา : <?php echo getBranchName($rs->id_branch); ?><br/>
														พนักงาน : <?php echo employee_name($rs->id_employee); ?><br/>
                            ปรับปรุงล่าสุด : <?php echo thaiDate($rs->date_upd); ?>
                        "><i class="fa fa-bars"></i></button>
									<?php endif; ?>
                </td>
            </tr>
<?php		$no++; ?>
<?php		endwhile; ?>
<?php else : ?>
			<tr>
            	<td colspan="10" class="text-center"><h4>ไม่พบรายการ</h4></td>
            </tr>
<?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
          <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
			 </div>
			 <div class="modal-body" id="modalBody"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			 </div>
		</div>
	</div>
</div>

<?php
if(getConfig('ALLOW_IMPORT_ORDER') == 1) :
	include('import_order.php');
endif;
?>

<script>
$(function () {
  $('[data-toggle="popover"]').popover();
});
</script>
<script src="script/order/order_list.js?token=<?php echo date('Ymd'); ?>"></script>
