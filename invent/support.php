<?php
	$id_tab   = 38;
  $pm       = checkAccess($id_profile, $id_tab);
	$view     = $pm['view'];
	$add      = $pm['add'];
	$edit     = $pm['edit'];
	$delete   = $pm['delete'];

	accessDeny($view);

  include 'function/employee_helper.php';
  include 'function/date_helper.php';
  include 'function/support_helper.php';

  if( isset( $_GET['add']))
  {

    include 'include/support_budget/support_budget_add.php';

  }
	else if( isset( $_GET['edit']))
	{

		include 'include/support_budget/support_budget_edit.php';

	}
  else if( isset( $_GET['view_detail']))
  {

    include 'include/support_budget/support_budget_detail.php';

  }
  else
  {

    include 'include/support_budget/support_budget_list.php';

  }

	?>

<script src="script/support_budget/support_budget.js"></script>
