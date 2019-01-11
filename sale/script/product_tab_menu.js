// JavaScript Document
function expandTab(el){
	var className = "open";
	if (el.classList){
		el.classList.add(className);
	}else if (!hasClass(el, className)){
		el.className += " " + className;
	}
}

function collapseTab(el)
{
	var className = "open";
	if (el.classList){
		el.classList.remove(className);
	}else if (hasClass(el, className)) {
		var reg = new RegExp("(\\s|^)" + className + "(\\s|$)");
		el.className=el.className.replace(reg, " ");
	}
}


function toggleTab(el){
	console.log(el);
	/*
	var className = 'open';
	var st = el.parent();
	console.log(st);
	if(el.parent().hasClass(className)){
		el.parent().removeClass(className);
	}else{
		el.parent().addClass(className);
	}
	*/
}

//--------------------------------  โหลดรายการสินค้าสำหรับดูยอดคงเหลือ  -----------------------------//
function getSaleViewTabs(id) {
	var output = $("#cat-" + id);
	$(".tab-pane").removeClass("active");
	$(".menu").removeClass("active");
	if (output.html() == "") {
		load_in();
		$.ajax({
			url: "../invent/controller/orderController.php?getSaleProductsInViewTab",
			type: "POST",	 cache: "false", data: { "id": id },
			success: function(rs) {
				load_out();
				var rs = $.trim(rs);
				if (rs != "no_product") {
					output.html(rs);
				} else {
					output.html("<center><h4>ไม่พบสินค้าในหมวดหมู่ที่เลือก</h4></center>");
				}
			}
		});
	}
	output.addClass("active");
}

//--------------------------------  โหลดรายการสินค้าสำหรับจิ้มสั่งสินค้า  -----------------------------//
function getSaleOrderTabs(id) {
	var output = $("#cat-" + id);
	$(".tab-pane").removeClass("active");
	$(".menu").removeClass("active");
	if (output.html() == "") {
		load_in();
		$.ajax({
			url: "../invent/controller/orderController.php?getSaleProductsInOrderTab",
			type: "POST", cache: "false", data: { "id": id },
			success: function(rs) {
				load_out();
				var rs = $.trim(rs);
				if (rs != "no_product") {
					output.html(rs);
				} else {
					output.html("<center><h4>ไม่พบสินค้าในหมวดหมู่ที่เลือก</h4></center>");
					$(".tab-pane").removeClass("active");
					output.addClass("active");
				}
			}
		});
	}
	output.addClass("active");
}
