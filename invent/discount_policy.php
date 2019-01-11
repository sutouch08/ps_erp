<?php
//--------------- เพิ่ม/แก้ไข นโยบายส่วนลด
$id_tab = 87;
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
if( isset( $_GET['add'] ) )
{
  include 'include/policy/policy_add.php';
}
else if( isset($_GET['viewDetail']))
{
  include 'include/policy/policy_detail.php';
}
else
{
  include 'include/policy/policy_list.php';
}
 ?>
</div>


<div class="modal fade" id="rule-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:800px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกกฎส่วนลด</h4>
      </div>
      <div class="modal-body" id="rule-body">
        <div class="row">
          <div class="scrollbar-inner">
            <div class="col-sm-12" style="width:800px; max-height:400px;" id="result">
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="addRule()"><i class="fa fa-plus"></i> เพิ่มในนโยบาย</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<script id="rule-template" type="text/x-handlebarsTemplate">
<table class="table table-striped tablesorter margin-bottom-0" id="myTable">
  <thead>
    <tr>
      <th class="width-5">เลือก</th>
      <th class="width-25">รหัส</th>
      <th class="width-50">ชื่อกฏ</th>
      <th class="width-20">แก้ไขล่าสุด</th>
    </tr>
  </thead>
  <tbody>
{{#each this}}
  {{#if nodata}}
    <tr>
      <td colspan="3" class="text-center">ไม่พบรายการ</td>
    </tr>
  {{else}}
    <tr class="font-size-12">
      <td class="text-center">
        <input type="checkbox" class="chk-rule" name="ruleId[{{id_rule}}]" id="ruleId_{{id_rule}}" value="{{id_rule}}" />
      </td>
      <td>
        <label for="ruleId_{{id_rule}}" class="padding-5">{{ruleCode}}</label>
      </td>
      <td>
        <label for="ruleId_{{id_rule}}" class="padding-5">{{ruleName}}</label>
      </td>
      <td>
        <label for="ruleId_{{id_rule}}" class="padding-5">{{date_upd}}</label>
      </td>
    </tr>
  {{/if}}
{{/each}}
  </tbody>
</table>
</script>

<script src="script/policy/policy.js"></script>
