
<?php
$sCode    = getFilter('sCode', 'sOrderCode', '');
$sName    = getFilter('sName', 'sCustomerName', '');
$sEmp     = getFilter('sEmp', 'sOrderEmp', '');
$sRole    = getFilter('sRole', 'sOrderRole', '');
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate   = getFilter('toDate', 'toDate', '');
$sBranch  = getFilter('sBranch', 'sBranch', '');

 ?>
 <form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm search-box" id="sCode" name="sCode" value="<?php echo $sCode; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm search-box" id="sName" name="sName" value="<?php echo $sName; ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>พนักงาน</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sEmp" name="sEmp" value="<?php echo $sEmp; ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>รูปแบบ</label>
    <select class="form-control input-sm" name="sRole" id="sRole">
      <option value="">ทั้งหมด</option>
      <?php echo selectRole($sRole); ?>
    </select>
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
    <input type="text" class="form-control input-sm text-center input-discount search-box" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>"/>
    <input type="text" class="form-control input-sm text-center input-unit search-box" id="toDate" name="toDate" value="<?php echo $toDate; ?>"/>
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">Search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">Search</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
</form>
<hr class="margin-top-10 margin-bottom-10"></hr>
