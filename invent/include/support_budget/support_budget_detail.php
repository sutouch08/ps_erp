
<?php
$id_tab = 82; //--- เพิ่ม/แก้ไข งบประมาณอภินันท์

//---	ตรวจสอบสิทธิ์การเข้าถึงงบประมาณ
$ps     = checkAccess($id_profile, 82);
$bAdd   = FALSE;
$bEdit  = FALSE;
$bDelete  = FALSE;
$add = FALSE;
$edit = FALSE;
$delete = FALSE;

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
