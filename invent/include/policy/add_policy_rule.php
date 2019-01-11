<?php
$id = isset($_GET['id_policy']) ? $_GET['id_policy'] : FALSE;
$policy =  new discount_policy($id);
$reference = $id === FALSE ? $policy->getNewReference() : $policy->reference;
//--- ถ้ามีไอดีแล้ว
$disabled = $id === FALSE ? '' : 'disabled';
$startDate = $id === FALSE ? '' : thaiDate($policy->date_start);
$endDate = $id === FALSE ? '' : thaiDate($policy->date_end);
?>


<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-triangle"></i> เพิ่ม/แก้ไข กฏของนโยบายส่วนลด</h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goEdit(<?php echo $id; ?>)">
        <i class="fa fa-arrow-left"></i> กลับ
      </button>
  <?php if( $add ) : ?>
    <button type="button" class="btn btn-sm btn-success" id="btn-save-rule" onclick="saveRule()" disabled>
      <i class="fa fa-save"></i> บันทึก
    </button>
  <?php endif; ?>
    </p>
  </div>
</div>
<hr class="margin-bottom-15"/>


<?php if($id == FALSE OR ! is_numeric($id) ) : ?>
<?php  include 'include/page_error.php'; ?>
<?php else : ?>
<div class="row">
  <div class="col-sm-3">
      เลขที่นโยบาย : <?php echo $reference; ?>
  </div>
  <div class="col-sm-9">
    ชื่อนโยบาย : <?php echo $policy->name; ?>
  </div>
</div>
<hr class="margin-top-15"/>
<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block"><a href="#discount" data-toggle="tab">ส่วนลด</a></li>
        <li class="li-block active"><a href="#customer" data-toggle="tab">ลูกค้า</a></li>
        <li class="li-block"><a href="#product" data-toggle="tab">สินค้า</a></li>
        <li class="li-block"><a href="#channels" data-toggle="tab">ช่องทางการขาย</a></li>
        <li class="li-block"><a href="#payments" data-toggle="tab">ช่องทางการชำระเงิน</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
        <?php include 'include/policy/rule/discount_rule.php'; ?>
        <?php include 'include/policy/rule/customer_rule.php'; ?>
        <?php include 'include/policy/rule/product_rule.php'; ?>
        <?php include 'include/policy/rule/channels_rule.php'; ?>
        <?php include 'include/policy/rule/payments_rule.php'; ?>
</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->
<script src="script/policy/rule/customer_tab.js"></script>
<?php endif; ?>
