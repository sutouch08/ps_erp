<div class="row">
	<div class="col-sm-12 hide" id="cancle-table">
    <form id="cancleForm">
			<input type="text" name="xxx" class="hide" />
    	<table class="table table-striped table-bordered">
      	<thead>
					<tr>
						<th colspan="6" class="text-center">
							<h4 class="title">โซนยกเลิก</h4>
						</th>
					</tr>
        	<tr id="cancle-btn">
          	<th colspan="6">
							<div class="col-sm-6">
              	<button type="button" class="btn btn-sm btn-warning" onclick="addAllCancleToMove()">ย้ายรายการทั้งหมด</button>
              </div>
              <div class="col-sm-6">
                <p class="pull-right top-p">
                  <button type="button" class="btn btn-sm btn-primary" onclick="addCancleToMove()">ย้ายรายการที่เลือก</button>
                </p>
              </div>
            </th>
          </tr>

          <tr>
          	<th class="width-5 text-center">ลำดับ</th>
            <th class="width-15 text-center">บาร์โค้ด</th>
            <th class="width-40 text-center">สินค้า / เลขที่เอกสาร</th>
            <th class="width-20 text-center">โซนต้นทาง</th>
            <th class="width-10 text-center">จำนวน</th>
            <th class="width-10 text-center">ย้ายออก</th>
          </tr>
          </thead>

          <tbody id="cancle-list"> </tbody>

        </table>
      </form>
    </div>
	</row>

		<script id="cancleTemplate" type="text/x-handlebars-template">
		{{#each this}}
		{{#if nodata}}
		<tr>
			<td colspan="6" class="text-center">
				<h4>ไม่พบสินค้าในโซนยกเลิก</h4>
			</td>
		</tr>
		{{else}}
		<tr class="font-size-12">
			<td class="text-center">{{ no }}</td>
		  <td class="text-center">{{ barcode }}</td>
		  <td>
        {{ product }} &nbsp; / &nbsp; <span class="blue">{{ order }}</span>
      </td>
      <td class="text-center">
        {{ zoneName }}
        <input type="hidden" name="zone[{{ id_cancle }}]" id="cancle-zone-{{ id_cancle }}" value="{{ id_zone }}" />
      </td>
		  <td class="cancle-qty-label text-center" id="calcle-qty-label-{{ id_cancle }}">{{ qty }}</td>
		  <td class="text-center">
		  	<input type="number" min="0" class="form-control input-sm text-center input-cancle-qty" id="moveCancleQty_{{ id_cancle }}" name="moveCancleQty[{{id_cancle}}]" onkeyup="validCancleQty({{id_cancle}}, {{ qty }})" />
				<input type="hidden" name="product[{{ id_cancle }}]" id="cancle-product_{{ id_cancle }}" value="{{ id_product }}" />
        <input type="hidden" name="order[{{id_cancle}}]" id="order-{{id_cancle}}" value="{{ id_order }}" />
		  </td>
		</tr>
		{{/if}}
		{{/each}}
		</script>
