<?php 
	$id_tab 	= 22;
    $pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);
  	include 'function/transport_helper.php';
	include 'function/customer_helper.php';
	?>    
<div class="container">
<!-- page place holder -->
<div class="row top-row">
	<div class="col-lg-6 top-col" ><h4 class="title"><i class="fa fa-truck"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
		<?php if( $add ) : ?>
        	<button type="button" class="btn btn-sm btn-success" onclick="newAddress()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
        <?php endif; ?>
        </p>
    </div>
</div>
<hr class="margin-bottom-10" />
<?php
	$sCustomer	= isset( $_POST['sCustomer'] ) ? trim( $_POST['sCustomer'] ) : ( getCookie('sCustomer') ? getCookie('sCustomer') : "" );
	$sAddress	= isset( $_POST['sAddress'] ) ? trim( $_POST['sAddress'] ) : ( getCookie('sAddress') ? getCookie('sAddress') : "" );
	$sProvince	= isset( $_POST['sProvince'] ) ? trim( $_POST['sProvince'] ) : ( getCookie('sProvince') ? getCookie('sProvince') : "" );
?>

<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-3">
    	<label>ลูกค้า</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCustomer" id="sCustomer" value="<?php echo $sCustomer; ?>" />    
    </div>
    <div class="col-sm-3">
    	<label>ที่อยู่</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sAddress" id="sAddress" value="<?php echo $sAddress; ?>" />
    </div>
    <div class="col-sm-3">
    	<label>จังหวัด</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sProvince" id="sProvince" value="<?php echo $sProvince; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">Search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">Reset</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
</div>
</form>
<hr class="margin-top-15" />
<?php
	$where = "WHERE id_address != '' ";
	if( $sCustomer != '' )
	{
		createCookie('sCustomer', $sCustomer);
		$where .= "AND ( first_name LIKE '%".$sCustomer."%' OR last_name LIKE '%".$sCustomer."%' OR company LIKE '%".$sCustomer."%' ) ";
	}
	
	if( $sAddress != '' )
	{
		createCookie('sAddress', $sAddress);
		$where .= "AND ( address1 LIKE '%".$sAddress."%' OR address2 LIKE '%".$sAddress."%' ) ";
	}
	
	if( $sProvince != '' )
	{
		createCookie('sProvince', $sProvince);
		$where .= "AND city LIKE '%".$sProvince."%' ";	
	}
	
	$where .= "ORDER BY date_upd DESC";
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_address', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=customer_address');
	
	$qs = dbQuery("SELECT * FROM tbl_address ". $where ." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
<div class="row">
	<div class="col-sm-12">
 		<table class="table table-striped border-1">
        	<thead>
            	<tr>
            	<th class="width-5 text-center">ลำดับ</th>
                <th class="width-30">ลูกค้า</th>
                <th class="width-10">ชื่อแทน</th>
                <th class="width-35">ที่อยู่</th>
                <th class="width-10">จังหวัด</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : 	?>
    <?php	$no = row_no(); 			?>     
    <?php	while( $rs = dbFetchObject($qs) ) : ?>
    		<tr class="font-size-12" id="row_<?php echo $rs->id_address; ?>">
            	<td class="middle text-center"><?php echo $no; ?></td>
                <td class="middle"><?php echo customerName($rs->id_customer); ?></td>
                <td class="middle"><?php echo $rs->alias; ?></td>
                <td class="middle"><?php echo $rs->address1.' '.$rs->address2; ?></td>
                <td class="middle"><?php echo $rs->city; ?></td>
                <td class="middle text-right">
                	<button type="button" class="btn btn-xs btn-info" onclick="getInfo(<?php echo $rs->id_address; ?>)"><i class="fa fa-eye"></i></button>
		<?php if( $edit ) : ?>
        			<button type="button" class="btn btn-xs btn-warning" onclick="editAddress(<?php echo $rs->id_address; ?>)"><i class="fa fa-pencil"></i></button>
        <?php endif; ?>
        <?php if( $delete ) : ?>
        			<button type="button" class="btn btn-xs btn-danger" onclick="deleteAddress(<?php echo $rs->id_address; ?>, '<?php echo $rs->alias; ?>')"><i class="fa fa-trash"></i></button>
        <?php endif; ?>
                </td>
            </tr>
    <?php	$no++;		?>
    <?php	endwhile; ?>
    
    <?php else : ?>
    
    <?php endif; ?>            
            </tbody>
		</table>
	</div>
</div>


<!-------------  Add New And Edit Address Modal  --------->
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
            <input type="hidden" name="id_customer" id="id_customer" />
            <input type="hidden" id="isEdit" value="0" />
            <div class="row">
            	<div class="col-sm-12 paddint-0">
                	<label class="input-label">ลูกค้า</label>
                        <input type="text" class="form-control input-sm" name="customer" id="customer" placeholder="ระบุลูกค้า" />
                        <span class="required">*</span>
                        <span class="help-block red" id="customer-error"></span>
                </div>
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






<!-- Address Info Modal -->
<div class='modal fade' id='addressInfo' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' id='modal_info' style="width:500px;">
		<div class='modal-content'>
  			<div class='modal-header'>	</div>
			 <div class='modal-body' id='info_body'></div>
			 <div class='modal-footer'>
             	<button type='button' class='btn btn-default' data-dismiss='modal' aria-hidden='true'>ปิด</button>
			 </div>
		</div>
	</div>
</div>

<script id='info_template' type="text/x-handlebars-template">
<div class="row">
	<div class="col-lg-12">
    <table class="table table-bordered table-striped" style="margin-bottom:0px;">
    	<tr><td width="25%">ชื่อแทน</td><td>{{ alias }}</td></tr>
		<tr><td width="25%">บริษัท/ร้าน</td><td>{{ company }}</td></tr>
		<tr><td width="25%">ผู้ติดต่อ</td><td>{{ customer }}</td></tr>
        <tr><td width="25%">ที่อยู่</td><td>{{ address }}</td></tr>
		<tr><td width="25%">จังหวัด</td><td>{{ city }}</td></tr>
		<tr><td width="25%">รหัสไปรษณีย์</td><td>{{ postcode }}</td></tr>
        <tr><td width="25%">เบอร์โทร</td><td>{{ phone }}</td></tr>
		<tr><td width="25%">หมายเหตุ</td><td>{{ remark }}</td></tr>
    </table>
    </div>
</div>
</script>
<script>
<?php $prov = getProvinceArray(); ?>
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

                       
</div><!--/ container -->
<script src="script/address.js"></script>
