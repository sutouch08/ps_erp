<div class="row top-row">
	<div class="col-sm-6 top-col"><h4 class="title"><i class="fa fa-home"></i>&nbsp;<?php echo $pageName; ?></h4></div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <?php if( $add ) : ?>
        	<button type="button" class="btn btn-sm btn-success" onclick="syncWarehouse()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
        <?php endif; ?>
       </p>
    </div>
</div>
<hr class="margin-bottom-15" />


<?php
	$emp = new employee();
	$whCode		= getFilter('whCode', 'whCode', '');
	$whName		= getFilter('whName', 'whName', '');
	$whRole		= getFilter('whRole', 'whRole', 0);
	$sBranch  = getFilter('sBranch', 'sBranch', '');
	$underZero	= getFilter('underZero', 'underZero', 2);

?>
<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>รหัส</label>
        <input type="text" class="form-control input-sm search-box" name="whCode" id="whCode" placeholder="ค้นหารหัสคลัง" value="<?php echo $whCode; ?>" />
    </div>
    <div class="col-sm-3 padding-5">
    	<label>ชื่อคลัง</label>
        <input type="text" class="form-control input-sm search-box" name="whName" id="whName" placeholder="ค้าหาชื่อคลัง" value="<?php echo $whName; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ประเภทคลัง</label>
        <select class="form-control input-sm search-select" name="whRole" id="whRole">
        <?php echo selectWarehouseRole($whRole); ?>
        </select>
    </div>
		<div class="col-sm-2 padding-5">
			<label>สาขา</label>
			<select class="form-control input-sm search-select" name="sBranch" id="sBranch">
				<option value="">โปรดเลือก</option>
				<?php echo selectBranch($sBranch); ?>
			</select>
		</div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label >สต็อกติดลบ</label>
        <select class="form-control input-sm search-select" name="underZero" id="underZero">
        	<option value="2" <?php echo isSelected($underZero, 2); ?>>ทั้งหมด</option>
            <option value="1" <?php echo isSelected($underZero, 1); ?>>ติดลบได้</option>
            <option value="0" <?php echo isSelected($underZero, 0); ?>>ติดลบไม่ได้</option>
        </select>
    </div>
    <div class="col-sm-1 padding-5">
    	<label class="display-block not-show">ใช้ตัวกรอง</label>
        <button type="button" class="btn btn-sm btn-block btn-success" onclick="getSearch()"><i class="fa fa-search"></i> ใช้ตัวกรอง</button>
    </div>
     <div class="col-sm-1 padding-5 last">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-block btn-warning" onclick="resetSearch()"><i class="fa fa-retweet"></i> รีเซ็ต</button>
    </div>
</div>
</form>
<hr class="margin-top-10"/>
<?php
	$where 	= "WHERE id != '' ";
	if( $whCode != '' )
	{
		createCookie('whCode', $whCode);
		$where .= "AND code LIKE '%".$whCode."%' ";
	}


	if( $whName != '' )
	{
		createCookie('whName', $whName);
		$where .= "AND name LIKE '%".$whName."%' ";
	}


	if( $whRole != 0 )
	{
		createCookie('whRole', $whRole);
		$where .= "AND role = ".$whRole." ";
	}


	if($sBranch !='')
	{
		createCookie('sBranch', $sBranch);
		$where .= "AND id_branch = ".$sBranch." ";
	}

	if( $underZero != 2 )
	{
		createCookie('underZero', $underZero);
		$where .= "AND allow_under_zero = ".$underZero." ";
	}

	$where .= "ORDER BY code ASC";

	$paginator	= new paginator();
	$get_rows 	= get_rows();
	$page		= get_page();
	$paginator->Per_Page("tbl_warehouse",$where,$get_rows);
	$paginator->display($get_rows,"index.php?content=warehouse");
	$qs = dbQuery("SELECT * FROM tbl_warehouse ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped">
        	<thead>
            	<tr class="font-size-12">
                	<th class="width-5 text-center">ลำดับ</th>
                  <th class="width-10 text-center">รหัสคลัง</th>
                  <th class="width-25">ชื่อคลัง</th>
									<th class="width-10 text-center">สาขา</th>
                  <th class="width-10 text-center">ประเภทคลัง</th>
									<th class="width-5 text-center">โซน</th>
                  <th class="width-5 text-center">ขาย</th>
                  <th class="width-5 text-center">จัด</th>
                  <th class="width-5 text-center">ติดลบ</th>
                  <th class="width-5 text-center">ใช้งาน</th>
									<th class="text-center">แก้ไข</th>
                  <th class="width-8 text-center"></th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : ?>
    <?php	$no	= ($get_rows * ($page -1)) + 1 ;	?>
		<?php $zone = new zone(); ?>
    <?php	while( $rs = dbFetchObject($qs) ) : 	?>
		<?php $branch = new branch($rs->id_branch); ?>
    			<tr style="font-size:12px;" id="row_<?php echo $rs->id; ?>">
            <td class="text-center middle">
              <?php echo number_format($no); ?>
            </td>

            <td class="text-center middle">
              <?php echo $rs->code; ?>
            </td>

            <td class="middle">
              <?php echo $rs->name; ?>
            </td>

						<td class="middle text-center">
							<?php echo $branch->name; ?>
						</td>

            <td class="text-center middle">
              <?php echo getWarehouseRoleName($rs->role); ?>
            </td>

						<td class="text-center middle">
              <?php echo number($zone->countWarehouseZone($rs->id)); ?>
            </td>

            <td class="text-center middle">
              <?php if( $edit ) : ?>
              <input type="hidden" id="sell-<?php echo $rs->id; ?>" value="<?php echo $rs->sell; ?>" />
              <a href="javascript:void(0)" id="sell-label-<?php echo $rs->id; ?>" onclick="setSell('<?php echo $rs->id; ?>')">
              <?php echo isActived($rs->sell); ?>
              </a>
              <?php else : ?>
                <?php echo isActived($rs->sell); ?>
              <?php endif; ?>
            </td>

            <td class="text-center middle">
              <?php if( $edit ) : ?>
              <input type="hidden" id="prepare-<?php echo $rs->id; ?>" value="<?php echo $rs->sell; ?>" />
              <a href="javascript:void(0)" id="prepare-label-<?php echo $rs->id; ?>" onclick="setPrepare('<?php echo $rs->id; ?>')">
              <?php echo isActived($rs->prepare); ?>
              </a>
              <?php else : ?>
                <?php echo isActived($rs->prepare); ?>
              <?php endif; ?>

            </td>

            <td class="text-center middle">
              <?php if( $edit ) : ?>
              <input type="hidden" id="auz-<?php echo $rs->id; ?>" value="<?php echo $rs->sell; ?>" />
              <a href="javascript:void(0)" id="auz-label-<?php echo $rs->id; ?>" onclick="setAuz('<?php echo $rs->id; ?>')">
              <?php echo isActived($rs->allow_under_zero); ?>
              </a>
              <?php else : ?>
                <?php echo isActived($rs->allow_under_zero); ?>
              <?php endif; ?>
            </td>

            <td class="text-center middle">
              <?php if( $edit ) : ?>
              <input type="hidden" id="active-<?php echo $rs->id; ?>" value="<?php echo $rs->sell; ?>" />
              <a href="javascript:void(0)" id="active-label-<?php echo $rs->id; ?>" onclick="setActive('<?php echo $rs->id; ?>')">
              <?php echo isActived($rs->active); ?>
              </a>
              <?php else : ?>
                <?php echo isActived($rs->active); ?>
              <?php endif; ?>

            </td>

						<td class="middle text-center">
							<?php if($rs->emp_upd != '') : ?>
								<?php echo $emp->getName($rs->emp_upd); ?>
							<?php endif; ?>
						</td>

            <td align="right" class="middle">
            <?php if( $edit ) : ?>
              <button type="button" class="btn btn-xs btn-warning" onclick="edit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
					  <?php endif; ?>
            <?php if( $delete ) : ?>
              <button type="button" class="btn btn-xs btn-danger" onclick="deleteWarehouse('<?php echo $rs->id; ?>')"><i class="fa fa-trash"></i></button>
					  <?php endif; ?>
            </td>
          </tr>
	<?php 	$no++; ?>
	<?php	endwhile; ?>
    <?php endif; ?>
        </tbody>

    </table>
  </div>
</div>
