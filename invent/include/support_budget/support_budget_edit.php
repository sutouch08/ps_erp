
<?php
$id_tab = 82; //--- เพิ่ม/แก้ไข งบประมาณสปอนเซอร์

//---	ตรวจสอบสิทธิ์การเข้าถึงงบประมาณ
$ps     = checkAccess($id_profile, 81);
$bAdd   = $ps['add'];
$bEdit  = $ps['edit'];
$bDelete  = $ps['delete'];

accessDeny($ps['view']);

 ?>
<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col hidden-xs">
      <h4 class="title"><i class="fa fa-credit-card"></i> <?php echo $pageTitle; ?></h4>
    </div>

    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        <?php if( isset( $_GET['id_support']) && $bAdd ) : ?>
          <button type="button" class="btn btn-sm btn-success" onclick="getNewBudgetForm()"><i class="fa fa-plus"></i> เพิ่มงบประมาณ</button>
        <?php endif; ?>
      </p>
    </div>
  </div>
  <hr/>
<?php
if( isset( $_GET['id_support']) && $_GET['id_support'] > 0)
{

  //--- รายละเอียดส่วนหัว ผู้รับสปอนเซอร์
  include 'include/support_budget/support_detail.php';

  //--- รายการงบประมาณของผู้มรับทั้งหมด
  include 'include/support_budget/budget_list.php';
}
else
{

   include 'include/page_error.php';

}
?>
</div><!-- container-->
<script src="script/support_budget/support_budget_edit.js"></script>
