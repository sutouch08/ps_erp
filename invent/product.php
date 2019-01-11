<?php
	$id_tab 		= 1;
  $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);
	
	require 'function/product_helper.php';
	require 'function/product_group_helper.php';
	require 'function/category_helper.php';
	require 'function/kind_helper.php';
	require 'function/type_helper.php';
	require 'function/productTab_helper.php';
	require 'function/date_helper.php';

	?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tags"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">

            <?php if( ! isset( $_GET['edit'] ) && ! isset( $_GET['deleted'] ) && ! isset( $_GET['view_detail'] ) ) : ?>
            	<button type="button" class="btn btn-sm btn-success" onclick="syncMaster()"><i class="fa fa-plus"></i> อัพเดตข้อมูล</button>
			<?php endif; ?>


            <?php if( isset( $_GET['edit'] ) OR isset( $_GET['deleted'] ) OR isset( $_GET['view_detail'] ) ) : ?>
            	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
            <?php endif; ?>
            </p>
        </div>
    </div>
    <hr />

<?php
	$pd = new product();
	$pg = new product_group();
	$bd = new brand();

	if( isset( $_GET['edit'] ) )
	{
		include 'include/product/product_edit.php';
	}
	else if( isset( $_GET['deleted'] ) )
	{
		include 'include/product/product_deleted.php';
	}
	else
	{
		include 'include/product/product_list.php';
	}

?>


</div><!--/ Container -->

<script src="script/product/product.js"></script>
