<?php
//--------------- เพิ่ม/แก้ไข นโยบายส่วนลด
$id_tab = 93;
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

?>

<div class="container">

<?php
if( isset( $_GET['add'] ) && isset($_GET['id_rule']) )
{
  include 'include/rule/rule_add.php';
}
else if(isset($_GET['add']) && !isset($_GET['id_rule']))
{
  include 'include/rule/new_rule.php';
}
else if(isset($_GET['viewDetail']) && isset($_GET['id_rule']))
{
  include 'include/rule/rule_detail.php';
}
else
{
  include 'include/rule/rule_list.php';
}
 ?>
</div>

<script src="script/rule/rule.js"></script>
