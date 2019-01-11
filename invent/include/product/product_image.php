         <!-----------------------------------------------------  รูปภาพ  ---------------------------------------------------->
         <div class="tab-pane fade <?php echo $tab3; ?>" id="images-list">
         	<div class="row">
         		<div class="col-sm-4"><span class="form-control label-right"><h4 class="title">เพิ่มรูปภาพสำหรับสินค้านี้</h4></span></div>
                    <div class="col-sm-4">
                    	<button type="button" class="btn btn-primary btn-block" onClick="showUploadBox()"><i class="fa fa-cloud-upload"></i> เพิ่มรูปภาพ</button>
                    </div>
                    <div class="col-sm-4"><span class="help-block" style="margin-top:15px; margin-bottom:0px;">ไฟล์ : jpg, png, gif ขนาดสูงสุด 2 MB</span></div>
			</div><!--/ row -->
            <hr/>
            <div class="row" id="imageTable">
            <?php if( $id_style != 0 ) : ?>
            <?php 	$qs = $pd->getProductImages($id_style);	?>
            <?php 	if( dbNumRows($qs) > 0 )	:	?>
            <?php		while( $rs = dbFetchArray($qs) ) : 	?>
            <?php			$id_img 	= $rs['id'];		?>
            <?php			$cover	= $rs['cover'] == 1 ? 'btn-success' : ''; ?>
                <div class="col-sm-3" id="div-image-<?php echo $id_img; ?>">
                    <div class="thumbnail">
                        <a data-rel="colorbox" href="<?php echo $image->getImagePath($id_img, 4); ?>">
                            <img class="img-rounded" src="<?php echo $image->getImagePath($id_img, 3); ?>" />
                        </a>
                        <div class="caption">
                            <button type="button" id="btn-cover-<?php echo $id_img; ?>" class="btn btn-sm <?php echo $cover; ?> btn-cover" style="position:relative;" onClick="setAsCover('<?php echo $id_style; ?>', <?php echo $id_img; ?>)">
                            <i class="fa fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" style="position:absolute; right:25px;" onClick="removeImage('<?php echo $id_style; ?>', <?php echo $id_img; ?>)"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
			<?php		endwhile; ?>
            <?php	else : ?>
            	<div class="col-sm-12"><h4 style="text-align:center; padding-top:50px; color:#AAA;"><i class="fa fa-file-image-o fa-2x"></i> No image now</h4></div>
            <?php	endif;	?>
            <?php endif; ?>

         			<!-- Load Image Table with ajax -->
            </div><!--/ row -->


            <div class="modal fade" id="uploadBox" tabindex="-1" role="dialog" aria-labelledby="uploader" aria-hidden="true">
            	<div class="modal-dialog" style="width:800px">
                	<div class="modal-content">
                    	<div class="modal-header">
                            <h4 class="modal-title">อัพโหลดรูปภาพสำหรับสินค้านี้</h4>
                        </div>
                        <div class="modal-body">
                        	<form class="dropzone" id="imageForm" action="">
                            </form>
                        </div>
                        <div class="modal-footer">
                        	<button type="button" class="btn btn-sm btn-default" onClick="clearUploadBox()">ปิด</button>
                            <button type="button" class="btn btn-sm btn-primary" onClick="doUpload()">Upload</button>
                        </div>
                    </div>
                </div>
            </div>

         </div><!--/ tab-pane #tab3 -->
<script id="imageTableTemplate" type="text/x-handlebars-temlate">
{{#each this}}
	{{#if id_img}}
		<div class="col-sm-3" id="div-image-{{ id_img }}">
			<div class="thumbnail">
				<a data-rel="colorbox" href="{{ bigImage }}">
					<img class="img-rounded" src="{{ thumbImage }}" />
				</a>
				<div class="caption">
					<button type="button" id="btn-cover-{{ id_img }}" class="btn btn-sm {{ isCover }} btn-cover" style="position:relative;" onClick="setAsCover('{{ id_pd }}', {{ id_img }})"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-sm btn-danger" style="position:absolute; right:25px;" onClick="removeImage({{ id_pd }}, {{ id_img }})"><i class="fa fa-trash"></i></button>
				</div>
			</div>
		</div>
	{{else}}
		<div class="col-sm-12"><h4 style="text-align:center; padding-top:50px; color:#AAA;"><i class="fa fa-file-image-o fa-2x"></i> No image now</h4></div>
	{{/if}}
{{/each}}
</script>
<script src="script/product/product_image.js"></script>
