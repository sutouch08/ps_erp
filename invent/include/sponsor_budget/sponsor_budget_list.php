<div class="container">
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-credit-card"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php if( $add ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มผู้รับใหม่</button>
    <?php endif; ?>
    </p>
  </div>
</div>

<hr/>

<?php
//--  ค้นหาชื่อผู้รับ
$sName = getFilter('sName', 'sSponsorName', '');
$sYear = getFilter('sYear', 'sSponsorYear', '');

?>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-3">
    <label>ผู้รับ</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sName" name="sName" value="<?php echo $sName; ?>" placeholder="กรองตามชื่อผู้รับ" autofocus />
  </div>

  <div class="col-sm-2">
    <label>ปีงบประมาณ</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sYear" name="sYear" value="<?php echo $sYear; ?>" placeholder="กรองตามปีงบประมาณ" autofocus />
  </div>


<div class="col-sm-2">
  <label class="display-block not-show">apply</label>
  <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
</div>

<div class="col-sm-2">
  <label class="display-block not-show">reset</label>
  <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
</div>

<input type="hidden" name="sActive" id="sActive" value="<?php echo $sActive; ?>" />

</div>

</form>
<hr class="margin-top-10 margin-bottom-10"/>

<?php
  $where = "WHERE tbl_sponsor.id != 0 ";

  if( $sName != '')
  {
    createCookie('sSponsorName', $sName);
    $where .= "AND name LIKE '%".$sName."%' ";
  }

  if( $sYear != '')
  {
    createCookie('sSponsorYear', $sYear);
    $where .= "AND year = '".dbYear($sYear)."' ";
  }


  $where .= "ORDER BY name ASC";

  $table = "tbl_sponsor LEFT JOIN tbl_sponsor_budget ON tbl_sponsor.id_budget = tbl_sponsor_budget.id ";

  $paginator = new paginator();
  $get_rows  = get_rows();
  $paginator->Per_Page($table, $where, $get_rows);
  $paginator->display($get_rows, 'index.php?content=sponsor');

  $qs = dbQuery("SELECT tbl_sponsor.* FROM ".$table . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>

 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped border-1">
       <thead>
         <tr class="font-size-12">
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-10 text-center">รหัส</th>
           <th class="width-25">ผู้รับ</th>
           <th class="width-10 text-center">ปีงบประมาณ</th>
           <th class="width-10 text-center">งบประมาณ</th>
           <th class="width-10 text-center">ใช้ไป</th>
           <th class="width-10 text-center">คงเหลือ</th>
           <th class=""></th>
         </tr>
       </thead>
       <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
  <?php $no = row_no(); ?>
  <?php $cus = new customer(); ?>
  <?php while( $rs = dbFetchObject($qs)) : ?>
  <?php   $bd = new sponsor_budget($rs->id_budget); ?>
      <tr class="font-size-12">
        <td class="middle text-center"><?php echo $no; ?></td>
        <td class="middle text-center"><?php echo $cus->getCode($rs->id_customer); ?></td>
        <td class="middle"><?php echo $rs->name; ?></td>
        <td class="middle text-center"><?php echo $bd->year; ?></td>
        <td class="middle text-center"><?php echo number($bd->budget, 2); ?></td>
        <td class="middle text-center"><?php echo number($bd->used, 2); ?></td>
        <td class="middle text-center"><?php echo number($bd->balance, 2); ?></td>
        <td class="middle text-right">
          <button type="button" class="btn btn-xs btn-info" onclick="getDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
          <?php if( $edit ) : ?>
          <button type="button" class="btn btn-xs btn-warning" onclick="goEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
        <?php endif; ?>
          <?php if( $delete ) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="removeSponsor(<?php echo $rs->id; ?>, '<?php echo $rs->id_customer; ?>', '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></butoon>
          <?php endif; ?>
        </td>
      </tr>

  <?php $no++; ?>
<?php endwhile; ?>

<?php else : ?>
  <tr>
    <td colspan="7" class="middle text-center"><h4>ไม่พบรายการ</h4></td>
  </tr>
<?php endif; ?>

       </tbody>
     </table>
   </div>
 </div>

</div><!-- Container -->

<script src="script/sponsor_budget/sponsor_budget_list.js"></script>
