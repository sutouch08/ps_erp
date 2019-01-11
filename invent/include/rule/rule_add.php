<?php
$id = isset($_GET['id_rule']) ? $_GET['id_rule'] : FALSE;
$cs = new discount_rule($id);
$active = $cs->active == 1 ? 'btn-success' : '';
$disActive = $cs->active == 0 ? 'btn-danger' :'';
include 'function/discount_rule_helper.php';
?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-circle"></i>&nbsp; <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่</label>
    <input type="text" class="form-control input-sm text-center" id="txt-policy" value="<?php echo $cs->code; ?>" disabled />
  </div>
  <div class="col-sm-8 padding-5">
    <label>ชื่อเงื่อนไข</label>
    <input type="text" class="form-control input-sm" maxlength="150" id="txt-rule-name" value="<?php echo $cs->name; ?>" disabled />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ใช้งาน</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm <?php echo $active; ?> width-50" id="btn-active-rule" onclick="activeRule()" disabled>
        <i class="fa fa-check"></i>
      </button>
      <button type="button" class="btn btn-sm <?php echo $disActive; ?> width-50" id="btn-dis-rule" onclick="disActiveRule()" disabled>
        <i class="fa fa-times"></i>
      </button>
    </div>
  </div>
  <?php if($add) : ?>
  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">add</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="updateRule()"><i class="fa fa-save"></i> Update</button>
  </div>
  <?php endif; ?>
</div>
<input type="hidden" id="id_rule" value="<?php echo $cs->id; ?>" />
<input type="hidden" id="isActive" value="<?php echo $cs->active; ?>" />

<hr/>

<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block active"><a href="#discount" data-toggle="tab">ส่วนลด</a></li>
        <li class="li-block"><a href="#customer" data-toggle="tab">ลูกค้า</a></li>
        <li class="li-block"><a href="#product" data-toggle="tab">สินค้า</a></li>
        <li class="li-block"><a href="#channels" data-toggle="tab">ช่องทางขาย</a></li>
        <li class="li-block"><a href="#payment" data-toggle="tab">ช่องทางการชำระเงิน</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
        <?php include 'include/rule/discount_rule.php'; ?>
        <?php include 'include/rule/customer_rule.php'; ?>
        <?php include 'include/rule/product_rule.php'; ?>
        <?php include 'include/rule/channels_rule.php'; ?>
        <?php include 'include/rule/payment_rule.php'; ?>

</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->

<script src="script/rule/rule_add.js"></script>
<script src="script/rule/discount_tab.js"></script>
<script src="script/rule/customer_tab.js"></script>
<script src="script/rule/product_tab.js"></script>
<script src="script/rule/channels_tab.js"></script>
<script src="script/rule/payment_tab.js"></script>
