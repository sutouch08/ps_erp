
$('#fromDatex').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDatex').datepicker('option', 'minDate', sd);
  }
});



$('#toDatex').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDatex').datepicker('option', 'maxDate', sd);
  }
});



$(document).ready(function() {
  $('#budgetx').numberOnly();
});




$(function () {
  $('[data-toggle="popover"]').popover()
});
