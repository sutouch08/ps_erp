<?php
	$id_tab = 69;
 	$pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$delete 	= $pm['delete'];
	accessDeny($view);

?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tags"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button type="button" class="btn btn-sm btn-success" onClick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>
    
<?php $uCode = isset( $_POST['uCode'] ) ? trim( $_POST['uCode'] ) : ( getCookie('uCode') ? getCookie('uCode') : '' );		?>
<?php $uName	= isset( $_POST['uName'] ) ? trim( $_POST['uName'] ) : ( getCookie('uName') ? getCookie('uName') : '' ); 	?>

<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-3">
    	<label>รหัส</label>
        <input type="text" class="form-control input-sm text-center" name="uCode" id="uCode"  value="<?php echo $uCode; ?>" autofocus />
    </div>
    <div class="col-sm-3">
    	<label>ชื่อหน่วยนับ</label>
        <input type="text" class="form-control input-sm text-center" name="uName" id="uName" value="<?php echo $uName; ?>" />
    </div> 
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
</div>
</form>
<hr class="margin-top-10"/>
<?php
	$where = "WHERE id != '' ";
	if( $uCode != '' )
	{
		createCookie('uCode', $uCode);
		$where .= "AND code LIKE '%" . $uCode . "%' ";	
	}
	
	if( $uName != '' )
	{
		createCookie('uName', $uName);
		$where .= "AND name LIKE '%" . $uName ."%' ";
	}
	
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$page		= get_page();
	$paginator->Per_Page('tbl_unit', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=unit');


	$qs = dbQuery("SELECT * FROM tbl_unit ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped" style="border:solid 1px #CCC;">
        	<thead>
            	<tr>
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-15 ">รหัส</th>
                    <th class="width-50 ">ชื่อหน่วยนับ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : ?>
    <?php	$no = row_no(); ?>
    <?php 	while( $rs = dbFetchObject($qs) ) : ?>
    			<tr style="font-size:14px;" id="row_<?php echo $rs->id; ?>">
                	<td align="center"><?php echo $no; ?></td>
                    <td><?php echo $rs->code; ?></td>
                    <td><?php echo $rs->name; ?></td>
                    <td align="right">
                    <?php if( $delete ) : ?>
                    	<button type="button" class="btn btn-sm btn-danger" onClick="deleteUnit('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                    </td>
                </tr>   
	<?php	$no++; 	?>
    <?php	endwhile; ?>      
    <?php else : ?>
    			<tr>
                	<td colspan="4" align="center"><h4>ไม่พบรายการตามเงื่อนไขที่กำหนด</h4></td>
                </tr>
    <?php endif; ?>            	
            </tbody>
        </table>
    </div>
</div>
 
</div><!--/ Container -->
<script src="script/unit.js"></script>