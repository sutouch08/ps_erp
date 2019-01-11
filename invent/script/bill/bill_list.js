$(document).ready(function(){
  var intv = setInterval(function(){ goBack();}, 60000);
});






function getSearch(){
  $("#searchForm").submit();
}





function clearFilter(){
  $.get("controller/billController.php?clearFilter", function(){ goBack(); });
}





$(".search-box").keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$('#sBranch').change(function(){
  getSearch();
});



$("#sRole").change(function() {
  getSearch();
});



$("#fromDate").datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $("#toDate").datepicker("option", "minDate", sd);
  }
});


$("#toDate").datepicker({
  dateFormat: 'dd-mm-yy',
  onClose:function(sd){
    $("#fromDate").datepicker("option", "maxDate", sd);
  }
});



$(document).ready(function() {
	//---	reload ทุก 5 นาที
	setTimeout(function(){ goBack(); }, 300000);
});
