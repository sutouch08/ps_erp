function goBack(){
  window.location.href = 'index.php?content=support';
}




//--  Add new support person
function goAdd(){
  window.location.href = 'index.php?content=support&add=Y';
}



//--- Edit support  and budget
function goEdit(id){
  window.location.href = 'index.php?content=support&edit=Y&id_support='+id;
}



//--- go to detail page
function getDetail(id){
  window.location.href = 'index.php?content=support&view_detail=Y&id_support='+id;
}



$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});



$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


$(document).ready(function() {
  $('#budget').numberOnly();
});
