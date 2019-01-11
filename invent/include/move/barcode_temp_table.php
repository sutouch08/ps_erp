<div class="row">
  <div class="col-sm-12 hide" id="temp-table">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th colspan="5">
            <div class="col-sm-2 col-sm-offset-3">
              <div class="input-group">
                <span class="input-group-addon">จำนวน</span>
                <input type="text" class="form-control input-sm" id="qty-to" value="1" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon">บาร์โค้ดสินค้า</span>
                <input type="text" class="form-control input-sm" id="barcode-item-to" placeholder="ยิงบาร์โค้ดเพื่อย้ายสินค้าออก" />
              </div>
            </div>
          </th>
          </tr>
          <tr>
            <th class="width-5 text-center">ลำดับ</th>
            <th class="width-15 text-center">บาร์โค้ด</th>
            <th class="width-45 text-center">สินค้า</th>
            <th class="width-25 text-center">ต้นทาง</th>
            <th class="width-10 text-center">จำนวน</th>
          </tr>
        </thead>
        <tbody id="temp-list">

        </tbody>
      </table>
  </div>
</div>


<script id="tempTableTemplate" type="text/x-handlebars-template">
{{#each this}}
	{{#if nodata}}
	<tr>
		<td colspan="6" class="text-center"><h4>ไม่พบรายการ</h4></td>
	</tr>
	{{else}}
		<tr class="font-size-12" id="row-temp-{{ id }}">
			<td class="middle text-center">{{ no }}</td>
			<td class="middle">{{ barcode }}</td>
			<td class="middle">{{ products }}</td>
			<td class="middle text-center">
				<input type="hidden" id="from-{{ barcode }}" value="{{ from_zone }}" />
				<input type="hidden" id="row_{{barcode}}" value="{{id}}" />
				<input type="hidden" id="qty-{{barcode}}" value="{{qty}}" />
				{{ fromZone }}
			</td>

			<td class="middle text-center" id="qty-label-{{barcode}}">
				{{ qty }}
			</td>
		</tr>
	{{/if}}
{{/each}}
</script>
