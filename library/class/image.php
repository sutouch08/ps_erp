<?php

class image
{
	public $id;
	public $id_style;
	public $position;
	public $cover;


	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_image WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->id_style	= $rs->id_style;
				$this->position	= $rs->position;
				$this->cover	= $rs->cover;
			}
		}
	}


	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_image (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}


	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_image SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}


	public function delete($id)
	{
		$sc = FALSE;
		$qs = dbQuery("DELETE FROM tbl_image WHERE id = ".$id);
		if( $qs )
		{
			$qr = dbQuery("DELETE FROM tbl_product_image WHERE id_image = ".$id);
			if( $qr )
			{
				$size = 4;
				while( $size > 0 )
				{
					unlink($this->getImagePath($id, $size) );
					$size--;
				}
				$sc = TRUE;
			}
		}
		return  $sc;
	}


	//--- return image path
	public function getProductImage($id, $size)
	{
		$qs = dbQuery("SELECT id_image FROM tbl_product_image WHERE id_product = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			list( $id_image ) = dbFetchArray($qs);
		}
		else
		{
			$pd = new product();
			$id_style = $pd->getStyleId($id);
			$id_image = $this->getCover($id_style);
		}
		return $this->getImagePath($id_image, $size);
	}



	public function isProductImageExists($id_product, $id_image)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_image FROM tbl_product_image WHERE id_product = '".$id_product."' AND id_image = ".$id_image);
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}


	public function isExixts($id)
	{

	}



	public function newCover($id_style)
	{
		return dbQuery("UPDATE tbl_image SET cover = 1 WHERE id_style = '".$id_style."' LIMIT 1 ");
	}

	public function getLastId()
	{
		$sc = 0;
		$qs = dbQuery("SELECT MAX(id) AS id FROM tbl_image");
		list( $id ) = dbFetchArray($qs);
		if( ! is_null($id) )
		{
			$sc = $id;
		}
		return $sc;
	}


	public function newImageId()
	{
		return $this->getLastId() + 1;
	}

	public function getImagePath($id, $useSize = 2)
	{
		$id_style = $this->getStyle($id);
		$style = new style();
		$code  = $style->getCode($id_style);
		$path		= WEB_ROOT."img/product/";
		$noImgPath	= WEB_ROOT."img/product/";

		if( $code != '')
		{
			$path .= $code.'/';
		}

		$size		= '_default';
		switch( $useSize )
		{
			case 1 :
				$size = '_mini';
			break;
			case 2 :
				$size = '_default';
			break;
			case 3 :
				$size = '_medium';
			break;
			case 4 :
				$size = '_lage';
			break;
		}

		$noImgPath = $noImgPath.'no_image'.$size.'.jpg';

		$path .= 'product' . $size . '_'. $id .'.jpg';

		$file = $_SERVER['DOCUMENT_ROOT'].$path;

		if( file_exists($file))
		{
			return $path;
		}
		else
		{
			return $noImgPath;
		}
	}


	public function doUpload($file, $id_style)
	{
		$style = new style();
		$code  = $style->getCode($id_style);
		$sc 			= TRUE;
		$id_image	= $this->newImageId(); //-- เอา id_image ล่าสุด มา + 1
		$imgName 	= $id_image; //-- ตั้งชื่อรูปตาม id_image
		$imgPath 	= '../../img/product/';
		if( $code != '')
		{
			$imgPath .= $code.'/';
		}

		$image 	= new upload($file);
		$size 	= 4; //---- ใช้ทั้งหมด 4 ขนาด
		if( $image->uploaded )
		{
			//------  ทำการปรับขนาดและตั้งชื่อไฟล์แต่ละไซด์ที่ต้องการใช้งาน
			while( $size > 0 )
			{
				$img	= $this->getImageSizeProperties($size); //--- ได้ $img['prefix'] , $img['size'] กลับมา
				$size--;
				$image->file_new_name_body	= $img['prefix'] . $imgName; 		//--- เปลี่ยนชือ่ไฟล์ตาม prefix + id_image
				$image->image_resize			= TRUE;		//--- อนุญาติให้ปรับขนาด
				$image->image_retio_fill			= TRUE;		//--- เติกสีให้เต็มขนาดหากรูปภาพไม่ได้สัดส่วน
				$image->file_overwrite			= TRUE;		//--- เขียนทับไฟล์เดิมได้เลย
				$image->auto_create_dir		= TRUE;		//--- สร้างโฟลเดอร์อัตโนมัติ กรณีที่ไม่มีโฟลเดอร์
				$image->image_x					= $img['size'];		//--- ปรับขนาดแนวตั้ง
				$image->image_y					= $img['size'];		//--- ปรับขนาดแนวนอน
				$image->image_background_color	= "#FFFFFF";		//---  เติมสีให้ตามี่กำหนดหากรูปภาพไม่ได้สัดส่วน
				$image->image_convert			= 'jpg';		//--- แปลงไฟล์

				$image->process($imgPath);						//--- ดำเนินการตามที่ได้ตั้งค่าไว้ข้างบน

				if( ! $image->processed )	//--- ถ้าไม่สำเร็จ
				{
					$sc 	= $image->error;
				}
			}//--- end while
		}//--- end if
		$image->clean();	//--- เคลียร์รูปภาพออกจากหน่วยความจำ
		$cover	= $this->hasCover($id_style) == TRUE ? 0 : 1  ;  		//--- มี cover อยู่แล้วหรือป่าว  มีอยู่แล้ว = TRUE , ไม่มี = FALSE
		$top		= $this->newPosition($id_style); 	//--- ตำแหน่งล่าสุดของรูปสินค้านั้นๆ +1
		$arr = array(
							"id"		=> $id_image,
							"id_style"	=> $id_style,
							"position"	=> $top,
							"cover"	=> $cover
						);

		$rs 		= $this->add($arr);		//--- เพิ่มข้อมูลรูปภาพลงฐานข้อมูล
		return $sc;
	}


	public function hasCover($id_style)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_image WHERE id_style = '".$id_style."' AND cover = 1");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}


	public function newPosition($id_style)
	{
		$sc = 0;
		$qs = dbQuery("SELECT MAX(position) AS position FROM tbl_image WHERE id_style = '".$id_style."'");
		list( $pos ) = dbFetchArray($qs);
		if( ! is_null( $pos) )
		{
			$sc = $pos;
		}
		return $sc + 1;
	}



	public function getImageSizeProperties($size)
	{
		$sc = array();
		switch($size)
		{
			case "1" :
			$sc['prefix']	= "product_mini_";
			$sc['size'] 	= 60;
			break;
			case "2" :
			$sc['prefix'] 	= "product_default_";
			$sc['size'] 	= 125;
			break;
			case "3" :
			$sc['prefix'] 	= "product_medium_";
			$sc['size'] 	= 250;
			break;
			case "4" :
			$sc['prefix'] 	= "product_lage_";
			$sc['size'] 	= 1500;
			break;
			default :
			$sc['prefix'] 	= "";
			$sc['size'] 	= 300;
			break;
		}//--- end switch
		return $sc;
	}


	public function setCover($id_style, $id_image)
	{
		$sc = FALSE;
		$qs = dbQuery("UPDATE tbl_image SET cover = 0 WHERE id_style = '".$id_style."'");
		if( $qs )
		{
			$sc = dbQuery("UPDATE tbl_image SET cover = 1 WHERE id = ".$id_image);
		}
		return $sc;
	}



	public function isCover($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_image WHERE id = ".$id." AND cover = 1");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}


	public function getCover($id_style)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_image WHERE id_style = '".$id_style."' AND cover = 1 ");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	public function getStyle($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT id_style FROM tbl_image WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}

}//---- End class

?>
