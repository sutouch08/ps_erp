<?php
  $id_tab = 17;
  $pm = checkAccess($id_profile, $id_tab);
  $view = $pm['view'];
  $add = $pm['add'];
  $edit = $pm['edit'];
  $delete = $pm['delete'];

  $ps	= checkAccess($id_profile, 57); /// จัดสินค้าแทนคนอื่นได้หรือป่าว

  $supervisor = $ps['add'] + $ps['edit'] + $ps['delete'] > 0 ? 1 : 0;

  include 'function/prepare_helper.php';
  include 'function/qc_helper.php';
  include 'function/product_helper.php';
  include 'function/customer_helper.php';
  include 'function/employee_helper.php';
  include 'function/order_helper.php';
  include 'function/branch_helper.php';
  accessDeny($view);

?>
<div class="container">
<?php
  if( isset( $_GET['process']))
  {
    include 'include/prepare/prepare_process.php';
  }
  else if( isset( $_GET['viewProcess']))
  {
    include 'include/prepare/prepare_view_process.php';
  }
  else
  {
    include 'include/prepare/prepare_list.php';
  }

?>

<script src="script/prepare/prepare.js"></script>


</div>
