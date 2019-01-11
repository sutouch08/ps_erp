<?php include 'function/discount_rule_helper.php'; ?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-circle"></i> <?php echo $pageTitle; ?></h4>
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
  $sCode     = getFilter('sCode', 'ruleCode', '');
  $sName     = getFilter('sName', 'ruleName', '');
  $sPolicy   = getFilter('sPolicy', 'sPolicy', '');
  $sDisc     = getFilter('sDisc', 'sDisc', '');
  $isActive  = getFilter('isActive', 'isActive', 2);  //--- 0 = inactive, 1 = active, 2 = all;
  $btn_all   = $isActive == 2 ? 'btn-primary' : '';
  $btn_active = $isActive == 1 ? 'btn-primary' : '';
  $btn_inactive = $isActive == 0 ? 'btn-primary' : '';
 ?>

<form id="searchForm" method="post" >
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" autofocus />
  </div>
  <div class="col-sm-3 padding-5">
    <label>ชื่อเงื่อนไข</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName" value="<?php echo $sName; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label>รหัส/ชื่อ นโยบาย</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sPolicy" id="sPolicy" value="<?php echo $sPolicy; ?>" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>ส่วนลด</label>
    <input type="number" class="form-control input-sm text-center search-box" name="sDisc" id="sDisc" value="<?php echo $sDisc; ?>">
  </div>

  <div class="col-sm-2 col-2-harf padding-5">
    <label class="display-block not-show">active</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-33 <?php echo $btn_all; ?>" id="btn-all" onclick="setActiveFilter(2)">ทั้งหมด</button>
      <button type="button" class="btn btn-sm width-33 <?php echo $btn_active; ?>" id="btn-active" onclick="setActiveFilter(1)">ใช้งาน</button>
      <button type="button" class="btn btn-sm width-34 <?php echo $btn_inactive; ?>" id="btn-inactive" onclick="setActiveFilter(0)">ไม่ใช้งาน</button>
    </div>
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
<input type="hidden" id="isActive" name="isActive" value="<?php echo $isActive; ?>" />
</form>

<hr class="margin-top-10 margin-bottom-10"/>

<?php
  $where = "WHERE isDeleted = 0 ";

  if($sCode != '')
  {
    createCookie('ruleCode', $sCode);
    $where .= "AND code LIKE '%".$sCode."%' ";
  }

  if($sName != '')
  {
    createCookie('ruleName', $sName);
    $where .= "AND name LIKE '%".$sName."%' ";
  }

  if($sDisc != '')
  {
      createCookie('sDisc', $sDisc);
      $where .= "AND (item_disc = '".$sDisc."' OR item_price = '".$sDisc."') ";
  }


  if($sPolicy != '')
  {
    createCookie('sPolicy', $sPolicy);
    $where .= "AND id_discount_policy IN(".getPolicyIn($sPolicy).") ";
  }

  if($isActive != 2)
  {
    createCookie('isActive', $isActive);
    $where .= "AND active = ".$isActive." ";
  }


  //echo $where;
  $where .= "ORDER BY code DESC";

  $paginator = new paginator();
  $get_rows = get_rows();
  $paginator->Per_page('tbl_discount_rule', $where, $get_rows);
  $paginator->display($get_rows, 'index.php?content=discount_rule');
  $qs = dbQuery("SELECT * FROM tbl_discount_rule ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>

 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped border-1">
       <thead>
         <tr>
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-15 text-center">เลขที่เงื่อนไข</th>
           <th class="width-35">เงื่อนไข</th>
           <th class="width-15 text-center">เลขที่นโยบาย</th>
           <th class="width-10 text-center">ส่วนลด</th>
           <th class="width-5 text-center">สถานะ</th>
           <th class=""></th>
         </tr>
       </thead>
       <tbody>
<?php if(dbNumRows($qs) > 0) : ?>
  <?php $no = 1; ?>
  <?php $rule = new discount_rule(); ?>
  <?php while($rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center no"><?php echo number($no); ?></td>
          <td class="middle text-center"><?php echo $rs->code; ?></td>
          <td class="middle"><?php echo $rs->name; ?></td>
          <td class="middle text-center"><?php echo $rule->getPolicyCode($rs->id); ?></td>
          <td class="middle text-center"><?php echo showItemDiscountLabel($rs->item_price, $rs->item_disc, $rs->item_disc_unit); ?></td>
          <td class="middle text-center"><?php echo isActived($rs->active); ?></td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="viewDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
      <?php if($edit) : ?>
            <button type="button" class="btn btn-xs btn-warning" onclick="goEdit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
      <?php endif; ?>
      <?php if($delete) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="getDelete('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
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


<script src="script/rule/rule_list.js"></script>
