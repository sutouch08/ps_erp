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
$sCode = getFilter('sCode', 'SMsCode', '');
$sInv  = getFilter('sInv', 'SMsInv', '');
$sCus  = getFilter('sCus', 'SMsCus', '');
$Return = getFilter('Return', 'Return', '');
$notReturn = getFilter('notReturn', 'notReturn','');
$Valid = getFilter('Valid', 'Valid', '');
$notValid = getFilter('notValid', 'notValid', '');
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate = getFilter('toDate', 'toDate', '');

$return_btn = $Return == '' ? '' : 'btn-info';
$notreturn_btn = $notReturn == '' ? '' : 'btn-info';
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
    <label>ใบส่งสินค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sInv" value="<?php echo $sInv; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCus" value="<?php echo $sCus; ?>" />
  </div>
  <div class="col-sm-3 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" placeholder="เริ่มต้น" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" placeholder="สิ้นสุด" value="<?php echo $toDate; ?>" />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block not-show">Search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>
  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block not-show">Search</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>

  <div class="col-sm-2">
    <label class="display-block not-show">btn-group</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-50 <?php echo $return_btn; ?>" id="btn-return" onclick="toggleReturn()">คืนสินค้า</button>
      <button type="button" class="btn btn-sm width-50 <?php echo $notreturn_btn; ?>" id="btn-notreturn" onclick="toggleNotReturn()">ไม่คืนสินค้า</button>
    </div>
  </div>

  <div class="col-sm-2">
    <label class="display-block not-show">btn-group</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-50 <?php echo $valid_btn; ?>" id="btn-valid" onclick="toggleValid()">รับเข้าแล้ว</button>
      <button type="button" class="btn btn-sm width-50 <?php echo $notvalid_btn; ?>" id="btn-notvalid" onclick="toggleNotValid()">ยังไม่รับเข้า</button>
    </div>
  </div>
</div>

  <input type="hidden" name="Valid" id="Valid" value="<?php echo $Valid; ?>" />
  <input type="hidden" name="notValid" id="notValid" value="<?php echo $notValid; ?>" />
  <input type="hidden" name="Return" id="Return" value="<?php echo $Return; ?>" />
  <input type="hidden" name="notReturn" id="notReturn" value="<?php echo $notReturn; ?>" />
</form>
<hr class="margin-top-15"/>
<?php
$bookcode = getConfig('BOOKCODE_RETURN_ORDER');
//$where = "WHERE bookcode = '".$bookcode."' ";
$where = "WHERE reference != '' ";
createCookie('SMsCode', $sCode);
createCookie('SMsInv', $sInv);
createCookie('SMsCus', $sCus);
createCookie('Return', $Return);
createCookie('notReturn', $notReturn);
createCookie('Valid', $Valid);
createCookie('notValid', $notValid);
createCookie('fromDate', $fromDate);
createCookie('toDate', $toDate);

if( $sCode != '')
{
  $where .= "AND reference LIKE '%".$sCode."%' ";
}

if( $sInv != '')
{
  $where .= "AND invoice LIKE '%".$sInv."%' ";
}

if( $sCus != '')
{
  $where .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}


if( $Return != '')
{
  $where .= "AND isReturn = 1 ";
}

if( $notReturn != '')
{
  $where .= "AND isReturn = 0 ";
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
$paginator->Per_Page('tbl_return_order', $where, $get_rows);

$qs = dbQuery("SELECT tbl_return_order.*, SUM(received) AS totalReceived FROM tbl_return_order ". $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>
 <div class="row">
   <div class="col-sm-7">
     <?php $paginator->display($get_rows, 'index.php?content=return_order'); ?>
   </div>
   <div class="col-sm-5" style="padding-top:25px;">
     <p class="pull-right top-p">
       <span>ว่าง</span><span class="margin-right-15"> = ปกติ</span>
       <span class="blue">NC</span><span class="margin-right-15"> = ยังไม่รับเข้า</span>
       <span>Y</span><span class="margin-right-15"> = คืนสินค้า</span>
       <span>N</span><span class="margin-right-15"> = ไม่คืนสินค้า</span>
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
           <th class="width-10 text-center">ใบส่งสินค้า</th>
           <th class="width-35 text-center">ลูกค้า</th>
           <th class="width-10 text-center">มูลค่า</th>
           <th class="width-10 text-center">วันที่</th>
           <th class="width-5 text-center">สถานะ</th>
           <th class="width-5 text-center">คืน</th>
           <th></th>
         </tr>
       </thead>
       <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php   $no = row_no(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->reference; ?>">
          <td class="middle text-center"><?php echo number($no); ?></td>
          <td class="middle text-center"><?php echo $rs->reference; ?></td>
          <td class="middle text-center"><?php echo $rs->invoice; ?></td>
          <td class="middle"><?php echo customerName($rs->id_customer); ?></td>
          <td class="middle text-right"><?php echo number($rs->amount_ex, 2); ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add, '/'); ?></td>
          <td class="middle text-center"><?php echo returnStatusLabel($rs->isCancle, $rs->valid); ?></td>
          <td class="middle text-center"><?php echo ($rs->isReturn == 1 ? 'Y' : 'N'); ?></td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="viewDetail('<?php echo $rs->reference; ?>')">
              <i class="fa fa-eye"></i>
            </button>
            <?php if( ($add OR $edit ) && $rs->isReturn == 1 && $rs->valid == 0 ) : ?>
              <button type="button" class="btn btn-xs btn-primary" title="ปิดเอกสาร" onclick="doValid('<?php echo $rs->reference; ?>')">
                <i class="fa fa-check"></i>
              </button>
              <button type="button" class="btn btn-xs btn-warning" onclick="goEdit('<?php echo $rs->reference; ?>')">
                <i class="fa fa-pencil"></i>
              </button>
            <?php endif; ?>

            <?php if( $edit && $rs->isReturn == 1 && $rs->valid == 1 && $rs->totalReceived == 0 ) : ?>
              <button type="button" class="btn btn-xs btn-warning" title="ย้อนสถานะ" onclick="disValid('<?php echo $rs->reference; ?>')">
                <i class="fa fa-refresh"></i>
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

 <script src="script/return_order/return_order_list.js"></script>
