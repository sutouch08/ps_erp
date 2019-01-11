
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
<?php	$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sCreditCode') ? trim( getCookie('sCreditCode') ) : '' ); 		?>
<?php	$sName		= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sCreditName') ? trim( getCookie('sCreditName') ) : '' ); 	?>    
    
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>รหัสลูกค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" placeholder="ค้นหารหัสกลุ่ม" value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-3">
        	<label>ชื่อชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName" placeholder="ค้นหารชื่อกลุ่ม" value="<?php echo $sName; ?>" />
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
	$where	= "WHERE id_customer != '' ";
	if( $sCode != '' )
	{
		createCookie('sCreditCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}
	
	if( $sName != '' )
	{
		createCookie('sCreditName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}
	
	$where .= "ORDER BY code ASC";
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_customer_credit', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=customer_credit');
	
	$qs = dbQuery("SELECT * FROM tbl_customer_credit ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>    
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-5 text-center">ลำดับ</th>
                        <th class="width-15">รหัสลูกค้า</th>
                        <th class="width-35">ชื่อลูกค้า</th>
                        <th class="width-10 text-center">วงเงิน</th>
                        <th class="width-10 text-center">ใช้ไป</th>
                        <th class="width-10 text-center">คงเหลือ</th> 
                        <th class="width-15 text-center">ปรับปรุงล่าสุด</th> 
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-center"><?php echo number_format($rs->credit,2); ?></td>
                        <td class="middle text-center"><?php echo number_format($rs->used,2); ?></td>
                        <td class="middle text-center"><?php echo number_format($rs->balance,2); ?></td>
                        <td class="middle text-center"><?php echo thaiDate($rs->date_upd); ?></td>
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
    
</div><!--/ Container -->
<script src="script/customer_credit.js"></script>