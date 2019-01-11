<?php
	$id_tab   = 96;
  $pm       = checkAccess($id_profile, $id_tab);
	$view     = $pm['view'];
	$add      = $pm['add'];
	$edit     = $pm['edit'];
	$delete   = $pm['delete'];

	accessDeny($view);

  include 'function/employee_helper.php';

	$isActive = getFilter('isActive', 'isActive', 2);
	$sName = getFilter('sName', 'sName', '');
	$btn_all = $isActive == 2 ? 'btn-primary' : '';
	$btn_yes = $isActive == 1 ? 'btn-primary' : '';
	$btn_no  = $isActive == 0 ? 'btn-primary' : '';

	?>

<div class="container">
	<div class="row top-row">
		<div class="col-sm-6 top-col">
			<h4 class="title"><i class="fa fa-user"></i> <?php echo $pageTitle; ?></h4>
		</div>
		<div class="col-sm-6">
			<p class="pull-right top-p">
				<?php if($add) : ?>
					<button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
				<?php endif; ?>
			</p>
		</div>
	</div>
	<hr/>
<form id="searchForm" method="post">
	<div class="row">
		<div class="col-sm-3 padding-5 first">
			<label class="display-block">สถานะ</label>
			<div class="btn-group width-100">
				<button type="button" class="btn btn-sm width-33 <?php echo $btn_all; ?>" id="btn-active-all" onclick="toggleActive(2)">ทั้งหมด</button>
				<button type="button" class="btn btn-sm width-33 <?php echo $btn_yes; ?>" id="btn-active-yes" onclick="toggleActive(1)">ใช้งาน</button>
				<button type="button" class="btn btn-sm width-33 <?php echo $btn_no;  ?>" id="btn-active-no" onclick="toggleActive(0)">ไม่ใช้งาน</button>
			</div>
		</div>
		<div class="col-sm-3 padding-5">
			<label class="display-block">ชื่อผู้อนุมัติ</label>
			<input type="text" class="form-control input-sm text-center" name="sName" id="sName" value="<?php echo $sName; ?>" autofocus />
		</div>
		<div class="col-sm-1 padding-5">
			<label class="display-block not-show">search</label>
			<button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
		</div>
		<div class="col-sm-1 padding-5">
			<label class="display-block not-show">Reset</label>
			<button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>

		<input type="hidden" name="isActive" id="isActive" value="<?php echo $isActive; ?>" />
	</div>
</from>
<hr/>

<?php
	$qr  = "SELECT ap.id, em.first_name, em.last_name, ap.active ";
	$qr .= "FROM tbl_approver AS ap ";
	$qr .= "JOIN tbl_employee AS em ON ap.id_employee = em.id_employee ";

	$table = "tbl_approver AS ap JOIN tbl_employee AS em ON ap.id_employee = em.id_employee";

	$where = "WHERE ap.doc_type = 'SU-BUDGET' ";

	createCookie('isActive', $isActive);
	createCookie('sName', $sName);

	if($isActive != 2)
	{
		$where .= "AND ap.active = ".$isActive." ";
	}

	if($sName != '')
	{
		$where .= "AND (em.first_name LIKE '%".$sName."%' OR em.last_name LIKE '%".$sName."%') ";
	}

	$where .= "ORDER BY date_upd DESC";

	$paginator = new paginator();
  $get_rows = get_rows();
  $paginator->Per_Page($table, $where, $get_rows);
  $paginator->display($get_rows, 'index.php?content=support_approver');

  $qs = dbQuery($qr. $where ." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped border-1">
			<thead>
				<tr>
					<th class="width-10 text-center">No.</th>
					<th class="">ผู้อนุมัติ</th>
					<th class="width-8 text-center">สถานะ</th>
					<th class="width-15 text-center"></th>
				</tr>
			</thead>
			<tbody id="result">
<?php if(dbNumRows($qs) > 0 ) : ?>
	<?php $no = row_no(); ?>
	<?php while($rs = dbFetchObject($qs)) : ?>
		<?php $empName = $rs->first_name.' '.$rs->last_name; ?>
		<?php $btn_active = $rs->active == 1 ? 'btn-success' : ''; ?>
		<?php $btn_disactive = $rs->active == 0 ? 'btn-danger' : ''; ?>
			<tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
				<td class="middle text-center no"><?php echo $no; ?></td>
				<td class="middle"><?php echo $empName; ?></td>
				<td class="middle text-center">
				<?php if($edit) : ?>
						<div class="btn-group width-100">
							<button type="button" class="btn btn-xs width-50 <?php echo $btn_active; ?>" id="btn-active-<?php echo $rs->id; ?>" onclick="setActive(<?php echo $rs->id; ?>)"><i class="fa fa-check"></i></button>
							<button type="button" class="btn btn-xs width-50 <?php echo $btn_disactive; ?>" id="btn-disactive-<?php echo $rs->id; ?>" onclick="disActive(<?php echo $rs->id; ?>)"><i class="fa fa-times"></i></button>
						</div>
					<?php else : ?>
					<?php echo isActived($rs->active); ?>
				<?php endif; ?>
				</td>
				<td class="middle text-right">
				<?php if($delete) : ?>
					<button type="button" class="btn btn-xs btn-danger" onclick="confirmRemove(<?php echo $rs->id; ?>, '<?php echo $empName; ?>')"><i class="fa fa-trash"></i></button>
				<?php endif; ?>
				</td>
			</tr>
	  <?php $no++; ?>
	<?php endwhile; ?>
<?php else : ?>

<?php endif; ?>

			</tbody>
		</table>
	</div>
</div>

<script id="template" type="text/x-handlebarsTemplate">
<tr class="font-size-12" id="row-{{id}}">
	<td class="middle text-center no"></td>
	<td class="middle">{{empName}}</td>
	<td class="middle text-center">
	<?php if($edit) : ?>
			<div class="btn-group width-100">
				<button type="button" class="btn btn-xs width-50 btn-success" id="btn-active-{{id}}" onclick="setActive({{id}})"><i class="fa fa-check"></i></button>
				<button type="button" class="btn btn-xs width-50 " id="btn-disactive-{{id}}" onclick="disActive({{id}})"><i class="fa fa-times"></i></button>
			</div>
		<?php else : ?>
		<?php echo isActived(1); ?>
	<?php endif; ?>
	</td>
	<td class="middle text-right">
	<?php if($delete) : ?>
		<button type="button" class="btn btn-xs btn-danger" onclick="confirmRemove({{id}}, '{{empName}}')"><i class="fa fa-trash"></i></button>
	<?php endif; ?>
	</td>
</tr>
</script>



<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:400px;">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">เพิ่มผู้อนุมัติ</h4>
			 </div>
			 <div class="modal-body">
				 <div class="row">
				 	<div class="col-sm-12">
				 		<label>พนักงาน</label>
						<input type="text" class="form-control input-sm text-center" id="txt-empName" placeholder="ค้นหาพนักงาน" />
						<input type="hidden" id="id_employee" value="" />
				 	</div>
				 </div>
			 </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="addToList()" >เพิ่ม</button>
			 </div>
		</div>
	</div>
</div>


</div>	<!-- container -->

<script src="script/support_approver/support_approver.js"></script>
