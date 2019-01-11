$(document).ready(function(){
  var interv = setInterval(function(){ goBack(); }, 300000);
});

$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});

$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose: function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


function getSearch(){
  $('#searchForm').submit();
}


$('.search-box').keyup(function(e) {
  if(e.keyCode == 13){
    getSearch();
  }
});


$('.search-select').change(function(event) {
  getSearch();
});


function clearFilter(){
  $.get('controller/qcController.php?clearFilter', function(){
    window.location.reload();
  });
}
