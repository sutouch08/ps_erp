<?php
	$id_tab 		= 23;
	$id_profile 	= $_COOKIE['profile_id'];
    $pm 			= checkAccess($id_profile, $id_tab);
	$view 		= $pm['view'];
	$delete 		= $pm['delete'];
	accessDeny($view);
	
	include 'function/group_helper.php';
?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-users"></i> กลุ่มลูกค้า</h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button class="btn btn-sm btn-success" onClick="syncCustomerGroup()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>
<?php	$cgCode 	= isset( $_POST['cgCode'] ) ? trim( $_POST['cgCode'] ) : ( getCookie('cgCode') ? trim( getCookie('cgCode') ) : '' ); 		?>
<?php	$cgName		= isset( $_POST['cgName'] ) ? trim( $_POST['cgName'] ) : ( getCookie('cgName') ? trim( getCookie('cgName') ) : '' ); 	?>    
    
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>รหัสกลุ่ม</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cgCode" id="cgCode" placeholder="ค้นหารหัสกลุ่ม" value="<?php echo $cgCode; ?>"  />
        </div>
        <div class="col-sm-3">
        	<label>ชื่อกลุ่ม</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cgName" id="cgName" placeholder="ค้นหารชื่อกลุ่ม" value="<?php echo $cgName; ?>" autofocus />
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
	if( $cgCode != '' )
	{
		createCookie('cgCode', $cgCode);
		$where .= "AND code LIKE '%". $cgCode ."%' ";
	}
	
	if( $cgName != '' )
	{
		createCookie('cgName', $cgName);
		$where .= "AND name LIKE '%". $cgName ."%' ";
	}
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_customer_group', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=group');
	
	$qs = dbQuery("SELECT * FROM tbl_customer_group ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>    
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-20">รหัสกลุ่ม</th>
                        <th class="width-50">ชื่อกลุ่ม</th>
                        <th class="width-10 text-center">สมาชิก</th>
                        <th class="width-10"></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cg = new customer_group(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-center"><?php echo number_format( $cg->countMember($rs->code) ); ?></td>
                        <td class="middle text-right">
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-sm btn-danger" onClick="deleteGroup('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></button>
                        <?php endif; ?>
                        </td>
                    </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php	else : ?>
				<tr>
                	<td colspan="5" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>          
                </tbody>                
            </table>
        </div>
    </div>
    
</div><!--/ Container -->
<script src="script/customer_group.js"></script>