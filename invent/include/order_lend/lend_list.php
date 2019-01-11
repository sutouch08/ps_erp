<?php

//--- function getFilter in function/tools.php
$sCode 	= getFilter('sCode', 'sOrderCode', '');	//---	reference
$sCus	 	= getFilter('sCus', 'sOrderCus', '' );	//---	customer
$sEmp	  = getFilter('sEmp', 'sOrderEmp', '' );	//---	Employee
$fromDate	= getFilter('fromDate', 'fromDate', '' );
$toDate	  = getFilter('toDate', 'toDate', '' );
$sBranch = getFilter('sBranch', 'sBranch', '');

?>
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<?php if( $add ) : ?>
            <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
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
    	<label>ผู้ยืม[ลูกค้า/พนักงาน]</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
    </div>

    <div class="col-sm-2 padding-5">
    	<label>ผู้สั่ง[พนักงาน]</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sEmp" id="sEmp" value="<?php echo $sEmp; ?>" />
    </div>

		<div class="col-sm-2 padding-5">
			<label>สาขา</label>
			<select class="form-control input-sm search-select" name="sBranch" id="sBranch">
				<option value="">ทั้งหมด</option>
				<?php echo selectBranch($sBranch); ?>
			</select>
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

<hr class="margin-top-10 margin-bottom-10"/>

<?php
	$where = "WHERE role = 6 ";
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
		$where .= "AND id_customer IN(".getCustomerIn($sCode).") "; //--- function/customer_helper.php
	}

	//--- Employee
	if( $sEmp != "" )
	{
		createCookie('sOrderEmp', $sEmp);
		$where .= "AND id_employee IN(".getEmployeeIn($sEmp).") "; //--- function/employee_helper.php
	}

	if($sBranch != '')
	{
		createCookie('sBranch', $sBranch);
		$where .= "AND id_branch = '".$sBranch."' ";
	}

	if( $fromDate != "" && $toDate != "" )
	{
		createCookie('fromDate', $fromDate);
		createCookie('toDate', $toDate);
		$where .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '". toDate($toDate)."' ";
	}

	$where .= "ORDER BY reference DESC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_order' , $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=order_lend');
	$qs = dbQuery("SELECT * FROM tbl_order " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>

<div class="row">
	<div class="col-sm-12">
    	<table class="table table-bordered">
        	<thead>
            	<tr class="font-size-10">
                <th class="width-5 text-center">ลำดับ</th>
								<th class="width-10 text-center">วันที่</th>
                <th class="width-10 text-center">เลขที่เอกสาร</th>
								<th class="width-20 text-center">ผู้เบิก[ลูกค้า/พนักงาน]</th>
                <th class="width-20 text-center">ผู้สั่งงาน[พนักงาน]</th>
                <th class="width-10 text-center">ยอดเงิน</th>
                <th class="width-10 text-center">สถานะ</th>
                <th class="width-10 text-center">สาขา</th>
                <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no 		= row_no();			?>
<?php	$cs 		= new customer(); ?>
<?php	$order 	= new order(); ?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>

			<tr class="font-size-10" <?php echo stateColor($rs->state, $rs->status, $rs->isExpire); //--- order_help.php ?>>
        <td class="middle text-cennter pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo $no; ?>
				</td>

				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo thaiDate($rs->date_add); ?>
				</td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo $rs->reference; ?>
				</td>

				<td class="middle pointer" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo customerName($rs->id_customer); ?>
				</td>

        <td class="middle pointer" onclick="goEdit(<?php echo $rs->id; ?>)">
        	<?php echo employee_name($rs->id_employee); ?>
        </td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo number_format($order->getTotalAmount($rs->id), 2); ?>
				</td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo stateName($rs->state, $rs->status, $rs->isExpire); ?>
				</td>

				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo getBranchName($rs->id_branch); ?>
				</td>

        <td class="middle text-center">
					<a href="javascript:void(0)"  data-toggle="popover" data-html="true" data-placement="left" data-trigger="focus"
						data-content="
							พนักงาน : <?php echo employee_name($rs->id_employee); ?><br/>
              ปรับปรุงล่าสุด : <?php echo thaiDate($rs->date_upd); ?>
              ">เพิ่มเติม</a>

        </td>
      </tr>
<?php		$no++; ?>
<?php		endwhile; ?>
<?php else : ?>
			<tr>
      	<td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>
            </tbody>
        </table>
    </div>
</div>





<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
<script src="script/order/order_list.js?token=<?php echo date('Ymd'); ?>"></script><!--- ใช้ของ order เพราะเหมือนกัน -->
