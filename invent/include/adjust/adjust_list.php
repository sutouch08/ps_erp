<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-magic"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php if( $add ) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
      <?php endif; ?>
    </p>
  </div>
</div>

<hr/>

<?php

//--- ตัวกรองตามเลขที่เอกสาร
$sCode = getFilter('sCode', 'sAdjustCode', '');

//--- กรองตามการอ้างถึง
$sRefer = getFilter('sRefer', 'sAdjustRefer', '');

//--- กรองตามพนักงานผู้ขอปรับยอด
$sEmp = getFilter('sEmp', 'sEmp', '');

//--- กรองตามช่วงวันที่ (เริ่มต้น)
$fromDate = getFilter('fromDate', 'fromDate', '');

//--- กรองตามช่วงวันที่ (สิ้นสุด)
$toDate = getFilter('toDate', 'toDate', '');

 ?>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sCode" name="sCode" placeholder="กรองตามเอกสาร" value="<?php echo $sCode; ?>" autofocus />
  </div>

  <div class="col-sm-2 padding-5">
    <label>อ้างถึง</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sRefer" name="sRefer" placeholder="การอ้างอิง" value="<?php echo $sRefer; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label>ผู้ขอปรับยอด</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sEmp" name="sEmp" placeholder="ชื่อผู้ขอปรับยอด" value="<?php echo $sEmp; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" placeholder="เริ่มต้น" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" placeholder="สิ้นสุด" value="<?php echo $toDate; ?>" />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">Search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">Reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
</form>

<hr class="margin-top-15 margin-bottom-15" />

<?php
$where = "WHERE id != 0 ";

if( $sCode != '')
{
    createCookie('sAdjustCode', $sCode);
    $where .= "AND reference LIKE '%".$sCode."%' ";
}

if( $sRefer != '')
{
  createCookie('sAdjustRefer', $sRefer);
  $where .= "AND refer LIKE '%".$sRefer."%' ";
}

if( $sEmp != '')
{
  createCookie('sEmp', $sEmp);
  $where .= "AND requester LIKE '%".$sEmp."%' ";
}

if( $fromDate != '' && $toDate != '')
{
  createCookie('fromDate', $fromDate);
  $where .= "AND date_add >= '".fromDate($fromDate)."' ";

  createCookie('toDate', $toDate);
  $where .= "AND date_add <= '".toDate($toDate)."' ";
}

$where .= "ORDER BY reference DESC";

$paginator = new paginator();
$get_rows  = get_rows();
$paginator->Per_Page('tbl_adjust', $where, $get_rows);


$qs = dbQuery("SELECT * FROM tbl_adjust ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>
 <div class="row">
   <div class="col-sm-7" style="margin-top:-5px;">
    	<?php $paginator->display($get_rows, 'index.php?content=adjust'); ?>
   </div>
   <div class="col-sm-5 margin-top-15">
     <p class="pull-right">
       <span class="red">NC</span><span class="margin-right-15"> = ยังไม่บันทึก, </span>
       <span class="red">CN</span><span class="margin-right-15"> = ยกเลิก, </span>
       <span class="red">NE</span><span> = ยังไม่ส่งออกไป FORMULA</span>
     </p>
   </div>
 </div>
 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped border-1">
       <thead>
         <tr>
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-15">เลขที่เอกสาร</th>
           <th class="width-15">อ้างถึง</th>
           <th class="width-15">ผู้ขอปรับยอด</th>
           <th class="width-20">หมายเหตุ</th>
           <th class="width-10 text-center">วันที่</th>
           <th class="width-5 text-center">สถานะ</th>
           <th class="text-right"></th>
         </tr>
       </thead>
       <tbody>
  <?php if( dbNumRows($qs) > 0) : ?>
  <?php   $no = row_no(); ?>
  <?php   while($rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12">
          <td class="middle text-center"><?php echo number($no); ?></td>
          <td class="middle"><?php echo $rs->reference; ?></td>
          <td class="middle"><?php echo $rs->refer; ?></td>
          <td class="middle"><?php echo $rs->requester; ?></td>
          <td class="middle"><?php echo $rs->remark; ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
          <td class="middle text-center">
            <?php echo statusLabel($rs->isCancle, $rs->isExport, $rs->isSaved); ?>
          </td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="goDetail(<?php echo $rs->id; ?>)">
              <i class="fa fa-eye"></i>
            </button>

          <?php if( $edit && $rs->isCancle == 0 && $rs->isSaved == 0) : ?>
            <button type="button" class="btn btn-xs btn-warning" onclick="goEdit(<?php echo $rs->id; ?>)">
              <i class="fa fa-pencil"></i>
            </button>
          <?php endif; ?>

          <?php if( $delete && $rs->isCancle == 0 ) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="goDelete(<?php echo $rs->id; ?>, '<?php echo $rs->reference; ?>')">
              <i class="fa fa-trash"></i>
            </button>
          <?php endif; ?>

          </td>
        </tr>
  <?php    $no++; ?>
  <?php   endwhile; ?>
  <?php else: ?>
        <tr>
          <td colspan="8" class="text-center">
            <h4>ไม่พบรายการ</h4>
          </td>
        </tr>
  <?php endif; ?>

       </tbody>
     </table>
   </div>
 </div>





<script src="script/adjust/adjust_list.js"></script>
