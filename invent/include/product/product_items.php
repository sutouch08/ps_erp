<?php 
	include 'function/image_helper.php';	
	$image	= new image();
	
	$qr = "SELECT p.*, c.code AS color, s.code AS size ";
	$qr .= "FROM tbl_product p ";
	$qr .= "LEFT JOIN tbl_color c ON p.id_color = c.id ";
	$qr .= "LEFT JOIN tbl_size s ON p.id_size = s.id ";
	$qr .= "WHERE p.id_style = '".$id_style."' AND p.is_deleted = 0 ";
	$qr .= "ORDER BY c.code ASC, s.position";
	
	$qs = dbQuery($qr);
?>

<!-------------------------------------------------------  รายการสินค้า  ----------------------------------------------------->        
<div class="tab-pane fade <?php echo $tab2; ?>" id="items-list">
    <div class="row">
        <div class="col-sm-12">
        <button type="button" class="btn btn-sm btn-info" onClick="setImage()">เชื่อมโยงรูปภาพ</button>
        </div>
	</div>
    <hr/>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped border-1">
                <thead>
                    <tr class="font-size-12">
                        <th class="width-5 text-center">รูปภาพ</th>
                        <th class="width-15">รหัสอ้างอิง</th>
                        <th class="width-5 text-center" >สี</th>
                        <th class="width-5 text-center">ไซส์</th>
                        <th class="width-8 text-center">ต้นทุน</th>
                        <th class="width-8 text-center">ราคา</th>
                        <th class="width-10 text-center">สต็อก</th>
                        <th class="width-5 text-center">เซลล์</th>
                        <th class="width-5 text-center" >ตัวแทน</th>
                        <th class="width-5 text-center">เว็บไซต์</th>
                        <th class="width-5 text-center">ขาย</th>
                        <th class="width-5 text-center">สถานะ</th> 
                        <th></th>
                    </tr>
                </thead>
                <tbody id="sku-table">
    <?php	if( dbNumRows($qs) > 0 ) : ?>
    <?php		while( $rs = dbFetchObject($qs) ) : 	?>
        			<tr class="font-size-12">
                    	<td class="middle text-center"><img src="<?php echo $image->getProductImage($rs->id, 1 ); ?>" width="40px" /></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle text-center"><?php echo $rs->color; ?></td>
                        <td class="middle text-center"><?php echo $rs->size; ?></td>
                        <td class="middle text-center"><?php echo number_format($rs->cost, 2); ?></td>
                        <td class="middle text-center"><?php echo number_format($rs->price, 2); ?></td>
                        <td class="middle text-center"><?php echo isActived($rs->count_stock); ?></td>
                        <td class="middle text-center">
                        	<a href="javascript:void(0)" id="showInSale-<?php echo $rs->id; ?>" onclick="setShowInSale('<?php echo $rs->id; ?>')">
								<?php echo isActived($rs->show_in_sale); ?>
                            </a>
                        </td>
                        <td class="middle text-center">
                            <a href="javascript:void(0)" id="showInCustomer-<?php echo $rs->id; ?>" onclick="setShowInCustomer('<?php echo $rs->id; ?>')">
                            	<?php echo isActived($rs->show_in_customer); ?>
                            </a>
                        </td>
                        <td class="middle text-center">
							<a href="javascript:void(0)" id="showInOnline-<?php echo $rs->id; ?>" onclick="setShowInOnline('<?php echo $rs->id; ?>')">
								<?php echo isActived($rs->show_in_online); ?>
                        	</a>
                        </td>
                        <td class="middle text-center">
                            <a href="javascript:void(0)" id="canSell-<?php echo $rs->id; ?>" onclick="setCanSell('<?php echo $rs->id; ?>')">
                                <?php echo isActived($rs->can_sell); ?>
                            </a>
                        </td>
                        <td class="middle text-center">
                            <a href="javascript:void(0)" id="active-<?php echo $rs->id; ?>" onclick="setActive('<?php echo $rs->id; ?>')">
                                <?php echo isActived($rs->active); ?>
                            </a>
                        </td>
                        <td class="middle text-right" >
						<?php if( $edit ) : ?>
                			<button type="button" class="btn btn-sm btn-warning" onclick="getItem('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
                		<?php endif; ?>  
                		<?php if( $delete ) : ?>
                			<button type="button" class="btn btn-sm btn-danger" onclick="removeItem('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
                		<?php endif; ?>                      
                        </td>
                    </tr>    
	<?php		endwhile; 	?>                        
    <?php	else : 	?>
    				<tr>
                    	<td colspan="13" class="text-center middle">
                        	<h4 style="text-align:center; color:#AAA;"><i class="fa fa-tags fa-2x"></i> No SKU Now</h4>
                        </td>
                    </tr>
    <?php 	endif; ?>       
                </tbody>
            </table>
        </div>
    </div>                    
        



<script id="itemTemplate" type="text/x-handlebars-template">
<form id="editForm">
<div class="row">
	<div class="col-sm-4 label-left">
		<label>รหัสสินค้า</label>
	</div>
	<div class="col-sm-8">
		<label class="form-control input-sm input-large" disabled >{{ pdCode }}</label> 
		<input type="hidden" name="id_pd" id="id_pd" value="{{ id_pd }}" />
	</div>
	<div class="col-sm-4 label-left top-col">
		<label>น้ำหนัก</label>
	</div>
	<div class="col-sm-8 top-col">
		<input type="text" class="form-control input-sm input-mini inline input-number" name="weight" id="editWeight" value="{{weight}}" />
		<span>กรัม</span>
	</div>
	<div class="col-sm-4 label-left top-col">
		<label>กว้าง</label>
	</div>
	<div class="col-sm-8 top-col">
		<input type="text" class="form-control input-sm input-mini inline input-number" name="width" id="editWidth" value="{{width}}" />
		<span>เซนติเมตร</span>
	</div>
	<div class="col-sm-4 label-left top-col">
		<label>ยาว</label>
	</div>
	<div class="col-sm-8 top-col">
		<input type="text" class="form-control input-sm input-mini inline input-number" name="length" id="editLength" value="{{length}}" />
		<span>เซนติเมตร</span>
	</div>
	<div class="col-sm-4 label-left top-col">
		<label>สูง</label>
	</div>
	<div class="col-sm-8 top-col">
		<input type="text" class="form-control input-sm input-mini inline input-number" name="height" id="editHeight" value="{{height}}" />
		<span>เซนติเมตร</span>
	</div>
</div><!--/ row -->
</form>
</script>


             
             
             
            <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemedit" aria-hidden="true">
            	<div class="modal-dialog" style="width:500px;">
                	<div class="modal-content">
                    	<div class="modal-header">
                            <h4 class="modal-title text-center">แก้ไขรายการสินค้า</h4>
                        </div>
                        <div class="modal-body" id="itemBody">
                            
                        </div>
                        <div class="modal-footer">
                        	<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">ปิด</button>
                            <button type="button" class="btn btn-sm btn-primary" onClick="saveItem()"><i class="fa fa-save"></i> บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
			<form id="mappingForm">
            <div class="modal fade" id="imageMappingTable" tabindex="-1" role="dialog" aria-labelledby="mapping" aria-hidden="true">
            	<div class="modal-dialog" style="width:1000px">
                	<div class="modal-content">
                    	<div class="modal-header">
                            <h4 class="modal-title">จับคู่รูปภาพกับสินค้า</h4>
                        </div>
                        <div class="modal-body">
                        <div class="table-responsive" id="mappingBody"></div>
                        
                        </div>
                        <div class="modal-footer">
                        	<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">ปิด</button>
                            <button type="button" class="btn btn-sm btn-primary" onClick="doMapping()">ดำเนินการ</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>    				
</div><!--/ tab-pane #tab2 -->

<script src="script/product/product_items.js"></script>