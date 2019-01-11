<?php
$tab    = 40;
$pm     = checkAccess($id_profile, $tab);
$view   = $pm['view'];
$add    = $pm['add'];
$edit   = $pm['edit'];
$delete = $pm['delete'];
accessDeny($view);

include 'function/customer_helper.php';
include 'function/return_order_helper.php';
 ?>

<div class="container">
<?php
if( isset($_GET['edit']))
{
  include 'include/return_order/return_order_receive.php';
}
else if( isset($_GET['view_detail']))
{
  include 'include/return_order/return_order_detail.php';
}
else
{
  include 'include/return_order/return_order_list.php';
}

 ?>

</div><!-- container -->
<script src="script/return_order/return_order.js"></script>
