<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-triangle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
<?php if( $add ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มนโยบาย</button>
<?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<?php
  $sCode     = getFilter('sCode', 'policyCode', '');
  $sName     = getFilter('sName', 'policyName', '');
  $isActive  = getFilter('isActive', 'isActive', 2);  //--- 0 = inactive, 1 = active, 2 = all;
  $startDate = getFilter('fromDate', 'startDate', '');
  $endDate   = getFilter('toDate', 'endDate', '');
  $btn_all   = $isActive == 2 ? 'btn-primary' : '';
  $btn_active = $isActive == 1 ? 'btn-primary' : '';
  $btn_inactive = $isActive == 0 ? 'btn-primary' : '';

 ?>
<form id="searchForm" method="post" >
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่นโยบาย</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" autofocus />
  </div>
  <div class="col-sm-3 padding-5">
    <label>ชื่อนโยบาย</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName" value="<?php echo $sName; ?>" />
  </div>
  <div class="col-sm-2 col-2-harf padding-5">
    <label class="display-block not-show">active</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-33 <?php echo $btn_all; ?>" id="btn-all" onclick="setActiveFilter(2)">ทั้งหมด</button>
      <button type="button" class="btn btn-sm width-33 <?php echo $btn_active; ?>" id="btn-active" onclick="setActiveFilter(1)">ใช้งาน</button>
      <button type="button" class="btn btn-sm width-33 <?php echo $btn_inactive; ?>" id="btn-inactive" onclick="setActiveFilter(0)">ไม่ใช้งาน</button>
    </div>
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block">ช่วงวันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $startDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $endDate; ?>" />
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
<input type="hidden" name="isActive" id="isActive" value="<?php echo $isActive; ?>">
</form>

<hr class="margin-top-10 margin-bottom-10"/>

<?php
  $where = "WHERE isDeleted = 0 ";

  if($sCode != '')
  {
    createCookie('policyCode', $sCode);
    $where .= "AND reference LIKE '%".$sCode."%' ";
  }

  if($sName != '')
  {
    createCookie('policyName', $sName);
    $where .= "AND name LIKE '%".$sName."%' ";
  }

  if($isActive != 2)
  {
    createCookie('isActive', $isActive);
    $where .= "AND active = ".$isActive." ";
  }


  if($startDate != '' && $endDate != '')
  {
    createCookie('startDate', $startDate);
    createCookie('endDate', $endDate);
    $where .= "AND ((date_start >= '".dbDate($startDate)."' AND date_start <= '".dbDate($endDate)."') ";
    $where .= "OR (date_end >= '".dbDate($startDate)."' AND date_end <= '".dbDate($endDate)."')) ";
  }

  //echo $where;
  $where .= "ORDER BY reference DESC";

  $paginator = new paginator();
  $get_rows = get_rows();
  $paginator->Per_page('tbl_discount_policy', $where, $get_rows);
  $paginator->display($get_rows, 'index.php?content=discount_policy');
  $qs = dbQuery("SELECT * FROM tbl_discount_policy ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>

 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped border-1">
       <thead>
         <tr>
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-15 text-center">เลขที่นโยบาย</th>
           <th class="width-35">ชื่อนโยบาย</th>
           <th class="width-10 text-center">เริ่มต้น</th>
           <th class="width-10 text-center">สิ้นสุด</th>
           <th class="width-5 text-center">สถานะ</th>
           <th class=""></th>
         </tr>
       </thead>
       <tbody>
<?php if(dbNumRows($qs) > 0) : ?>
  <?php $no = 1; ?>
  <?php while($rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center no"><?php echo number($no); ?></td>
          <td class="middle text-center"><?php echo $rs->reference; ?></td>
          <td class="middle"><?php echo $rs->name; ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_start, '/'); ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_end, '/'); ?></td>
          <td class="middle text-center"><?php echo isActived($rs->active); ?></td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="viewDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
      <?php if($edit) : ?>
            <button type="button" class="btn btn-xs btn-warning" onclick="goEdit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
      <?php endif; ?>
      <?php if($delete) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="getDelete('<?php echo $rs->id; ?>', '<?php echo $rs->reference; ?>')"><i class="fa fa-trash"></i></button>
      <?php endif; ?>

          </td>
        </tr>
    <?php $no++; ?>
  <?php endwhile; ?>

<?php else : ?>
        <tr>
          <td colspan="7" class="text-center">
            <h4>ไม่พบรายการ</h4>
          </td>
        </tr>
<?php endif; ?>
       </tbody>
     </table>
   </div>
 </div>


<script src="script/policy/policy_list.js"></script>
