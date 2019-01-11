<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">No.</th>
          <th class="text-center">สินค้า</th>
          <th class="width-10 text-center">ราคา</th>
          <th class="width-10 text-center">จำนวน</th>
          <th class="width-10 text-center">คืนแล้ว</th>
          <th class="width-10 text-center">คงเหลือ</th>
          <th class="width-10 text-center">ครั้งนี้</th>
        </tr>
      </thead>
      <tbody id="detail-table">

      </tbody>
    </table>
  </div>
</div>


<script id="template" type="text/x-handlebarsTemplate">
{{#each details}}
  {{#if nodata}}
  <tr>
    <td colspan="7" class="text-center"><h4>ไม่พบข้อมูล</h4></td>
  </tr>
  {{else}}
    {{#if @last}}
    <tr>
      <td colspan="3" class="middle text-center">รวม</td>
      <td class="middle text-center">{{totalQty}}</td>
      <td class="middle text-center">{{totalReceived}}</td>
      <td class="middle text-center">{{totalBalance}}</td>
      <td id="sumLabel">0</td>
    </tr>
    {{else}}
      <tr class="font-size-12" id="row-{{id}}">
        <td class="middle text-center">{{no}}</td>
        <td class="middle">{{product}}</td>
        <td class="middle text-center">{{price}}</td>
        <td class="middle text-center">{{qty}}</td>
        <td class="middle text-center">{{received}}</td>
        <td class="middle text-center" id="balance-{{id}}">{{balance}}</td>
        <td class="middle text-center">
          <input type="number" class="form-control input-sm text-center return-qty" id="qty-{{id}}" />
        </td>
      </tr>
    {{/if}}
  {{/if}}
{{/each}}
</script>
