<?php
$approver  = new approver();
$doc_type = 'SU-BUDGET'; //--- งบประมาณสปอนเซอร์
$apv = $approver->getApprover($doc_type, getCookie('user_id'));
?>
<div class="row">
  <div class="col-sm-12">
    <p class="red">
      *** หากมีการ "เพิ่มงบประมาณใหม"่ หรือ มีการ "แก้ไขงบประมาณ" ให้ยอดเงินเพิ่มขึ้นจากเดิม จะต้องได้รับการอนุมัติก่อน จึงจะใช้งานได้
    </p>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-10">เลขที่อ้างอิง</th>
          <th class="width-5 text-center">ปีงบประมาณ</th>
          <th class="width-10 text-center">เริ่มต้น</th>
          <th class="width-10 text-center">สิ้นสุด</th>
          <th class="width-10 text-center">งบประมาณ</th>
          <th class="width-10 text-center">ใช้ไป</th>
          <th class="width-10 text-center">คงเหลือ</th>
          <th class="width-5 text-center">สถานะ</th>
          <th class="width-10 text-center">หมายเหตุ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
<?php $qs   = $bd->getBudgetList($sp->id); ?>
<?php if( dbNumRows($qs) > 0 ) :  ?>
<?php   $no = 1;                  ?>
<?php   while( $rs = dbFetchObject($qs) ) : ?>
      <tr class="font-size-12">
        <td class="middle text-center"><?php echo $no; ?></td>
        <td class="middle"><?php echo $rs->reference; ?></td>
        <td class="middle text-center"><?php echo $rs->year; ?></td>
        <td class="middle text-center"><?php echo thaiDate($rs->start); ?></td>
        <td class="middle text-center"><?php echo thaiDate($rs->end); ?></td>
        <td class="middle text-center"><?php echo number($rs->budget, 2); ?></td>
        <td class="middle text-center"><?php echo number($rs->used, 2); ?></td>
        <td class="middle text-center"><?php echo number($rs->balance, 2); ?></td>
        <td class="middle text-center"><?php echo isActived($rs->active); ?></td>
        <td class="middle text-center">
          <a href="#" data-container="body" data-toggle="popover" data-placement="left"  data-trigger="focus" data-content="<?php echo $rs->remark; ?>">
            <?php echo limitText($rs->remark,40 ); ?>
          </a>
        </td>
        <td class="middle text-right">
          <?php if( $apv !== FALSE && !isset( $_GET['view_detail'])) : ?>
            <?php if( $rs->active == 0) : ?>
              <button type="button" class="btn btn-xs btn-default" onclick="setActive(<?php echo $rs->id; ?>,<?php echo $apv->id_employee; ?>, '<?php echo $apv->approve_key; ?>',1)">
                อนุมัติ
              </button>
            <?php else : ?>
              <button type="button" class="btn btn-xs btn-default" onclick="disActive(<?php echo $rs->id; ?>, <?php echo $apv->id_employee; ?>, '<?php echo $apv->approve_key; ?>',0)">
                ยกเลิก
              </button>
            <?php endif; ?>
          <?php endif; ?>
          <?php if( $bEdit ) : ?>
            <button type="button" class="btn btn-xs btn-warning" onclick="getEditBudgetForm(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i> แก้ไข</button>
          <?php endif; ?>
        </td>
      </tr>
<?php   $no++;  ?>
<?php   endwhile; ?>
<?php else : ?>
      <tr>
        <td colspan="11" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>

      </tbody>
    </table>
  </div>
</div>


<div class="modal fade" id="budget-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal" style="width:400px;">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modal-title"></h4>
        <input type="hidden" id="id_budget" value="" />
			 </div>
			 <div class="modal-body" id="modal_body">
         <div class="row">

           <div class="col-sm-12 margin-top-10">
             <label>เลขที่เอกสาร/เลขที่อ้างอิง/สัญญา</label>
             <input type="text" class="form-control input-sm input-medium" name="reference" id="reference" placeholder="ระบุเลขที่เอกสาร/อ้างอิง/สัญญา" />
           </div>

           <div class="col-sm-12 margin-top-10">
             <label>งบประมาณ</label>
             <input type="text" class="form-control input-sm input-small" name="budget" id="budget" placeholder="ระบุงบประมาณ" />
             <span class="help-block red hide" id="budget-error">งบประมาณไม่ถูกต้อง</span>
           </div>

           <div class="col-sm-12 margin-top-10">
             <label class="display-block">ระยะเวลา</label>
             <input type="text" class="form-control input-sm input-mini inline text-center" name="fromDate" id="fromDate" placeholder="เริ่มต้น" />
             <input type="text" class="form-control input-sm input-mini inline text-center" name="toDate" id="toDate" placeholder="สิ้นสุด" />
             <span class="help-block red hide" id="date-error">วันที่ไม่ถูกต้อง</span>
           </div>

           <div class="col-sm-12 margin-top-10">
             <label class="display-block">ปีงบประมาณ</label>
             <select class="form-control input-sm input-mini inline" name="year" id="year">
               <?php echo selectYears(date('Y')); ?>
             </select>
             <span class="help-block red hide" id="year-error">ปีงบประมาณซ้ำ กรุณาเลือกใหม่</span>
           </div>

           <div class="col-sm-12 margin-top-10">
             <label class="display-block">หมายเหตุ</label>
             <textarea class="form-control" rows="6" name="remark" id="remark" placeholder="ระบุหมายเหตู(ถ้ามี)"></textarea>
           </div>

           <div class="divider-hidden"></div>
			 </div>

       <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-success" id="btn-save-add" onClick="saveBudget()" ><i class="fa fa-save"></i> บันทึก</button>
        <button type="button" class="btn btn-success hide" id="btn-save-edit" onClick="updateBudget()" ><i class="fa fa-save"></i> บันทึก</button>
			 </div>
		</div>
	</div>
</div>


<script src="script/support_budget/budget_list.js"></script>
