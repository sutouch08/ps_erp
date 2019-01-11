<div class="row">
	<div class="col-sm-12 hide" id="zone-table">
    <form id="productForm">
    	<table class="table table-striped table-bordered">
      	<thead>
					<tr>
						<th colspan="6" class="text-center">
							<h4 class="title" id="zoneName"></h4>
						</th>
					</tr>
        	<tr>
          	<th colspan="6">
							<div class="col-sm-6">
              <?php if( $delete ) : ?>
              	<label class="margin-right-15px padding-10" id="underZeroLabel">
                <input type="checkbox" name="allowUnderZeroAll" id="allowUnderZeroAll" value="1" style="margin-right:5px;" />ติดลบได้ทั้งหมด</label>
              <?php endif; ?>
              	<button type="button" class="btn btn-sm btn-warning" onclick="addAllToTransfer()">ย้ายรายการทั้งหมด</button>
              </div>
              <div class="col-sm-6">
                <p class="pull-right top-p">
                  <button type="button" class="btn btn-sm btn-primary" onclick="addToTransfer()">ย้ายรายการที่เลือก</button>
                </p>
              </div>
            </th>
          </tr>

          <tr>
          	<th class="width-10 text-center">ลำดับ</th>
            <th class="width-20 text-center">บาร์โค้ด</th>
            <th class="width-40 text-center">สินค้า</th>
            <th class="width-10 text-center">จำนวน</th>
            <th class="width-10 text-center">ย้ายออก</th>
            <th class="width-10 text-center">ตัวเลือก</th>
          </tr>
          </thead>

          <tbody id="zone-list"> </tbody>

        </table>
      </form>
    </div>



	<div class="col-sm-12" id="transfer-table">
  	<table class="table table-striped table-bordered">
    	<thead>
      	<tr>
        	<th colspan="7" class="text-center">รายการโอนย้าย</th>
        </tr>

      	<tr>
        	<th class="width-5 text-center">ลำดับ</th>
          <th class="width-15 text-center">บาร์โค้ด</th>
          <th class="width-30 text-center">สินค้า</th>
          <th class="width-15 text-center">ต้นทาง</th>
          <th class="width-15 text-center">ปลายทาง</th>
          <th class="width-10 text-center">จำนวน</th>
          <th class="width-10 text-center">การกระทำ</th>
        </tr>
      </thead>

      <tbody id="transfer-list">
<?php	$qs = $cs->getDetails($id); ?>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = 1;						?>
<?php 	$product = new product(); ?>
<?php 	$barcode = new barcode(); ?>
<?php 	$zone    = new zone();    ?>
<?php		while( $rs = dbFetchObject($qs) ) : 	?>
<?php			$pReference = $product->getCode($rs->id_product);	?>
<?php			$id_td	 = $rs->id;			?>
				<tr class="font-size-12" id="row-<?php echo $id_td; ?>">

	      	<td class="middle text-center">
						<?php echo $no; ?>
					</td>

					<!--- บาร์โค้ดสินค้า --->
	        <td class="middle">
						<?php echo $barcode->getBarcode($rs->id_product); ?>
					</td>

					<!--- รหัสสินค้า -->
	        <td class="middle">
						<?php echo $pReference; ?>
					</td>

					<!--- โซนต้นทาง --->
	        <td class="middle text-center">
	      		<input type="hidden" class="row-zone-from" id="row-from-<?php echo $id_td; ?>" value="<?php echo $rs->from_zone; ?>" />
						<?php echo $zone->getName($rs->from_zone); ?>
	        </td>


	        <td class="middle text-center" id="row-label-<?php echo $id_td; ?>">
	        	<?php if( $rs->to_zone == 0 OR $rs->valid == 0 ) : ?>
	        	<button type="button" class="btn btn-xs btn-primary" id="btn_<?php echo $id_td; ?>" onclick="move_in(<?php echo $id_td; ?>, <?php echo $rs->from_zone; ?>)">
							ย้ายเข้าโซน
						</button>
	        	<?php else : ?>
							<?php 	echo $zone->getName($rs->to_zone); 	?>
	        	<?php endif; ?>
	        </td>

	        <td class="middle text-center" id="qty-<?php echo $rs->id; ?>" >
						<?php echo number_format($rs->qty).' / '. number_format($rs->qty - $cs->getTempQty($rs->id)); ?>
					</td>

	        <td class="middle text-center">
	          <?php if( $edit && $cs->isSaved == 0) : ?>
	          	<button type="button" class="btn btn-xs btn-danger" onclick="deleteMoveItem(<?php echo $id_td; ?>, '<?php echo $pReference; ?>')"><i class="fa fa-trash"></i></button>
	          <?php endif; ?>
	        </td>

	      </tr>
<?php			$no++;			?>
<?php		endwhile;			?>
<?php	else : ?>
 				<tr>
        	<td colspan="7" class="text-center"><h4>ไม่พบรายการ</h4></td>
        </tr>
<?php	endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script id="zoneTemplate" type="text/x-handlebars-template">
{{#each this}}
{{#if nodata}}
<tr>
	<td colspan="6" class="text-center">
		<h4>ไม่พบสินค้าในโซน</h4>
	</td>
</tr>
{{else}}
<tr>
	<td align="center">{{ no }}</td>
  <td align="center">{{ barcode }}</td>
  <td>{{ products }}</td>
  <td align="center" class="qty-label" id="qty-label-{{ id_stock }}">{{ qty }}</td>
  <td align="center">
  	<input type="text" class="form-control input-sm text-center input-qty" id="moveQty_{{ id_stock }}" name="moveQty[{{id_stock}}]" onkeyup="validQty({{ id_stock}}, {{ qty }})" />
		 <input type="hidden" name="id_product[{{ id_stock }}]" id="id_product_{{ id_stock }}" value="{{ id_product }}" />
  </td>
  <td align="center">
<?php if( $delete ) : ?>
    {{#if isAllowUnderZero}}
    	<input type="checkbox" name="allowUnderZero[{{id_stock}}]" id="allowUnderZero_{{ id_stock }}" onChange="validQty({{ id_stock }}, {{ qty }})" value="1" />
      <label for="allowUnderZero_{{ id_stock }}">ติดลบได้</label>
    {{/if}}
<?php endif; ?>
    </td>
</tr>
{{/if}}
{{/each}}
</script>



<script id="transferTableTemplate" type="text/x-handlebars-template">
{{#each this}}
	{{#if nodata}}
	<tr>
		<td colspan="7" class="text-center"><h4>ไม่พบรายการ</h4></td>
	</tr>
	{{else}}
		<tr class="font-size-12" id="row-{{ id }}">
			<td class="middle text-center">{{ no }}</td>
			<td class="middle">{{ barcode }}</td>
			<td class="middle">{{ products }}</td>
			<td class="middle text-center">
				<input type="hidden" class="row-zone-from" id="row-from-{{ id }}" value="{{ from_zone }}" />
				{{ fromZone }}
			</td>
			<td class="middle text-center" id="row-label-{{id}}">{{{ toZone }}}</td>
			<td class="middle text-center" id="qty-{{id}}">{{ qty }} / {{ temp }}</td>
			<td class="middle text-center">{{{ btn_delete }}}</td>
		</tr>
	{{/if}}
{{/each}}
</script>
