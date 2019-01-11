<?php
$id  = $_GET['id_return_lend'];
$cs  = new return_lend($id);
$cus = new customer($cs->id_customer);
$emp = new employee($cs->id_employee);
$zone = new zone();

 ?>

 <?php if( $cs->isCancle == 1 ) : ?>
 	<div style="width:40%; position:absolute; left:30%;  top:150px;color:red; text-align:center; z-index:10000; opacity:0.2">
     	<span style="font-size:150px;">ยกเลิก</span>
     </div>
 <?php endif; ?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>

  <div class="col-sm-6">
    <p class="pull-right top-p">
    <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    <?php if($cs->isCancle == 0) : ?>
    <button type="button" class="btn btn-sm btn-info" onclick="exportReturnLend()"><i class="fa fa-send"></i> ส่งข้อมูลไป Formula</button>
    <?php endif; ?>
    <button type="button" class="btn btn-sm btn-default" onclick="printReturnLend()"><i class="fa fa-print"></i> พิมพ์</button>
    </p>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" disabled value="<?php echo $cs->reference; ?>" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" disabled value="<?php echo thaiDate($cs->date_add) ?>" />
  </div>
  <div class="col-sm-3 col-3-harf padding-5">
    <label>ผู้ยืม</label>
    <input type="text" class="form-control input-sm text-center" disabled value="<?php echo $cus->code.' : '.$cus->name; ?>" />
  </div>
  <div class="col-sm-3 col-3-harf padding-5">
    <label>ผู้รับคืน</label>
    <input type="text" class="form-control input-sm text-center" disabled value="<?php echo $emp->first_name.' '.$emp->last_name; ?>" />
  </div>
  <div class="col-sm-2 padding-5 last">
    <label>ใบยืมสินค้า</label>
    <input type="text" class="form-control input-sm text-center" disabled value="<?php echo $cs->order_code; ?>" />
  </div>

  <div class="col-sm-12">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" disabled value="<?php echo $cs->remark; ?>" />
  </div>
</div>
<input type="hidden" id="id_return_lend" value="<?php echo $cs->id; ?>" />
<hr class="margin-top-10"/>

<?php $qs = $cs->getDetails($cs->id);?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">No.</th>
          <th class="width-45">สินค้า</th>
          <th class="text-center">เข้าโซน</th>
          <th class="width-10 text-center">จำนวน</th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php if(dbNumRows($qs) > 0) : ?>
<?php   $no = 1; ?>
<?php   $totalQty = 0; ?>
<?php   $pd = new product(); ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
<?php   $pd->getData($rs->id_product); ?>
        <tr class="font-size-12">
          <td class="text-center"><?php echo $no; ?></td>
          <td class=""><?php echo limitText($pd->code.' : '.$pd->name, 80); ?></td>
          <td class="text-center"><?php echo $zone->getName($rs->to_zone); ?></td>
          <td class="text-center"><?php echo number($rs->qty); ?></td>
        </tr>
<?php   $no++; ?>
<?php   $totalQty += $rs->qty; ?>
<?php endwhile; ?>
        <tr>
          <td colspan="3" class="text-right">รวม</td>
          <td class="text-center"><?php echo number($totalQty); ?></td>
        </tr>
<?php endif;  ?>

      </tbody>
    </table>
  </div>
</div>
<script src="script/print/print_return_lend.js"></script>
