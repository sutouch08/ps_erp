
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-file-text-o"></i> รายการรอเปิดบิล</h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </p>
  </div>

</div>
<hr/>
<?php
if( ! isset($_GET['id_order']) OR $_GET['id_order'] == '')
{
  include 'include/page_error.php';
  exit;
}
?>

<?php
  $order = new order($_GET['id_order']);
  if( $order->state == 7)
  {
    include 'include/bill/bill_confirm_detail.php';
  }
  else if( $order->state == 8)
  {
    include 'include/bill/bill_closed_detail.php';
  }
  else
  {
    include 'include/bill/bill_state_error.php';
  }


?>
