<p class="help-block red">*** เมื่อเปิดบิลแล้ว จำนวนยืม = จำนวนที่มีการเปิดบิล (ซึ่งอาจน้อยกว่าจำนวนที่ยืม) หากยังไม่ถูกเปิดบิล จำนวนยืม = จำนวนที่ยืมตามเอกสาร (ยังไม่ได้รับของจริง)</p>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped border-1">
        <thead>
        	<tr class="font-size-12">
          	<th class="width-5 text-center">No.</th>
            <th class="width-5 text-center">รูปภาพ</th>
						<th class="width-35">สินค้า</th>
						<th class="width-10 text-center">ราคา</th>
						<th class="width-10 text-center">จำนวน</th>
						<th class="width-10 text-center">คืนแล้ว</th>
						<th class="width-10 text-center">คงเหลือ</th>
            <th class="width-15 text-right">มูลค่าคงเหลือ</th>
          </tr>
        </thead>
        <tbody id="detail-table">
<?php $detail = $order->getDetails($order->id); ?>
<?php if( dbNumRows($detail) > 0 ) : ?>
<?php 	$no = 1; 							       ?>
<?php 	$total_lend 		= 0;		     ?>
<?php   $total_returned = 0;				 ?>
<?php   $total_balance 	= 0;				 ?>
<?php   $total_amount 	= 0;         ?>
<?php	$image = new image();          ?>
<?php	while( $rs = dbFetchObject($detail) ) : ?>

<?php 	$lend_qty = $lend->getLendQty($order->id, $rs->id_product); 			//---	จำนวนที่ยืม(เปิดบิลแล้ว)  ?>
<?php 	$returned = $lend->getReturnedQty($order->id, $rs->id_product); 	//---	จำนวนที่คืนแล้ว ?>
<?php   $qty = $lend_qty > 0 ? $lend_qty : $rs->qty; 	//--- หากยังไม่เปิดบิลให้ใช้จำนวนที่สั่งมา ?>
<?php   $balance = $qty - $returned;  								//--- จำนวนคงเหลือ  ?>


			<tr class="font-size-10" id="row_<?php echo $rs->id; ?>">
      	<td class="middle text-center">
					<?php echo $no; ?>
				</td>

        <td class="middle text-center padding-0">
        	<img src="<?php echo $image->getProductImage($rs->id_product, 1); ?>" width="40px" height="40px"  />
        </td>

        <td class="middle">
					<?php echo $rs->product_code .' : '. $rs->product_name; ?>
				</td>

				<td class="middle text-center">
					<?php echo number($rs->price,2); ?>
				</td>

				<td class="middle text-center">
					<?php
					//---	จำนวนที่ยืม
					//---	หากเปิดบิลแล้วแสดงจำนวนที่เปิดบิล (tbl_order_lend_detail)
					//---	หากยังไม่เปิดบิลแสดงจำนวนที่สั่ง	(tbl_order_detail)
					echo number($qty);
					?>
				</td>

        <td class="middle text-center">
					<?php
					//---	รายการสินค้าที่คืนแล้ว
					echo number($returned);
					 ?>
				</td>

        <td class=" middle text-center">
					<?php
						echo number($balance);
					 ?>
        </td>

        <td class="middle text-right">
        <?php echo number($balance * $rs->price, 2); ?>
        </td>
			</tr>

<?php		$total_lend 		+= $qty;									?>
<?php   $total_returned += $returned;							?>
<?php   $total_balance	+= $balance;							?>
<?php 	$total_amount 	+= $balance * $rs->price;	?>
<?php		$no++; 																	  ?>
<?php 	endwhile; ?>
			<tr class="font-size-12">
        <td colspan="4" class="text-right"><b>รวม</b></td>
				<td class="text-center"><b><?php echo number($total_lend); ?></b></td>
				<td class="text-center"><b><?php echo number($total_returned); ?></b></td>
				<td class="text-center"><b><?php echo number($total_balance); ?></b></td>
				<td class="text-right"><b><?php echo number($total_amount,2); ?></b></td>
      </tr>
<?php else : ?>
			<tr>
        <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>

        </tbody>
        </table>
    </div>
</div>
<!---  End Order Detail --->


<!--- order detail template ------>
<script id="detail-table-template" type="text/x-handlebars-template">
{{#each this}}
	{{#if @last}}
        <tr>
        	<td colspan="6" class="text-right" ><b>จำนวนรวม</b></td>
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

            <td class="middle" id="transform-box-{{ id }}">
							{{ trasProduct }}
							<input type="hidden" id="transform-qty-{{ id }}" value="{{ trans_qty }}" />
						</td>

            <td class="middle text-right">
							<button type="button" class="btn btn-xs btn-success btn-block" onclick="addTransformProduct({{ id }})"><i class="fa fa-plus"></i> เชื่อมโยง</button>
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

<script src="script/order_transform/lend_detail.js?token=<?php echo date('Ymd'); ?>"></script>
