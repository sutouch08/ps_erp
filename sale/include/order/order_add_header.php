<?php if( isset( $_GET['id_order'])) : ?>
<?php   $customer = new customer($order->id_customer); ?>
<div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a href="#collapseSingleOne" data-toggle="collapse" class="collapsed width-100">
              รายละเอียดเอกสาร
            </a>
          </h4>
        </div>
        <div class="panel-collapse collapse" id="collapseSingleOne" style="height: 0px;">
          <div class="panel-body">
<?php endif; ?>

        <div class="row">

          <div class="col-sm-1 col-1-harf padding-5 first hidden-xs">
            	<label>เลขที่</label>
              <label class="form-control input-sm text-center" <?php echo $disabled; ?>><?php echo $order->reference; ?></label>
          </div>

          <div class="col-sm-1 col-1-harf padding-5 hidden-xs">
          	<label>วันที่</label>
            <input type="text" class="form-control input-sm text-center input-header" style="font-size:10px;" id="dateAdd" value="<?php echo thaiDate($order->date_add); ?>" <?php echo $disabled; ?> />
          </div>

					<div class="col-sm-3 col-xs-12 padding-5 first-xs last-xs">
					 	<label>ลูกค้า [ในระบบ]</label>
					  <input type="text" class="form-control input-sm text-center input-header" id="customer" value="<?php echo (isset($_GET['id_order']) ? $customer->name.' ['.$customer->province.']' : ''); ?>"  <?php echo $disabled; ?>/>
					</div>

          <div class="col-sm-2 col-xs-6 padding-5 first-xs">
					 	<label>เครดิตคงเหลือ</label>
					  <input type="text" class="form-control input-sm text-center" id="credit" value="" disabled />
					</div>

          <div class="col-sm-2 col-xs-6 padding-5 last-xs">
            <label>สาขา</label>
            <select class="form-control input-sm input-header margin-bottom-10" id="branch" <?php echo $disabled; ?>>
              <option value="">ทั้งหมด</option>
              <?php echo selectBranch($order->id_branch); ?>
            </select>
          </div>

          <div class="col-sm-2 col-xs-6 padding-5 last first-xs">
          	<label>ช่องทาง</label>
            <select class="form-control input-sm input-header margin-bottom-10" id="channels" <?php echo $disabled; ?>>
            <?php echo selectOfflineChannels($order->id_channels); ?>
            </select>
          </div>

					<div class="col-sm-2 col-xs-6 padding-5 first last-xs">
					 	<label>การชำระเงิน</label>
					  <select class="form-control input-sm input-header margin-bottom-10" id="paymentMethod" <?php echo $disabled; ?>>
					  <?php echo selectPaymentMethod($order->id_payment); ?>
					  </select>
					</div>

					<div class="col-sm-8 col-xs-12 padding-5 first-xs last-xs">
						<label>หมายเหตุ</label>
					  <input type="text" class="form-control input-sm input-header" id="remark" value="<?php echo $order->remark; ?>" <?php echo $disabled; ?> />
					</div>

					<div class="col-sm-2 col-xs-12 padding-5 last first-xs last-xs">
					  <label class="display-block not-show">btn</label>
            <?php if($order->isExpire == 0) : ?>
  						<?php if( isset( $_GET['id_order'] ) && $order->state < 8): ?>
  						<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit-order" onclick="getEdit()">แก้ไข</button>
  						<button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update-order" onclick="validUpdate()">บันทึก</button>
  						<?php else : ?>
  						<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()">สร้างออเดอร์</button>
  						<?php endif; ?>
            <?php endif;?>
					</div>

				</div>
				<input type="hidden" id="id_customer" value="<?php echo $order->id_customer; ?>" />
        <input type="hidden" id="isOnline" value="0" />
        <input type="hidden" id="id_branch" value="<?php echo $order->id_branch; ?>" />



<?php if( isset( $_GET['id_order'])) : ?>
          </div>
        </div>
      </div>
<?php endif; ?>
