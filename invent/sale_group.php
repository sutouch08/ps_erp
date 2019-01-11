<?php
	$id_tab 		= 68;
	$id_profile 	= $_COOKIE['profile_id'];
    $pm 			= checkAccess($id_profile, $id_tab);
	$view 		= $pm['view'];
	$delete 		= $pm['delete'];
	accessDeny($view);
?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-users"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button class="btn btn-sm btn-success" onClick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>
<?php	$stCode 	= isset( $_POST['stCode'] ) ? trim( $_POST['stCode'] ) : ( getCookie('stCode') ? trim( getCookie('stCode') ) : '' ); 		?>
<?php	$stName		= isset( $_POST['stName'] ) ? trim( $_POST['stName'] ) : ( getCookie('stName') ? trim( getCookie('stName') ) : '' ); 	?>    
    
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>รหัสกลุ่ม</label>
            <input type="text" class="form-control input-sm text-center search-box" name="stCode" id="stCode" placeholder="ค้นหารหัสทีมขาย" value="<?php echo $stCode; ?>"  />
        </div>
        <div class="col-sm-3">
        	<label>ชื่อกลุ่ม</label>
            <input type="text" class="form-control input-sm text-center search-box" name="stName" id="stName" placeholder="ค้นหารชื่อทีมขาย" value="<?php echo $stName; ?>" autofocus />
        </div>
        <div class="col-sm-2">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
        </div>
        <div class="col-sm-2">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
        </div>
        
    </div>
    </form>
    <hr class="margin-top-15" />
    
<?php 
	$where	= "WHERE id != 0 ";
	if( $stCode != '' )
	{
		createCookie('stCode', $stCode);
		$where .= "AND code LIKE '%". $stCode ."%' ";
	}
	
	if( $stName != '' )
	{
		createCookie('stName', $stName);
		$where .= "AND name LIKE '%". $stName ."%' ";
	}
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_sale_group', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=group');
	
	$qs = dbQuery("SELECT * FROM tbl_sale_group ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>    
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-20">รหัสทีมขาย</th>
                        <th class="width-50">ชื่อทีมขาย</th>
                        <th class="width-10 text-center">สมาชิก</th>
                        <th class="width-10"></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$st = new sale_group(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->code; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-center"><?php echo number_format( $st->countMember($rs->id) ); ?></td>
                        <td class="middle text-right">
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-sm btn-danger" onClick="deleteGroup('<?php echo $rs->code; ?>', '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></button>
                        <?php endif; ?>
                        </td>
                    </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php	else : ?>
				<tr>
                	<td colspan="6" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>          
                </tbody>                
            </table>
        </div>
    </div>
    
</div><!--/ Container -->
<script src="script/sale_group.js"></script>