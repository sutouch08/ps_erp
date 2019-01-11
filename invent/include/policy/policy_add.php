<?php
$id = isset($_GET['id_policy']) ? $_GET['id_policy'] : FALSE;
$policy =  new discount_policy($id);
$reference = $id === FALSE ? $policy->getNewReference() : $policy->reference;
//--- ถ้ามีไอดีแล้ว
$disabled = $id === FALSE ? '' : 'disabled';
$startDate = $id === FALSE ? '' : thaiDate($policy->date_start);
$endDate = $id === FALSE ? '' : thaiDate($policy->date_end);
$active = $policy->active == 1 ? 'btn-success' : '';
$disActive = $policy->active == 0 ? 'btn-danger' : '';
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-triangle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
  <?php echo goBackButton(); ?>
  <?php if( $add && $id != FALSE ) : ?>
    <button type="button" class="btn btn-sm btn-info" onclick="getActiveRuleList()"><i class="fa fa-plus"></i> เพิ่มกฏ</button>
  <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<?php include 'include/policy/policy_header.php'; ?>

<?php include 'include/policy/rule_list.php'; ?>

<script src="script/policy/policy_add.js"></script>
