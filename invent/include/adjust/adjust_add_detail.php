<?php
  $qs = $cs->getDetails($id);
?>

<div class="row">
  <div class="col-sm-12">
    <p class="pull-right top-p">
      <span style="margin-right:30px;"><i class="fa fa-check green"></i> = ปรับยอดแล้ว</span>
      <span><i class="fa fa-times red"></i> = ยังไม่ปรับยอด</span>
    </p>
  </div>
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-10 text-center">บาร์โค้ด</th>
          <th class="width-20">รหัสสินค้า</th>
          <th class="width-20">สินค้า</th>
          <th class="width-20 text-center">โซน</th>
          <th class="width-8 text-center">เพิ่ม</th>
          <th class="width-8 text-center">ลด</th>
          <th class="width-5 text-center">สถานะ</th>
          <th class="width-5 text-right"></th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php   $no = 1;    ?>
<?php   $pd = new product(); ?>
<?php   $bc = new barcode(); ?>
<?php   $zone = new zone(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php    $pdCode = $pd->getCode($rs->id_product); ?>
      <tr class="font-size-12 rox" id="row-<?php echo $rs->id; ?>">
        <td class="middle text-center no">
          <?php echo $no; ?>
        </td>
        <td class="middle text-center">
          <?php echo $bc->getBarcode($rs->id_product); ?>
        </td>
        <td class="middle">
          <?php echo $pdCode; ?>
        </td>
        <td class="middle">
          <?php echo $pd->getName($rs->id_product); ?>
        </td>
        <td class="middle text-center">
          <?php echo $zone->getName($rs->id_zone); ?>
        </td>
        <td class="middle text-center" id="qty-up-<?php echo $rs->id; ?>">
          <?php echo $rs->qty > 0 ? number($rs->qty) : 0 ; ?>
        </td>
        <td class="middle text-center" id="qty-down-<?php echo $rs->id; ?>">
          <?php echo $rs->qty < 0 ? number($rs->qty * -1) : 0 ; ?>
        </td>
        <td class="middle text-center">
          <?php echo isActived($rs->valid); ?>
        </td>
        <td class="middle text-right">
        <?php if( ($add OR $edit) && $cs->isCancle == 0 && $cs->isExport == 0 && $cs->isSaved == 0 ) : ?>
          <button type="button" class="btn btn-xs btn-danger" onclick="deleteDetail(<?php echo $rs->id; ?>, '<?php echo $pdCode; ?>')">
            <i class="fa fa-trash"></i>
          </button>
        <?php endif; ?>
        </td>
      </tr>
<?php     $no++; ?>
<?php   endwhile; ?>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<script id="detail-template" type="text/x-handlebars-template">
<tr class="font-size-12 rox" id="row-{{id}}">
  <td class="middle text-center no">{{no}}</td>
  <td class="middle text-center">{{ barcode }}</td>
  <td class="middle">{{ pdCode }}</td>
  <td class="middle">{{ pdName }}</td>
  <td class="middle text-center">{{ zoneName }}</td>
  <td class="middle text-center" id="qty-up-{{id}}">{{ up }}</td>
  <td class="middle text-center" id="qty-down-{{id}}">{{ down }}</td>
  <td class="middle text-center">
    {{#if valid}}
    <i class="fa fa-times red"></i>
    {{else}}
    <i class="fa fa-check green"></i>
    {{/if}}
  </td>
  <td class="middle text-right">
  <?php if( ($add OR $edit) && $cs->isCancle == 0 && $cs->isExport == 0 && $cs->isSaved == 0 ) : ?>
    <button type="button" class="btn btn-xs btn-danger" onclick="deleteDetail({{ id }}, '{{ pdCode }}')">
      <i class="fa fa-trash"></i>
    </button>
  <?php endif; ?>
  </td>
</tr>
</script>
