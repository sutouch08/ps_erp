<?php
//--------------- ตัดยอดฝากขาย
$id_tab = 34;
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

//--------------- ยกเลิกเอกสารการตัดยอดฝากขายที่เปิดบิลแล้ว
$id_tab = 36;
$pm = checkAccess($id_profile, $id_tab);
$canUnbill = ($pm['add'] + $pm['edit'] + $pm['delete']) > 0 ? TRUE : FALSE;

//--- สามารถ แก้ไขส่วนลดได้หรือไม่
$id_tab = 35;
$pm = checkAccess($id_profile, $id_tab);
$canEditDiscount = ($pm['add'] + $pm['edit'] + $pm['delete']) > 0 ? TRUE : FALSE;

//--- สามารถ แก้ไขราคาได้หรือไม่
$id_tab = 65;
$pm = checkAccess($id_profile, $id_tab);
$canEditPrice = ($pm['add'] + $pm['edit'] + $pm['delete']) > 0 ? TRUE : FALSE;


include 'function/customer_helper.php';
 ?>

 <div class="container">
<?php
if( isset( $_GET['add'] ) )
{
  include 'include/consign/consign_add.php';
}
else if( isset( $_GET['view_detail']))
{
  include 'include/consign/consign_detail.php';
}
else
{
  include 'include/consign/consign_list.php';
}

 ?>

 </div><!-- container -->
 <script src="script/consign/consign.js"></script>
