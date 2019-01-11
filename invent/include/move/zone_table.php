<div class="row">
	<div class="col-sm-12 hide" id="zone-table">
    <form id="productForm">
			<input type="text" name="xx" class="hide" />
    	<table class="table table-striped table-bordered">
      	<thead>
					<tr>
						<th colspan="5" class="text-center">
							<h4 class="title" id="zoneName"></h4>
						</th>
					</tr>
        	<tr id="row-btn">
          	<th colspan="5">
							<div class="col-sm-6">
              	<button type="button" class="btn btn-sm btn-warning" onclick="addAllToMove()">ย้ายรายการทั้งหมด</button>
              </div>
              <div class="col-sm-6">
                <p class="pull-right top-p">
                  <button type="button" class="btn btn-sm btn-primary" onclick="addToMove()">ย้ายรายการที่เลือก</button>
                </p>
              </div>
            </th>
          </tr>

          <tr>
          	<th class="width-10 text-center">ลำดับ</th>
            <th class="width-20 text-center">บาร์โค้ด</th>
            <th class="width-40 text-center">สินค้า</th>
            <th class="width-10 text-center">จำนวน</th>
            <th class="width-10 text-center">ย้ายออก</th>
          </tr>
          </thead>

          <tbody id="zone-list"> </tbody>

        </table>
      </form>
    </div>
	</row>

		<script id="zoneTemplate" type="text/x-handlebars-template">
		{{#each this}}
		{{#if nodata}}
		<tr>
			<td colspan="6" class="text-center">
				<h4>ไม่พบสินค้าในโซน</h4>
			</td>
		</tr>
		{{else}}
		<tr>
			<td class="text-center">{{ no }}</td>
		  <td class="text-center">{{ barcode }}</td>
		  <td>{{ products }}</td>
		  <td class="qty-label text-center" id="qty-label-{{ id_stock }}">{{ qty }}</td>
		  <td class="text-center">
		  	<input type="text" class="form-control input-sm text-center input-qty" id="moveQty_{{ id_stock }}" name="moveQty[{{id_stock}}]" onkeyup="validQty({{ id_stock}}, {{ qty }})" />
				<input type="hidden" name="id_product[{{ id_stock }}]" id="id_product_{{ id_stock }}" value="{{ id_product }}" />
		  </td>

		</tr>
		{{/if}}
		{{/each}}
		</script>
