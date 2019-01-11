<?php

//--- function getFilter in function/tools.php
$sCode 	= getFilter('sCode', 'sOrderCode', '');	//---	reference
$sCus	 	= getFilter('sCus', 'sOrderCus', '' );	//---	customer
$sEmp	  = getFilter('sEmp', 'sOrderEmp', '' );	//---	Employee
$sZone  = getFilter('sZone', 'sOrderZone', '');	//--- consing zone
$fromDate	= getFilter('fromDate', 'fromDate', '' );
$toDate	  = getFilter('toDate', 'toDate', '' );
$sBranch  = getFilter('sBranch', 'sBranch', '');

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
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ลูกค้า</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
    	<label>พนักงาน</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sEmp" id="sEmp" value="<?php echo $sEmp; ?>" />
    </div>

		<div class="col-sm-2 padding-5">
    	<label>พื้นที่เก็บ</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sZone" id="sZone" value="<?php echo $sZone; ?>" />
    </div>
		<div class="col-sm-1 col-1-harf padding-5">
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
	$where = "WHERE role = 2 AND is_so = 1 ";
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


	//---	Consign zone
	if( $sZone != '')
	{
		createCookie('sOrderZone', $sZone);
		$where .= "AND id_zone IN(".getConsignZoneIn($sZone).") ";
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

	$table = "tbl_order LEFT JOIN tbl_order_consign ON tbl_order.id = tbl_order_consign.id_order ";
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page($table , $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=order_consign');
	$qs = dbQuery("SELECT tbl_order.*, id_zone FROM ".$table . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>

<div class="row">
	<div class="col-sm-12">
    	<table class="table table-bordered">
        	<thead>
            	<tr class="font-size-10">
                <th class="width-5 text-center">ลำดับ</th>
                <th class="width-8 text-center">เลขที่เอกสาร</th>
                <th class="width-25 text-center">ลูกค้า</th>
								<th class="width-25 text-center">พื้นที่เก็บ</th>
                <th class="width-8 text-center">ยอดเงิน</th>
                <th class="width-8 text-center">สถานะ</th>
                <th class="width-8 text-center">วันที่</th>
                <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no 		= row_no();			?>
<?php	$cs 		= new customer(); ?>
<?php	$order 	= new order(); ?>
<?php $zone 	= new zone(); ?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>

			<tr class="font-size-10" <?php echo stateColor($rs->state, $rs->status, $rs->isExpire); //--- order_help.php ?>>
        <td class="middle text-cennter pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo $no; ?>
				</td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo $rs->reference; ?>
				</td>

        <td class="middle pointer" onclick="goEdit(<?php echo $rs->id; ?>)">
        	<?php echo customerName($rs->id_customer); ?>
        </td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo $zone->getName($rs->id_zone); ?>
				</td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo number_format($order->getTotalAmount($rs->id), 2); ?>
				</td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo stateName($rs->state, $rs->status, $rs->isExpire); ?>
				</td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)">
					<?php echo thaiDate($rs->date_add); ?>
				</td>

        <td class="middle text-center">
					<a href="#"  data-toggle="popover" data-html="true" data-placement="left" data-trigger="focus"
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
      	<td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
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
