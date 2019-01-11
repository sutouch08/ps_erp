<?php
  $qs = $cs->getDetails($cs->reference);
 ?>

 <div class="row">
   <div class="col-sm-12">
     <form id="receiveForm">
       <input type="text" class="hide" name="xxx" />
     <table class="table table-striped table-bordered">
       <thead>
         <tr>
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-15 text-center">บาร์โค้ด</th>
           <th class="">สินค้า</th>
           <th class="width-10 text-center">จำนวนที่คืน</th>
           <th class="width-10 text-center">จำนวนที่รับ</th>
         </tr>
       </thead>
       <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = 1; ?>
<?php   $bc = new barcode(); ?>
<?php   $totalQty = 0; ?>
<?php   $totalReceived = 0; ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php   $barcode = $bc->getBarcode($rs->id_product); ?>
        <tr>
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $barcode; ?></td>
          <td class="middle"><?php echo $rs->product_code; ?></td>
          <td class="middle text-center" id="qty-label-<?php echo $rs->id_product; ?>"><?php echo number($rs->qty); ?></td>
          <td class="middle text-center">
      <?php if( $rs->valid == 0) : ?>
            <input type="number" class="form-control input-sm text-center qty qty-<?php echo $no; ?>" index="<?php echo $no; ?>" name="qty[<?php echo $rs->id_product; ?>]" id="qty-<?php echo $rs->id_product; ?>" value="" />
            <?php
            $qm = $bc->getBarcodes($rs->id_product);
            while( $rm = dbFetchObject($qm))
            {
              echo '<input type="hidden" class="'.$rm->barcode.'" id="'.$rm->id_product.'" value="'.$rm->unit_qty.'"/>';
            }
            ?>
      <?php else : ?>
        <?php echo number($rs->received); ?>
      <?php endif;?>
          </td>
        </tr>
<?php     $no++; ?>
<?php     $totalQty += $rs->qty; ?>
<?php     $totalReceived += $rs->received; ?>
<?php   endwhile; ?>
        <tr>
          <td colspan="3" class="text-right">รวม</td>
          <td class="middle text-center"><?php echo number($totalQty); ?></td>
          <td class="middle text-center" id="totalReceived"><?php echo number($totalReceived); ?></td>
        </tr>
<?php else: ?>
        <tr>
          <td colspan="4" class="text-center">
            <h4>ไม่พบรายการ</h4>
          </td>
        </tr>
<?php endif; ?>
       </tbody>
     </table>
   </form>
   </div>
 </div>
