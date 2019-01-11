<?php	$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sCode') ? getCookie('sCode') : '' ); 		?>
<?php	$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sName') ? getCookie('sName') : '' ); 		?>
<?php 	$stName	= isset( $_POST['stName'] ) ? trim( $_POST['stName'] ) : ( getCookie('stName') ? getCookie('stName') : '' );	?>
    
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>รหัสพนักงาน</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" placeholder="ค้นหารหัสพนักงานขาย" value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-3">
        	<label>ชื่อพนักงาน</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName" placeholder="ค้นหารชื่อพนักงานขาย" value="<?php echo $sName; ?>" autofocus />
        </div>
        <div class="col-sm-3">
        	<label>ทีมขาย</label>
            <input type="text" class="form-control input-sm text-center search-box" name="stName" id="stName" placeholder="ค้นหารชื่อพนักงานขาย" value="<?php echo $stName; ?>" autofocus />
        </div>
        <div class="col-sm-1 col-1-harf">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
        </div>
        <div class="col-sm-1 col-1-harf">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearDeletedFilter()"><i class="fa fa-retweet"></i> Reset</button>
        </div>
        
    </div>
    </form>
    <hr class="margin-top-15" />
    
<?php 
	$where	= "WHERE is_deleted = 1 ";
	if( $sCode != '' )
	{
		createCookie('sCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}
	
	
	if( $sName != '' )
	{
		createCookie('sName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}
	
	if( $stName != '' )
	{
		createCookie('stName', $stName);
		$in		= saleGroupCodeIn($stName);
		$where .= $in === FALSE ? "AND group_code = '000' " : "AND group_code IN(".$in.") ";
	}
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_sale', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=sale');
	
	$qs = dbQuery("SELECT * FROM tbl_sale ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>    
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
     
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-10">รหัส</th>
                        <th class="width-30">ชื่อ</th>
                        <th class="width-10">ทีมขาย</th>
                        <th class="width-15">ผู้ลบ</th>
                        <th class="width-10 text-center">วันที่ลบ</th>
                        <th class=""></th>
    
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$sg = new sale_group(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle"><?php echo $sg->getSaleGroupName($rs->id_group); ?></td>
                        <td class="middle"><?php echo employee_name($rs->emp_delete); ?></td>
                        <td class="middle text-center"><?php echo thaiDate($rs->date_upd, '/'); ?></td>
                        <td class="middle text-right">
                        <?php if( $add ) : ?>
                        	<button type="button" class="btn btn-sm btn-warning" onClick="unDelete('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')">ยกเลิกการลบ</button>
                        <?php endif; ?>
                        </td>
                    </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php	else : ?>
				<tr>
                	<td colspan="7" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>          
                </tbody>                
            </table>
        </div>
    </div>