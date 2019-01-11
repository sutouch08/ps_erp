<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check"></i> <?php echo $pageTitle; ?></h4>
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
//--- reference
$sCode = getFilter('sCode', 'sCheckCode', '');

//--- customer name
$sCus  = getFilter('sCus', 'sCheckCus', '');

//--- zone name
$sZone = getFilter('sZone', 'sCheckZone', '');

//--- document date from
$fromDate = getFilter('fromDate', 'fromDate', '');

//--- document date end
$toDate = getFilter('toDate', 'toDate', '');

//--- is document saved ?
$sStatus = getFilter('sStatus', 'sCheckStatus', '');

//--- is document already link to consign order or not
$sValid  = getFilter('sValid', 'sCheckValid', '');

//----  document has save and not save add class to btn
$btn_s_all = $sStatus == '' ? 'btn-primary' : '';

//--- document has saved only add class to btn
$btn_s_saved = $sStatus == 1 ? 'btn-primary' : '';

//--- document has not saved only add class to btn
$btn_s_not_saved = $sStatus == 0 ? 'btn-primary' : '';

//--- add class to btn  if selected option (all)
$btn_v_all = $sValid == '' ? 'btn-primary' : '';

//--- add class to btn if selected option (valid)
$btn_v_valid = $sValid == '' ? 'btn-primary' : '';

//--- add class to btn if selected option (not valid)
$btn_v_not_valid = $sValid == '' ? 'btn-primary' : '';

 ?>
 <form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label class="display-block">เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">โซน</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sZone" id="sZone" value="<?php echo $sZone; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">submit</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"<i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
</form>

<hr class="margin-top-15"/>

<?php
createCookie('sCheckCode', $sCode);
createCookie('sCheckCus', $sCus);
createCookie('sCheckZone', $sZone);
createCookie('fromDate', $fromDate);
createCookie('toDate', $toDate);

$where = "WHERE id != 0 ";

if( $sCode != '')
{
  $where .= "AND reference LIKE '%".$sCode."%' ";
}


if( $sCus != '')
{
  $where .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}


if( $sZone != '')
{
  $where .= "AND id_zone IN(".getZoneIn($sZone).") ";
}


if( $fromDate != '' && $toDate != '')
{
  $where .= "AND date_add >= '".fromDate($fromDate)."' ";
  $where .= "AND date_add <= '".toDate($toDate)."' ";
}

$where .= "ORDER BY date_add DESC";

$paginator = new paginator();
$get_rows = get_rows();
$paginator->Per_Page('tbl_consign_check', $where, $get_rows);


$qs = dbQuery("SELECT * FROM tbl_consign_check ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>

 <div class="row">
   <div class="col-sm-6 padding-5 first">
     <?php $paginator->display($get_rows, 'index.php?content=consign_check'); ?>
   </div>
   <div class="col-sm-6 padding-5 last" style="padding-top:25px;">
     <p class="pull-right top-p">
       <span class="green font-size-">OK</span><span class="margin-right-15"> = ตัดยอดฝากขายแล้ว</span>
       <span class="blue">NC</span><span class="margin-right-15"> = ยังไม่ตัดยอดฝากขาย</span>
       <span class="red">NS</span><span class="margin-right-15"> = ยังไม่บันทึก</span>
       <span class="red">CN</span><span class="margin-right-15"> = ยกเลิก</span>
     </p>
   </div>
 </div>
 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped table-bordered">
       <thead>
         <tr class="font-size-12">
           <th class="width-10 text-center">วันที่</th>
           <th class="width-15 text-center">เลขที่เอกสาร</th>
           <th class="width-30">ลูกค้า</th>
           <th class="width-30">โซน</th>
           <th class="width-5 text-center">สถานะ</th>
           <th></th>
         </tr>
       </thead>
       <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php   $zone = new zone(); ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center">
            <?php echo thaiDate($rs->date_add); ?>
          </td>
          <td class="middle text-center">
            <?php echo $rs->reference; ?>
          </td>
          <td class="middle">
            <?php echo customerName($rs->id_customer); ?>
          </td>
          <td class="middle">
            <?php echo $zone->getName($rs->id_zone); ?>
          </td>
          <td class="middle text-center" id="xLabel-<?php echo $rs->id; ?>">
            <?php if($rs->valid == 1 ) : ?>
              <span class="green">OK</span>
            <?php else : ?>
              <?php if($rs->status == 1) : ?>
                <span class="blue">NC</span>
              <?php elseif($rs->status == 2) : ?>
                <span class="red">CN</span>
              <?php else : ?>
                <span class="red">NS</span>
              <?php endif; ?>
            <?php endif; ?>
          </td>
          <td class="middle text-right">
          <?php if($rs->status != 2) :  //--- ถ้าสถานะไม่ใ่ช่ยกเลิก ?>
            <button type="button" class="btn btn-xs btn-info" title="รายละเอียด" onclick="goDetail(<?php echo $rs->id;?>)"><i class="fa fa-eye"></i></button>
          <?php if( ($edit && $rs->valid == 0 && $rs->status == 0) OR ($delete && $rs->valid == 0) ): ?>
            <button type="button" class="btn btn-xs btn-warning" title="แก้ไข" id="btn-edit-<?php echo $rs->id; ?>" onclick="goAdd(<?php echo $rs->id;?>)"><i class="fa fa-pencil"></i></button>
          <?php endif; ?>

          <?php if( $delete && $rs->valid == 0 ): ?>
            <button type="button" class="btn btn-xs btn-danger" title="ลบ" id="btn-delete-<?php echo $rs->id; ?>" onclick="goDelete(<?php echo $rs->id;?>, '<?php echo $rs->reference; ?>')">
              <i class="fa fa-trash"></i>
            </button>
          <?php endif; ?>
        <?php endif; //--- end if status != 2 ?>
          </td>
        </tr>
<?php   endwhile; ?>

<?php else : ?>

<?php endif; ?>
       </tbody>

     </table>
   </div>
 </div>
 <script src="script/consign_check/consign_check_list.js"></script>
