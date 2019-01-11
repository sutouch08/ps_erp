<?php

function imageAttributeGrid($id_style)
{
	$pd = new product();
	$image = new image();
	//$color = getAllColors($id_style); //-- result is array of all color on this style
	//$size = getAllSizes($id_style);  //--- result is array of all size on this style
	
	$sc = 'noimage';
	$qs = $pd->getProductsByStyle($id_style);
	$qr = $pd->getProductImages($id_style);
	$ic	 = dbNumRows($qr);  //---- จำนวนรูปภาพ
	$pc = dbNumRows($qs); //---- จำนวนรายการสินค้า
	if( $pc > 0 && $ic > 0 )
	{
		$topRow	= $qr;
		$imgs		= array();
		//$width	= ceil(80/$ic);
		
		$sc = '<table class="table table-bordered">';
		
		//---- image header
		$sc .= '<tr><td></td>';
		while($ra = dbFetchArray($topRow) )
		{
			$sc .= '<td>';
			$sc .= '<img src="'.$image->getImagePath($ra['id'], 2).'" width="100%" />';
			$sc .= '</td>';
			$imgs[] = $ra['id'];
		}
		$sc .= '</tr>';
		//---- End image header
		
		while( $rs = dbFetchArray($qs) )
		{
			$img	= $imgs;
			$sc .= '<tr>';
			$sc .= '<td>'.$rs['code'].'</td>';
			
			foreach($img as $id)
			{
				$sc .= '<td><label style="width:100%; text-align:center;"><input type="radio" name="items['.$rs['id'].']" value="'.$id.'" '.isChecked( $id, $pd->getImageId( $rs['id'] ) ).' /></label></td>';
			}
			$sc .= '</tr>';
		}
		$sc .= '</table>';		
	}	
	
	return $sc;
}
 


?>