<?php
$tab    = 91; ///-- ลดหนี้ซื้อ
$pm     = checkAccess($id_profile, $tab);
$view   = $pm['view'];
$add    = $pm['add'];
$edit   = $pm['edit'];
$delete = $pm['delete'];
accessDeny($view);

include 'function/supplier_helper.php';
include 'function/return_received_helper.php';
 ?>

<div class="container">
<?php
if( isset($_GET['edit']))
{
  include 'include/return_received/return_received_edit.php';
}
else if( isset($_GET['view_detail']))
{
  include 'include/return_received/return_received_detail.php';
}
else
{
  include 'include/return_received/return_received_list.php';
}

 ?>

</div><!-- container -->
<script src="script/return_received/return_received.js"></script>
