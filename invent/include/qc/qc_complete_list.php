<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr><th colspan="6" class="text-center">รายการที่ครบแล้ว</th></tr>
        <tr class="font-size-12">
          <th class="width-15">บาร์โค้ด</th>
          <th class="width-50">สินค้า</th>
          <th class="width-8 text-center">จำนวนที่สั่ง</th>
          <th class="width-8 text-center">จำนวนที่จัด</th>
          <th class="width-8 text-center">ตรวจแล้ว</th>
          <th class="text-right">จากโซน</th>
        </tr>
      </thead>
      <tbody id="complete-table">

<?php  $qs = $qc->getCompleteList($order->id);  ?>
<?php  $bc = new barcode(); ?>
<?php  if( dbNumRows($qs) > 0) : ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php   $barcode = $bc->getBarcode($rs->id_product); ?>

      <tr class="font-size-12" id="row-<?php echo $rs->id_product; ?>">
        <td class="middle text-center"><?php echo $barcode; ?></td>
        <td class="middle"><?php echo $rs->product_code.' : '.$rs->product_name; ?></td>
        <td class="middle text-center"><?php echo number($rs->order_qty); ?></td>
        <td class="middle text-center" id="prepared-<?php echo $rs->id_product; ?>"><?php echo number($rs->prepared); ?></td>
        <td class="middle text-center" id="qc-<?php echo $rs->id_product; ?>"><?php echo number($rs->qc); ?></td>
        <td class="middle text-right">
          <?php if(($rs->qc > $rs->prepared OR $rs->qc > $rs->order_qty) && $delete) : ?>
            <button type="button" class="btn btn-xs btn-warning" onclick="showEditOption('<?php echo $order->id; ?>', '<?php echo $rs->id_product; ?>', '<?php echo $rs->product_code; ?>')">
              <i class="fa fa-pencil"></i> แก้ไข
            </button>

          <?php endif; ?>
          <button
            type="button"
            class="btn btn-default btn-xs btn-pop"
            data-container="body"
            data-toggle="popover"
            data-placement="left"
            data-trigger="focus"
            data-content="<?php echo prepareFromZone($order->id, $rs->id_product); ?>"
            data-original-title=""
            title="">
            ที่เก็บ
          </button>
          <input type="hidden" id="id-<?php echo $rs->id_product; ?>" value="<?php echo $rs->id_product; ?>" />
        </td>
      </tr>

<?php   endwhile; ?>
<?php endif; ?>

      </tbody>
    </table>
  </div>
</div>
