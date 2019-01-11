<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i>&nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div>
  <hr/>
  <!--
  <div class="row">
    <div class="col-sm-3 col-sm-offset-3 padding-5">
      <label>ชื่อพนักงาน</label>
      <input type="text" class="form-control input-sm text-center" id="search-box" name="sEmp" />
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">show</label>
      <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ค้าหา</button>
    </div>
  </div>
  <hr/>
-->

</div><!--- container --->
<script>
  function doExport(){
    var token = new Date().getTime();
    var target = 'controller/otherReportController.php?permissionByEmployee&export&token='+token;
    get_download(token);
    window.location.href = target;
  }
</script>
