
<?php
$id_tab = 81; //--- เพิ่ม/แก้ไข งบประมาณสปอนเซอร์

//---	ตรวจสอบสิทธิ์การเข้าถึงงบประมาณ
$ps     = checkAccess($id_profile, 81);
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
if( isset( $_GET['id_sponsor']) && $_GET['id_sponsor'] > 0)
{

  //--- รายละเอียดส่วนหัว ผู้รับสปอนเซอร์
  include 'include/sponsor_budget/sponsor_detail.php';

  //--- รายการงบประมาณของผู้มรับทั้งหมด
  include 'include/sponsor_budget/budget_list.php';
}
else
{

   include 'include/page_error.php';

}
?>
</div><!-- container-->
