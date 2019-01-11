<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-info" onclick="viewProcess()"><i class="fa fa-hourglass-half"></i> &nbsp; รายการกำลังตรวจ</button>
    </p>
  </div>
</div>

<hr/>
<?php
$sCode      = getFilter('sCode', 'sOrderCode','');
$sCus       = getFilter('sCus', 'sOrderCus', '');
$sEmp       = getFilter('sEmp', 'sOrderEmp', '');
$fromDate   = getFilter('fromDate', 'fromDate', '');
$toDate     = getFilter('toDate', 'toDate', '');
$sBranch    = getFilter('sBranch', 'sBranch', '');
?>
<form id="searchForm" method="post">
<div class="row">
    <div class="col-sm-2 padding-5 first">
        <label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCode" name="sCode" value="<?php echo $sCode; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label>ลูกค้า/ผู้รับ/ผู้ยืม</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCus" name="sCus" value="<?php echo $sCus; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label>พนักงาน/ผู้เบิก</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sEmp" name="sEmp" value="<?php echo $sEmp; ?>"/>
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label>สาขา</label>
      <select class="form-control input-sm search-select" id="sBranch" name="sBranch">
        <option value="">ทั้งหมด</option>
        <?php echo selectBranch($sBranch); ?>
      </select>
    </div>

    <div class="col-sm-2 padding-5">
        <label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>"/>
        <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" value="<?php echo $toDate; ?>"/>
    </div>
    <div class="col-sm-1 padding-5">
        <label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">reset</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>

    </div>
</div>
</form>

<hr class="margin-top-10"/>
<?php
$qr  = "SELECT * FROM tbl_order ";
$qr .= "WHERE state = 5 ";
$qr .= "AND status = 1 ";
$qr .= "AND isExpire = 0 ";
$qr .= "AND isCancle = 0 ";

if( $sCode != "")
{
    createCookie('sOrderCode', $sCode);
    $qr .= "AND (reference LIKE'%".$sCode."%' OR ref_code LIKE '%".$sCode."%') ";
}



if( $sCus != "")
{
    createCookie('sOrderCus', $sCus);
    $qr .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}


if( $sEmp != '')
{
  createCookie('sOrderEmp', $sEmp);
  $qr .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
}


if( $sBranch != '')
{
  createCookie('sBranch', $sBranch);
  $qr .= "AND id_branch = '".$sBranch."' ";
}


if( $fromDate != "" && $toDate != "" )
{
    createCookie('fromDate', $fromDate);
    createCookie('toDate', $toDate);
    $qr .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '". toDate($toDate)."' ";
}

$qr .= "ORDER BY date_add ASC";

$qs = dbQuery($qr);
 ?>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-20">เลขที่เอกสาร</th>
          <th class="">ลูกค้า</th>
          <th class="width-10 text-center">รูปแบบ</th>
          <th class="width-15 text-center">พนักงาน</th>
          <th class="width-10 text-center">วันที่</th>
          <th class="width-10 text-center">สาขา</th>
          <th class="width-10"></th>
        </tr>
      </thead>
      <tbody id="qcList">

<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = 1; ?>
<?php   while( $rs = dbFetchObject($qs) ) : ?>
        <tr class="font-size-12" id="list-<?php echo $rs->id; ?>">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle">
            <?php echo $rs->reference; ?>
            <?php echo ($rs->ref_code != '' ? ' ['.$rs->ref_code.']' : ''); ?>
          </td>
          <td class="middle"><?php echo customerName($rs->id_customer); ?></td>
          <td class="middle text-center"><?php echo roleName($rs->role); ?></td>
          <td class="middle text-center"><?php echo employee_name($rs->emp_upd); ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
          <td class="middle text-center"><?php echo getBranchName($rs->id_branch); ?></td>
          <td class="middle text-right">
          <?php if( $add || $edit ) : ?>
            <button type="button" class="btn btn-xs btn-default" onclick="goQc(<?php echo $rs->id; ?>)">ตรวจสินค้า</button>
          <?php endif; ?>
          </td>
        </tr>
<?php     $no++; ?>
<?php   endwhile; ?>
<?php else : ?>
        <tr>
          <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="script/qc/qc_list.js"></script>
