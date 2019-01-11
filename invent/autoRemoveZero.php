
<script src="../library/js/jquery.min.js"></script>
<script>

$(document).ready(function() {
  document.write('clear zero stock');
  setTimeout(function(){
    removeZero();
  }, 3000);

});

function removeZero(){
	$.ajax({
		url:'controller/stockController.php?removeZero',
		type:'GET',
		cache:'false',
		success:function(rs){
			var rs = $.trim(rs);
			window.close();
		}
	});
}
</script>
