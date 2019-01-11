<?php
	$id_tab 		= 3;
    $pm 			= checkAccess($id_profile, $id_tab);
	$view 		= $pm['view'];
	$add			= $pm['add'];
	$edit			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);	
	include "function/color_helper.php";
?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tint"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button class="btn btn-sm btn-success" onClick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>
<?php	
			$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('cCode') ? getCookie('cCode') : '' ); 		
			$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('cName') ? getCookie('cName') : '' ); 
			$sGroup	= isset( $_POST['sGroup'] ) ? trim( $_POST['sGroup'] ) : ( getCookie('cGroup') ? getCookie('cGroup') : '' );
?>			
    
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-2">
        	<label>รหัส</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" placeholder="รหัสลี" value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>ชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName" placeholder="ชื่อสี" value="<?php echo $sName; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>กลุ่มสี</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sGroup" id="sGroup" placeholder="กลุ่มสี" value="<?php echo $sGroup; ?>"  />
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
		createCookie('cCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}
	
	if( $sName != '' )
	{
		createCookie('cName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}
	
	if( $sGroup != '' )
	{
		createCookie('cGroup', $sGroup);
		$where .= "AND id_group IN(".colorGroupIn($sGroup).") ";	
	}
	
	$where .= "ORDER BY code ASC";
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_color', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=color');
	
	$qs = dbQuery("SELECT * FROM tbl_color ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>    
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-10 text-center">รหัสสี</th>
                        <th class="width-20 text-center">ชื่อสี</th>
                        <th class="width-15 text-center">กลุ่มสี</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cs = new color(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle text-center"><?php echo $rs->code; ?></td>
                        <td class="middle text-center"><?php echo $rs->name; ?></td>
                        <td class="middle text-center">
                        <?php if( $edit ) : ?>
                            <div class="input-group">
                                <select id="group_<?php echo $rs->id; ?>" class="form-control input-sm">
                                    <?php echo selectColorGroup($rs->id_group); ?>
                                </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-default" onclick="edit('<?php echo $rs->id; ?>')"><i class="fa fa-save"></i></button>
                                    </span>
                                </div>
                    	<?php else : ?>
							<?php echo $cs->getGroupName($rs->id_group); ?>
                        <?php endif; ?>
                        </td>
                        <td class="middle text-right"> 
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-sm btn-danger" onClick="remove('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
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
<?php $paginator->display($get_rows, 'index.php?content=color'); ?>
        </div>
    </div>
    
</div><!--/ Container -->
<script src="script/color.js"></script>