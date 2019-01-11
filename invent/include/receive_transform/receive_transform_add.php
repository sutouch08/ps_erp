<?php $id_receive_transform = intval($_GET['id_receive_transform']) ;
	$cs = new receive_transform($id_receive_transform);
?>
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
<?php if($cs->isSaved == 1) : ?>
<?php 	echo goBackButton(); ?>
<?php else : ?>
			<button type="button" class="btn btn-sm btn-warning" onclick="leave()"><i class="fa fa-arrow-left"></i> กลับ</button>
<?php endif; ?>
<?php 	if( $add && $cs->isSaved == 0 ) : ?>
				<button type="button" class="btn btn-sm btn-success" onclick="checkLimit()"><i class="fa fa-save"></i> บันทึก</button>
<?php	endif; ?>
        </p>
    </div>
</div>
<hr />
<?php if($id_receive_transform) : ?>
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
  	<label class="display-block">เลขที่เอกสาร</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
  	<label>วันที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center header-box"  id="dateAdd" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
  </div>

	<div class="col-sm-8 padding-5">
  	<label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" name="remark" id="remark" value="<?php echo $cs->remark; ?>"  disabled/>
  </div>

	<?php if($edit && $cs->isSaved == 0) : ?>
	<div class="col-sm-1 padding-5 last">
		<label class="display-block not-show">Edit</label>
		<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="activeHeader()">
			<i class="fa fa-pencil"></i> แก้ไข</button>
		</button>
		<button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="updateHeader()">
			<i class="fa fa-save"></i> บันทึก
		</button>
	</div>
	<?php endif; ?>

</div><!--/ row -->
<hr class="margin-top-10" />
<?php
$dis = $cs->isSaved == 1 ? 'disabled' : '';
?>
<div class="row">
	<div class="col-sm-2 padding-5 first">
  	<label>ใบเบิกสินค้า</label>
      <input type="text" class="form-control input-sm text-center header-box" id="poCode" placeholder="ระบุใบเบิกสินค้า" value="<?php echo $cs->order_code; ?>" <?php echo $dis; ?> />
			<span class="help-block red" style="margin:0px;" id="poCode-error">&nbsp;</span>
  </div>
<?php if($cs->isSaved == 0) : ?>
	<div class="col-sm-1 padding-5">
		<label class="display-block not-show">ใบเบิก</label>
		<button type="button" class="btn btn-sm btn-info btn-block hide" id="btn-change" onclick="changeOrder()" >
			<i class="fa fa-retweet"></i> เปลี่ยน
		</button>
		<button type="button" class="btn btn-sm btn-primary btn-block" id="btn-load" onclick="getData()" >
			แสดงรายการ
		</button>
	</div>
<?php endif; ?>

  <div class="col-sm-2 padding-5">
  	<label>ใบส่งสินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="invoice" placeholder="อ้างอิงใบส่งสินค้า" <?php echo $dis; ?> />
      <span class="help-block red" style="margin:0px;" id="invoice-error"></span>
  </div>

	<div class="col-sm-3">
  	<label>ชื่อโซน</label>
      <input type="text" class="form-control input-sm text-center zone" name="zoneName" id="zoneName" placeholder="ค้นหาชื่อโซน" <?php echo $dis; ?> />
      <span class="help-block red" style="margin:0px;" id="zone-error"></span>
  </div>
</div>
<hr class="margin-top-15"/>

<div class="row">
	<div class="col-sm-1">
    	<label>จำนวน</label>
        <input type="text" class="form-control input-sm text-center" id="qty" value="1.00" />
    </div>
    <div class="col-sm-3 ">
    	<label>บาร์โค้ดสินค้า</label>
        <input type="text" class="form-control input-sm text-center" id="barcode" placeholder="ยิงบาร์โค้ดเพื่อรับสินค้า" autocomplete="off"  />
    </div>
    <div class="col-sm-1">
    	<label class="display-block not-show">ok</label>
        <button type="button" class="btn btn-sm btn-primary" onclick="checkBarcode()"><i class="fa fa-check"></i> ตกลง</button>
    </div>

    <input type="hidden" name="id_zone" id="id_zone" />
		<input type="hidden" name="id_order" id="id_order" />
    <input type="hidden" name="id_receive_transform" id="id_receive_transform" value="<?php echo $cs->id; ?>" />


</div>
<hr class="margin-top-5 margin-bottom-15"/>

<form id="receiveForm">
<div class="row">
	<div class="col-sm-12">
    <table class="table table-striped table-bordered">
    	<thead>
      	<tr class="font-size-12">
      		<th class="width-5 text-center">ลำดับ	</th>
          <th class="width-15 text-center">บาร์โค้ด</th>
          <th class="width-15 text-center">รหัสสินค้า</th>
          <th class="width-35">ชื่อสินค้า</th>
          <th class="width-10 text-center">สั่งซื้อ</th>
          <th class="width-10 text-center">ค้างรับ</th>
          <th class="width-10 text-center">จำนวน</th>
        </tr>
      </thead>
      <tbody id="receiveTable">
			</tbody>
    </table>
    </div>
</div>
<input type="hidden" name="id_emp" id="id_emp" />
<input type="hidden" name="approvKey" id="approvKey" />
</form>


<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog input-xlarge">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
				<h4 class='modal-title-site text-center' > ผู้มีอำนาจอนุมัติรับสินค้าเกิน </h4>
            </div>
            <div class="modal-body">
            	<div class="col-sm-12">
                	<input type="password" class="form-control input-sm text-center" id="sKey" />
                </div>
            </div>
            <div class="modal-footer">
            	<span class="help-block red text-center not-show" id="approvError">รหัสไม่ถูกต้องหรือไม่มีอำนาจในการอนุมัตินี้</span>
            </div>
        </div>
    </div>
</div>

<script id="template" type="text/x-handlebarsTemplate">
{{#each this}}
	{{#if @last}}
        <tr>
            <td colspan="4" class="middle text-right"><strong>รวม</strong></td>
            <td class="middle text-center">{{qty}}</td>
            <td class="middle text-center">{{backlog}}</td>
            <td class="middle text-center"><span id="total-receive">0</span></td>
        </tr>
    {{else}}
        <tr class="font-size-12">
            <td class="middle text-center">
			{{ no }}
			</td>
            <td class="middle id_pd" id="barcode_{{id_pd}}">{{barcode}}</td>
            <td class="middle">{{pdCode}}</td>
            <td class="middle">{{pdName}}</td>
            <td class="middle text-center" id="qty_{{id_pd}}">
				{{qty}}
				<input type="hidden" id="limit_{{id_pd}}" value="{{limit}}"/>
			</td>
            <td class="middle text-center" id="backlog_{{id_pd}}">{{backlog}}</td>
            <td class="middle text-center">
                <input type="text" class="form-control input-sm text-center receive-box pdCode" name="receive[{{id_pd}}]" id="receive-{{id_pd}}" />
            </td>
        </tr>
    {{/if}}
{{/each}}
</script>
<?php else : ?>
<?php include 'include/page_error.php'; ?>
<?php endif; ?>
<script src="script/receive_transform/receive_transform_add.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/validate.js"></script>
