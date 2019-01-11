<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-recycle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-success" onclick="syncDocument()"><i class="fa fa-retweet"></i> อัพเดตข้อมูล</button>
    </p>
  </div>
</div>
<hr/>
<?php
$sCode = getFilter('sCode', 'BMCode', '');
$sInv  = getFilter('sInv', 'BMInv', '');
$sSup  = getFilter('sSup', 'sSup', '');
$Valid = getFilter('Valid', 'Valid', '');
$notValid = getFilter('notValid', 'notValid', '');
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate = getFilter('toDate', 'toDate', '');

$valid_btn = $Valid == '' ? '' : 'btn-info';
$notvalid_btn = $notValid == '' ? '' : 'btn-info';
 ?>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" value="<?php echo $sCode; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ใบรับสินค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sInv" value="<?php echo $sInv; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ผู้ขาย</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sSup" value="<?php echo $sSup; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" placeholder="เริ่มต้น" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" placeholder="สิ้นสุด" value="<?php echo $toDate; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">Valid</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-50 <?php echo $valid_btn; ?>" id="btn-valid" onclick="toggleValid()">บันทึกแล้ว</button>
      <button type="button" class="btn btn-sm width-50 <?php echo $notvalid_btn; ?>" id="btn-notvalid" onclick="toggleNotValid()">ยังไม่บันทึก</button>
    </div>
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

  <input type="hidden" name="Valid" id="Valid" value="<?php echo $Valid; ?>" />
  <input type="hidden" name="notValid" id="notValid" value="<?php echo $notValid; ?>" />

</form>
<hr class="margin-top-15"/>
<?php
$bookcode = getConfig('BOOKCODE_RETURN_ORDER');
//$where = "WHERE bookcode = '".$bookcode."' ";
$where = "WHERE reference != '' ";
createCookie('BMCode', $sCode);
createCookie('SMInv', $sInv);
createCookie('sSup', $sSup);
createCookie('Valid', $Valid);
createCookie('notValid', $notValid);
createCookie('fromDate', $fromDate);
createCookie('toDate', $toDate);

if( $sCode != '')
{
  $where .= "AND reference LIKE '%".addslashes($sCode)."%' ";
}

if( $sInv != '')
{
  $where .= "AND invoice LIKE '%".addslashes($sInv)."%' ";
}

if( $sSup != '')
{
  $where .= "AND id_supplier IN(".supplier_in(addslashes($sSup)).") ";
}


if( $Valid != '')
{
  $where .= "AND valid = 1 ";
}

if( $notValid != '')
{
  $where .= "AND valid = 0 ";
}

if( $fromDate != '' && $toDate != '')
{
  $where .= "AND date_add >= '".dbDate($fromDate)."' ";
  $where .= "AND date_add <= '".dbDate($toDate)."' ";
}

$where .= "GROUP BY reference ORDER BY date_add DESC";

$paginator = new paginator();
$get_rows = get_rows();
$paginator->Per_Page('tbl_return_received', $where, $get_rows);

$qs = dbQuery("SELECT tbl_return_received.* FROM tbl_return_received ". $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>
 <div class="row">
   <div class="col-sm-7">
     <?php $paginator->display($get_rows, 'index.php?content=return_order'); ?>
   </div>
   <div class="col-sm-5" style="padding-top:25px;">
     <p class="pull-right top-p">
       <span class="green">OK</span><span class="margin-right-15"> = บันทึกแล้ว</span>
       <span class="blue">NC</span><span class="margin-right-15"> = ยังไม่บันทึก</span>
       <span class="red">CN</span><span class="margin-right-15"> = ยกเลิก</span>
     </p>
   </div>
 </div>

 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped table-bordered">
       <thead>
         <tr class="font-size-10">
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-10 text-center">เลขที่เอกสาร</th>
           <th class="width-10 text-center">ใบรับสินค้า</th>
           <th class="width-35 text-center">ผู้ขาย</th>
           <th class="width-10 text-center">มูลค่า</th>
           <th class="width-10 text-center">วันที่</th>
           <th class="width-5 text-center">สถานะ</th>
           <th></th>
         </tr>
       </thead>
       <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php   $no = row_no(); ?>
<?php   $sup = new supplier(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->reference; ?>">
          <td class="middle text-center"><?php echo number($no); ?></td>
          <td class="middle text-center"><?php echo $rs->reference; ?></td>
          <td class="middle text-center"><?php echo $rs->invoice; ?></td>
          <td class="middle"><?php echo $sup->getName($rs->id_supplier); ?></td>
          <td class="middle text-right"><?php echo number($rs->amount_ex, 2); ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add, '/'); ?></td>
          <td class="middle text-center"><?php echo returnRecievdStatusLabel($rs->isCancle, $rs->valid); ?></td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="viewDetail('<?php echo $rs->reference; ?>')">
              <i class="fa fa-eye"></i>
            </button>
            <?php if( ($add OR $edit ) && $rs->isCancle == 0 && $rs->valid == 0 ) : ?>
              <button type="button" class="btn btn-xs btn-warning" onclick="goEdit('<?php echo $rs->reference; ?>')">
                <i class="fa fa-pencil"></i>
              </button>
            <?php endif; ?>

            <?php if( $delete && $rs->isCancle == 0 ) : ?>
              <button type="button" class="btn btn-xs btn-danger" onclick="goDelete('<?php echo $rs->reference; ?>')">
                <i class="fa fa-trash"></i>
              </button>
            <?php endif; ?>
          </td>
        </tr>
<?php   $no++; ?>
<?php endwhile; ?>
<?php else : ?>
        <tr>
          <td colspan="9" class="text-center">
            <h4>ไม่พบรายการ</h4>
          </td>
        </tr>
<?php endif; ?>
       </tbody>
     </table>
   </div>
 </div>

 <script src="script/return_received/return_received_list.js"></script>
