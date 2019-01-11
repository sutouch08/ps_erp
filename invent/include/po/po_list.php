<?php
	$sCode	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sPoCode') ? getCookie('sPoCode') : '' );
	$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sPoName') ? getCookie('sPoName') : '' );
	$sFrom	= isset( $_POST['sFrom'] ) ? $_POST['sFrom'] : ( getCookie('sFrom') ? getCookie('sFrom') : '' );
	$sTo		= isset( $_POST['sTo'] ) ? $_POST['sTo'] : ( getCookie('sTo') ? getCookie('sTo') : '' );
	$sStatus	= isset( $_POST['sStatus'] ) ? $_POST['sStatus'] : ( getCookie('sPoStatus') ? getCookie('sPoStatus') : '' );
?>
<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-2">
    	<label>เอกสาร</label>
        <input type="text" class="form-control input-sm search-box text-center" id="sCode" name="sCode" value="<?php echo $sCode; ?>" aufocus />
    </div>
    <div class="col-sm-2">
    	<label>ผู้จำหน่าย</label>
        <input type="text" class="form-control input-sm search-box text-center" id="sName" name="sName" value="<?php echo $sName; ?>"  />
    </div>
    <div class="col-sm-3">
    	<label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm input-discount text-center" id="sFrom" name="sFrom" value="<?php echo $sFrom; ?>" />
        <input type="text" class="form-control input-sm input-unit text-center" id="sTo" name="sTo" value="<?php echo $sTo; ?>" />
    </div>
    <div class="col-sm-2">
    	<label >สถานะ</label>
        <select class="form-control input-sm select-box" id="sStatus" name="sStatus">
        	<option value="">ทั้งหมด</option>
            <option value="1" <?php echo isSelected(1, $sStatus); ?> >ยังไม่รับ</option>
            <option value="2" <?php echo isSelected(2, $sStatus); ?> >รับแล้วบางส่วน</option>
            <option value="3" <?php echo isSelected(3, $sStatus); ?> >ปิดแล้ว</option>
            <option value="C" <?php echo isSelected('C', $sStatus); ?> >ยกเลิก</option>
        </select>
    </div>
    <div class="col-sm-1">
    	<label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
</div>
</form>
<hr class="margin-top-15 margin-bottom-15" />

<?php
	$where = "WHERE id != 0 ";
	if( $sCode != "" )
	{
		createCookie('sPoCode', $sCode);
		$where .= "AND reference LIKE '%".$sCode."%' ";
	}

	if( $sName != "" )
	{
		createCookie('sPoName', $sName);
		$where .= "AND id_supplier IN(".supplier_in($sName).") ";
	}

	if( $sFrom != "" && $sTo != "")
	{
		createCookie('sFrom', $sFrom);
		createCookie('sTo', $sTo);
		$where .= "AND date_add >= '".fromDate($sFrom, FALSE)."' AND date_add <= '".toDate($sTo, FALSE)."' ";
	}


	if( $sStatus != "" )
	{
		createCookie('sPoStatus', $sStatus);
		if( $sStatus == 'C' )
		{
			$where .= "AND isCancle = 1 ";
		}
		else
		{
			$where .= "AND status = ".$sStatus." ";
		}
	}
	$where .= "GROUP BY reference ORDER BY reference DESC";

	$paginator	= new paginator();

	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_po', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=po');

	$qs = dbQuery("SELECT * FROM tbl_po " . $where . " LIMIT ".$paginator->Page_Start." , ".$paginator->Per_Page);

?>

<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped border-1">
        	<thead>
            	<tr>
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-10 text-center">วันที่</th>
                    <th class="width-15">เลขที่เอกสาร</th>
                    <th class="width-40">ผู้จำหน่าย</th>
                    <th class="width-10">มูลค่า</th>
                    <th class="width-5 text-center">สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cs = new supplier(); 	?>
<?php		$po = new po();		?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
				<tr class="font-size-12" id="row-<?php echo $rs->reference; ?>">
                	<td class="middle text-center"><?php echo $no; ?></td>
                    <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
                    <td class="middle"><?php echo $rs->reference; ?></td>
                    <td class="middle"><?php echo $cs->getName($rs->id_supplier); ?></td>
                    <td class="middle"><?php echo number_format( poAmount($rs->amount_ex, $rs->vat_amount), 2); ?></td>
                    <td class="middle text-center">
						<?php if( $rs->isCancle == 1 ) : ?>
                        <span class="red">Cancled</span>
                        <?php endif; ?>
                        <?php if( $rs->status == 2 ) : ?>
                        <span class="blue">Part</span>
                        <?php endif; ?>
                        <?php if( $rs->status == 3 ) : ?>
                        <span class="green">Closed</span>
                        <?php endif; ?>
					</td>
                    <td class="middle text-right">
                    	<button type="button" class="btn btn-xs btn-info" title="รายละเอียด" onclick="viewDetail('<?php echo $rs->reference; ?>')">
                        	<i class="fa fa-eye"></i>
                        </button>
											<?php if( $edit && $rs->status != 3 && $po->isCompleted($rs->reference) === TRUE) : ?>
												<button type="button" class="btn btn-xs btn-warning" title="ปิดใบสั่งซื้อ" onclick="closePO('<?php echo $rs->bookcode; ?>','<?php echo $rs->reference; ?>')">
													<i class="fa fa-lock"></i>
												</button>
											<?php endif; ?>
											<?php if( $delete && $rs->status == 1 ) : ?>
												<button type="button" class="btn btn-xs btn-danger" title="ลบใบสั่งซื้อ" onclick="deletePo('<?php echo $rs->reference; ?>')">
													<i class="fa fa-trash"></i>
												</button>
											<?php endif; ?>
                    </td>
                </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php 	else : ?>
				<tr>
                	<td colspan="7" class="text-center"><h4>ไม่พบรายการ</h4></td>
                </tr>
<?php	endif; ?>
            </tbody>
        </table>
    </div>
</div>
