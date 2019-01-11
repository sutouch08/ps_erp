<div class="modal fade" id="style-list-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">รุ่นสินค้าที่กำหนด</h4>
      </div>
      <div class="modal-body" id="style-list-body">
        <ul style="list-style-type:none;" id="style-list">
<?php
      $qr  = "SELECT ps.id, ps.code FROM tbl_discount_rule_product_style AS sr ";
      $qr .= "LEFT JOIN tbl_product_style AS ps ON sr.id_product_style = ps.id ";
      $qr .= "WHERE sr.id_rule = ".$id;
?>
<?php $qs = dbQuery($qr); ?>
<?php if(dbNumRows($qs) > 0) : ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
          <li style="min-height:15px; padding:5px;" id="style-id-<?php echo $rs->id; ?>">
            <a href="#" class="paddint-5" onclick="removeStyleId('<?php echo $rs->id; ?>')">
              <i class="fa fa-times red"></i>
            </a>
            <span style="margin-left:10px;"><?php echo $rs->code ?></span>
          </li>
          <input type="hidden" name="styleId[<?php echo $rs->id; ?>]" id="styleId-<?php echo $rs->id; ?>" class="styleId" value="<?php echo $rs->id; ?>" />
<?php endwhile; ?>
<?php endif; ?>
        </ul>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="pd-group-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกกลุ่มสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-group-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_product_group"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductGroup($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-group" name="chk-pd-group-<?php echo $rs->id; ?>" id="chk-pd-group-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
                <?php echo $rs->name; ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="pd-sub-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกกลุ่มสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-sub-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_product_sub_group"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductSubGroup($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-sub" name="chk-pd-sub-<?php echo $rs->id; ?>" id="chk-pd-sub-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
                <?php echo $rs->name; ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="pd-cat-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกหมวดหมู่สินค้า</h4>
      </div>
      <div class="modal-body" id="pd-cat-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_product_category"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductCategory($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-cat" name="chk-pd-cat-<?php echo $rs->id; ?>" id="chk-pd-cat-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
                <?php echo $rs->name; ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="pd-type-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกชนิดสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-type-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_product_type"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductType($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-type" name="chk-pd-type-<?php echo $rs->id; ?>" id="chk-pd-type-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
                <?php echo $rs->name; ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="pd-kind-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกประเภทสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-kind-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_product_kind"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductKind($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-kind" name="chk-pd-kind-<?php echo $rs->id; ?>" id="chk-pd-kind-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
                <?php echo $rs->name; ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="pd-brand-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกยี่ห้อสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-brand-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_brand"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductBrand($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-brand" name="chk-pd-brand-<?php echo $rs->id; ?>" id="chk-pd-brand-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
                <?php echo $rs->name; ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="pd-year-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกปีสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-year-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT DISTINCT year FROM tbl_product"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $sd = getRuleProductYear($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($sd[$rs->year]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-pd-year" name="chk-pd-year-<?php echo $rs->year; ?>" id="chk-pd-year-<?php echo $rs->year; ?>" value="<?php echo $rs->year; ?>" <?php echo $se; ?> />
                <?php echo (($rs->year == '0000' OR $rs->year == '') ? 'ไม่ระบุ' :$rs->year); ?>
              </label>
      <?php endwhile; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>
