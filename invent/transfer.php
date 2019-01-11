<?php
$id_tab = 43;
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
  include 'include/transfer/transfer_add.php';
}
else if( isset( $_GET['view_detail']))
{
  include 'include/transfer/transfer_detail.php';
}
else
{
  include 'include/transfer/transfer_list.php';
}
?>

</div><!--- container --->
<script src="script/transfer/transfer.js"></script>
<script src="script/beep.js"></script>
