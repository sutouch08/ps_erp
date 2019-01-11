<?php
$qs = $cs->getDetails($cs->id);
?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-10 text-center">บาร์โค้ด</th>
          <th class=" text-center">สินค้า</th>
          <th class="width-8 text-center">ราคา</th>
          <th class="width-8 text-center">ส่วนลด(%)</th>
          <th class="width-8 text-center">ส่วนลด(เงิน)</th>
          <th class="width-8 text-center">จำนวน</th>
          <th class="width-10 text-center">มูลค่า(หักส่วนลด)</th>
          <th class="width-5"></th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php if(dbNumRows($qs) > 0) : ?>
<?php  $no = 1; ?>
<?php  $totalQty = 0; ?>
<?php  $totalAmount = 0; ?>
<?php  $bc = new barcode(); ?>
<?php  while($rs = dbFetchObject($qs)) : ?>
<?php   $disc = explode(' ', $rs->discount); ?>
<?php   $p_disc = count($disc) > 1 ? $disc[0] : 0; ?>
<?php   $a_disc = count($disc) == 1 ? $disc[0] : 0; ?>
        <tr class="font-size-12 rox" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center no">
            <?php echo $no; ?>
          </td>
          <td class="middle text-center">
            <?php echo $bc->getBarcode($rs->id_product); ?>
          </td>
          <td class="middle hide-text">
            <?php echo $rs->product_code.' : '.$rs->product_name; ?>
          </td>
          <td class="middle text-center">
            <span class="price" id="price-<?php echo $rs->id; ?>"><?php echo number($rs->price,2); ?></span>
            <input type="number" class="form-control input-xs text-center hide input-price" id="input-price-<?php echo $rs->id; ?>" value="<?php echo $rs->price; ?>" />
          </td>
          <td class="middle text-center">
            <span class="p-disc" id="p_disc-<?php echo $rs->id; ?>"><?php echo number($p_disc,2); ?></span>
            <input type="number" class="form-control input-xs text-center hide input-p_disc" id="input-p_disc-<?php echo $rs->id; ?>" value="<?php echo $p_disc; ?>" />
          </td>
          <td class="middle text-center">
            <span class="a-disc" id="a_disc-<?php echo $rs->id; ?>"><?php echo number($a_disc, 2); ?></span>
            <input type="number" class="form-control input-xs text-center hide input-a_disc" id="input-a_disc-<?php echo $rs->id; ?>" value="<?php echo $a_disc; ?>" />
          </td>
          <td class="middle text-center qty" id="qty-<?php echo $rs->id; ?>">
            <?php echo number($rs->qty); ?>
          </td>
          <td class="middle text-right amount" id="amount-<?php echo $rs->id; ?>">
            <?php echo number($rs->total_amount, 2); ?>
          </td>
          <td class="middle text-center">
          <?php if($rs->status == 0 && ($edit OR $delete)) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="deleteRow('<?php echo $rs->id; ?>', '<?php echo $rs->product_code; ?>')">
              <i class="fa fa-trash"></i>
            </button>
          <?php endif; ?>
          </td>
        </tr>

<?php  $no++; ?>
<?php  $totalQty += $rs->qty; ?>
<?php  $totalAmount += $rs->total_amount; ?>
<?php endwhile; ?>
      <tr id="total-row">
        <td colspan="6" class="middle text-right"><strong>รวม</strong></td>
        <td id="total-qty" class="middle text-center"><?php echo number($totalQty); ?></td>
        <td id="total-amount" colspan="2" class="middle text-center"><?php echo number($totalAmount,2); ?></td>
      </tr>

<?php else : ?>
  <tr id="total-row">
    <td colspan="6" class="middle text-right"><strong>รวม</strong></td>
    <td id="total-qty" class="middle text-center">0</td>
    <td id="total-amount" colspan="2" class="middle text-center">0</td>
  </tr>
<?php endif; ?>

      </tbody>
    </table>
  </div>
</div>

<script id="new-row-template" type="text/x-handlebarsTemplate">
<tr class="font-size-12 rox" id="row-{{id}}">
  <td class="middle text-center no"></td>
  <td class="middle text-center">{{barcode}}</td>
  <td class="middle hide-text">{{product}}</td>
  <td class="middle text-center">
    <span class="price" id="price-{{id}}">{{price}}</span>
    <input type="number" class="form-control input-xs text-center hide input-price" id="input-price-{{id}}" value="{{price}}" />
  </td>
  <td class="middle text-center">
    <span class="p-disc" id="p_disc-{{id}}">{{p_disc}}</span>
    <input type="number" class="form-control input-xs text-center hide input-p_disc" id="input-p_disc-{{id}}" value="{{p_disc}}" />
  </td>
  <td class="middle text-center">
    <span class="a-disc" id="a_disc-{{id}}">{{a_disc}}</span>
    <input type="number" class="form-control input-xs text-center hide input-a_disc" id="input-a_disc-{{id}}" value="{{a_disc}}" />
  </td>
  <td class="middle text-center qty" id="qty-{{id}}">{{qty}}</td>
  <td class="middle text-right amount" id="amount-{{id}}">{{amount}}</td>
  <td class="middle text-center">
    <button type="button" class="btn btn-xs btn-danger" onclick="deleteRow('{{id}}', '{{product}}')">
      <i class="fa fa-trash"></i>
    </button>
  </td>
</tr>
</script>


<script id="row-template" type="text/x-handlebarsTemplate">
  <td class="middle text-center no"></td>
  <td class="middle text-center">{{barcode}}</td>
  <td class="middle hide-text">{{product}}</td>
  <td class="middle text-center price" id="price-{{id}}">{{price}}</td>
  <td class="middle text-center p-disc" id="p_disc-{{id}}">{{p_disc}}</td>
  <td class="middle text-center a-disc" id="a_disc-{{id}}">{{a_disc}}</td>
  <td class="middle text-center qty" id="qty-{{id}}">{{qty}}</td>
  <td class="middle text-right amount" id="amount-{{id}}">{{amount}}</td>
  <td class="middle text-center">
    <button type="button" class="btn btn-xs btn-danger" onclick="deleteRow('{{id}}', '{{product}}')">
      <i class="fa fa-trash"></i>
    </button>
  </td>
</script>

<script id="detail-template" type="text/x-handlebarsTemplate">
{{#each this}}
  {{#if @last}}
  <tr id="total-row">
    <td colspan="6" class="middle text-right"><strong>รวม</strong></td>
    <td id="total-qty" class="middle text-center">{{ total_qty }}</td>
    <td id="total-amount" colspan="2" class="middle text-center">{{ total_amount }}</td>
  </tr>
  {{else}}
  <tr class="font-size-12 rox" id="row-{{id}}">
    <td class="middle text-center no"></td>
    <td class="middle text-center">{{barcode}}</td>
    <td class="middle">{{product}}</td>
    <td class="middle text-center">
      <input type="number" class="form-control input-xs text-center padding-5 price" min="0" id="price-{{id}}" value="{{price}}" onKeyup="reCal('{{id}}')" onChange="reCal('{{id}}')" />
    </td>
    <td class="middle text-center">
      <input type="number" class="form-control input-xs text-center p-disc" min="0" max="100" id="p_disc-{{id}}" value="{{p_disc}}" onKeyup="p_disc_recal('{{id}}')" onChange="p_disc_recal('{{id}}')" />
    </td>
    <td class="middle text-center">
      <input type="number" class="form-control input-xs text-center a-disc" min="0" id="a_disc-{{id}}" value="{{a_disc}}" onKeyup="a_disc_recal('{{id}}')" onChange="a_disc_recal('{{id}}')" />
    </td>
    <td class="middle text-center">
      <input type="number" class="form-control input-xs text-center qty" min="0" id="qty-{{id}}" value="{{qty}}" onKeyup="reCal('{{id}}')" onChange="reCal('{{id}}')" />
    </td>
    <td class="middle text-right amount" id="amount-{{id}}">{{ amount }}</td>
    <td class="middle text-center">
      <button type="button" class="btn btn-xs btn-danger" onclick="deleteRow('{{id}}', '{{product}}')"><i class="fa fa-trash"></i></button>
    </td>
  </tr>
  {{/if}}

{{/each}}
</script>
