

<div class="tab-pane fade" id="address">

	<div class="row">
        <div class="col-sm-12 top-col">
            <h4 class="title">ที่อยู่หลัก</h4>
        </div>
        <div class="divider"></div>
        <div class="col-sm-3"><span class="form-control left-label text-right">ที่อยู่</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400" style="display:inline-block"><?php echo $customer->address1; ?></label>            
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right">&nbsp;</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400" style="display:inline-block"><?php echo $customer->address2; ?></label>            
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right">&nbsp;</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400" style="display:inline-block"><?php echo $customer->address3; ?></label>            
        </div>
        
        <div class="col-sm-12 top-col">
            <h4 class="title">ที่อยู่ปัจจุบัน</h4>
        </div>
        <div class="divider"></div>
        
    	<div class="col-sm-3"><span class="form-control left-label text-right">เลที่</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small" style="display:inline-block"><?php echo $customer->address_no; ?></label>            
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เลขที่ห้อง</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small"><?php echo $customer->room_no; ?></label>
            
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ชั้นที่</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small"><?php echo $customer->floor_no; ?></label>
            
        </div>
       
      
              
        <div class="col-sm-3"><span class="form-control left-label text-right">อาคาร</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->building; ?></label>
            
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ซอย</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->soi; ?></label>
            
        </div>
        
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ถนน</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->road; ?></label>
           
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">หมู่ที่</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small"><?php echo $customer->village_no; ?></label>
            
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ตำบล/แขวง</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->tambon; ?></label>
           
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right">อำเภอ/เขต</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->amphur; ?></label>
            
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right">จังหวัด</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->province; ?></label>
            
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right">รหัสไปรษณีย์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small"><?php echo $customer->zip; ?></label>
        </div>
        
        <div class="col-sm-3"><span class="form-control left-label text-right"></span></div>
        <div class="col-sm-9">
            <span class="help-block">*** ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
  
    </div>

</div><!--- Tab-pane --->