<?php $qs = $cs->getDetails($cs->id); ?>


<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-15 text-center">บาร์โค้ด</th>
          <th class="width-40 text-center">สินค้า</th>
          <th class="width-10 text-center">ราคา</th>
          <th class="width-10 text-center">ส่วนลด</th>
          <th class="width-10 text-center">จำนวน</th>
          <th class="width-10 text-center">มูลค่า</th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = 1; ?>
<?php   $totalQty = 0; ?>
<?php   $totalAmount = 0; ?>
<?php   $bc = new barcode(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $bc->getBarcode($rs->id_product); ?></td>
          <td class="middle"><?php echo limitText($rs->product_code.' : '.$rs->product_name, 120); ?></td>
          <td class="middle text-center"><?php echo number($rs->price, 2); ?></td>
          <td class="middle text-center"><?php echo $rs->discount; ?></td>
          <td class="middle text-center"><?php echo number($rs->qty); ?></td>
          <td class="middle text-right"><?php echo number($rs->total_amount,2); ?></td>
        </tr>
<?php    $no++; ?>
<?php    $totalQty += $rs->qty; ?>
<?php    $totalAmount += $rs->total_amount; ?>
<?php   endwhile; ?>
        <tr id="total-row">
          <td colspan="5" class="middle text-right"><strong>รวม</strong></td>
          <td id="total-qty" class="middle text-center"><?php echo number($totalQty); ?></td>
          <td id="total-amount" class="middle text-right"><?php echo number($totalAmount,2); ?></td>
        </tr>
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
