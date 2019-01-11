<!--- Control ---->
<hr/>
<?php if($cs->hasDetails($id) === TRUE) : ?>
  <?php if($cs->valid == 0 && $cs->status == 0 && !isset($_GET['view_detail'])) : ?>
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>บาร์โค้ดกล่อง</label>
    <input type="text" class="form-control input-sm text-center box" id="txt-box-barcode" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>จำนวน</label>
    <input type="number" class="form-control input-sm text-center item" id="txt-qty" value="1" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm text-center item" id="txt-pd-barcode" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block not-show">change</label>
    <button type="button" class="btn btn-sm btn-info btn-block item" id="btn-change-box" onclick="changeBox()">
      <i class="fa fa-refresh"></i> เปลี่ยนกล่อง
    </button>
  </div>

  <div class="col-sm-3 col-3-harf">
    <h4 class="pull-right" id="box-label">จำนวนในกล่อง</h4>
  </div>
  <div class="col-sm-2 padding-5 last">
    <div class="title middle text-center" style="height:55px; background-color:black; color:white; padding-top:20px; margin-top:0px;">
      <h4 id="box-qty" class="inline text-center">0</h4>
    </div>
  </div>

</div>
<?php endif; ?>
<hr/>
<div class="row">
  <div class="col-sm-2 col-sm-offset-3 text-center">
    <label class="display-block">ในโซน</label>
    <span><h4 class="title" id="total-zone">Loading...</h4></span>
  </div>
  <div class="col-sm-2 text-center">
    <label class="display-block">ตรวจนับ</label>
    <span><h4 class="title" id="total-checked">Loading...</h4></span>
  </div>
  <div class="col-sm-2 text-center">
    <label class="display-block">ยอดต่าง</label>
    <span><h4 class="title" id="total-diff">Loading...</h4></span>
  </div>
  <div class="col-sm-3 text-right top-col">
    <button type="button" class="btn btn-sm btn-info" onclick="getBoxList()"><i class="fa fa-file-text"></i> พิมพ์ใบปะหน้ากล่อง</button>
  </div>
</div>

<input type="hidden" id="id_box" value="" />
<?php else : ?>
  <?php if(!isset($_GET['view_detail'])) : ?>
<div class="row">
  <div class="col-sm-3 col-sm-offset-4 margin-top-15">
    <button type="button" class="btn btn-sm btn-info btn-block" onclick="buildDetails()"><i class="fa fa-bolt"></i> ดึงยอดตั้งต้นในโซน</button>
  </div>
</div>
  <?php endif; ?>

<?php endif; ?>

<div class="modal fade" id="box-list-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:500px;">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			 </div>
			 <div class="modal-body" id="box-list-body">

       </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			 </div>
		</div>
	</div>
</div>


<script id="box-list-template" type="text-handlebarsTemplate">
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped margin-bottom-5">
      <thead>
        <tr>
          <th class="width-35 text-center">กล่อง</th>
          <th class="width-35 text-center">บาร์โค้ด</th>
          <th class="width-30 text-right">พิมพ์</th>
        </tr>
      </thead>
      <tbody>
    {{#each this}}
      {{#if nodata}}
      <tr>
        <td colspan="3" class="text-center">ไม่พบรายการ</td>
      </tr>
      {{else}}
      <tr>
        <td class="middle text-center">{{ box_no }}</td>
        <td class="middle text-center">{{ barcode }}</td>
        <td class="middle text-right">
          <button type="button" class="btn btn-sm btn-info" onclick="printConsignBox({{ id_box }})">
            <i class="fa fa-print"></i> พิมพ์
          </button>
        </td>
      </tr>
      {{/if}}
    {{/each}}
      </tbody>
    </table>
  </div>
</div>
</script>

<script src="script/consign_check/consign_check_control.js"></script>
<!--/ Control --->
