
<div class="row">
	<div class="col-sm-12" id="transfer-table">
  	<table class="table table-striped table-bordered">
    	<thead>
      	<tr>
        	<th class="width-5 text-center">ลำดับ</th>
          <th class="width-15 text-center">บาร์โค้ด</th>
          <th class="width-30 text-center">สินค้า</th>
          <th class="width-20 text-center">โซนต้นทาง</th>
          <th class="width-20 text-center">โซนปลายทาง</th>
          <th class="width-10 text-center">จำนวน</th>
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
	        		<span class="red">ยังไม่ย้ายเข้าโซน</span>
	        	<?php else : ?>
							<?php 	echo $zone->getName($rs->to_zone); 	?>
	        	<?php endif; ?>
	        </td>

	        <td class="middle text-center" >
						<?php echo number_format($rs->qty) .' / '. number_format(($rs->qty - $cs->getTempQty($rs->id))); ?>
						<?php if( $edit && ($rs->valid == 0 OR $rs->to_zone == 0)) : ?>
	          	<button type="button" class="btn btn-xs btn-danger pull-right" onclick="deleteMoveItem(<?php echo $id_td; ?>, '<?php echo $pReference; ?>')"><i class="fa fa-trash"></i></button>
	          <?php endif; ?>
					</td>
	      </tr>
<?php			$no++;			?>
<?php		endwhile;			?>
<?php	else : ?>
 				<tr>
        	<td colspan="6" class="text-center"><h4>ไม่พบรายการ</h4></td>
        </tr>
<?php	endif; ?>
      </tbody>
    </table>
  </div>
</div>
