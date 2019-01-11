<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-file-text-o"></i> รายการรอเปิดบิล</h4>
  </div>
  <div class="col-sm-6"></div>

</div>
<hr/>

<?php include 'include/bill/bill_filter.php'; ?>

<?php

  $where = "WHERE state = 7 ";

  if($sCode != '')
  {
    createCookie('sOrderCode', $sCode);
    $where .= "AND (reference LIKE'%".$sCode."%' OR ref_code LIKE '%".$sCode."%') ";
  }

  if($sName != '')
  {
    createCookie('sCustomerName', $sName);
    $where .= "AND id_customer IN(".getCustomerIn($sName).") ";
  }


  if($sEmp != '')
  {
    createCookie('sOrderEmp', $sEmp);
    $where .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
  }


  if( $sRole != '')
  {
    createCookie('sOrderRole', $sRole);
    $where .= "AND role = ".$sRole." ";
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
    $where .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '".toDate($toDate)."' ";
  }


  $where .= "ORDER BY date_add DESC";

  $paginator = new paginator();
  $get_rows = get_rows();
  $paginator->Per_Page('tbl_order', $where, $get_rows);
  $paginator->display($get_rows, 'index.php?content=bill');

  $qs = dbQuery("SELECT * FROM tbl_order ". $where ." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-8 text-center">วันที่</th>
          <th class="width-20">เลขที่เอกสาร</th>
          <th class="">ลูกค้า/ผู้รับ/ผู้เบิก</th>
          <th class="width-10 text-center">ยอดเงิน</th>
          <th class="width-10 text-center">รูปแบบ</th>
          <th class="width-15 text-center">พนักงาน</th>
          <th class="width-10 text-center">สาขา</th>
        </tr>
      </thead>
      <tbody>
<?php if( dbNumRows($qs) > 0 )  : ?>
<?php   $no = row_no(); ?>
<?php   $order = new order(); ?>
<?php   while( $rs = dbFetchObject($qs))  : ?>

        <tr class="font-size-12">

          <td class="text-center pointer" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo $no; ?>
          </td>

          <td class="pointer text-center" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo thaiDate($rs->date_add); ?>
          </td>

          <td class="pointer" onclick="goDetail(<?php echo $rs->id;?>)">
            <?php echo $rs->reference; ?>
            <?php echo ($rs->ref_code != '' ? ' ['.$rs->ref_code.']' : ''); ?>
          </td>

          <td class="pointer hide-text" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo customerName($rs->id_customer); ?>
          </td>

          <td class="pointer text-center" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo number($order->getTotalAmount($rs->id)); ?>
          </td>

          <td class="pointer text-center" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo roleName($rs->role); ?>
          </td>

          <td class="pointer text-center hide-text" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo employee_name($rs->id_employee); ?>
          </td>

          <td class="pointer text-center" onclick="goDetail(<?php echo $rs->id; ?>)">
            <?php echo getBranchName($rs->id_branch); ?>
          </td>

        </tr>
<?php  $no++; ?>
<?php endwhile; ?>
<?php else : ?>
      <tr>
        <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<script src="script/bill/bill_list.js?token=<?php echo date('Ymd'); ?>"></script>
