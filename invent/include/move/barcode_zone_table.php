<div class="row">
	<div class="col-sm-12 hide" id="zone-table">
    <form id="productForm">
    	<table class="table table-striped table-bordered">
      	<thead>
					<tr>
						<th colspan="4" class="text-center">
							<h4 class="title" id="zoneName"></h4>
						</th>
					</tr>
        	<tr>
          	<th colspan="4">
								<div class="col-sm-3">

								</div>
								<div class="col-sm-2">
								 <div class="input-group">
											 <span class="input-group-addon">จำนวน</span>
											 <input type="text" class="form-control input-sm" id="qty-from" value="1" />
									 </div>
							 </div>
							 <div class="col-sm-4">
								 <div class="input-group">
											 <span class="input-group-addon">บาร์โค้ดสินค้า</span>
											 <input type="text" class="form-control input-sm" id="barcode-item-from" placeholder="ยิงบาร์โค้ดเพื่อย้ายสินค้าออก" />
									 </div>
							 </div>
            </th>
          </tr>

          <tr>
          	<th class="width-10 text-center">ลำดับ</th>
            <th class="width-20 text-center">บาร์โค้ด</th>
            <th class="text-center">สินค้า</th>
            <th class="width-10 text-center">จำนวน</th>

          </tr>
          </thead>

          <tbody id="zone-list"> </tbody>

        </table>
      </form>
    </div>
  </div>


  <script id="zoneTemplate" type="text/x-handlebars-template">
  {{#each this}}
  {{#if nodata}}
  <tr>
  	<td colspan="4" class="text-center"><h4>ไม่พบสินค้าในโซน</h4></td>
  </tr>
  {{else}}
  <tr>
  	<td align="center">{{ no }}</td>
      <td align="center">{{ barcode }}</td>
      <td>
  		{{ products }}
  		<input type="hidden" id="qty_{{barcode}}" value="{{qty}}" />
  	</td>
      <td align="center" id="qty-label_{{barcode}}">	{{ qty }}	</td>
  </tr>
  {{/if}}
  {{/each}}
  </script>
