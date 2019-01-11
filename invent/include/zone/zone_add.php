<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-map-marker"></i> &nbsp; <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr class="margin-bottom-15"/>

<div class="row">
	<div class="col-sm-2">
  	<label>คลังสินค้า</label>
    <select class="form-control input-sm" id="add-zWH">
    <?php echo selectWarehouse(); ?>
    </select>
    <span class="display-block margin-top-5 red not-show" id="add-zWH-error">โปรดเลือกคลัง</span>
  </div>

  <div class="col-sm-2">
  	<label>รหัสโซน</label>
    <input type="text" class="form-control input-sm" id="add-zCode" placeholder="* จำเป็น | ห้ามซ้ำ" />
    <span class="display-block margin-top-5 red not-show" id="add-zCode-error">รหัสซ้ำ</span>
  </div>
  <div class="col-sm-4">
  	<label>ชื่อโซน</label>
    <input type="text" class="form-control input-sm" id="add-zName" placeholder="* จำเป็น | ห้ามซ้ำ" />
    <span class="display-block margin-top-5 red not-show" id="add-zName-error">ชื่อซ้ำ</span>
  </div>
    <div class="col-sm-1">
    	<?php if( $add ) : ?>
        <label class="display-block not-show">Submit</label>
        <button type="button" class="btn btn-sm btn-success btn-block" onclick="saveAdd()"><i class="fa fa-plus"></i> เพิ่ม</button>
        <?php endif; ?>
    </div>
</div>
<input type="hidden" id="id_customer" />

<hr class="margin-top-0"/>

<div class="row">
	<div class="col-sm-12">
    	<table class="table">
        	<tbody id="add-table">

            </tbody>
        </table>
    </div>
</div>

<script id="addRow-Template" type="text/x-handlebars-template">
<tr>
	<td>{{ barcode }}</td>
  <td>{{ zone_name }}</td>
  <td>{{ warehouse_name }}</td>
</tr>
</script>

<script src="script/zone/zone_add.js"></script>
