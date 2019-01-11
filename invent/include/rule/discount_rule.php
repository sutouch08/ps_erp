<?php
$set_price = $cs->item_price > 0 ? 'Y' : 'N';
$price = $cs->item_price;
$btn_price_yes = $cs->item_price > 0 ? 'btn-primary' : '';
$btn_price_no = $cs->item_price > 0 ? '' : 'btn-primary';
$ac_price = $set_price == 'Y' ? '' : 'disabled';

$btn_disc_yes = $cs->item_disc > 0 ? 'btn-primary' : '';
$btn_disc_no = $cs->item_disc > 0 ? '' : 'btn-primary';
$btn_unit_p = $cs->item_disc_unit == 'percent' ? 'btn-primary' : '';
$btn_unit_a = $cs->item_disc_unit == 'amount' ? 'btn-primary' : '';
$unit = $cs->item_disc_unit == 'amount' ? 'A' :'P';
$ac_disc = $set_price == 'Y' ? 'disabled' : '';

$can_group = $cs->canGroup == 1 ? 'Y' : 'N';
$btn_can_group_yes = $can_group == 'Y' ? 'btn-primary' : '';
$btn_can_group_no = $can_group == 'N' ? 'btn-primary' : '';
?>

<div class="tab-pane fade active in" id="discount">

	<div class="row">
        <div class="col-sm-8 top-col">
            <h4 class="title">กำหนดส่วนลด</h4>
        </div>
				<div class="col-sm-4">
					<p class="pull-right top-p">

					</p>
				</div>
        <div class="divider margin-top-5"></div>
        <div class="col-sm-2">
					<span class="form-control left-label text-right">ราคาขาย</span>
				</div>
        <div class="col-sm-2">
          <div class="btn-group width-100">
          	<button type="button" class="btn btn-sm width-50 <?php echo $btn_price_yes; ?>" id="btn-set-price-yes" onclick="toggleSetPrice('Y')">YES</button>
						<button type="button" class="btn btn-sm width-50 <?php echo $btn_price_no; ?>" id="btn-set-price-no" onclick="toggleSetPrice('N')">NO</button>
          </div>
        </div>
        <div class="col-sm-2">
          <input type="number" class="form-control input-sm text-center" id="txt-price" value="<?php echo $cs->item_price; ?>" <?php echo $ac_price; ?> />
				</div>
				<div class="divider-hidden"></div>


        <div class="col-sm-2">
					<span class="form-control left-label text-right">ส่วนลด</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<input type="number" class="form-control input-sm text-center" id="txt-discount" value="<?php echo $cs->item_disc; ?>" <?php echo $ac_disc; ?> />
					</div>
        </div>
				<div class="col-sm-3">
          <div class="btn-group width-100">
          	<button type="button" class="btn btn-sm width-50 <?php echo $btn_unit_p; ?>" id="btn-pUnit" onclick="toggleUnit('P')" <?php echo $ac_disc; ?>>เปอร์เซ็นต์</button>
						<button type="button" class="btn btn-sm width-50 <?php echo $btn_unit_a; ?>" id="btn-aUnit" onclick="toggleUnit('A')" <?php echo $ac_disc; ?>>จำนวนเงิน</button>
          </div>
				</div>
				<div class="divider-hidden"></div>


        <div class="col-sm-2">
					<span class="form-control left-label text-right">จำนวนขั้นต่ำ</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<input type="number" class="form-control input-sm text-center" id="txt-qty" value="<?php echo $cs->qty; ?>" />
					</div>
        </div>
				<div class="divider-hidden"></div>


        <div class="col-sm-2">
					<span class="form-control left-label text-right">มูลค่าขั้นต่ำ</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<input type="number" class="form-control input-sm text-center" id="txt-amount" value="<?php echo $cs->amount; ?>" />
					</div>
        </div>
				<div class="divider-hidden"></div>

        <div class="col-sm-2">
					<span class="form-control left-label text-right">รวมยอดได้</span>
				</div>
        <div class="col-sm-2">
          <div class="btn-group width-100">
          	<button type="button" class="btn btn-sm width-50 <?php echo $btn_can_group_yes; ?>" id="btn-cangroup-yes" onclick="toggleCanGroup('Y')">YES</button>
						<button type="button" class="btn btn-sm width-50 <?php echo $btn_can_group_no; ?>" id="btn-cangroup-no" onclick="toggleCanGroup('N')">NO</button>
          </div>
        </div>
				<div class="divider-hidden"></div>
				<div class="col-sm-2">&nbsp;</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-sm btn-success btn-block" onclick="saveDiscount()"><i class="fa fa-save"></i> บันทึก</button>
				</div>


    </div>

		<input type="hidden" id="set_price" value="<?php echo $set_price; ?>" />
		<input type="hidden" id="disc_unit" value="<?php echo $unit; ?>" />
		<input type="hidden" id="can_group" value="<?php echo $can_group; ?>" />

</div><!--- Tab-pane --->
