<?php
require "../../library/config.php";
require"../../library/functions.php";
require "../function/tools.php";
require '../function/product_helper.php';
require '../function/product_group_helper.php';
require '../function/category_helper.php';
require '../function/kind_helper.php';
require '../function/type_helper.php';
require '../function/productTab_helper.php';
require '../function/image_helper.php';


if( isset( $_GET['saveProduct'] ) )
{
	$sc = TRUE;
	$id_style = $_POST['id_style'];
	$pd = new product();

	$arr 	= array(
						"id_sub_group" => $_POST['pdSubGroup'],
						"id_kind"		=> $_POST['pdKind'],
						"id_type"		=> $_POST['pdType'],
						"id_category"	=> $_POST['pdCategory'],
						"year"			=> $_POST['pdYear'],
						"weight"		=> $_POST['weight'],
						"width"		=> $_POST['width'],
						"length"		=> $_POST['length'],
						"height"		=> $_POST['height'],
						"count_stock"	=> $_POST['isVisual'],
						"show_in_sale"	=> $_POST['inSale'],
						"show_in_customer"	=> $_POST['inCustomer'],
						"show_in_online"		=> $_POST['inOnline'],
						"can_sell"		=> $_POST['canSell'],
						"active"		=> $_POST['active'],
						"emp"			=> getCookie('user_id') //---- Who last edit products
						);

	if( $pd->updateProducts($id_style, $arr) !== TRUE)
	{
		$sc = FALSE;
		$message = 'บันทึกข้อมูลสินค้าไม่สำเร็จ';
	}

	if( $pd->updateDescription($id_style, $_POST['description']) !== TRUE)
	{
		$sc = FALSE;
		$message = 'บันทึกคำอธิบายสินค้าไม่สำเร็จ';
	}

	if(isset($_POST['tabs']))
	{
		//---	แถบแสดงสินค้า
		$tab = new product_tab();
		$tabs		= $_POST['tabs']; //---- will be receive in array
		if($tab->updateTabsProduct($id_style, $tabs) !== TRUE )
		{
			$sc = FALSE;
			$message = 'บันทึกแถบแสดงสินค้าไม่สำเร็จ';
		}
	}

	echo $sc === TRUE ? 'success' : $message;

}



if( isset( $_GET['saveItem'] ) )
{
	$sc = 'success';
	$id		= $_POST['id_pd'];
	$arr = array(
						"weight"	=> $_POST['weight'],
						"width"	=> $_POST['width'],
						"length"	=> $_POST['length'],
						"height"	=> $_POST['height']
						);
	$pd = new product();
	if( $pd->update($id, $arr) === FALSE )
	{
		$sc = 'บันทึกข้อมูลไม่สำเร็จ';
	}
	echo $sc;
}

//------ Get Item Data for Edit
if( isset( $_GET['getItem'] ) )
{
	$id = $_GET['id']; //--- id product
	$pd = new product($id);
	if( $pd->id != '')
	{
		$arr = array(
							"id_pd"		=> $pd->id,
							"pdCode"		=> $pd->code,
							"weight"		=> $pd->weight,
							"width"		=> $pd->width,
							"length"		=> $pd->length,
							"height"		=> $pd->height
							);
		$sc = json_encode($arr);
	}
	else
	{
		$sc = 'ไม่พบข้อมูลที่ต้องการแก้ไข';
	}
	echo $sc;
}



if( isset( $_GET['setShowInSale'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'show_in_sale';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';
	}
	echo $sc;
}



if( isset( $_GET['setShowInCustomer'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'show_in_customer';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';
	}
	echo $sc;
}


if( isset( $_GET['setShowInOnline'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'show_in_online';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';
	}
	echo $sc;
}




if( isset( $_GET['setCanSell'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'can_sell';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';
	}
	echo $sc;
}




if( isset( $_GET['setActive'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'active';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';
	}
	echo $sc;
}




//---------------------------------- Upload images  ------------------------//
if( isset( $_GET['upload'] ) )
{
	$id_style 	= $_GET['id_style'];
	$sc		= 'success';
	$image	= new image();
	//require '../../library/class/class.upload.php';
	if( ! empty( $_FILES ) )
	{

		$files 			= $_FILES['file'];
		if( is_string($files['name']) )
		{
			$rs = $image->doUpload($files, $id_style);
		}
		else if( is_array($files['name']) )
		{
			$fileCount = count($files['name']);
			for($i = 0; $i < $fileCount; $i++)
			{
				$file = array(
								'name' => $files['name'][$i],
			    				'type' => $files['type'][$i],
			    				'size' => $files['size'][$i],
								'tmp_name' => $files['tmp_name'][$i],
			  					'error' => $files['error'][$i]
								);
				$rs = $image->doUpload($file, $id_style);
				if( $rs !== TRUE )
				{
					$sc = 'fail';
				}
			}//--------- For Loop
		}//----- endif
	}
	else
	{
		$sc = "no_file";
	}//--- end if
	echo $sc;
}




//------------------------  Set Cover image  ---------------//
if( isset( $_GET['setCoverImage'] ) )
{
	$id_image	= $_POST['id_image'];
	$id_style		= $_POST['id_product'];
	$image 		= new image();
	$rs = $image->setCover($id_style, $id_image);
	$sc = $rs === TRUE ? 'success' : 'fail';
	echo $sc;
}



//----------------------  Delete image  ----------------//

if( isset( $_GET['removeImage'] ) )
{
	$sc = FALSE;
	$id_image	= $_POST['id_image'];
	$id_style		= $_POST['id_style'];
	$image		= new image();
	$isCover		= $image->isCover($id_image);
	if( $image->delete($id_image) === TRUE )
	{
		if( $isCover )
		{
			$sc = $image->newCover($id_style);
		}
		else
		{
			$sc = TRUE;
		}
	}
	$cover = $image->getCover($id_style);
	echo $sc === TRUE? 'success | '.$cover : 'fail';
}

//-------------------  Load Image Table ----------------//
if( isset( $_GET['getImageTable'] ) )
{
	$sc 	= array();
	$id_style = $_POST['id_style'];
	$pd = new product();
	$qs 	= $pd->getProductImages($id_style);
	$image = new image();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchArray($qs) )
		{
			$id_img		= $rs['id'];
			$cover		= $rs['cover'] == 1 ? 'btn-success' : '';
			$ds = array(
							'id_pd'		=> $id_style,
							'id_img'		=> $id_img,
							'thumbImage'	=> $image->getImagePath($id_img, 3),
							'bigImage'	=> $image->getImagePath($id_img, 4),
							'isCover'		=> $cover
						);
			array_push($sc, $ds);
		}
	}
	else
	{
		$sc = array("noimage" => "noimage");
	}
	echo json_encode($sc);
}



//---- List of image for mapping
if( isset( $_GET['getImageAttributeGrid'] ) )
{
	$id_style = $_POST['id_style'];
	echo imageAttributeGrid($id_style);
}

//---------------- จับคู่รูปภาพกับสินค้า
if( isset( $_GET['doMappingImageWithProductAttribute'] ) )
{
	$sc 		= 'success';
	$items	= $_POST['items'];
	$pd		= new product();
	$image	= new image();
	if( count($items) > 0 )
	{
		foreach( $items as $id_product => $id_image )
		{
			$arr = array("id_product" => $id_product, "id_image" => $id_image);
			if( $pd->hasImage($id_product) )
			{
				$qs = $pd->updateImage($id_product, $id_image);
			}
			else
			{
				$qs = $pd->addImage($id_product, $id_image);
			}
			if( ! $qs ){ $sc = 'fail'; }
		}
	}
	echo $sc;
}


//----	รายงานสินค้าคงเหลืรายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getIdProduct']))
{
	$pdCode = $_GET['pdCode'];
	$pd = new product();
	$id = $pd->getId($pdCode);

	echo $id === FALSE ? 'notfound' : $id;
}


if(isset($_GET['getIdStyle']))
{
	$code = $_GET['styleCode'];
	$style = new style();
	$id = $style->getId($code);

	echo $id === FALSE ? 'notfound' : $id;
}

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sProductCode');
	deleteCookie('sProductName');
	deleteCookie('sProductGroup');
	deleteCookie('sProductCategory');
	deleteCookie('sProductYear');
	echo 'done';
}

?>
