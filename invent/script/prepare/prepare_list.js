

function getSearch(){
  $("#searchForm").submit();
}






function clearFilter(){
  $.get("controller/prepareController.php?clearFilter", function(){ goBack(); });
}





$(".search-box").keyup(function(e){
  if( e.keyCode == 13){
    getSearch();
  }
});



$('.search-select').change(function(event) {
  getSearch();
});




$("#fromDate").datepicker({
  dateFormat: 'dd-mm-yy',
  onClose: function(sd){
    $("#toDate").datepicker("option", "minDate", sd);
  }
});


$("#toDate").datepicker({
  dateFormat: 'dd-mm-yy',
  onClose: function(sd){
    $("#fromDate").datepicker("option", "maxDate", sd);
  }
});





//---- Reload page every 5 minute
$(document).ready(function(){
  setInterval(function(){ goBack();}, 300000);
});
