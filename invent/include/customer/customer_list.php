
<?php
	$cName	= isset( $_POST['cName'] ) ?  trim( $_POST['cName'] ) : ( getCookie('cName') ? getCookie('cName') : '') ;
 	$cCode	= isset( $_POST['cCode'] ) ? trim( $_POST['cCode'] ) : ( getCookie('cCode') ? getCookie('cCode') : '' );
	$cKind	= isset( $_POST['cKind'] ) ? trim( $_POST['cKind'] ) : ( getCookie('cKind') ? getCookie('cKind') : '0' );
	$cType	= isset( $_POST['cType'] ) ? trim( $_POST['cType'] ) : ( getCookie('cType') ? getCookie('cType') : '0' );
	$cClass	= isset( $_POST['cClass'] ) ? trim( $_POST['cClass'] ) : ( getCookie('cClass') ? getCookie('cClass') : '0' );
 	$cGroup 	= isset( $_POST['cGroup'] ) ? trim( $_POST['cGroup'] ) : ( getCookie('cGroup') ? getCookie('cGroup') : '0' );
	$cArea	= isset( $_POST['cArea'] ) ? trim( $_POST['cArea'] ) : ( getCookie('cArea') ? getCookie('cArea') : '' );
 	$cProvince = isset( $_POST['cProvince'] ) ? trim( $_POST['cProvince'] ) : ( getCookie('cProvince') ? getCookie('cProvince') : '' );

?>

    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-1 col-1-harf padding-5 first">
        	<label>ชื่อลูกค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cName" id="cName" placeholder="" value="<?php echo $cName; ?>"  />
        </div>
        <div class="col-sm-1 col-1-harf padding-5">
        	<label>รหัสลูกค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cCode" id="cCode" placeholder="" value="<?php echo $cCode; ?>"  />
        </div>
        <div class="col-sm-1 col-1-harf padding-5">
        	<label>กลุ่มลูกค้า</label>
            <select class="form-control input-sm select-box" name="cGroup" id="cGroup">
            <?php echo selectCustomerGroup($cGroup); ?>
            </select>
        </div>
        <div class="col-sm-1 col-1-harf padding-5">
        	<label>ประเภท</label>
            <select class="form-control input-sm select-box" name="cKind" id="cKind">
            <?php echo selectCustomerKind($cKind); ?>
            </select>

        </div>

        <div class="col-sm-1 col-1-harf padding-5">
        	<label>ชนิด</label>
            <select class="form-control input-sm select-box" name="cType" id="cType">
            <?php echo selectCustomerType($cType); ?>
            </select>
        </div>
        <div class="col-sm-1 col-1-harf padding-5">
        	<label>เกรด</label>
            <select class="form-control input-sm select-box" name="cClass" id="cClass">
            <?php echo selectCustomerClass($cKind); ?>
            </select>

        </div>
        <div class="col-sm-1 col-1-harf padding-5">
        	<label>พื้นที่การขาย</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cArea" id="cArea" placeholder="" value="<?php echo $cArea; ?>"  />
        </div>

        <div class="col-sm-1 col-1-harf padding-5">
        	<label>จังหวัด</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cProvince" id="cProvince" placeholder="" value="<?php echo $cProvince; ?>" />
        </div>
        <div class="col-sm-1">
        	<label class="display-block not-show">apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()" ><i class="fa fa-search"></i> Search</button>
        </div>
       <div class="col-sm-1">
        	<label class="display-block not-show">reset</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()" ><i class="fa fa-retweet"></i> Reset</button>
        </div>
    </div>
    </form>
<hr class="margin-top-15"/>

<?php
	$where = "WHERE is_deleted = 0 ";
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

	if( $cGroup != '0' )
	{
		createCookie('cGroup', $cGroup);
		$where .= "AND id_group = '".$cGroup."' ";
	}

	if( $cKind !="0" )
	{
		createCookie('cKind', $cKind);
		$where .= "AND id_kind = '".$cKind."' ";
	}


	if( $cType !="0" )
	{
		createCookie('cType', $cType);
		$where .= "AND id_type = '".$cType."' ";
	}

	if( $cClass !="0" )
	{
		createCookie('cClass', $cClass);
		$where .= "AND id_class = '".$cClass."' ";
	}


	if( $cArea != "" )
	{
		createCookie('cArea', $cArea);
		$where .= "AND id_area IN(".customerAreaIn($cArea).") ";
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
                <th class="width-30">ชื่อ - สกุล</th>
                <th class="width-15 text-center">กลุ่มลูกค้า</th>
                <th class="width-15 text-center">พื้นที่การขาย</th>
                <th class="width-10 text-center">จังหวัด</th>
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
                    <td class="middle text-center"><?php echo $rs->province; ?></td>
                    <td class="middle" align="right">
											<button type="button" class="btn btn-xs btn-info" onclick="viewDetail('<?php echo $rs->id; ?>')">
												<i class="fa fa-eye"></i>
											</button>
							<?php if( $edit ) : ?>
											<button type="button" class="btn btn-xs btn-warning" onclick="getEdit('<?php echo $rs->id; ?>')">
												<i class="fa fa-pencil"></i>
											</button>
							<?php endif; ?>
							<?php if($delete) : ?>
											<button type="button" class="btn btn-xs btn-danger" onclick="deleteCustomer('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')">
												<i class="fa fa-trash"></i>
											</button>
							<?php endif; ?>
                    </td>
                </tr>
	<?php	$no++; ?>
    <?php	endwhile; ?>
    <?php else : ?>
    		<tr>
            	<td colspan="7" align="center"><h4> ไม่พบข้อมูลตามเงื่อนไขที่กำหนด</h4></td>
            </tr>
    <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
