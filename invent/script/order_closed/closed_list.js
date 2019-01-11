var intv = setInterval(function(){ goBack(); }, 60000*5); //--- Reload every 5 minutes.

function toggleViewDate(i){
  //--- 0 = date_add   1 = date_upd
  $("#viewDate").val(i);
  getSearch();
}

function toggleButton(name){
    var i = $("#s"+name).val();
    var i = i == 1 ? 0 : 1;
    $("#s"+name).val(i);
    getSearch();
}



$("#fromDate").datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $("#toDate").datepicker('option', 'minDate', sd);
  }
});


$("#toDate").datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $("#fromDate").datepicker('option', 'maxDate', sd);
  }
});



$(".search-box").keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$('#sBranch').change(function(event) {
  getSearch();
});

function getSearch(){
  $("#searchForm").submit();
}



function clearFilter(){
  $.get('controller/orderClosedController.php?clearFilter', function(){ goBack();});
}


$(document).ready(function() {
	//---	reload ทุก 5 นาที
	setTimeout(function(){ goBack(); }, 300000);
});
