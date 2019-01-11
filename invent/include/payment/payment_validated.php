<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-exclamation-triangle"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        </p>
    </div>
</div>

<hr />
<?php
//--- function getFilter in function/tools.php
$sCode 	= getFilter('sCode', 'sPaymentCode', '');	//---	reference
$sRefCode = getFilter('sRefCode', 'sRefCode', ''); //---	อ้างอิงออเดอร์ข้างนอก
$sCus	 	= getFilter('sCus', 'sPaymentCus', '' );	//---	customer
$sAcc	= getFilter('sAcc', 'sAcc', '' );	//---	เลขที่บัญชี

$fromDate	= getFilter('fromDate', 'fromDate', '' );
$toDate	= getFilter('toDate', 'toDate', '' );

?>

<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCode" name="sCode" value="<?php echo $sCode; ?>"  />
    </div>
		<div class="col-sm-2 padding-5">
			<label>เลขที่อ้างอิง</label>
			<input type="text" class="form-control input-sm text-center search-box" id="sRefCode" name="sRefCode" value="<?php echo $sRefCode; ?>" />
		</div>
    <div class="col-sm-2 padding-5">
    	<label>ลูกค้า</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCus" name="sCus" value="<?php echo $sCus; ?>"  />
    </div>
    <div class="col-sm-2 padding-5">
    	<label>เลขที่บัญชี</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sAcc" name="sAcc" value="<?php echo $sAcc; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
    	<label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm text-center input-discount" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" placeholder="เริ่มต้น" />
        <input type="text" class="form-control input-sm text-center input-unit" name="toDate" id="toDate" value="<?php echo $toDate; ?>" placeholder="สิ้นสุด" />
    </div>
    <div class="col-sm-1 padding-5">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
        </div>
        <div class="col-sm-1 padding-5 last">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
        </div>
</div>
</form>
<hr class="margin-top-15 margin-bottom-15" />
<?php
	$where = "WHERE p.valid = 1 ";
	//--- Reference
	if( $sCode != "" )
	{
		createCookie('sOrderCode', $sCode);
		$where .= "AND o.reference LIKE '%".$sCode."%' ";
	}

	if( $sRefCode != '')
	{
		createCookie('sRefCode', $sRefCode);
		$where .= "AND o.ref_code LIKE '%".$sRefCode."%' ";
	}

	//--- Customer
	if( $sCus != "" )
	{
		createCookie('sOrderCus', $sCus);
		$where .= "AND ( o.id_customer IN(".getCustomerIn($sCus).") OR o.online_code LIKE '%".$sCus."%') "; //--- function/customer_helper.php
	}

	//--- Employee
	if( $sAcc != "" )
	{
		createCookie('sAcc', $sAcc);
		$where .= "AND (a.bank_name LIKE '%".$sAcc."%' OR a.branch LIKE '%".$sAcc."%' OR a.acc_name LIKE '%".$sAcc."%' OR a.acc_no LIKE '%".$sAcc."%') ";
	}

	if( $fromDate != "" && $toDate != "" )
	{
		createCookie('fromDate', $fromDate);
		createCookie('toDate', $toDate);
		$where .= "AND p.paydate >= '".fromDate($fromDate)."' AND p.paydate <= '". toDate($toDate)."' ";
	}

	$where .= "ORDER BY p.paydate DESC";

	$qx = "SELECT p.*, o.reference, o.id_customer, o.online_code, o.isOnline, o.id_channels, o.id_employee, o.ref_code FROM ";
	$qr = "tbl_payment AS p ";
	$qr .= "LEFT JOIN tbl_order AS o ON p.id_order = o.id ";
	$qr .= "LEFT JOIN tbl_customer_online AS c ON o.online_code = c.code ";
	$qr .= "LEFT JOIN tbl_bank_account AS a ON p.id_account = a.id_account ";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page($qr, $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=payment_order&validated=Y');
	$qx = $qx . $qr . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page;
	$qs = dbQuery($qx);
?>

<div class="row">
	<div class="col-sm-12">
	<table class="table" style="border:solid 1px #ccc;">
		<thead>
    	<tr class="font-size-10">
        <th class="width-5 text-center">No.</th>
        <th class="width-15">Order No.</th>
				<th class="width-10 text-center">ช่องทาง</th>
        <th class="width-20">ลูกค้า</th>
				<th class="width-15">พนักงาน</th>
        <th class="width-8 text-center">ยอดชำระ</th>
        <th class="width-8 text-center">ยอดโอน</th>
        <th class="width-10 text-center">เลขที่บัญชี</th>
        <th class="text-right"></th>
      </tr>
    </thead>
        <tbody id="orderTable">
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no = row_no(); 	?>
<?php $channels = new channels(); ?>
<?php $emp = new employee(); ?>
<?php $customer_online = new customer_online(); ?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>
<?php		$bank = new bank_account($rs->id_account); ?>
			<tr class="font-size-12" id="<?php echo $rs->id_order; ?>">
        <td class="text-center"><?php echo $no; ?></td>
        <td>
					<?php echo $rs->reference; ?>
					<?php if($rs->ref_code !='') : ?>
						 [<?php echo $rs->ref_code; ?>]
					<?php endif; ?>
				</td>
				<td class="text-center"><?php echo $channels->getName($rs->id_channels); ?></td>
        <td><?php echo $rs->isOnline == 1 ? $customer_online->getName($rs->online_code) : customerName($rs->id_customer); ?></td>
				<td><?php echo $emp->getName($rs->id_employee); ?></td>
        <td class="text-center"><?php echo number_format($rs->order_amount, 2); ?></td>
        <td class="text-center"><?php echo number_format($rs->pay_amount, 2); ?></td>
        <td class="text-center"><?php echo $bank->acc_no; ?></td>
        <td class="text-right">
        	<button type="button" class="btn btn-xs btn-warning" onclick="viewValidDetail(<?php echo $rs->id_order; ?>)"><i class="fa fa-eye"></i></button>
          <button type="button" class="btn btn-xs btn-danger" onclick="removeValidPayment(<?php echo $rs->id_order; ?>, '<?php echo $rs->reference; ?>')">
						<i class="fa fa-trash"></i>
					</button>
        </td>
      </tr>
<?php	$no++;	?>
<?php 	endwhile; ?>

<?php else : ?>
		<tr><td colspan="10" class="text-center"><h4>ไม่พบรายการ</h4></td></tr>
<?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<script src="script/payment/payment_validated.js"></script>
