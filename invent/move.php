<?php
$id_tab = 9;
$pm     = checkAccess($id_profile, $id_tab);
$view   = $pm['view'];
$add    = $pm['add'];
$edit   = $pm['edit'];
$delete = $pm['delete'];
accessDeny($view);

 ?>
<div class="container">

  <input type="hidden" id="canAdd" value="<?php echo $add; ?>" />
  <input type="hidden" id="canEdit" value="<?php echo $edit; ?>" />
  <input type="hidden" id="canDelete" value="<?php echo $delete; ?>" />

<?php
if( isset( $_GET['add']))
{
  include 'include/move/move_add.php';
}
else if( isset( $_GET['view_detail']))
{
  include 'include/move/move_detail.php';
}
else
{
  include 'include/move/move_list.php';
}
?>

</div><!--- container --->
<script src="script/move/move.js"></script>
<script src="script/beep.js"></script>
