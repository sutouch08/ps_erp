<?php 
	$pd = new product(); 
	$qs = $pd->getStyleData($id_style);
	if( dbNumRows($qs) > 0 )
?>	
<div class="tab-pane fade active in" id="general">
	<div class="row">
    
    	<div class="col-sm-12 top-col">
    	<h4 class="title">ข้อมูลทั่วไป</h4>
    	</div>
    	<div class="divider"></div>
        
        
    	<div class="col-sm-3"><span class="form-control left-label text-right">รหัส</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small"><?php echo $customer->code; ?></label>
        </div>

        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ชื่อ - สกุล ลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400"><?php echo $customer->name; ?></label>
        </div>
       
      
              
        <div class="col-sm-3"><span class="form-control left-label text-right">โทรศัพท์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->tel; ?></label>
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">แฟกซ์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->fax; ?></label>
        </div>
        
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">อีเมล์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->email; ?></label>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">สถานะ</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-mini"><?php echo isActived($customer->active); ?></label>
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เลขประจำตัวประชาชน</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->m_id; ?></label>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เลขประจำตัวผู้เสียภาษี</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->tax_id; ?></label>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ชื่อผู้ติดต่อ</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400"><?php echo $customer->contact; ?></label>
        </div>
       
      
              
        <div class="col-sm-3"><span class="form-control left-label text-right">กลุ่มลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $cg->getGroupName($customer->id_group); ?></label>
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เขตลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $ca->getAreaName($customer->id_area); ?></label>
        </div>
        
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">พนักงานขาย</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $sale->getSaleName($customer->id_sale); ?></label>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">วงเงินเครดิต</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo number_format($customer->credit, 2); ?></label>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เครดิตเทอม</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->term; ?></label>
        </div>
      
      <div class="col-sm-3"><span class="form-control left-label text-right">&nbsp;</span></div>
        <div class="col-sm-9">
            <span class="help-block">*** ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
    
    </div>

</div><!--- Tab-pane --->