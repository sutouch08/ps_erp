// JavaScript Document


$(document).ready(function(e) {
   setColorbox();
});




//----------------  Dropzone --------------------//
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#imageForm", {
	url: "controller/productController.php?upload&id_style="+$("#id_style").val(),
	paramName: "file", // The name that will be used to transfer the file
	maxFilesize: 2, // MB
	uploadMultiple: true,
	maxFiles: 20,
	acceptedFiles: "image/*",
	parallelUploads: 5,
	autoProcessQueue: false,
	addRemoveLinks: true
});

myDropzone.on('complete', function(){ 
	clearUploadBox();
	loadImageTable();
});
					
function doUpload()
{
	myDropzone.processQueue();	
}

function clearUploadBox()
{
	$("#uploadBox").modal('hide');
	myDropzone.removeAllFiles();
}

function showUploadBox()
{
	$("#uploadBox").modal('show');	
}


function loadImageTable()
{
	var id_pd = $("#id_style").val();
	load_in();
	$.ajax({
		url:"controller/productController.php?getImageTable",
		type:"POST", cache:"false", data:{ "id_style" : id_pd },
		success: function(rs){
			load_out();
			var source 	= $("#imageTableTemplate").html();
			var data		= $.parseJSON(rs);
			var output	= $("#imageTable");
			render(source, data, output);
			setColorbox();
		}
	});
}



function removeImage(id_style, id_img)
{
	load_in();
	$.ajax({
		url:"controller/productController.php?removeImage",
		type:"POST", cache:"false", data:{ "id_style" : id_style, "id_image" : id_img },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			var arr = rs.split(' | ');
			if( arr[0] == 'success' )
			{
				$("#div-image-"+id_img).remove();	
				showNewCover(arr[1]);
			}
			else
			{
				swal("ข้อผิดพลาด", "ลบรูปภาพไม่สำเร็จ", "error");
			}
		}
	});
}



function showNewCover(id){
	$(".btn-cover").removeClass('btn-success');
	$("#btn-cover-"+id).addClass('btn-success');
}

function setAsCover(id_pd, id_img)
{
	$.ajax({
		url:"controller/productController.php?setCoverImage",
		type:"POST", cache:"false", data:{ "id_product" : id_pd, "id_image" : id_img },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' )
			{
				$(".btn-cover").removeClass('btn-success');
				$("#btn-cover-"+id_img).addClass('btn-success');
			}
		}
	});			
}




function setColorbox()
{
	var colorbox_params = {
				rel: 'colorbox',
				reposition: true,
				scalePhotos: true,
				scrolling: false,
				previous: '<i class="fa fa-arrow-left"></i>',
				next: '<i class="fa fa-arrow-right"></i>',
				close: 'X',
				current: '{current} of {total}',
				maxWidth: '800px',
				maxHeight: '800px',
				opacity:0.5,
				speed: 500,
				onComplete: function(){
					$.colorbox.resize();
				}
		}
		
	$('[data-rel="colorbox"]').colorbox(colorbox_params);
}



