<?php
	$id_tab 		= 4;
    $pm 			= checkAccess($id_profile, $id_tab);
	$view 		= $pm['view'];
	$add			= $pm['add'];
	$edit			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);
?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tag"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button class="btn btn-sm btn-success" onClick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>
<?php
			$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('bCode') ? getCookie('bCode') : '' );
			$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('bName') ? getCookie('bName') : '' );
?>


    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-2">
        	<label>รหัส</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode"  value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>ชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName"  value="<?php echo $sName; ?>"  />
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


	$where .= "ORDER BY code ASC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_brand', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=brand');

	$qs = dbQuery("SELECT * FROM tbl_brand ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-15">รหัส</th>
                        <th class="width-30">ชื่อ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cs = new brand(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-right">
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-xs btn-danger" onClick="remove('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')">
                            	<i class="fa fa-trash"></i>
                            </button>
                        <?php endif; ?>
                        </td>
                    </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php	else : ?>
				<tr>
                	<td colspan="4" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div><!--/ Container -->
<script src="script/brand.js"></script>
