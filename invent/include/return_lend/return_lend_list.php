<?php
include 'function/employee_helper.php';
include 'function/customer_helper.php';
 ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php if($add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<?php
//--- เลขที่เลขที่เอกสาร
$sCode = getFilter('sCode', 'sCode', '');

//--- เลขที่ออเดอร์ยืมสินค้า
$sLendCode = getFilter('sLendCode', 'sLendCode', '');

//--- ผู้ยืมสินค้า เป็นชื่อลูกค้าตามออเดอร์ที่ยืม
$sCus = getFilter('sCus', 'sCus', '');

//--- พนักงานผู้รับคืนสินค้า (คนทำรายการ)
$sEmp = getFilter('sEmp', 'sEmp' ,'');

//--- วันที่เอกสาร
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate = getFilter('toDate', 'toDate', '');
 ?>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ใบยืมสินค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sLendCode" id="sLendCode" value="<?php echo $sLendCode; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ผู้คืน [ผู้ยืม]</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ผู้รับคืน</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sEmp" id="sEmp" value="<?php echo $sEmp; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">Reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>

</div>
</form>
<hr class="margin-top-10"/>
<?php
$where = "WHERE id != '' ";

if($sCode != '')
{
  createCookie('sCode', $sCode);
  $where .= "AND reference LIKE '%".$sCode."%' ";
}

if($sLendCode != '')
{
  createCookie('sLendCode', $sLendCode);
  $where .= "AND order_code LIKE '%".$sLendCode."%' ";
}

if($sCus != '')
{
  createCookie('sCus', $sCus);
  $where .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}

if($sEmp != '')
{
  createCookie('sEmp', $sEmp);
  $where .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
}

if($fromDate != '' && $toDate != '')
{
  createCookie('fromDate', $fromDate);
  createCookie('toDate', $toDate);
  $where .= "AND date_add >= '".fromDate($fromDate)."' ";
  $where .= "AND date_add <= '".toDate($toDate)."' ";
}

$where .= "ORDER BY reference DESC";

$paginator = new paginator();
$get_rows = get_rows();
$paginator->Per_Page('tbl_return_lend', $where, $get_rows);


$qs = dbQuery("SELECT * FROM tbl_return_lend ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
 ?>


 <div class="row">
   <div class="col-sm-7">
     <?php $paginator->display($get_rows, 'index.php?content=consign'); ?>
   </div>
   <div class="col-sm-5" style="padding-top:25px;">
     <p class="pull-right top-p">
       <span class="">ว่าง</span><span class="margin-right-15"> = ปกติ</span>
       <span class="red">CN</span><span class=""> = ยกเลิก</span>
     </p>
   </div>
 </div>

 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped border-1">
       <thead>
         <tr class="font-size-12">
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-10 text-center">วันที่</th>
           <th class="width-10 text-center">เลขที่เอกสาร</th>
           <th class="width-10 text-center">ใบยืมสินค้า</th>
           <th class="width-10 text-center">จำนวน</th>
           <th class="width-25 text-center">ผู้ยืม</th>
           <th class="width-15 text-center">ผู้รับคืน</th>
           <th class="width-5 text-center">สถานะ</th>
           <th></th>
         </tr>
       </thead>
       <tbody>
<?php if(dbNumRows($qs) > 0) : ?>
  <?php $no = row_no(); ?>
  <?php $cs = new return_lend(); ?>
  <?php while($rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center no"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
          <td class="middle text-center"><?php echo $rs->reference; ?></td>
          <td class="middle text-center"><?php echo $rs->order_code; ?></td>
          <td class="middle text-center"><?php echo number($cs->getSumQty($rs->id)); ?></td>
          <td class="middle text-center"><?php echo customerName($rs->id_customer); ?></td>
          <td class="middle text-center"><?php echo employeeName($rs->id_employee); ?></td>
          <td class="middle text-center" id="label-<?php echo $rs->id; ?>">
            <?php if($rs->isCancle == 1) : ?>
              <span class="red">CN</span>
            <?php endif;?>
          </td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" title="รายละเอียด" onclick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
          <?php if($delete && $rs->isCancle == 0) : ?>
            <button type="button" class="btn btn-xs btn-danger" id="btn-del-<?php echo $rs->id; ?>" title="ยกเลิก" onclick="goCancle(<?php echo $rs->id; ?>, '<?php echo $rs->reference; ?>')">
              <i class="fa fa-times"></i>
            </button>
          <?php endif; ?>
          </td>
        </tr>
    <?php $no++; ?>
  <?php endwhile; ?>
<?php else : ?>
        <tr>
          <td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
        </tr>
<?php endif; ?>

       </tbody>
     </table>
   </div>
 </div>

<script src="script/return_lend/return_lend_list.js"></script>
