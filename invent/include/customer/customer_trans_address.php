

<div class="tab-pane fade" id="trans_address">

	<div class="row">
        <div class="col-sm-6 top-col">
            <h4 class="title">ที่อยู่สำหรับจัดส่ง</h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
	<?php if( $add ) : ?>
    			<button type="button" class="btn btn-sm btn-success" onclick="newAddress()"><i class="fa fa-plus"></i> เพิ่มที่อยู่ใหม่</button>
    <?php endif; ?>            
            </p>
        </div>
        <div class="divider"></div>
        
        <div class="col-sm-12">
        	<table class="table table-bordered">
            	<thead>
                	<tr class="font-size-12">
                    	<th class="width-10 text-center">ชื่อเรียก</th>
                        <th class="width-20 text-center">ผู้รับ</th>
                        <th class="width-45">ที่อยู่</th>
                        <th class="width-15 text-center">โทรศัพท์</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="address-table">
<?php	$qs = dbQuery("SELECT * FROM tbl_address WHERE id_customer = '".$customer->id."'");		        	?>
<?php	if( dbNumRows($qs) > 0 ) : 	?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id_address; ?>">
                    	<td align="center" class="alias-name"><?php echo $rs->alias; ?></td>
                        <td align="center"><?php echo $rs->company == "" ? $rs->first_name.' '.$rs->last_name : $rs->company; ?></td>
                        <td><?php echo $rs->address1.' '.$rs->address2 .' '.$rs->city.' '.$rs->postcode; ?></td>
                        <td align="center"><?php echo $rs->phone; ?></td>
                        <td align="right">
			<?php	if( $edit ) : ?>
            				<button type="button" class="btn btn-xs btn-warning" onclick="editAddress(<?php echo $rs->id_address; ?>)"><i class="fa fa-pencil"></i></button>
            <?php	endif; ?>    
            <?php	if( $delete ) : ?>
            				<button type="button" class="btn btn-xs btn-danger" onclick="deleteAddress(<?php echo $rs->id_address; ?>, '<?php echo $rs->alias; ?>')"><i class="fa fa-trash"></i></button>
            <?php	endif; ?>                   
                        </td>
                    </tr>
<?php		endwhile; ?>	   
<?php	else : ?>
				<tr class="font-size-12">
                	<td colspan="5" align="center">ไม่พบที่อยู่สำหรับจัดส่ง</td>
                </tr>
<?php	endif; ?>            
			</table>
        </div>
  
    </div>
    
<!-------------  Add New Address Modal  --------->
<div class='modal fade' id='addressModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title-site text-center' >เพิ่ม/แก้ไข ที่อยู่สำหรับจัดส่ง</h4>
            </div>
            <div class='modal-body'>
            <form id="addAddressForm"	>
            <input type="hidden" name="id_address" id="id_address" />
            <input type="hidden" name="cAlias" id="currentAlias" />
            <div class="row">
            	<div class="col-sm-12 padding-0">
                    <div class="col-sm-6">
                        <label class="input-label">ชื่อ</label>
                        <input type="text" class="form-control input-sm" name="Fname" id="Fname" placeholder="ชื่อผู้รับ (จำเป็น)" />
                        <span class="required">**</span>
                        <span class="help-block red" id="name-error"></span>
                    </div>
                    <div class="col-sm-6">
                        <label class="input-label">สกุล</label>
                        <input type="text" class="form-control input-sm" name="Lname" id="Lname" placeholder="นามสกุลผู้รับ" />
                    </div>
                    
                </div>
                <div class="col-sm-12">
                	<label class="input-label">บริษัท/ห้าง/ร้าน</label>
                    <input type="text" class="form-control input-sm" name="company" id="company" placeholder="ชื่อ บริษัท/ห้าง/ร้าน (จำเป็น)" />
                    <span class="required">**</span>
                    <span class="help-block red" id="company-error"></span>
                </div>
                <div class="col-sm-12">
                	<label class="input-label">ที่อยู่ 1 </label>
                    <input type="text" class="form-control input-sm" name="address1" id="address1" placeholder="เลขที่, หมู่บ้าน, ถนน (จำเป็น)" />
                    <span class="required">*</span>
                    <span class="help-block red" id="address-error"></span>
                </div>
                <div class="col-sm-12">
                	<label class="input-label">ที่อยู่ 2 </label>
                    <input type="text" class="form-control input-sm" name="address2" id="address2" placeholder="ตำบล, อำเภอ" />
                    <span class="help-block red" id="name-error"></span>
                </div>
                <div class="col-sm-12 padding-0">
                    <div class="col-sm-6">
                        <label class="input-label">จังหวัด</label>
                        <input type="text" class="form-control input-sm" name="province" id="province" placeholder="จังหวัด (จำเป็น)" />
                        <span class="required">*</span>
                        <span class="help-block red" id="province-error"></span>
                    </div>
                    <div class="col-sm-6">
                        <label class="input-label">รหัสไปรษณีย์</label>
                        <input type="text" class="form-control input-sm" name="postcode" id="postcode" placeholder="รหัสไปรษณีย์" />
                    </div>
                </div>
                <div class="col-sm-12 padding-0">
                    <div class="col-sm-6">
                        <label class="input-label">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control input-sm" name="phone" id="phone" placeholder="000 000 0000" />
                    </div>
                    <div class="col-sm-6">
                        <label class="input-label">ชื่อเรียก</label>
                        <input type="text" class="form-control input-sm" name="alias" id="alias" placeholder="ใช้เรียกที่อยู่ เช่น บ้าน, ที่ทำงาน (จำเป็น)" />
                        <span class="required">*</span>
                        <span class="help-block red" id="alias-error"></span>
                    </div>
            	</div>
                 <div class="col-sm-12">
                	<label class="input-label">หมายเหตุ </label>
                    <textarea class='form-control input-sm' name='remark' id='remark' rows='8' placeholder="ใส่หมายเหตุ (ถ้ามี) เช่น ร้านปิด 17.00 ต้องไปก่อนร้านปิด เป็นต้น"></textarea>
                </div>
            </div>
            </form>
            </div>
            <div class='modal-footer'>
                <button type="button" class="btn btn-sm btn-success" onClick="saveAddress()" ><i class="fa fa-save"></i> บันทึก</button>
            </div>
        </div>
    </div>
</div>

<script id="address-table-template" type="text/x-handlebars-template">
{{#each this}}

{{#if noaddress}}
	<tr class="font-size-12">
    	<td colspan="5" align="center">ไม่พบที่อยู่สำหรับจัดส่ง</td>
    </tr>
{{else}}
	<tr class="font-size-12" id="row_{{ id_address }}">
        <td align="center" class="alias-name">{{ alias }}</td>
        <td align="center">{{ name }}</td>
        <td>{{ address }}</td>
        <td align="center">{{ phone }}</td>
        <td align="right">
        <?php	if( $edit ) : ?>
        <button type="button" class="btn btn-xs btn-warning" onclick="editAddress({{ id_address }})"><i class="fa fa-pencil"></i></button>
        <?php	endif; ?>    
        <?php	if( $delete ) : ?>
        <button type="button" class="btn btn-xs btn-danger" onclick="deleteAddress({{ id_addreess }}, '{{ alais }}')"><i class="fa fa-trash"></i></button>
        <?php	endif; ?>                   
        </td>
    </tr>
{{/if}}
{{/each}}	
</script>
<script>
<?php $prov = getProvinceList(); ?>
var Province = [<?php foreach($prov as $province){ echo '"'.$province .'", '; } ?>];
$('#province').autocomplete({
	source: Province,
	autoFocus: true
});

var Alias = [];
$(document).ready(function(e) {
    $(".alias-name").each(function(index, element) {
        Alias.push($(this).text());
    });
});
</script>    

</div><!--- Tab-pane --->