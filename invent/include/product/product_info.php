<?php

	$qr = "SELECT s.code AS pdCode, s.name AS pdName, p.* FROM tbl_product AS p ";
	$qr .= "JOIN tbl_product_style AS s ON p.id_style = s.id ";
	$qr .= "WHERE p.id_style = '".$id_style."' AND s.is_deleted = 0 GROUP BY p.id_style LIMIT 1";

	$qs = dbQuery($qr);
	if( dbNumRows($qs) == 1 ) :
		$rs = dbFetchObject($qs);

	//---- is visual
	$vs	= $rs->count_stock == 1 ? 'btn-success' : '';
	$nvs	= $rs->count_stock == 0 ? 'btn-danger' : '';

	//---- is active
	$ac	= $rs->active == 1 ? 'btn-success' : '';
	$nac	= $rs->active == 0 ? 'btn-danger' : '';

	//----- sale can be order ?
	$is		= $rs->show_in_sale == 1 ? 'btn-success' : '';
	$nis	= $rs->show_in_sale == 0 ? 'btn-danger' : '';

	//------ show to diler page ?
	$ic		= $rs->show_in_customer == 1 ? 'btn-success' : '';
	$nic	= $rs->show_in_customer == 0 ? 'btn-danger' : '';

	//------ show on website online ?
	$io		= $rs->show_in_online == 1 ? 'btn-success' : '';
	$nio	= $rs->show_in_online == 0 ? 'btn-danger' : '';

	//------ Can sell ?
	$ps 	= $rs->can_sell == 1 ? 'btn-success' : '';
	$nps 	= $rs->can_sell == 0 ? 'btn-danger' : '';


?>

<!-------------------------------------------------------  ข้อมูลสินค้า  ----------------------------------------------------->
            <div class="tab-pane fade <?php echo $tab1; ?>" id="info">
            <form id="productForm">
            	<div class="row">
                	<div class="col-sm-3"><span class="form-control label-left">รหัสสินค้า</span></div>
                    <div class="col-sm-9">
                    	<label class="form-control input-sm input-large" disabled ><?php echo $rs->pdCode; ?></label>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">ชื่อสินค้า</span></div>
                    <div class="col-sm-9">
                    	<label class="form-control input-sm input-large" disabled ><?php echo $rs->pdName; ?></label>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">แบรนด์สินค้า</span></div>
                    <div class="col-sm-9">
                        <label class="form-control input-sm input-large" disabled ><?php echo $bd->getBrandName($rs->id_brand); ?></label>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">กลุ่มสินค้า</span></div>
                    <div class="col-sm-9">
                        <label class="form-control input-sm input-large" disabled ><?php echo $pg->getProductGroupName($rs->id_group); ?></label>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


										<div class="col-sm-3"><span class="form-control label-left">กลุ่มย่อยสินค้า</span></div>
                    <div class="col-sm-9">
											<select class="form-control input-sm input-large" name="pdSubGroup" id="pdSubGroup">
													<?php echo selectSubGroup($rs->id_sub_group); ?>
											</select>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>



                    <div class="col-sm-3"><span class="form-control label-left">ประเภท</span></div>
                    <div class="col-sm-9">
                    	<select class="form-control input-sm input-large" name="pdKind" id="pdKind">
                        	<?php echo selectKind($rs->id_kind); ?>
                        </select>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">ชนิด</span></div>
                    <div class="col-sm-9">
                    	<select class="form-control input-sm input-large" name="pdType" id="pdType">
                        	<?php echo selectType($rs->id_type); ?>
                        </select>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">หมวดหมู่สินค้า</span></div>
                    <div class="col-sm-9">
                    	<select class="form-control input-sm input-large" name="pdCategory" id="pdCategory">
                        	<?php echo selectCategory($rs->id_category); ?>
                        </select>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">ปี</span></div>
                    <div class="col-sm-9">
                    	<select class="form-control input-sm input-large" name="pdYear" id="pdYear">
                        	<?php echo selectYears($rs->year); ?>
                        </select>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">แถบแสดงสินค้า</span></div>
                    <div class="col-sm-9"><?php echo productTabsTree($rs->id_style);  ?></div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>



                    <div class="col-sm-3"><span class="form-control label-left">ทุน</span></div>
                    <div class="col-sm-9">
                    	<label class="form-control input-sm input-small" disabled ><?php echo number_format($rs->cost, 2); ?>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>

                    <div class="col-sm-3"><span class="form-control label-left">ราคาขาย</span></div>
                    <div class="col-sm-9">
                    	<label class="form-control input-sm input-small" disabled ><?php echo number_format($rs->price, 2); ?>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>



                    <div class="col-sm-3"><span class="form-control label-left">น้ำหนัก</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini inline ops" name="weight" id="weight" value="<?php echo $rs->weight; ?>" />
                        <span class="label-left inline" style="margin-left:15px;">กิโลกรัม</span>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>

                    <div class="col-sm-3"><span class="form-control label-left">ความกว้าง</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini inline ops" name="width" id="width" value="<?php echo $rs->width; ?>" />
                        <span class="label-left inline" style="margin-left:15px;">เซ็นติเมตร</span>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>

                    <div class="col-sm-3"><span class="form-control label-left">ยาว</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini inline ops" name="length" id="length" value="<?php echo $rs->length; ?>" />
                        <span class="label-left inline" style="margin-left:15px;">เซ็นติเมตร</span>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>

                    <div class="col-sm-3"><span class="form-control label-left">สูง</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini inline ops" name="height" id="height" value="<?php echo $rs->height ?>" />
                        <span class="label-left inline" style="margin-left:15px;">เซ็นติเมตร</span>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">นับสต็อก</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $vs; ?>" id="btn-vs" onClick="toggleVisual(1)" style="width:50%;">ใช่</button>
                            <button type="button" class="btn btn-sm <?php echo $nvs; ?>" id="btn-nvs" onClick="toggleVisual(0)" style="width:50%;">ไม่ใช่</button>
                        </div>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">แสดงในหน้าพนักงานขาย</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $is; ?>" id="btn-is" onClick="toggleSale(1)" style="width:50%;">ใช่</button>
                            <button type="button" class="btn btn-sm <?php echo $nis; ?>" id="btn-nis" onClick="toggleSale(0)" style="width:50%;">ไม่ใช่</button>
                        </div>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">แสดงในหน้าตัวแทน</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $ic; ?>" id="btn-ic" onClick="toggleCustomer(1)" style="width:50%;">ใช่</button>
                            <button type="button" class="btn btn-sm <?php echo $nic; ?>" id="btn-nic" onClick="toggleCustomer(0)" style="width:50%;">ไม่ใช่</button>
                        </div>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>

                    <div class="col-sm-3"><span class="form-control label-left">แสดงในเว็บไซต์</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $io; ?>" id="btn-io" onClick="toggleOnline(1)" style="width:50%;">ใช่</button>
                            <button type="button" class="btn btn-sm <?php echo $nio; ?>" id="btn-nio" onClick="toggleOnline(0)" style="width:50%;">ไม่ใช่</button>
                        </div>
                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>


                    <div class="col-sm-3"><span class="form-control label-left">อนุญาติให้ขาย</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $ps; ?>" id="btn-ps" onClick="toggleCanSell(1)" style="width:50%;">ใช่</button>
                            <button type="button" class="btn btn-sm <?php echo $nps; ?>" id="btn-nps" onClick="toggleCanSell(0)" style="width:50%;">ไม่ใช่</button>
                        </div>

                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>



                    <div class="col-sm-3"><span class="form-control label-left">เปิดใช้งาน</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $ac; ?>" id="btn-ac" onClick="toggleActive(1)" style="width:50%;">ใช่</button>
                            <button type="button" class="btn btn-sm <?php echo $nac; ?>" id="btn-nac" onClick="toggleActive(0)" style="width:50%;">ไม่ใช่</button>
                        </div>

                    </div>
                    <div class="divider-hidden" style="margin-top:5px; margin-bottom:5px;"></div>

                    <div class="col-sm-3"><span class="form-control label-left">คำอธิบายสินค้า</span></div>
                    <div class="col-sm-9">
                    	<textarea class="form-control input-xlarge" name="description" rows="4" placeholder="กำหนดคำอธิบายสินค้า ( สำหรับลูกค้า )"><?php echo $pd->getDescription($rs->id_style); ?></textarea>
                    </div>
                    <input type="hidden" name="id_style" id="id_style" value="<?php echo $id_style; ?>" />
                    <input type="hidden" name="isVisual" id="isVisual" value="<?php echo $rs->count_stock; ?>" />
                    <input type="hidden" name="active" id="active" value="<?php echo $rs->active; ?>" />
                    <input type="hidden" name="inSale" id="inSale" value="<?php echo $rs->show_in_sale; ?>" />
                    <input type="hidden" name="inCustomer" id="inCustomer" value="<?php echo $rs->show_in_customer; ?>" />
                    <input type="hidden" name="inSale" id="inSale" value="<?php echo $rs->show_in_sale; ?>" />
                    <input type="hidden" name="inOnline" id="inOnline" value="<?php echo $rs->show_in_online; ?>" />
                    <input type="hidden" name="canSell" id="canSell" value="<?php echo $rs->can_sell; ?>" />
                    <div class="divider-hidden" style="margin-top:25px; margin-bottom:25px;"></div>

                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <button type="button" class="btn btn-success input-xlarge" onClick="saveProduct()" ><i class="fa fa-save"></i> บันทึก</button>
                    </div>
                    <div class="divider-hidden" style="margin-top:15px; margin-bottom:15px;"></div>

				</div>
            </form>
            </div><!--/ tab-pane #tab1 -->
<?php endif; ?>
<script src="script/product/product_info.js"></script>
