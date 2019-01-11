
<div class="row">
	<div class="col-sm-12">
		<span class="red" style="padding-top:10px;">** สามารถเชื่อมโยงสินค้าได้เฉพาะสถานะ "รอชำระเงิน" เท่านั้น</span>
    	<table class="table table-striped border-1">
        <thead>
        	<tr class="font-size-12">
          	<th class="width-5 text-center">No.</th>
            <th class="width-5 text-center"></th>
            <th class="width-15">รหัสสินค้า</th>
						<th class="width-20">ชื่อสินค้า</th>
						<th class="width-10 text-center">จำนวน</th>
						<th class="width-10 text-center">ไม่คืน</th>
						<th class="width-25">สินค้าแปรสภาพ</th>
						<th class="width-10 text-center"></th>
            <th class="width-10 text-center"></th>
          </tr>
        </thead>
        <tbody id="detail-table">
<?php $detail = $order->getDetails($order->id); ?>
<?php if( dbNumRows($detail) > 0 ) : ?>
<?php 	$no = 1; 							?>
<?php 	$total_qty = 0;		?>
<?php	$image = new image(); ?>
<?php	while( $rs = dbFetchObject($detail) ) : ?>
<?php 	$hasTransformProduct = $transform->hasTransformProduct($rs->id); ?>
<?php 	$checked = $hasTransformProduct === FALSE ? 'checked' : ''; ?>
			<tr class="font-size-10" id="row_<?php echo $rs->id; ?>">
      	<td class="middle text-center">
					<?php echo $no; ?>
				</td>

        <td class="middle text-center padding-0">
        	<img src="<?php echo $image->getProductImage($rs->id_product, 1); ?>" width="40px" height="40px"  />
        </td>

        <td class="middle">
					<?php echo $rs->product_code; ?>
				</td>

				<td class="middle">
					<?php echo $rs->product_name; ?>
				</td>

				<td class="middle text-center qty" id="qty-<?php echo $rs->id; ?>">
					<?php echo number($rs->qty); ?>
				</td>


				<td class="middle text-center">
				<?php if( $order->isExpire == 0) : ?>
					<input type="checkbox" class="not-return" id="chk-<?php echo $rs->id; ?>" onchange="toggleReturn(<?php echo $rs->id; ?>)" <?php echo $checked; ?> />
				<?php else : ?>
					<?php echo $hasTransformProduct === TRUE ? '' : isActived(1); ?>
				<?php endif; ?>
				</td>


        <td class="middle" id="transform-box-<?php echo $rs->id; ?>">
					<?php
						//---	รายการสินค้าที่เชื่อมโยงแล้ว
						echo getTransformProducts($rs->id, $order->state, $order->isExpire);
					 ?>
					<!--- ยอดรวมของสินค้าที่เชื่อมโยงแล้ว -->
					<input type="hidden" id="transform-qty-<?php echo $rs->id; ?>" value="<?php echo $transform->getSumTransformProductQty($rs->id); ?>" />
				</td>

        <td class="text-center" id="connect-box-<?php echo $rs->id; ?>">
				<?php if( $order->isExpire == 0 && $hasTransformProduct === TRUE ) : ?>
					<button type="button" class="btn btn-xs btn-success btn-block connect" id="btn-connect-<?php echo $rs->id; ?>" onclick="addTransformProduct(<?php echo $rs->id; ?>,'<?php echo $rs->id_product; ?>')"><i class="fa fa-plus"></i> เชื่อมโยง</button>
				<?php endif; ?>
        </td>

        <td class="middle text-right">
        <?php if( $order->isExpire == 0 && ($edit OR $add) && $order->state < 3 ) : ?>
        	<button type="button" class="btn btn-xs btn-danger" onclick="removeDetail(<?php echo $rs->id; ?>, '<?php echo $rs->product_code; ?>')"><i class="fa fa-trash"></i></button>
        <?php endif; ?>
        </td>
			</tr>

<?php	$total_qty += $rs->qty;	?>
<?php		$no++; ?>
<?php 	endwhile; ?>
			<tr class="font-size-12">
        <td colspan="7" class="text-right"><b>จำนวนรวม</b></td>
        <td class="text-right"><b><?php echo number_format($total_qty); ?></b></td>
        <td class="text-center"><b>Pcs.</b></td>
      </tr>
<?php else : ?>
			<tr>
        <td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>

        </tbody>
        </table>
    </div>
</div>
<!---  End Order Detail --->

<div class="modal fade" id="transform-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:500px;">
		<div class="modal-content">
  		<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" >เพิ่มการเชื่อมโยง</h4>
        <input type="hidden" id="id_order_detail" value="" />
				<input type="hidden" id="detail-qty" value="" />
				<input type="hidden" id="id_product" value="" />
				<input type="hidden" id="from_product" value="" />
			 </div>
			 <div class="modal-body">
				 <div class="row">
				 	<div class="col-sm-3">
				 		<label>จำนวน</label>
						<input type="text" class="form-control input-sm text-center" id="trans-qty" value="" />
						<span class="help-block red not-show" id="qty-error">error</span>
				 	</div>
					<div class="col-sm-9">
						<label>สินค้าแปรสภาพ</label>
						<input type="text" class="form-control input-sm" id="trans-product" value="" />
						<span class="help-block red not-show" id="product-error">error</span>
					</div>
					<div class="col-sm-12">

					</div>
				 </div>

			 </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="addToTransform()" >เชื่อมโยง</button>
			 </div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:500px;">
		<div class="modal-content">
  		<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" >แก้ไขการเชื่อมโยง</h4>
        <input type="hidden" id="id-order-detail" value="" />
				<input type="hidden" id="max-qty" value="" />
				<input type="hidden" id="id-product" value="" />
			 </div>
			 <div class="modal-body">
				 <div class="row">
				 	<div class="col-sm-3">
				 		<label>จำนวน</label>
						<input type="number" class="form-control input-sm text-center" id="edit-trans-qty" value="" />
						<span class="help-block red not-show" id="edit-qty-error">error</span>
				 	</div>
					<div class="col-sm-9">
						<label>สินค้าแปรสภาพ</label>
						<input type="text" class="form-control input-sm" id="tr-product" value="" disabled />
					</div>
					<div class="col-sm-12">

					</div>
				 </div>

			 </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="updateTransformQty()" >เชื่อมโยง</button>
			 </div>
		</div>
	</div>
</div>


<!--- order detail template ------>
<script id="detail-table-template" type="text/x-handlebars-template">
{{#each this}}
	{{#if @last}}
        <tr>
        	<td colspan="7" class="text-right" ><b>จำนวนรวม</b></td>
          <td class="text-right"><b>{{ total_qty }}</b></td>
          <td class="text-center"><b>Pcs.</b></td>
        </tr>
	{{else}}
        <tr class="font-size-10" id="row_{{ id }}">
            <td class="middle text-center">{{ no }}</td>
            <td class="middle text-center padding-0">
            	<img src="{{ imageLink }}" width="40px" height="40px"  />
            </td>
            <td class="middle">{{ productCode }}</td>

            <td class="middle">{{ productName }}</td>

            <td class="middle text-center qty" id="qty-{{ id }}">{{ qty }}</td>

						<td class="middle text-center">
							<input type="checkbox" class="not-return" id="chk-{{ id }}" onchange="toggleReturn({{ id }})" {{checkbox}} />
						</td>

            <td class="middle" id="transform-box-{{ id }}">
							{{{ transProduct }}}
							<input type="hidden" id="transform-qty-{{ id }}" value="{{ trans_qty }}" />
						</td>

            <td class="middle text-right" id="connect-box-{{ id }}">
							{{#if button}}
							<button type="button" class="btn btn-xs btn-success btn-block connect" id="btn-connect-{{ id }}" onclick="addTransformProduct({{ id }})"><i class="fa fa-plus"></i> เชื่อมโยง</button>
							{{/if}}
						</td>

            <td class="middle text-right">
            <?php if( $edit OR $add ) : ?>
            	<button type="button" class="btn btn-xs btn-danger" onclick="removeDetail({{ id }}, '{{ productCode }}')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
            </td>
        </tr>
	{{/if}}
{{/each}}
</script>

<script id="nodata-template" type="text/x-handlebars-template">
	<tr>
          <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
    </tr>
</script>

<script src="script/order_transform/transform_detail.js?token=<?php echo date('Ymd'); ?>"></script>
