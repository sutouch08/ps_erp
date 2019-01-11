<?php $qs = $cs->getDetails($cs->id); ?>
<?php if(dbNumRows($qs) > 0) : ?>
<?php  $no = 1; ?>
<?php  $sumStock = 0; ?>
<?php  $sumCount = 0; ?>
<?php  $sumDiff = 0; ?>
<?php  $bc = new barcode(); ?>
<hr/>
<style>
  #detail-table > tr:first-child {
    color:blue;
  }
</style>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">No.</th>
          <th class="width-10 text-center">บาร์โค้ด</th>
          <th class="">รหัสสินค้า</th>
          <th class="width-10 text-center">ยอดในโซน</th>
          <th class="width-10 text-center">ยอดตรวจนับ</th>
          <th class="width-10 text-center">ยอดต่าง</th>
          <th class="width-10 text-center"></th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php while($rs = dbFetchObject($qs)) : ?>
<?php  $barcode = $bc->getBarcode($rs->id_product); ?>
<?php  $diff = $rs->stock_qty - $rs->qty; ?>
<?php  $hide = $rs->qty == 0 ? 'hide' : ''; ?>
        <tr class="font-size-12" id="row-<?php echo $barcode; ?>">
          <td class="middle text-center row-no"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $barcode; ?></td>
          <td class="middle"><?php echo $rs->product_code; ?></td>
          <td class="middle text-center">
            <span class="stock-qty" id="stock-qty-<?php echo $barcode; ?>">
            <?php echo $rs->stock_qty; ?>
            </span>
          </td>
          <td class="middle text-center">
            <span class="checked-qty checked-<?php echo $rs->id_product; ?>" id="check-qty-<?php echo $barcode; ?>">
            <?php echo $rs->qty; ?>
            </span>
          </td>
          <td class="middle text-center">
            <span class="diff-qty diff-<?php echo $rs->id_product; ?>" id="diff-qty-<?php echo $barcode; ?>">
            <?php echo $diff; ?>
            </span>
          </td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info <?php echo $hide; ?>" id="btn-<?php echo $barcode; ?>" onclick="showDetail('<?php echo $rs->id_product; ?>')">
              <i class="fa fa-eye"></i>
            </button>
          </td>
        </tr>
<?php  $no++; ?>
<?php  $sumStock += $rs->stock_qty; ?>
<?php  $sumCount += $rs->qty; ?>
<?php  $sumDiff  += $diff; ?>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<input type="hidden" id="sumStock" value="<?php echo $sumStock; ?>" />
<input type="hidden" id="sumCount" value="<?php echo $sumCount; ?>" />
<input type="hidden" id="sumDiff" value="<?php echo $sumDiff; ?>" />
<?php else : ?>
  <center><h4>ไม่พบรายการ</h4></center>
<?php endif; ?>

<div class="modal fade" id="checked-detail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:400px;">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			 </div>
			 <div class="modal-body" id="modal_body">

       </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			 </div>
		</div>
	</div>
</div>


<script id="box-detail-template" type="text/handlebarsTemplate">
<div class="row">
  <div class="col-sm-12">
    <table class="table table-bordered">
      <tr>
        <td colspan="3" class="text-center">{{pdCode}}</td>
      </tr>
      <tr>
        <td class="width-50 text-center">กล่อง</td>
        <td class="width-30 text-center">จำนวน</td>
        <td class="width-20 text-right"></td>
      </tr>
      {{#each rows}}
        {{#if nodata}}
          <tr><td colspan="2" class="text-center">ไม่มีข้อมูล</td></tr>
        {{else}}
         <tr id="row-{{id_box}}-{{id_pd}}">
           <td class="middle text-center">{{ box }}</td>
           <td class="middle text-center">{{ qty }}</td>
           <td class="text-right">
      <?php if(($add OR $edit) && $cs->valid == 0 && $cs->status == 0) : ?>
             <button type="button" class="btn btn-xs btn-danger" onclick="removeCheckedItem('{{id_box}}', '{{id_pd}}', {{qty}},'{{ box }}')">
               <i class="fa fa-trash"></i>
             </button>
      <?php endif; ?>
           </td>
         </tr>
         {{/if}}
      {{/each}}
    </table>
  </div>
</div>
</script>


<script src="script/consign_check/consign_check_detail.js"></script>
