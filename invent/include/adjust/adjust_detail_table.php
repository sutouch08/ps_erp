<?php
  $qs = $cs->getDetails($id);
?>

<div class="row">
  <div class="col-sm-12">
    <p class="pull-right top-p">
      <span style="margin-right:30px;"><i class="fa fa-check green"></i> = ปรับยอดแล้ว</span>
      <span><i class="fa fa-times red"></i> = ยังไม่ปรับยอด</span>
    </p>
  </div>
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-10 text-center">บาร์โค้ด</th>
          <th class="width-20">รหัสสินค้า</th>
          <th class="width-25">สินค้า</th>
          <th class="width-20 text-center">โซน</th>
          <th class="width-8 text-center">เพิ่ม</th>
          <th class="width-8 text-center">ลด</th>
          <th class="width-5 text-center">สถานะ</th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php   $no = 1;    ?>
<?php   $pd = new product(); ?>
<?php   $bc = new barcode(); ?>
<?php   $zone = new zone(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php    $pdCode = $pd->getCode($rs->id_product); ?>
      <tr class="font-size-12 rox" id="row-<?php echo $rs->id; ?>">
        <td class="middle text-center no">
          <?php echo $no; ?>
        </td>
        <td class="middle text-center">
          <?php echo $bc->getBarcode($rs->id_product); ?>
        </td>
        <td class="middle">
          <?php echo $pdCode; ?>
        </td>
        <td class="middle">
          <?php echo $pd->getName($rs->id_product); ?>
        </td>
        <td class="middle text-center">
          <?php echo $zone->getName($rs->id_zone); ?>
        </td>
        <td class="middle text-center" id="qty-up-<?php echo $rs->id; ?>">
          <?php echo $rs->qty > 0 ? number($rs->qty) : 0 ; ?>
        </td>
        <td class="middle text-center" id="qty-down-<?php echo $rs->id; ?>">
          <?php echo $rs->qty < 0 ? number($rs->qty * -1) : 0 ; ?>
        </td>
        <td class="middle text-center">
          <?php echo isActived($rs->valid); ?>
        </td>

      </tr>
<?php     $no++; ?>
<?php   endwhile; ?>
<?php else : ?>
      <tr>
        <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
