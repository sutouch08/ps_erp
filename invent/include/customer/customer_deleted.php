
<?php
	
	$cName	= isset( $_POST['cName'] ) ?  trim( $_POST['cName'] ) : ( getCookie('cName') ? getCookie('cName') : '') ;
 	$cCode	= isset( $_POST['cCode'] ) ? trim( $_POST['cCode'] ) : ( getCookie('cCode') ? getCookie('cCode') : '' );
 	$cGroup 	= isset( $_POST['cGroup'] ) ? trim( $_POST['cGroup'] ) : ( getCookie('cGroup') ? getCookie('cGroup') : '' );
	$cArea	= isset( $_POST['cArea'] ) ? trim( $_POST['cArea'] ) : ( getCookie('cArea') ? getCookie('cArea') : '' );
 	$cProvince = isset( $_POST['cProvince'] ) ? trim( $_POST['cProvince'] ) : ( getCookie('cProvince') ? getCookie('cProvince') : '' );

?>

    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-2">
        	<label>ชื่อลูกค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cName" id="cName" placeholder="" value="<?php echo $cName; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>รหัสลูกค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cCode" id="cCode" placeholder="" value="<?php echo $cCode; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>กลุ่มลูกค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cGroup" id="cGroup" placeholder="" value="<?php echo $cGroup; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>พื้นที่การขาย</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cArea" id="cArea" placeholder="" value="<?php echo $cArea; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>จังหวัด</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cProvince" id="cProvince" placeholder="" value="<?php echo $cProvince; ?>" />
        </div>
        <div class="col-sm-1">
        	<label class="display-block not-show">apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()" ><i class="fa fa-search"></i> Search</button>
        </div>
       <div class="col-sm-1">
        	<label class="display-block not-show">reset</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearDeletedFilter()" ><i class="fa fa-retweet"></i> Reset</button>
        </div>
    </div>
    </form>
<hr class="margin-top-15"/>

<?php
	$where = "WHERE is_deleted = 1 ";
	if( $cName != '' )
	{
		createCookie('cName', $cName);
		$where .= "AND name LIKE '%". $cName ."%' ";
	}

	if( $cCode != '' )
	{
		createCookie('cCode', $cCode);
		$where .= "AND code LIKE '%" . $cCode . "%' ";
	}

	if( $cGroup != '' )
	{
		createCookie('cGroup', $cGroup);
		$where .= "AND group_code IN(".customerGroupIn($cGroup).") ";
	}

	if( $cArea != "" )
	{
		createCookie('cArea', $cArea);
		$where .= "AND area_code IN(".customerAreaIn($cArea).") ";
	}


	if( $cProvince != '' )
	{
		createCookie('cProvince', $cProvince);
		$where .= "AND province LIKE '%" . $cProvince ."%' ";
	}

	$where .= "ORDER BY name ASC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_customer', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=customer');

	$qs = dbQuery("SELECT * FROM tbl_customer ". $where ." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>

<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped border-1">
        	<thead>
            	<th class="width-5 text-center">ลำดับ</th>
                <th class="width-10">รหัสลูกค้า</th>
                <th class="width-25">ชื่อ - สกุล</th>
                <th class="width-15 text-center">กลุ่มลูกค้า</th>
                <th class="width-15 text-center">พื้นที่การขาย</th>
                <th class="width-10 text-center">ผู้ลบ</th>
                <th class="width-10 text-center">วันที่ลบ</th>
                <th></th>
            </thead>
            <tbody>
	<?php if( dbNumRows( $qs ) > 0 ) : ?>
    <?php	$no = row_no(); ?>
    <?php	$cg = new customer_group(); ?>
    <?php	$ca = new customer_area(); ?>
    <?php	while( $rs = dbFetchObject($qs) ) : ?>
    			<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                	<td class="middle text-center"><?php echo number_format($no); ?></td>
                    <td class="middle"><?php echo $rs->code; ?></td>
                    <td class="middle"><?php echo $rs->name; ?></td>
                    <td class="middle text-center"><?php echo $cg->getGroupName($rs->id_group); ?></td>
                    <td class="middle text-center"><?php echo $ca->getAreaName($rs->id_area); ?></td>
                    <td class="middle text-center"><?php echo employee_name($rs->emp); ?></td>
                    <td class="middle text-center"><?php echo thaiDate($rs->date_upd); ?></td>
                    <td class="middle" align="right">
                    <?php if( $delete ) : ?>
                    	<button type="button" class="btn btn-sm btn-primary" onclick="unDeleteCustomer('<?php echo $rs->id; ?>')">ยกเลิกการลบ</button>
                    <?php endif; ?>
                    </td>
                </tr>
	<?php	$no++; ?>
    <?php	endwhile; ?>
    <?php else : ?>
    		<tr>
            	<td colspan="8" align="center"><h4> ไม่พบข้อมูลตามเงื่อนไขที่กำหนด</h4></td>
            </tr>
    <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
