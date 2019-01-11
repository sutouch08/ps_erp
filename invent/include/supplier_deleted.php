<?php
	$where = "WHERE is_deleted = 1 ";
	
	if( $spCode != "" )
	{
		createCookie('spCode', $spCode);
		$where .= "AND tbl_supplier.code LIKE '%".$spCode."%' ";	
	}
	
	if( $spName != "" )
	{
		createCookie('spName', $spName);
		$where .= "AND tbl_supplier.name LIKE '%".$spName."%' ";	
	}
	
	if( $spGroup != "" )
	{
		createCookie('spGroup', $spGroup);
		$where .= "AND tbl_supplier_code.name LIKE '%".$spGroup."%' ";
	}
	
	$where .= "ORDER BY tbl_supplier.code ASC";
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page("tbl_supplier LEFT JOIN tbl_supplier_group ON tbl_supplier.id_group = tbl_supplier_group.id", $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=supplier');
	
	$qr = "SELECT tbl_supplier.*, tbl_supplier_group.name AS group_name FROM tbl_supplier LEFT JOIN tbl_supplier_group ON tbl_supplier.id_group = tbl_supplier_group.id ";
	//echo $qr . $where;
	$qs = dbQuery( $qr . $where . " LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>

	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-5 text-center">ลำดับ</th>
                        <th class="width-10">รหัส</th>
                        <th class="width-35">ชื่อผู้จำหน่าย</th>
                        <th class="width-20">กลุ่ม</th>
                        <th class="width-10 text-center">ผู้ลบ</th>
                        <th class="width-10 text-center">วันที่ลบ</th>
                        <th ></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle"><?php echo $rs->group_name; ?></td>
                        <td class="middle text-center"><?php echo employee_name($rs->emp); ?></td>
                        <td class="middle text-center"><?php echo thaiDate($rs->date_upd, '/'); ?></td>
                        <td class="middle text-right">
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-sm btn-info" onClick="unDelete('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')">ยกเลิกการลบ</button>
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