<?php
	$sc = "no data found";
	$order 		= new order($_GET['id_order']);
	$transform = new transform();
	$qs  			= $order->getDetails($order->id);
	if( dbNumRows($qs) > 0 )
	{
		$no = 1;
		$total_qty = 0;
		$image = new image();
		$ds = array();
		while( $rs = dbFetchObject($qs) )
		{
			$hasTransformProduct = $transform->hasTransformProduct($rs->id);
			$checked = $hasTransformProduct === FALSE ? 'checked' : '';
			$arr = array(
							"id"		=> $rs->id,
							"no"	=> $no,
							"imageLink"	=> $image->getProductImage($rs->id_product, 1),
							"productCode"	=> $rs->product_code,
							"productName"	=> $rs->product_name,
							"qty"	=> number_format($rs->qty),
							"transProduct"	=> getTransformProducts($rs->id),
							"trans_qty" => $transform->getSumTransformProductQty($rs->id),
							"checkbox"	=> $checked,
							"button" => $hasTransformProduct === FALSE ? '' : 'show'
							);
			array_push($ds, $arr);
			$total_qty += $rs->qty;
			$no++;
		}
		$arr = array(
					"total_qty" => number_format($total_qty)
				);
		array_push($ds, $arr);
		$sc = json_encode($ds);
	}
	echo $sc;

?>
