<?php
	$id_tab   = 24;
  $pm       = checkAccess($id_profile, $id_tab);
	$view     = $pm['view'];
	$add      = $pm['add'];
	$edit     = $pm['edit'];
	$delete   = $pm['delete'];

	accessDeny($view);

  include 'function/customer_helper.php';
  include 'function/date_helper.php';
  include 'function/sponsor_helper.php';

  if( isset( $_GET['add']))
  {

    include 'include/sponsor_budget/sponsor_budget_add.php';

  }
	else if( isset( $_GET['edit']))
	{

		include 'include/sponsor_budget/sponsor_budget_edit.php';

	}
  else if( isset( $_GET['view_detail']))
  {

    include 'include/sponsor_budget/sponsor_budget_detail.php';

  }
  else
  {

    include 'include/sponsor_budget/sponsor_budget_list.php';

  }

	?>

<script src="script/sponsor_budget/sponsor_budget.js"></script>
