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

<?php $id_zone	= isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : 0; ?>

<?php $rs			  = getZoneDetail($id_zone); ?>

<?php if( $rs !== FALSE ) : ?>

  <div class="col-sm-2">
    <label>คลังสินค้า</label>
    <select class="form-control input-sm" id="edit-zWH">
    <?php echo selectWarehouse($rs->id_warehouse); ?>
    </select>
    <span class="display-block margin-top-5 red not-show" id="edit-zWH-error">โปรดเลือกคลัง</span>
  </div>

  <div class="col-sm-2">
    <label>รหัสโซน</label>
    <input type="text" class="form-control input-sm" id="edit-zCode" placeholder="* จำเป็น | ห้ามซ้ำ" value="<?php echo $rs->barcode_zone; ?>" />
    <span class="display-block margin-top-5 red not-show" id="edit-zCode-error">รหัสซ้ำ</span>
  </div>

  <div class="col-sm-4">
    <label>ชื่อโซน</label>
    <input type="text" class="form-control input-sm" id="edit-zName" placeholder="* จำเป็น | ห้ามซ้ำ" value="<?php echo $rs->zone_name; ?>" />
    <span class="display-block margin-top-5 red not-show" id="edit-zName-error">ชื่อซ้ำ</span>
  </div>



  <div class="col-sm-1">
  <?php if( $edit ) : ?>
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="saveEdit()">ปรับปรุง</button>
  <?php endif; ?>
  </div>

 <input type="hidden" id="id_zone" value="<?php echo $id_zone; ?>" />

 <input type="hidden" id="id_customer" value="<?php echo $rs->id_customer; ?>" />

 <?php else : ?>
    <div class="col-sm-12 text-center middle">
      <h4>ไม่พบข้อมูล</h4>
    </div>
  <?php endif; ?>
</div>
<hr/>
<div class="row">
  <div class="col-sm-5">
    <label>ลูกค้า[กรณีฝากขาย]</label>
    <input type="text" class="form-control input-sm" id="customer" placeholder="ค้นหาลูกค้า" value="" />
    <span class="display-block margin-top-5 red not-show" id="customer-error">ชื่อซ้ำ</span>
  </div>
  <div class="col-sm-1">
    <label class="display-block not-show">เชื่อมโยง</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addCustomer()">เพิ่มลูกค้า</button>
  </div>
</div>
<hr/>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-25">รหัส</th>
          <th class="width-50">ชื่อลูกค้า</th>
          <th class="width-20 text-center"></th>
        </tr>
      </thead>
      <tbody id="result">
<?php
      $qr  = "SELECT cz.id, cs.code, cs.name ";
      $qr .= "FROM tbl_zone_customer AS cz ";
      $qr .= "JOIN tbl_customer AS cs ON cz.id_customer = cs.id ";
      $qr .= "WHERE cz.id_zone = '".$id_zone."' ";
?>
<?php $qs = dbQuery($qr); ?>
<?php if(dbNumRows($qs) > 0) : ?>
  <?php $no = 1; ?>
  <?php while($rd = dbFetchObject($qs)) : ?>
    <tr id="row-<?php echo $rd->id; ?>">
      <td class="middle text-center no"><?php echo $no; ?></td>
      <td class="middle"><?php echo $rd->code; ?></td>
      <td class="middle"><?php echo $rd->name; ?></td>
      <td class="middle text-right">
        <?php if($edit OR $add) : ?>
          <button type="button" class="btn btn-sm btn-danger" onclick="removeCustomer(<?php echo $rd->id; ?>, '<?php echo $rd->code; ?>')">
            <i class="fa fa-trash"></i>
          </button>
        <?php endif; ?>
      </td>
    </tr>
    <?php $no++; ?>
  <?php endwhile; ?>
<?php endif; ?>



      </tbody>
    </table>
  </div>
</div>


<script id="template" type="text/x-handlebarsTemplate">
<tr id="row-{{id}}">
  <td class="middle text-center no"></td>
  <td class="middle">{{code}}</td>
  <td class="middle">{{name}}</td>
  <td class="middle text-right">
    <?php if($edit OR $add) : ?>
      <button type="button" class="btn btn-sm btn-danger" onclick="removeCustomer({{id}}, '{{code}}')">
        <i class="fa fa-trash"></i>
      </button>
    <?php endif; ?>
  </td>
</tr>
</script>


<script src="script/zone/zone_edit.js"></script>
