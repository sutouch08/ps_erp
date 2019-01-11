<?php
$id = isset($_GET['id_policy']) ? $_GET['id_policy'] : FALSE;
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-triangle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
  <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr/>
<?php
if($id === FALSE)
{
  include 'include/page_error.php';
}
else
{
  $policy =  new discount_policy($id);
  $reference = $policy->reference;
  $startDate = $id === FALSE ? '' : thaiDate($policy->date_start);
  $endDate = $id === FALSE ? '' : thaiDate($policy->date_end);
  $active = $policy->active == 1 ? 'btn-success' : '';
  $disActive = $policy->active == 0 ? 'btn-danger' : '';
  $disabled = 'disabled';
  include 'include/policy/policy_header.php';
  include 'include/policy/rule_list.php';
}
?>

<script src="script/policy/policy_add.js"></script>
