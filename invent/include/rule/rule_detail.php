<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-circle"></i>&nbsp; <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr/>
<?php
$id_rule = (isset($_GET['id_rule']) && $_GET['id_rule'] != '') ? $_GET['id_rule'] : FALSE;
?>
<?php if(!$id_rule) : ?>
<?php    include 'include/page_error.php'; ?>
<?php else : ?>
<?php
$cs = new discount_rule($id_rule);
$pl = new discount_policy($cs->id_discount_policy);
$emp = new employee();
$currency = getConfig('CURRENCY');

?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <tr class="font-size-12">
        <td class="width-10 middle text-right"><strong>รหัสกฎ</strong></td>
        <td class="width-10 middle text-center"><?php echo $cs->code; ?></td>
        <td class="width-10 middle text-right"><strong>ชื่อกฏ</strong></td>
        <td class="width-20 middle" colspan="2"><?php echo $cs->name; ?></td>
        <td class="width-10 middle text-right"><strong>รหัสนโยบาย</strong></td>
        <td class="width-10 middle text-center"><?php echo $pl->reference; ?></td>
        <td class="width-10 middle text-right"><strong>ชื่อนโยบาย</strong></td>
        <td class="" colspan="2"><?php echo $pl->name; ?></td>
      </tr>
      <tr class="font-size-12">
        <td class="middle text-right"><strong>วันที่เพิ่ม</strong></td>
        <td class="middle text-center"><?php echo thaiDate($cs->date_add); ?></td>
        <td class="middle text-right"><strong>พนักงาน</strong></td>
        <td class="middle" colspan="2"><?php echo $emp->getFullName($cs->id_emp); ?></td>
        <td class="middle text-right"><strong>วันที่ปรับปรุง</strong></td>
        <td class="middle text-center"><?php echo thaiDate($cs->date_upd); ?></td>
        <td class="middle text-right"><strong>พนักงาน</strong></td>
        <td class="middle" colspan="2"><?php echo $emp->getFullName($cs->emp_upd); ?></td>
      </tr>
      <tr class="font-size-12">
        <td class="width-10 middle text-right"><strong>ส่วนลด</strong></td>
        <td class="width-10 middle text-center">
          <?php echo $cs->item_disc; ?>
          <?php echo ($cs->item_disc_unit == 'amount' ? $currency : '%'); ?>
        </td>
        <td class="width-10 middle text-right"><strong>กำหนดราคา</strong></td>
        <td class="width-10 middle text-center"><?php echo ($cs->item_price > 0 ? number($cs->item_price, 2).' '.$currency : 'No'); ?></td>
        <td class="width-10 middle text-right"><strong>จำนวนขั้นต่ำ</strong></td>
        <td class="width-10 middle text-center"><?php echo ($cs->qty > 0 ? number($cs->qty) : 'No'); ?></td>
        <td class="width-10 middle text-right"><strong>มูลค่าขั้นต่ำ</strong></td>
        <td class="width-10 middle text-center"><?php echo ($cs->amount > 0 ? number($cs->amount, 2).' '.$currency : 'No'); ?></td>
        <td class="width-10 middle text-right"><strong>รวมยอดได้</strong></td>
        <td class="width-10 middle text-center"><?php echo $cs->canGroup == 1 ? 'Yes' : 'No'; ?></td>
      </tr>

      <tr>
        <td colspan="10" class="text-center"><strong>ลูกค้า</strong></td>
      </tr>
      <?php if($cs->all_customer == 1) : ?>
      <tr class="font-size-12">
        <td class="middle text-right"><strong>ลูกค้า</strong></td>
        <td colspan="9">ทั้งหมด</td>
      </tr>
      <?php endif; ?>
      <!------------ รายชื่อลูกค้าแบบกำหนดรายบุคคล --------->
      <?php if($cs->all_customer == 0) : ?>
      <?php   $qs = $cs->getCustomerRuleList($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>รายชื่อลูกค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->code.' : '.$rs->name : ', '.$rs->code.' : '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!----------- จบรายชื่อลูกค้า  ------------>
      <!---------- กลุ่มลูกค้า ----------->
      <?php   $qs = $cs->getCustomerGroupRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>กลุ่มลูกค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>

      <!---------- จบกลุ่มลูกค้า ----------->
      <!---------- ชนิดลูกค้า ----------->
      <?php   $qs = $cs->getCustomerTypeRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>ชนิดลูกค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบชนิดลูกค้า ----------->
      <!---------- ประเภทลูกค้า ----------->
      <?php   $qs = $cs->getCustomerKindRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>ประเภทลูกค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบประเภทลูกค้า ----------->
      <!---------- เขตลูกค้า ----------->
      <?php   $qs = $cs->getCustomerAreaRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>เขตลูกค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบเชตลูกค้า ----------->
      <!---------- เกรดลูกค้า ----------->
      <?php   $qs = $cs->getCustomerClassRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>เกรดลูกค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบเกรดลูกค้า ----------->
      <?php endif; ?>
      <tr>
        <td colspan="10" class="text-center"><strong>สินค้า</strong></td>
      </tr>
      <?php if($cs->all_product == 1) : ?>
      <tr class="font-size-12">
        <td class="middle text-right"><strong>สินค้า</strong></td>
        <td colspan="9">ทั้งหมด</td>
      </tr>
      <?php endif; ?>

      <!------------ ถ้าไม่ได้เลือกสินค้าทั้งหมด แต่เลือกเป็นรุ่น --------->
      <?php if($cs->all_product == 0) : ?>
      <?php   $qs = $cs->getProductStyleRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>รุ่นสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->code : ', '.$rs->code; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!----------- จบรุ่นสินค้า  ------------>

      <!---------- กลุ่มสินค้า ----------->
      <?php   $qs = $cs->getProductGroupRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>กลุ่มสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบกลุ่มสินค้า ----------->
      <!---------- กลุ่มย่อยสินค้า ----------->
      <?php   $qs = $cs->getProductSubGroupRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>กลุ่มย่อยสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบกลุ่มย่อยสินค้า ----------->
      <!---------- ชนิดสินค้า ----------->
      <?php   $qs = $cs->getProductTypeRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>ชนิดสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบชนิดสินค้า ----------->
      <!---------- ประเภทสินค้า ----------->
      <?php   $qs = $cs->getProductKindRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>ประเภทสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบประเภทสินค้า ----------->
      <!---------- หมวดหมู่สินค้า ----------->
      <?php   $qs = $cs->getProductCategoryRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>หมวดหมู่สินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบหมวดหมู่สินค้า ----------->
      <!---------- ยี่ห้อสินค้า ----------->
      <?php   $qs = $cs->getProductBrandRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>ยี่ห้อสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบยี่ห้อสินค้า ----------->
      <!---------- ปีสินค้า ----------->
      <?php   $qs = $cs->getProductYearRule($id_rule); ?>
      <?php   if(dbNumRows($qs) > 0) : ?>
        <tr class="font-size-12">
          <td class="middle text-right"><strong>ปีสินค้า</strong></td>
          <td class="middle" colspan="9">
          <?php $i = 1; ?>
        <?php   while($rs = dbFetchObject($qs)) : ?>
          <?php echo $i == 1 ? $rs->year : ', '.$rs->year; ?>
          <?php $i++; ?>
        <?php endwhile; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!---------- จบปีสินค้า ----------->

    <?php endif; ?>

    <tr>
      <td colspan="10" class="text-center"><strong>ช่องทาง</strong></td>
    </tr>
    <tr class="font-size-12">
      <td class="middle text-right"><strong>ช่องทางขาย</strong></td>
      <td colspan="9">
        <?php if($cs->all_channels == 1) : ?>
            ทั้งหมด
        <?php else : ?>
          <?php $qs = $cs->getChannelsRule($id_rule); ?>
          <?php if(dbNumRows($qs) > 0) : ?>
            <?php $i = 1; ?>
            <?php while($rs = dbFetchObject($qs)) : ?>
              <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
              <?php $i++; ?>
            <?php endwhile; ?>
          <?php endif; ?>

        <?php endif; ?>
      </td>
    </tr>
    <tr class="font-size-12">
      <td class="middle text-right"><strong>การชำระเงิน</strong></td>
      <td colspan="9">
        <?php if($cs->all_payment == 1) : ?>
            ทั้งหมด
        <?php else : ?>
          <?php $qs = $cs->getPaymentRule($id_rule); ?>
          <?php if(dbNumRows($qs) > 0) : ?>
            <?php $i = 1; ?>
            <?php while($rs = dbFetchObject($qs)) : ?>
              <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
              <?php $i++; ?>
            <?php endwhile; ?>
          <?php endif; ?>

        <?php endif; ?>
      </td>
    </tr>

    </table>
  </div>

</div>









<?php endif; ?>
