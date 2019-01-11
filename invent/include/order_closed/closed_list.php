<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-file-text-o"></i> <?php echo $pageTitle; ?></h4>
  </div>

</div>
<hr/>
<?php
  //--- text box values
  $sCode = getFilter('sCode', 'sOrderCode', '');
  $sCust = getFilter('sCust', 'sOrderCustomer', '');
  $sEmp  = getFilter('sEmp', 'sOrderEmployee', '');
  $fromDate = getFilter('fromDate', 'fromDate', '');
  $toDate   = getFilter('toDate', 'toDate', '');

  //--- button values
  $viewDate = getFilter('viewDate', 'viewDate', 0); //--- 0 = date_add, 1 = date_upd
  $sOrder  = getFilter('sOrder', 'sOrder', 0); //---  ขาย   0 = not filter 1 = filter
  $sConsign = getFilter('sConsign', 'sConsign', 0); //---  ฝากขาย 0 = not filter 1 = filter
  $sSupport  = getFilter('sSupport', 'sSupport', 0); //---  อภินันท์  0 = not filter 1 = filter
  $sSponsor  = getFilter('sSponsor', 'sSponsor', 0); //---  สปอนเซอร์สโมสร  0 = not filter 1 = filter
  $sTransform  = getFilter('sTransform', 'sTransform', 0); //---  เบิกแรปสภาพ  0 = not filter 1 = filter
  $sLend  = getFilter('sLend', 'sLend', 0); //---  ยืมสินค้า  0 = not filter 1 = filter
  $sRequisition  = getFilter('sRequisition', 'sRequisition', 0); //---  เบิกทั่วไป  0 = not filter 1 = filter
  $sOnline = getFilter('sOnline', 'sOnline', 0); //--- ออนไลน์เท่านั้น
  $sBranch = getFilter('sBranch', 'sBranch', '');
  $isDelivered = getFilter('sDelivered', 'sDelivered', 0); //---- จัดส่งแล้วเท่านั้น
  $isNotDelivery = getFilter('sNotDelivery', 'sNotDelivery', 0); //--- ยังไม่จัดส่ง
  $btn = array(
        '1' => $sOrder,
        '2' => $sConsign,
        '3' => $sSupport,
        '4' => $sSponsor,
        '5' => $sTransform,
        '6'  => $sLend,
        '7'  => $sRequisition
      );
  //--- ไว้ตรวจสอบตอนสร้าง query
  $activeButton = array();

  foreach($btn as $key => $value)
  {
    if( $value == 1)
    {
      $activeButton[$key] = $value;
    }
  }

  //----------- active button
  //--- view date
  $b_add  = $viewDate == 0 ? 'btn-primary' : '';
  $b_upd  = $viewDate == 0 ? '' : 'btn-primary';

  //--- order
  $b_order      = $sOrder == 0 ? '' : 'btn-primary';
  $b_consign    = $sConsign == 0 ? '' : 'btn-primary';
  $b_support    = $sSupport == 0 ? '' : 'btn-primary';
  $b_sponsor    = $sSponsor == 0 ? '' : 'btn-primary';
  $b_transform  = $sTransform == 0 ? '' : 'btn-primary';
  $b_lend       = $sLend == 0 ? '' : 'btn-primary';
  $b_requis     = $sRequisition == 0 ? '' : 'btn-primary';
  $b_online     = $sOnline == 0 ? '' : 'btn-primary';


  //--- delivery
  $b_delivered    = $isDelivered == 0 ? '' : 'btn-primary';
  $b_notdelivery  = $isNotDelivery == 0 ? '' : 'btn-primary';
 ?>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
  </div>


  <div class="col-sm-1 col-1-harf padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCust" id="sCust" value="<?php echo $sCust; ?>" />
  </div>


  <div class="col-sm-1 col-1-harf padding-5">
    <label>พนักงาน</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sEmp" id="sEmp" value="<?php echo $sEmp; ?>" />
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
    <input type="text" class="form-control input-sm input-discount text-center search-box" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center search-box" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
  </div>


  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">view date</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-50 <?php echo $b_add; ?>" id="btn-date-add" onclick="toggleViewDate(0)">วันที่เอกสาร</button>
      <button type="button" class="btn btn-sm width-50 <?php echo $b_upd; ?>" id="btn-date-upd" onclick="toggleViewDate(1)">วันที่ปรับปรุง</button>
    </div>
  </div>


  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">filter</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>


  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">filter</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>

  <div class="divider-hidden margin-top-5"></div>

  <div class="col-sm-1 padding-5 first">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_order; ?>" onclick="toggleButton('Order')">ขาย</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_consign; ?>" onclick="toggleButton('Consign')">ฝากขาย</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_support; ?>" onclick="toggleButton('Support')">อภินันท์</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_sponsor; ?>" onclick="toggleButton('Sponsor')">สโมสร</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_transform; ?>" onclick="toggleButton('Transform')">แปรสภาพ</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_lend; ?>" onclick="toggleButton('Lend')">ยืม</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_requis; ?>" onclick="toggleButton('Requisition')">เบิกอื่น</button>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_online; ?>" onclick="toggleButton('Online')">ออนไลน์</button>
  </div>

  <div class="col-sm-1 col-sm-offset-2 padding-5">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_delivered; ?>" onclick="toggleButton('Delivered')">จัดส่งแล้ว</button>
  </div>
  <div class="col-sm-1 padding-5 last">
    <button type="button" class="btn btn-sm btn-block <?php echo $b_notdelivery; ?>" onclick="toggleButton('NotDelivery')">รอการจัดส่ง</button>
  </div>


  <input type="hidden" name="viewDate" id="viewDate" value="<?php echo $viewDate; ?>" />

  <input type="hidden" name="sOrder" id="sOrder" value="<?php echo $sOrder; ?>" />
  <input type="hidden" name="sConsign" id="sConsign" value="<?php echo $sConsign; ?>" />
  <input type="hidden" name="sSupport" id="sSupport" value="<?php echo $sSupport; ?>" />
  <input type="hidden" name="sSponsor" id="sSponsor" value="<?php echo $sSponsor; ?>" />
  <input type="hidden" name="sTransform" id="sTransform" value="<?php echo $sTransform; ?>" />
  <input type="hidden" name="sLend" id="sLend" value="<?php echo $sLend; ?>" />
  <input type="hidden" name="sRequisition" id="sRequisition" value="<?php echo $sRequisition; ?>" />
  <input type="hidden" name="sOnline" id="sOnline" value="<?php echo $sOnline; ?>" />
  <input type="hidden" name="sDelivered" id="sDelivered" value="<?php echo $isDelivered; ?>" />
  <input type="hidden" name="sNotDelivery" id="sNotDelivery" value="<?php echo $isNotDelivery; ?>" />

</div>
</form>

<hr/>
<?php

  $where = "WHERE state IN(8,9,10) ";

  createCookie('sDelivered', $isDelivered);
  createCookie('sNotDelivery', $isNotDelivery);

  if($isDelivered == 1 OR $isNotDelivery == 1)
  {
    if($isDelivered == 1 && $isNotDelivery == 1)
    {
      $where .= "AND (state = 10 OR state = 8) ";
    }

    if($isDelivered == 1 && $isNotDelivery == 0)
    {
      $where .= "AND state = 10 ";
    }

    if($isDelivered == 0 && $isNotDelivery == 1)
    {
      $where .= "AND state = 8 ";
    }
  }



  if( $sCode != '')
  {
    createCookie('sOrderCode', $sCode);
    $where .= "AND (reference LIKE '%".$sCode."%' OR ref_code LIKE '%".$sCode."%') ";
  }

  if( $sCust != '')
  {
    createCookie('sOrderCustomer', $sCust);
    $where .= "AND id_customer IN(".getCustomerIn($sCust).") ";
  }

  if( $sEmp != '')
  {
    createCookie('sOrderEmployee', $sEmp);
    $where .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
  }

  if( $sBranch != '')
  {
    createCookie('sBranch', $sBranch);
    $where .= "AND id_branch = '".$sBranch."' ";
  }

  if( $fromDate != '' && $toDate != '')
  {
    createCookie('fromDate', $fromDate);
    createCookie('toDate', $toDate);
    $where .= $viewDate == 0 ? "AND date_add >= '".fromDate($fromDate)."' " : "AND date_upd >= '".fromDate($fromDate)."' ";
    $where .= $viewDate == 0 ? "AND date_add <= '".toDate($toDate)."' " : "AND date_upd <= '".toDate($toDate)."' ";
  }

  createCookie('viewDate', $viewDate);
  createCookie('sOrder', $sOrder);
  createCookie('sConsign', $sConsign);
  createCookie('sSupport', $sSupport);
  createCookie('sSponsor', $sSponsor);
  createCookie('sTransform', $sTransform);
  createCookie('sLend', $sLend);
  createCookie('sRequisition', $sRequisition);
  createCookie('sOnline', $sOnline);

  //--- ถ้ามีการใช้ตัวกรอง order role
  if( ! empty( $activeButton ) )
  {
    $role_in = "";
    $i = 1;
    foreach($activeButton as $key => $value )
    {
      $role_in .= $i == 1 ? $key : ", ".$key;
      $i++;
    }

    $where .= "AND role IN(".$role_in.") ";
  }

  //--- ถ้าต้องการ ออนไลน์อย่างเดียว
  if( $sOnline != 0 )
  {
    $where .= "AND isOnline = 1 ";
  }

  $where .= "ORDER BY ".( $viewDate == 1 ? 'date_upd' : 'date_add')." DESC, reference DESC";


  $paginator  = new paginator();
  $get_rows   = get_rows();
	$paginator->Per_Page('tbl_order', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=order_closed');
	$qs = dbQuery("SELECT * FROM tbl_order " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);


?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-20 text-center">เลขที่เอกสาร</th>
          <th class="text-center">ลูกค้า</th>
          <th class="width-8 text-center">ยอดเงิน</th>
          <th class="width-8 text-center">รูปแบบ</th>
          <th class="width-15 text-center">พนักงาน</th>
          <th class="width-8 text-center">สาขา</th>
          <th class="width-8 text-center">วันที่</th>
          <th class="width-10 text-center">ปรับปรุง</th>
        </tr>
      </thead>
      <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
<?php   $order = new order(); ?>
<?php   $no = row_no(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-10" <?php echo stateColor($rs->state, $rs->status, $rs->isExpire); //--- order_help.php ?>>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo number($no); ?>
          </td>

          <td class="middle pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo $rs->reference; ?>
            <?php echo ($rs->ref_code != '' ? '['.$rs->ref_code.']' : ''); ?>
          </td>

          <td class="middle pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo customerName($rs->id_customer); ?>
          </td>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo number($order->getTotalSoldAmount($rs->id, TRUE), 2); ?>
          </td>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo roleName($rs->role); ?>
            <?php if( $rs->isOnline == 1 ) : ?>
                [online]
            <?php endif; ?>
          </td>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo employee_name($rs->id_employee); ?>
          </td>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo getBranchName($rs->id); ?>
          </td>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo thaiDate($rs->date_add); ?>
          </td>

          <td class="middle text-center pointer" onclick="viewDetail(<?php echo $rs->id; ?>)">
            <?php echo thaiDateTime($rs->date_upd); ?>
          </td>

        </tr>
<?php     $no++; ?>
<?php   endwhile; ?>

<?php else : ?>
      <tr>
        <td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>

</div>





<script src="script/order_closed/closed_list.js?token=<?php echo date('Ymd'); ?>"></script>
