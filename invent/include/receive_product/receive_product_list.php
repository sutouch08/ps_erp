<?php
	include 'function/supplier_helper.php';

	$sCode	= getFilter('sCode', 'sReceiveCode', '');
	$sPo		= getFilter('sPo', 'sReceivePo', '');
	$sInv		= getFilter('sInv', 'sReceiveInv', '');
	$sSup		= getFilter('sSup', 'sReceiveSup', '');
	$sFrom	= getFilter('sFrom', 'sFrom', '');
	$sTo		= getFilter('sTo', 'sTo', '');
	$sStatus	= getFilter('sStatus', 'sReceiveStatus', '');


?>

<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
<?php	if( $add ) : ?>
				<button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
<?php	endif; ?>

        </p>
    </div>
</div>
<hr />

<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ใบสั่งซื้อ</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sPo" id="sPo" value="<?php echo $sPo; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ใบส่งสินค้า</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sInv" id="sInv" value="<?php echo $sInv; ?>" />
    </div>

     <div class="col-sm-1 col-1-harf padding-5">
    	<label>ผู้จำหน่าย</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sSup" id="sSup" value="<?php echo $sSup; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>สถานะ</label>
        <select class="form-control input-sm select-box" id="sStatus" name="sStatus">
        	<option value="">ทั้งหมด</option>
            <option value="CN" <?php echo isSelected($sStatus, 'CN'); ?>>ยกเลิก</option>
            <option value="NE" <?php echo isSelected($sStatus, 'NE'); ?>>ยังไม่ส่งออก</option>
        </select>
    </div>
    <div class="col-sm-2 col-2-harf padding-5">
    	<label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm input-discount text-center" name="sFrom" id="sFrom" placeholder="เริ่มต้น" value="<?php echo $sFrom; ?>" />
        <input type="text" class="form-control input-sm input-unit text-center" name="sTo" id="sTo" placeholder="สิ้นสุด" value="<?php echo $sTo; ?>" />
    </div>
    <div class="col-sm-1 padding-5">
    	<label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-block btn-primary" onClick="getSearch()"><i class="fa fa-search"></i>  ค้นหา</button>
    </div>
    <div class="col-sm-1 padding-5 last">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-block btn-warning" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>

</div>
</form>

<hr class="margin-top-15" />
<?php
	$where = "WHERE id != 0 ";

	if( $sCode != "" )
	{
		createCookie('sReceiveCode', $sCode);
		$where .= "AND reference LIKE '%".$sCode."%' ";
	}

	if( $sPo != "" )
	{
		createCookie('sReceivePo', $sPo);
		$where .= "AND po LIKE '%".$sPo."%' ";
	}

	if( $sInv != "" )
	{
		createCookie('sReceiveInv', $sInv);
		$where .= "AND invoice LIKE '%".$sInv."%' ";
	}

	if( $sSup != "" )
	{
		createCookie('sReceiveSup', $sSup);
		$where .= "AND id_supplier IN(".	supplier_in($sSup).") ";
	}

	if( $sStatus != "" )
	{
		createCookie('sReceiveStatus', $sStatus);
		if( $sStatus == 'CN' )
		{
			$where .= "AND isCancle = 1 ";
		}
		elseif ( $sStatus == 'NE' )
		{
			$where .= "AND isExported = 0 ";
		}
	}

	if( $sFrom != "" && $sTo != "" )
	{
		createCookie('sFrom', $sFrom);
		createCookie('sTo', $sTo);
		$where .= "AND date_add >= '". fromDate($sFrom) ."' AND date_add <= '".toDate($sTo)."' ";
	}

	$where .= "ORDER BY reference DESC";

	$paginator = new paginator();
	$get_rows = get_rows();
	$paginator->Per_Page("tbl_receive_product", $where, $get_rows);

	$qs = dbQuery("SELECT * FROM tbl_receive_product ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>
<div class="row">
	<div class="col-sm-8">
    	<?php $paginator->display($get_rows, "index.php?content=receive_product"); ?>
    </div>
    <div class="col-sm-4 margin-top-15">
        <p class="pull-right">
        	<span class="red">CN</span><span> = ยกเลิก, </span>
            <span class="red">NE</span><span> = ยังไม่ส่งออกไป FORMULA</span>
    	</p>
    </div>
</div>

<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped border-1">
        	<thead>
            	<tr>
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-10 text-center">วันที่</th>
                    <th class="width-10">เลขที่เอกสาร</th>
                    <th class="width-10">ใบส่งสินค้า</th>
                    <th class="width-10">ใบสั่งซื้อ</th>
                    <th class="width-30">ผู้จำหน่าย</th>
                    <th class="width-10 text-center">จำนวน</th>
                    <th class="width-5"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no = row_no();			?>
<?php	$cs = new receive_product();	?>
<?php	$sp = new supplier();	?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>
				<tr class="font-size-12">
                	<td class="middle text-center"><?php echo number_format($no); ?></td>
                    <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
                    <td class="middle"><?php echo $rs->reference; ?></td>
                    <td class="middle"><?php echo $rs->invoice; ?></td>
                    <td class="middle"><?php echo $rs->po; ?></td>
                    <td class="middle"><?php echo $sp->getName($rs->id_supplier); ?></td>
                    <td class="middle text-center"><?php echo number_format( $cs->getTotalQty( $rs->id ) ); ?></td>
                    <td class="middle text-center">
					<?php if( $rs->isCancle == 1 ) : ?>
                    	<span class="red"><strong>CN</strong></span>
                    <?php elseif( $rs->isExported == 0 ) :  ?>
                    	<span class="red"><strong>NE</strong></span>
                    <?php endif; ?>

                    <td class="middle text-right">
                    	<button type="button" class="btn btn-xs btn-info" onClick="goDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
										<?php if(($edit OR $add) && !$rs->isCancle && !$rs->isSaved) : ?>
											<button type="button" class="btn btn-xs btn-warning" onclick="goAdd(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
										<?php endif; ?>
                    <?php if( $delete && $rs->isCancle == 0 ) : ?>
                    	<button type="button" class="btn btn-xs btn-danger" onClick="goDelete(<?php echo $rs->id; ?>, '<?php echo $rs->reference; ?>')"><i class="fa fa-times"></i></button>
                    <?php endif; ?>
                    </td>
                </tr>
<?php		$no++; ?>
<?php	endwhile; ?>
<?php else : ?>
				<tr>
                	<td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
                </tr>
<?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
