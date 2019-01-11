
<div class="modal fade" id="channels-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกกลุ่มลูกค้า</h4>
      </div>
      <div class="modal-body" id="channels-body">
        <div class="row">
          <div class="col-sm-12">
    <?php
    $qs = dbQuery("SELECT * FROM tbl_channels"); ?>
    <?php if(dbNumRows($qs) > 0) : ?>
      <?php $chn = getRuleChannels($id); ?>
      <?php while($rs = dbFetchObject($qs)) : ?>
        <?php $se = isset($chn[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox" class="chk-channels" name="chk-channels-<?php echo $rs->id; ?>" id="chk-channels-<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
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
