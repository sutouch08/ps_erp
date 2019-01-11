<?php
	$id_tab 			= 62;
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
	include 'function/bank_helper.php';
	include 'function/order_helper.php';
	include 'function/customer_helper.php';
	include 'function/date_helper.php';
?>
<div class="container">
<?php
if( isset( $_GET['validated'] ) )
{
	include 'include/payment/payment_validated.php';
}
else
{
	include 'include/payment/payment_list.php';
}
?>

</div><!--/ container -->
<div class='modal fade' id='confirmModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width:350px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
            </div>
            <div class='modal-body' id="detailBody">

            </div>
            <div class='modal-footer'>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='imageModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class="fa fa-times"></i></button>
            </div>
            <div class='modal-body' id="imageBody">

            </div>
            <div class='modal-footer'>
            </div>
        </div>
    </div>
</div>

<script id="detailTemplate" type="text/x-handlebars-template">
<div class="row">
	<div class="col-sm-12 text-center">ข้อมูลการชำระเงิน</div>
</div>
<hr/>
<div class="row">
	<div class="col-sm-4 label-left">ยอดที่ต้องชำระ :</div><div class="col-sm-8">{{ orderAmount }}</div>
	<div class="col-sm-4 label-left">ยอดโอนชำระ : </div><div class="col-sm-8"><span style="font-weight:bold; color:#E9573F;">฿ {{ payAmount }}</span></div>
	<div class="col-sm-4 label-left">วันที่โอน : </div><div class="col-sm-8">{{ payDate }}</div>
	<div class="col-sm-4 label-left">ธนาคาร : </div><div class="col-sm-8">{{ bankName }}</div>
	<div class="col-sm-4 label-left">สาขา : </div><div class="col-sm-8">{{ branch }}</div>
	<div class="col-sm-4 label-left">เลขที่บัญชี : </div><div class="col-sm-8"><span style="font-weight:bold; color:#E9573F;">{{ accNo }}</span></div>
	<div class="col-sm-4 label-left">ชื่อบัญชี : </div><div class="col-sm-8">{{ accName }}</div>
	{{#if imageUrl}}
		<div class="col-sm-12 top-row top-col text-center">
			<a href="javascript:void(0)" onClick="viewImage('{{ imageUrl }}')">
				รูปสลิปแนบ	<i class="fa fa-paperclip fa-rotate-90"></i>
			</a>
		</div>
	{{else}}
		<div class="col-sm-12 top-row top-col text-center">---  ไม่พบไฟล์แนบ  ---</div>
	{{/if}}
	{{#if valid}}
	<div class="col-sm-12 top-col">
		<button type="button" class="btn btn-warning btn-block" onClick="confirmPayment({{ id }})">
			<i class="fa fa-check-circle"></i> ยืนยันการชำระเงิน
		</button>
	</div>
	{{/if}}
</div>
</script>

<script id="orderTableTemplate" type="text/x-handlebars-template">
{{#each this}}
<tr id="{{ id }}" class="font-size-12">
<td class="text-center">{{ no }}</td>
<td> {{ reference }}</td>
<td align="center"> {{ channels }}</td>
<td>{{ customer }}</td>
<td>{{ employee }}</td>
<td align="center">{{ orderAmount }}</td>
<td align="center">{{ payAmount }}</td>
<td align="center">{{ accNo }}</td>
<td align="right">
	<button type="button" class="btn btn-xs btn-warning" onClick="viewDetail({{ id }})"><i class="fa fa-eye"></i></button>
	<button type="button" class="btn btn-xs btn-danger" onClick="removePayment({{ id }}, '{{ reference }}')"><i class="fa fa-trash"></i></button>
 </td>
</tr>
{{/each}}
</script>

<script src="script/payment/payment.js"></script>
