function toggleSale(id)
{
	$("#saleType").val(id);

	if( id == 0 ){
		$("#btn-sale").removeClass('btn-primary');
		$("#btn-consign").removeClass('btn-primary');
		$("#btn-all").addClass('btn-primary');
	}else if( id == 1 ){
		$("#btn-all").removeClass('btn-primary');
		$("#btn-consign").removeClass('btn-primary');
		$("#btn-sale").addClass('btn-primary');
	}else if( id == 8 ){
		$("#btn-all").removeClass('btn-primary');
		$("#btn-sale").removeClass('btn-primary');
		$("#btn-consign").addClass('btn-primary');
	}
}



$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy', onClose: function(sd){
		$("#toDate").datepicker('option', 'minDate', sd);
	}
});



$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy', onClose: function(sd){
		$("#fromDate").datepicker('option', 'maxDate', sd);
	}
});



function isLeapYear(year)
{
	var isLeap = new Date(year,2,1,-1).getDate()==29;
	return isLeap;
}



function getMaxDate(days, d, m, y)
{
	var leap = isLeapYear(y);
	if( m == '02' && leap == true ){ mD = 29; }else if( m == '02' && leap == false ){ mD = 28; }
	if( m == '01' || m == '03' || m == '05' || m == '07' || m == '08' || m == '10' || m == '12' ){ mD = 31; }
	if( m == '04' || m == '06' || m == '09' || m == '11' ){ mD = 30; }
	var date = parseInt(d);
	var days = parseInt(days);
	while( days > 0 )
	{
		if( date == mD )
		{
			date = 1;
			m++;
		}else{
			date++;
		}
		days--;
	}
	return date+'-'+m+'-'+y;
}



function getMinDate(days, d, m, y)
{
	var leap = isLeapYear(y);
	if( m == '02' && leap == true ){ mD = 29; }else if( m == '02' && leap == false ){ mD = 28; }
	if( m == '01' || m == '03' || m == '05' || m == '07' || m == '08' || m == '10' || m == '12' ){ mD = 31; }
	if( m == '04' || m == '06' || m == '09' || m == '11' ){ mD = 30; }
	var date = parseInt(d);
	var days = parseInt(days);
	while( days > 0 )
	{
		if( date == 1 )
		{
			m--;
			date = mD;
		}else{
			date--;
		}
		days--;
	}
	return date+'-'+m+'-'+y;
}





function doExport()
{
	var role 	= $("#saleType").val();
	var from 	= $("#fromDate").val();
	var to		= $("#toDate").val();

	if( !isDate(from) || !isDate(to) ){

    swal("วันที่ไม่ถูกต้อง");
    return false;

  }

	var token	= new Date().getTime();
  var url = 'controller/saleReportController.php?sale_deep_analyz&export';
  url += '&role='+role;
  url += '&fromDate='+from;
  url += '&toDate='+to;
  url += '&token='+token;
	get_download(token);
	window.location.href = url;
}
