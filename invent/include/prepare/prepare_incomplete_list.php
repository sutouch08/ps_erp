<?php $showZone = getCookie('showZone') ? '' : 'hide'; ?>
<?php $showBtn  = getCookie('showZone') ? 'hide' : '';  ?>
<?php $checked  = getCookie('showZone') ? 'checked' : ''; ?>


<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr><td colspan="6" align="center">รายการรอจัด</td></tr>
        <tr>
          <th class="width-15 middle text-center">บาร์โค้ด</th>
          <th class="width-30 middle text-center">สินค้า</th>
          <th class="width-10 middle text-center">จำนวน</th>
          <th class="width-10 middle text-center">จัดแล้ว</th>
          <th class="width-10 middle text-center">คงเหลือ</th>
          <th class="text-right">
            <label><input type="checkbox" id="showZone" style="margin-right:10px;" <?php echo $checked; ?> />แสดงที่เก็บ</label>
          </th>
        </tr>
      </thead>
      <tbody id="incomplete-table">

<?php $qs = $order->getNotValidDetails($order->id);  ?>
<?php $bc = new barcode(); ?>
<?php $pp = new prepare(); ?>
<?php $row = dbNumRows($qs); ?>
<?php  if(dbNumRows($qs) > 0) : ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php   $product = new product($rs->id_product); ?>
<?php   if( $product->count_stock == 1) : ?>
<?php     $prepared = $pp->getPrepared($order->id, $rs->id_product); ?>
<?php     $stockZone = stockInZone($rs->id_product, $order->id_branch); ?>
    <tr class="font-size-12 incomplete" id="incomplete-<?php echo $rs->id_product; ?>">
      <td class="middle text-center"><?php echo $bc->getBarcode($rs->id_product); ?></td>
      <td class="middle"><?php echo $product->code .' : '.$product->name; ?></td>
      <td class="middle text-center" id="order-qty-<?php echo $rs->id_product; ?>"><?php echo number($rs->qty); ?></td>
      <td class="middle text-center" id="prepared-qty-<?php echo $rs->id_product; ?>"><?php echo number($prepared); ?></td>
      <td class="middle text-center" id="balance-qty-<?php echo $rs->id_product; ?>"><?php echo number($rs->qty - $prepared); ?></td>
      <td class="middle text-right">
        <button
          type="button"
          class="btn btn-default btn-xs btn-pop <?php echo $showBtn; ?>"
          data-container="body"
          data-toggle="popover"
          data-placement="left"
          data-trigger="focus"
          data-content="<?php echo $stockZone == "" ? 'ไม่พบสินค้าคงเหลือ': $stockZone; ?>"
          data-original-title=""
          title="">
          ที่เก็บ
        </button>
        <span class="zoneLabel <?php echo $showZone; ?>">
            <?php echo $stockZone == "" ? 'ไม่พบสินค้าคงเหลือ': $stockZone; ?>
        </span>
      </td>
    </tr>
<?php   endif; ?>
<?php endwhile; ?>
<?php
      $force = $row > 0 ? '' : 'hide';
      $close = $row > 0 ? 'hide' : '';
?>

    <tr>
      <td colspan="6" class="text-center">
        <div id="force-bar" class="<?php echo $force; ?>">
          <label style="margin-right:15px;">
            <input type="checkbox" id="force-close" style="margin-right:5px;" onchange="toggleForceClose()" />
            สินค้าไม่ครบ
          </label>
          <button type="button" class="btn btn-sm btn-danger hide" id="btn-force-close" onclick="forceClose()">
            <i class="fa fa-exclamation-triangle"></i>
            &nbsp; บังคับจบ
          </button>
        </div>


        <div id="close-bar" class="<?php echo $close; ?>">
          <button type="button" class="btn btn-sm btn-success" onclick="finishPrepare()">จัดเสร็จแล้ว</button>
        </div>

      </td>
    </tr>

<?php else : ?>

  <tr>
    <td colspan="6" class="text-center">
      <div id="close-bar">
        <button type="button" class="btn btn-sm btn-success" onclick="finishPrepare()">จัดเสร็จแล้ว</button>
      </div>
    </td>
  </tr>

<?php endif; ?>
      </tbody>
    </table>
  </div><!--/ col -->
</div><!--/ row-->
