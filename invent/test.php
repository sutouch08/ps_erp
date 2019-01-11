
<div class="container">
  <div class="row top-row">
  	<div class="col-sm-6 top-col">
      	<h4 class="title"><i class="fa fa-exclamation-triangle"></i>&nbsp;<?php echo $pageTitle; ?></h4>
      </div>
      <div class="col-sm-6">
        <p class="pull-right top-p">
          <button type="button" class="btn btn-sm btn-success" onclick="goToStock()">Stock</button>
          <button type="button" class="btn btn-sm btn-info" onclick="goToMovement()">Movement</button>
          <button type="button" class="btn btn-sm btn-warning" onclick="goToBuffer()">Buffer</button>
          <button type="button" class="btn btn-sm btn-danger" onclick="goToCancle()">Cancle</button>
        </p>
      </div>
  </div><!-- / row -->

  <hr style="margin-bottom:15px;" />

<?php
if( isset( $_GET['stock']))
{
  include 'include/test/stock.php';
}
else if( isset( $_GET['movement']))
{
  include 'include/test/movement.php';
}
else if( isset( $_GET['buffer']))
{
  include 'include/test/buffer.php';
}
else if( isset($_GET['cancle']))
{
  include 'include/test/cancle.php';
}


?>

</div><!-- container -->
<script type="text/javascript">
  $('.search-box').keyup(function(e){
    if(e.keyCode == 13){
      getSearch();
    }
  });

  function getSearch(){
    $('#stockForm').submit();
  }

  function clearFilter(){
    $.get('controller/testController.php?clearFilter', function(){ window.location.reload(); });
  }
</script>
<script>

function goToStock(){
  window.location.href = "index.php?content=test_run&stock";
}

function goToMovement(){
  window.location.href = "index.php?content=test_run&movement";
}

function goToBuffer(){
  window.location.href = "index.php?content=test_run&buffer";
}

function goToCancle(){
  window.location.href = "index.php?content=test_run&cancle";
}
</script>
