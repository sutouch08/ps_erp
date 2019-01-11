<?php
//--------------- ตัดยอดฝากขาย
$id_tab = 52;
$pm = checkAccess($id_profile, $id_tab);

//--- เพิ่มเอกสารได้หรือไม่
$add = $pm['add'] == 1 ? TRUE : FALSE;

//--- แก้ไขเอกสารได้หรือไม่
$edit = $pm['edit'] == 1 ? TRUE : FALSE;

//--- ยกเลิกเอกสารได้หรือไม่
$delete = $pm['delete'] == 1 ? TRUE : FALSE;

//--- เข้าใช้งานเมนูได้หรือไม่
$view = $pm['view'] == 1 ? TRUE : FALSE;

//--- ตรวจสอบสิทธิ์การเข้าใช้งานเมนู
accessDeny($view);

include 'function/customer_helper.php';
include 'function/zone_helper.php';
 ?>

 <div class="container">
<?php
if( isset( $_GET['add'] ) )
{
  include 'include/consign_check/consign_check_add.php';
}
else if( isset( $_GET['view_detail']))
{
  include 'include/consign_check/consign_check_view_detail.php';
}
else
{
  include 'include/consign_check/consign_check_list.php';
}

 ?>

 </div><!-- container -->
 <script src="script/consign_check/consign_check.js"></script>
