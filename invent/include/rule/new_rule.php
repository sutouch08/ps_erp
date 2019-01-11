<?php
$cs = new discount_rule();
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
    <input type="text" class="form-control input-sm text-center" id="txt-policy" value="<?php echo $cs->getNewReference(); ?>" disabled />
  </div>
  <div class="col-sm-8 padding-5">
    <label>ชื่อเงื่อนไข</label>
    <input type="text" class="form-control input-sm" maxlength="150" id="txt-rule-name" placeholder="กำหนดชื่อเงื่อนไขส่วนลด (ไม่เกิน 150 ตัวอักษร)" autofocus />
  </div>
  <?php if($add) : ?>
  <div class="col-sm-2 padding-5 last">
    <label class="display-block not-show">add</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()"><i class="fa fa-plus"></i> สร้างเงื่อนไข</button>
  </div>
  <?php endif; ?>
</div>

<hr/>
<script src="script/rule/rule_add.js"></script>
