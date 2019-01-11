<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr><td colspan="6" align="center">รายการที่ครบแล้ว</td></tr>
        <tr>
          <th class="width-15 middle text-center">บาร์โค้ด</th>
          <th class="width-30 middle text-center">สินค้า</th>
          <th class="width-10 middle text-center">จำนวน</th>
          <th class="width-10 middle text-center">จัดแล้ว</th>
          <th class="width-10 middle text-center">คงเหลือ</th>
          <th class="text-right">จัดจากโซน</th>
        </tr>
      </thead>
      <tbody id="complete-table">

<?php $qs = $order->getValidDetails($order->id);  ?>
<?php $bc = new barcode(); ?>
<?php $pp = new prepare(); ?>
<?php  if(dbNumRows($qs) > 0) : ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php   $product = new product($rs->id_product); ?>
<?php   if( $product->count_stock == 1) : ?>
<?php     $prepared = $pp->getPrepared($order->id, $rs->id_product); ?>
<?php     $stockZone = prepareFromZone($order->id, $rs->id_product); ?>
    <tr class="font-size-12">
      <td class="middle text-center"><?php echo $bc->getBarcode($rs->id_product); ?></td>
      <td class="middle"><?php echo $product->code .' : '.$product->name; ?></td>
      <td class="middle text-center"><?php echo number($rs->qty); ?></td>
      <td class="middle text-center"><?php echo number($prepared); ?></td>
      <td class="middle text-center"><?php echo number($rs->qty - $prepared); ?></td>
      <td class="middle text-right">
        <button
          type="button"
          class="btn btn-default btn-xs btn-pop <?php echo $showBtn; ?>"
          data-container="body"
          data-toggle="popover"
          data-placement="left"
          data-trigger="focus"
          data-content="<?php echo $stockZone == "" ? 'ไม่พบโซน': $stockZone; ?>"
          data-original-title=""
          title="">
          จากโซน
        </button>
        <span class="zoneLabel <?php echo $showZone; ?>">
            <?php echo $stockZone == "" ? 'ไม่พบโซน': $stockZone; ?>
        </span>
      </td>
    </tr>
<?php   endif; ?>
<?php endwhile; ?>
<?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>
