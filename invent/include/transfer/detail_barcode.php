<div class="row">
	<div class="col-sm-12 hide" id="zone-table">
    <form id="productForm">
    	<table class="table table-striped table-bordered">
      	<thead>
					<tr>
						<th colspan="4" class="text-center">
							<h4 class="title" id="zoneName"></h4>
						</th>
					</tr>
        	<tr>
          	<th colspan="4">
								<div class="col-sm-3">
									<?php if( $delete ) : ?>
		              	<label class="margin-right-15px padding-10" id="underZeroLabel">
		                <input type="checkbox" name="allowUnderZeroAll" id="allowUnderZeroAll" value="1" style="margin-right:5px;" />ติดลบได้</label>
		              <?php endif; ?>
								</div>
								<div class="col-sm-2">
								 <div class="input-group">
											 <span class="input-group-addon">จำนวน</span>
											 <input type="text" class="form-control input-sm" id="qty-from" value="1" />
									 </div>
							 </div>
							 <div class="col-sm-4">
								 <div class="input-group">
											 <span class="input-group-addon">บาร์โค้ดสินค้า</span>
											 <input type="text" class="form-control input-sm" id="barcode-item-from" placeholder="ยิงบาร์โค้ดเพื่อย้ายสินค้าออก" />
									 </div>
							 </div>
            </th>
          </tr>

          <tr>
          	<th class="width-10 text-center">ลำดับ</th>
            <th class="width-20 text-center">บาร์โค้ด</th>
            <th class="text-center">สินค้า</th>
            <th class="width-10 text-center">จำนวน</th>

          </tr>
          </thead>

          <tbody id="zone-list"> </tbody>

        </table>
      </form>
    </div>


		<div class="col-sm-12 hide" id="temp-table">
    	<table class="table table-striped table-bordered">
      	<thead>
          <tr>
          	<th colspan="5">
             	<div class="col-sm-2 col-sm-offset-3">
            		<div class="input-group">
                	<span class="input-group-addon">จำนวน</span>
                	<input type="text" class="form-control input-sm" id="qty-to" value="1" />
              	</div>
            	</div>
	            <div class="col-sm-4">
	             	<div class="input-group">
	                <span class="input-group-addon">บาร์โค้ดสินค้า</span>
	                <input type="text" class="form-control input-sm" id="barcode-item-to" placeholder="ยิงบาร์โค้ดเพื่อย้ายสินค้าออก" />
	              </div>
              </div>
            </th>
            </tr>
          	<tr>
            	<th class="width-5 text-center">ลำดับ</th>
              <th class="width-15 text-center">บาร์โค้ด</th>
              <th class="width-45 text-center">สินค้า</th>
              <th class="width-25 text-center">ต้นทาง</th>
              <th class="width-10 text-center">จำนวน</th>
            </tr>
          </thead>
          <tbody id="temp-list">

          </tbody>
        </table>
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
	        	<?php if( $rs->to_zone == 0 ) : ?>
	        	<button type="button" class="btn btn-xs btn-primary" id="btn_<?php echo $id_td; ?>" onclick="move_in(<?php echo $id_td; ?>, <?php echo $rs->from_zone; ?>)">
							ย้ายเข้าโซน
						</button>
	        	<?php else : ?>
							<?php 	echo $zone->getName($rs->to_zone); 	?>
	        	<?php endif; ?>
	        </td>

	        <td class="middle text-center" >
						<?php echo number_format($rs->qty) .' / ' .number_format( $rs->qty - $cs->getTempQty($rs->id)); ?>
					</td>

	        <td class="middle text-center">
	          <?php if( $edit && $cs->isSaved == 0 ) : ?>
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
	<td colspan="4" class="text-center"><h4>ไม่พบสินค้าในโซน</h4></td>
</tr>
{{else}}
<tr>
	<td align="center">{{ no }}</td>
    <td align="center">{{ barcode }}</td>
    <td>
		{{ products }}
		<input type="hidden" id="qty_{{barcode}}" value="{{qty}}" />
	</td>
    <td align="center" id="qty-label_{{barcode}}">	{{ qty }}	</td>
</tr>
{{/if}}
{{/each}}
</script>




<script id="tempTableTemplate" type="text/x-handlebars-template">
{{#each this}}
	{{#if nodata}}
	<tr>
		<td colspan="6" class="text-center"><h4>ไม่พบรายการ</h4></td>
	</tr>
	{{else}}
		<tr class="font-size-12" id="row-temp-{{ id }}">
			<td class="middle text-center">{{ no }}</td>
			<td class="middle">{{ barcode }}</td>
			<td class="middle">{{ products }}</td>
			<td class="middle text-center">
				<input type="hidden" id="from-{{ barcode }}" value="{{ from_zone }}" />
				<input type="hidden" id="row_{{barcode}}" value="{{id}}" />
				<input type="hidden" id="qty-{{barcode}}" value="{{qty}}" />
				{{ fromZone }}
			</td>

			<td class="middle text-center" id="qty-label-{{barcode}}">
				{{ qty }}
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
			<td class="middle text-center">{{ qty }} / {{ temp }}</td>
			<td class="middle text-center">{{{ btn_delete }}}</td>
		</tr>
	{{/if}}
{{/each}}
</script>
