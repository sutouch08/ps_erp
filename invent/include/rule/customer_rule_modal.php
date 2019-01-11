<div class="modal fade" id="cust-name-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">รายชื่อลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-name-body">
        <ul style="list-style-type:none;" id="cust-list">
<?php
      $qr  = "SELECT cs.id, cs.code, cs.name FROM tbl_discount_rule_customers AS cr ";
      $qr .= "LEFT JOIN tbl_customer AS cs ON cr.id_customer = cs.id ";
      $qr .= "WHERE cr.id_rule = ".$id;
?>
<?php $qs = dbQuery($qr); ?>
<?php if(dbNumRows($qs) > 0) : ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
          <li style="min-height:15px; padding:5px;" id="cust-id-<?php echo $rs->id; ?>">
            <a href="#" class="paddint-5" onclick="removeCustId('<?php echo $rs->id; ?>')">
              <i class="fa fa-times red"></i>
            </a>
            <span style="margin-left:10px;"><?php echo $rs->code.' : '.$rs->name; ?></span>
          </li>
          <input type="hidden" name="custId[<?php echo $rs->id; ?>]" id="custId-<?php echo $rs->id; ?>" class="custId" value="<?php echo $rs->id; ?>" />
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




<div class="modal fade" id="cust-group-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกกลุ่มลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-group-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_customer_group"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $group_rule = getRuleCustomerGroup($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($group_rule[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-group" name="chk-group-<?php echo $rs->id; ?>" id="chk-group-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
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


<div class="modal fade" id="cust-type-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">ชนิดลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-type-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_customer_type"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $group_rule = getRuleCustomerType($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($group_rule[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-type" name="chk-type-<?php echo $rs->id; ?>" id="chk-type-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
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




<div class="modal fade" id="cust-kind-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">ประเภทลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-kind-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_customer_kind"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $group_rule = getRuleCustomerKind($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($group_rule[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-kind" name="chk-kind-<?php echo $rs->id; ?>" id="chk-kind-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
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




<div class="modal fade" id="cust-area-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เขตลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-area-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_customer_area"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $group_rule = getRuleCustomerArea($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($group_rule[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-area" name="chk-area-<?php echo $rs->id; ?>" id="chk-area-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
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




<div class="modal fade" id="cust-class-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:300px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เกรดลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-class-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_customer_class"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $group_rule = getRuleCustomerClass($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($group_rule[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-class" name="chk-class-<?php echo $rs->id; ?>" id="chk-class-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
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
