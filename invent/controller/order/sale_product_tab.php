<?php
	$ds = "";
	$id_tab = $_POST['id'];
	$cs = new product_tab();
	$pd = new product();
	$img = new image();
	$stock = new stock();
	$qs = $cs->getStyleInSaleTab($id_tab);
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$style = new style($rs->id_style);
			if( $style->active == 1 && $pd->isDisactiveAll($rs->id_style) === FALSE)
			{
				$ds 	.= 	'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4"	style="text-align:center;">';
				$ds 	.= 		'<div class="product" style="padding:5px;">';
				$ds 	.= 			'<div class="image">';
				$ds 	.= 				'<a href="javascript:void(0)" onClick="getOrderGrid(\''.$rs->id_style.'\')">';
				$ds 	.=					'<img class="img-responsive" src="'.$img->getImagePath($img->getCover($rs->id_style), 2).'" />';
				$ds 	.= 				'</a>';
				$ds	.= 			'</div>';
				$ds	.= 			'<div class="description" style="font-size:10px; min-height:50px;">';
				$ds	.= 				'<a href="javascript:void(0)" onClick="getOrderGrid(\''.$rs->id_style.'\')">';
				$ds	.= 			$style->code.'<br/>'. number_format($pd->getStylePrice($rs->id_style),2);
				$ds 	.=  		$pd->isCountStock($rs->id_style) === TRUE ? ' | <span style="color:red;">'.$pd->getStyleSellStock($rs->id_style).'</span>' : '';
				$ds	.= 				'</a>';
				$ds 	.= 			'</div>';
				$ds	.= 		'</div>';
				$ds 	.=	'</div>';
			}
		}
	}
	else
	{
		$ds = "no_product";
	}

	echo $ds;

?>
