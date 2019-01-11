<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1 qc-table">
      <thead>
        <tr class="font-size-12">
          <th class="width-15 text-center">บาร์โค้ด</th>
          <th class="width-50">สินค้า</th>
          <th class="width-8 text-center">จำนวนที่สั่ง</th>
          <th class="width-8 text-center">จำนวนที่จัด</th>
          <th class="width-8 text-center">ตรวจแล้ว</th>
          <th class="text-right">จากโซน</th>
        </tr>
      </thead>
      <tbody id="incomplete-table">

<?php  $qs = $qc->getIncompleteList($order->id);  ?>
<?php  $bc = new barcode(); ?>
<?php   $show_close = dbNumRows($qs) > 0 ? 'hide' : ''; ?>
<?php   $show_force = dbNumRows($qs) > 0 ? '' : 'hide'; ?>
<?php  if( dbNumRows($qs) > 0) : ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php   $barcode = $bc->getBarcode($rs->id_product); ?>

      <tr class="font-size-12 incomplete" id="row-<?php echo $rs->id_product; ?>">
        <td class="middle text-center td"><?php echo $barcode; ?></td>
        <td class="middle td"><?php echo $rs->product_code.' : '.$rs->product_name; ?></td>
        <td class="middle text-center td"><?php echo number($rs->order_qty); ?></td>
        <td class="middle text-center td" id="prepared-<?php echo $rs->id_product; ?>"> <?php echo number($rs->prepared); ?></td>
        <td class="middle text-center td" id="qc-<?php echo $rs->id_product; ?>"><?php echo number($rs->qc); ?></td>
        <td class="middle text-right td">
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
          <input type="hidden" class="hidden-qc" id="<?php echo $rs->id_product; ?>" value="0"/>
          <input type="hidden" id="id-<?php echo $rs->id_product; ?>" value="<?php echo $rs->id_product; ?>" />
        </td>
      </tr>

<?php   endwhile; ?>

<?php else : ?>
      <tr><td colspan="6" class="text-center"><h4>ไม่พบรายการ</td></tr>
<?php endif; ?>
        <tr>
          <td colspan="6" class="text-center">
            <div id="force-bar" class="<?php echo $show_force; ?>">
              <label style="margin-right:10px;">
                <input type="checkbox" class="close-order" style="margin-right:10px;" id="chk-force-close" <?php echo $active; ?> /> สินค้าไม่ครบ
              </label>
              <button type="button" class="btn btn-sm btn-success hide close-order" id="btn-force-close" onclick="forceClose()" <?php echo $active; ?>>
                บังคับจบ
              </button>
            </div>
            <div class="<?php echo $show_close; ?>" id="close-bar">
              <button type="button" class="btn btn-sm btn-success close-order" id="btn-close" onclick="closeOrder()" <?php echo $active; ?>>
                ตรวจเสร็จแล้ว
              </button>
            </div>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>
