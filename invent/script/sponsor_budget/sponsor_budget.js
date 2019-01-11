function goBack(){
  window.location.href = 'index.php?content=sponsor';
}




//--  Add new sponsor person
function goAdd(){
  window.location.href = 'index.php?content=sponsor&add=Y';
}



//--- Edit sponsor  and budget
function goEdit(id){
  window.location.href = 'index.php?content=sponsor&edit=Y&id_sponsor='+id;
}




//--- go to Detail pageTitle
function getDetail(id){
  window.location.href = 'index.php?content=sponsor&view_detail=Y&id_sponsor='+id;
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
