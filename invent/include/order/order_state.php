<?php
$ps 				= checkAccess($id_profile, 83); //--- ย้อนสถานะออเดอร์ได้หรือไม่
$px					= checkAccess($id_profile, 84);	//--- ย้อนสถานะออเดอร์ที่เปิดบิลแล้วได้หรือไม่
$canChange	= ($ps['add'] + $ps['edit'] + $ps['delete']) > 0 ? TRUE : FALSE;
$canUnbill	= ($px['add'] + $px['edit'] + $px['delete']) > 0 ? TRUE : FALSE;
?>

<div class="row" style="margin-left:0px; margin-right:0px; margin-bottom:5px;">
	<div class="col-sm-4 padding-left-0">
    	<table class="table border-1" style="margin-bottom:0px;">
        <?php if( $add OR $edit OR $delete ) : ?>
        	<tr>
            	<td class="width-30 middle text-right">สถานะ : </td>
                <td class="width-40">
                	<select class="form-control input-xs" style="padding-top:0px; padding-bottom:0px;" id="stateList">
                    	<option value="0">เลือกสถานะ</option>
							<?php if( $order->state != 11 && $order->isExpire == 0 ) : ?>
                 <?php if( $order->state <=3 && $edit) : ?>
                        <option value="1">รอการชำระเงิน</option>
                        <option value="2">แจ้งชำระเงิน</option>
                        <option value="3">รอจัดสินค้า</option>
								 <?php elseif($order->state > 3 && $order->state < 8 && $canChange ) : ?>
											 <option value="1">รอการชำระเงิน</option>
											 <option value="2">แจ้งชำระเงิน</option>
											 <option value="3">รอจัดสินค้า</option>
								 <?php elseif($order->state > 3 && $order->state >= 8 && $canUnbill ) : ?>
											 <option value="1">รอการชำระเงิน</option>
											 <option value="2">แจ้งชำระเงิน</option>
											 <option value="3">รอจัดสินค้า</option>
								 <?php endif; ?>
                 <?php if( $order->state < 8 && $delete ) : ?>
                        <option value="11">ยกเลิก</option>
								 <?php elseif( $order->state >= 8 && $canUnbill) : ?>
												<option value="11">ยกเลิก</option>
                 <?php endif; ?>
							<?php elseif($order->isExpire == 1 && $delete) : ?>
												<option value="11">ยกเลิก</option>
							<?php endif; ?>
                    </select>
                </td>
                <td class="width-30">
                <?php if( $order->status == 1 && $order->isExpire == 0 ) : ?>
                	<button class="btn btn-xs btn-primary btn-block" onclick="changeState()">เปลี่ยนสถานะ</button>
								<?php elseif($order->isExpire == 1 && $delete) : ?>
									<button class="btn btn-xs btn-primary btn-block" onclick="changeState()">เปลี่ยนสถานะ</button>
                <?php endif; ?>
                </td>
            </tr>
       <?php else : ?>
       <tr>
            	<td class="width-30 text-center">สถานะ</td>
                <td class="width-40 text-center">พนักงาน</td>
                <td class="width-30 text-center">เวลา</td>
            </tr>
       <?php endif; ?>
      </table>
	</div>
<?php $state = new state(); ?>
<?php $qs = $state->getOrderStateList($order->id); ?>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php 	while( $rs = dbFetchObject($qs) ) : ?>
	<div class="col-sm-1 col-1-harf padding-0 font-size-8" style="color:<?php echo $rs->font; ?>; background-color:<?php echo $rs->color; ?>">
    	<center><?php echo $rs->name; ?></center>
        <center><?php echo employee_name($rs->id_employee); ?></center>
        <center><?php echo thaiDateTime($rs->date_upd); ?></center>
    </div>
<?php	endwhile; ?>
<?php endif; ?>
</div>
