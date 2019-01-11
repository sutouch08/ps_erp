
<script src="../library/js/jquery.min.js"></script>
<script>

$(document).ready(function() {
  document.write('Set Order to Expired');
  setTimeout(function(){
    doExpired();
  }, 3000);

});

function doExpired(){
	$.ajax({
		url:'controller/orderController.php?setExpired',
		type:'GET',
		cache:'false',
		success:function(rs){
			var rs = $.trim(rs);
			window.close();
		}
	});
}
</script>
