<?php 
if( ! isset( $_GET['id_style'] ) ) : 
 	include 'include/page_error.php'; 
 else : 
	$id_style = $_GET['id_style'];		
	if( $edit ) :
		$activeTab		= isset( $_GET['tab'] ) ? $_GET['tab'] : 'info' ; 
		$tab1				= $activeTab == 'info' ? 'active in' : '';	
		$tab2				= $activeTab == 'items-list' ? 'active in' : '' ; 
		$tab3				= $activeTab == 'images-list' ? 'active in' : '';
?>
<script src="<?php echo WEB_ROOT; ?>library/js/dropzone.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="<?php  echo WEB_ROOT;?>library/css/dropzone.css" />
<link rel="stylesheet" href="<?php echo WEB_ROOT; ?>library/css/colorbox.css" />
<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block <?php echo $tab1; ?>" onClick="changeURL('info')"><a href="#info" data-toggle="tab">ข้อมูลสินค้า</a></li>
        <li class="li-block <?php echo $tab2; ?>" onClick="changeURL('items-list')"><a href="#items-list" data-toggle="tab">รายการ</a></li>
        <li class="li-block <?php echo $tab3; ?>" onClick="changeURL('images-list')"><a href="#images-list" data-toggle="tab">รูปภาพ</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
<?php
	include 'include/product/product_info.php';
	include 'include/product/product_items.php';
	include 'include/product/product_image.php';

?>
       
</div>
</div><!--/ col-sm-9  -->    
</div><!--/ row  -->

<?php	endif; ?>
<?php endif; ?>