<input type="hidden" id="id_order" value="<?php echo $order->id; ?>" />
<input type="hidden" id="online_code" value="<?php echo $order->online_code; ?>" />
<input type="hidden" id="id_customer" value="<?php echo $order->id_customer; ?>" />

<div class="row">
  <div class="col-sm-2">
    <label class="font-size-14 blod"><?php echo $order->reference; ?></label>
  </div>
  <div class="col-sm-5">
    <label class="font-size-14 blod">ลูกค้า : <?php echo orderCustomerName($order->id_customer, $order->online_code); ?></label>
  </div>
  <div class="col-sm-5 text-right">
    <label class="font-size-14 blod">พนักงาน : <?php echo employee_name($order->id_employee); ?></label>
  </div>
  <?php if( $order->remark != '') : ?>
    <div class="col-sm-12">
      <label class="font-size-14 blod">หมายเหตุ :</label>
      <?php echo $order->remark; ?>
    </div>
  <?php endif; ?>
</div>
<hr/>

<div class="row">
  <div class="col-sm-12 text-right">
    <button type="button" class="btn btn-sm btn-info" onclick="printAddress()"><i class="fa fa-print"></i> ใบนำส่ง</button>
    <button type="button" class="btn btn-sm btn-primary" onclick="printOrder()"><i class="fa fa-print"></i> Packing List </button>
    <button type="button" class="btn btn-sm btn-success" onclick="printOrderBarcode()"><i class="fa fa-print"></i> Packing List (barcode)</button>
    <button type="button" class="btn btn-sm btn-warning" onclick="showBoxList()"><i class="fa fa-print"></i> Packing List (ปะหน้ากล่อง)</button>
  </div>
</div>
<hr/>

<?php $bill = new bill(); ?>
<?php $pd = new product(); ?>
<?php $qs = $bill->getBilledDetail($order->id); ?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-35 text-center">สินค้า</th>
          <th class="width-10 text-center">ราคา</th>
          <th class="width-10 text-center">ออเดอร์</th>
          <th class="width-10 text-center">จัด</th>
          <th class="width-10 text-center">ตรวจ</th>
          <th class="width-10 text-center">ส่วนลด</th>
          <th class="width-10 text-center">มูลค่า</th>
        </tr>
      </thead>
      <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = 1;
        $totalQty = 0;
        $totalPrepared = 0;
        $totalQc = 0;
        $totalAmount = 0;
        $totalDiscount = 0;
        $totalPrice = 0;
?>
<?php   while( $rs = dbFetchObject($qs)) :  ?>
  <?php     $color = ($rs->order_qty == $rs->qc OR $rs->isCount == 0) ? '' : 'red'; ?>
          <tr class="font-size-12 <?php echo $color; ?>">
            <td class="text-center">
              <?php echo $no; ?>
            </td>

            <!--- รายการสินค้า ที่มีการสั่งสินค้า --->
            <td>
              <?php echo limitText($rs->product_code.' : '. $rs->product_name, 100); ?>
            </td>

            <!--- ราคาสินค้า  --->
            <td class="text-center">
              <?php echo number($rs->price, 2); ?>
            </td>

            <!---   จำนวนที่สั่ง  --->
            <td class="text-center">
              <?php echo number($rs->order_qty); ?>
            </td>

            <!--- จำนวนที่จัดได้  --->
            <td class="text-center">
              <?php echo $rs->isCount == 0 ? number($rs->order_qty) : number($rs->prepared); ?>
            </td>

            <!--- จำนวนที่ตรวจได้ --->
            <td class="text-center">
              <?php echo $rs->isCount == 0 ? number($rs->order_qty) : number($rs->qc); ?>
            </td>

            <!--- ส่วนลด  --->
            <td class="text-center">
              <?php echo discountLabel($rs->discount); ?>
            </td>

            <td class="text-right">
              <?php echo $rs->isCount == 0 ? number($rs->final_price * $rs->order_qty) : number( $rs->final_price * $rs->qc , 2); ?>
            </td>

          </tr>
  <?php
        $totalQty += $rs->order_qty;
        $totalPrepared += ($rs->isCount == 0 ? $rs->order_qty : $rs->prepared);
        $totalQc += ($rs->isCount == 0 ? $rs->order_qty : $rs->qc);
        $totalDiscount += ($rs->isCount == 0 ? $rs->discount_amount * $rs->order_qty : $rs->discount_amount * $rs->qc);
        $totalAmount += ($rs->isCount == 0 ? $rs->final_price * $rs->order_qty : $rs->final_price * $rs->qc);
        $totalPrice += ($rs->isCount == 0 ? $rs->price * $rs->order_qty : $rs->price * $rs->qc);
        $no++;
  ?>
<?php   endwhile; ?>
        <tr class="font-size-12">
          <td colspan="3" class="text-right font-size-14">
            รวม
          </td>

          <td class="text-center">
            <?php echo number($totalQty); ?>
          </td>

          <td class="text-center">
            <?php echo number($totalPrepared); ?>
          </td>

          <td class="text-center">
            <?php echo number($totalQc); ?>
          </td>

          <td class="text-center">
            ส่วนลดท้ายบิล
          </td>

          <td class="text-right">
            <?php echo number($order->bDiscAmount, 2); ?>
          </td>
        </tr>


        <tr>
          <td colspan="3" rowspan="3">
            หมายเหตุ : <?php echo $order->remark; ?>
          </td>
          <td colspan="3" class="blod">
            ราคารวม
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice, 2); ?>
          </td>
        </tr>

        <tr>
          <td colspan="3">
            ส่วนลดรวม
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalDiscount + $order->bDiscAmount, 2); ?>
          </td>
        </tr>

        <tr>
          <td colspan="3" class="blod">
            ยอดเงินสุทธิ
          </td>
          <td colspan="2" class="text-right">
            <?php echo number($totalPrice - ($totalDiscount + $order->bDiscAmount), 2); ?>
          </td>
        </tr>

<?php else : ?>
      <tr><td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td></tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<!--************** Address Form Modal ************-->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true">
  <div class="modal-dialog" style="width:500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="colse" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" id="info_body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="printSelectAddress()"><i class="fa fa-print"></i> พิมพ์</button>
      </div>
    </div>
  </div>
</div>

<?php include 'include/order_closed/box_list.php';  ?>

<script src="script/print/print_address.js"></script>
<script src="script/print/print_order.js"></script>
