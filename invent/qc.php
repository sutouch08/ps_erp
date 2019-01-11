<?php
  $id_tab = 18;
  $pm  = checkAccess($id_profile, $id_tab);
  $view = $pm['view'];
  $add  = $pm['add'];
  $edit = $pm['edit'];
  $delete = $pm['delete'];

  accessDeny($view);

	$ps = checkAccess($id_profile, 58);
	$suppervisor = $ps['add'] + $ps['edit'] + $ps['delete'] > 0 ? TRUE : FALSE;

  include 'function/qc_helper.php';
  include 'function/order_helper.php';
  include 'function/customer_helper.php';
  include 'function/prepare_helper.php';
  include 'function/branch_helper.php';

 ?>
<div class="container">

<?php
  if( isset($_GET['process']))
  {
    include 'include/qc/qc_process.php';
  }
  else if( isset( $_GET['view_process']))
  {
    include 'include/qc/qc_process_list.php';
  }
  else
  {
    include 'include/qc/qc_list.php';
  }
?>

</div><!--/ end container -->
<script src="script/qc/qc.js"></script>
<script src="../library/js/beep.js"></script>
