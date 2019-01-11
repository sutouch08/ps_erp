<?php
class stock_grid extends product
{
	public $filter;
	public function __construct()
	{
		parent::__construct();
		$this->filter = getConfig('MAX_SHOW_STOCK');
	}

	public function showStock($qty)
	{
		return $this->filter == 0 ? $qty : ($this->filter < $qty ? $this->filter : $qty);
	}

	public function getAttribute($id_style)
	{
		$sc = array();
		$color = dbNumRows(dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND id_color != '0' AND id_color != '' GROUP BY id_style"));
		$size = dbNumRows(dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND id_size != '0' AND id_size != '' GROUP BY id_style"));
		if( $color == 1 )
		{
			$sc[] = "color";
		}
		if( $size == 1 )
		{
			$sc[] = "size";
		}
		return $sc;
	}



	public function getOrderTableWidth($id_style)
	{
		$sc = 900; //--- ชั้นต่ำ
		$tdWidth = 70;  //----- แต่ละช่อง
		$padding = 100; //----- สำหรับช่องแสดงไซส์
		$qs = dbQuery("SELECT id_color FROM tbl_product WHERE id_style = '".$id_style."' AND id_color != '0' AND id_color != '' GROUP BY id_color");
		$rs 	= dbNumRows($qs);
		if( $rs > 0 )
		{
			$sc = ($rs + 1) * $tdWidth + $padding;
		}
		return $sc;
	}



	private function gridHeader(array $colors)
	{
		$sc = '<tr class="font-size-12"><td>&nbsp;</td>';
		foreach( $colors as $color )
		{
			$sc .= '<td class="text-center middle"><strong>'.$color['code'] . '<br/>'. $color['name'].'</strong></td>';
		}

		$sc .= '<td class="text-center middle"><strong>รวม</strong></td>';
		$sc .= '</tr>';
		return $sc;
	}




	public function getStockGrid($id_style, $view = FALSE, $id_order = '')
	{
		$sc = '';
		$isVisual = $this->isCountStock($id_style) === TRUE ? FALSE : TRUE;
		$attrs = $this->getAttribute($id_style);
		if( count($attrs) == 1  )
		{
			$sc .= $this->orderGridOneAttribute($id_style, $attrs[0], $isVisual, $view, $id_order);
		}
		else if( count( $attrs ) == 2 )
		{
			$sc .= $this->orderGridTwoAttribute($id_style, $isVisual, $view, $id_order);
		}
		return $sc;
	}






	private function orderGridOneAttribute($id_style, $attr, $isVisual, $view, $id_order = '')
	{
		$sc 		= '';
		$data 	= $attr == 'color' ? $this->getAllColors($id_style) : $this->getAllSizes($id_style);
		$ds 		= $this->getProductsByStyle($id_style);
		$sc 	 .= "<table class='table table-bordered'>";
		$i 		  = 0;
		while( $rs = dbFetchObject($ds) )
		{
			$id 			= $rs->id;
			$id_attr	= $rs->id_size == 0 || $rs->id_size == "" ? $rs->id_color : $rs->id_size;
			$sc 			.= $i%2 == 0 ? '<tr>' : '';
			$stock	  = $this->getStock($id); //---- สต็อกทั้งหมดทุกคลัง

			$sc 	.= '<td class="middle" style="border-right:0px;">';
			$sc 	.= '<strong>' .	$data[$id_attr]['code'] . '</strong>';
			$sc 	.= '</td>';

			$sc 	.= '<td class="middle" class="one-attribute">';
			$sc 	.= '<span class="green">'.$stock.'</span>';
			$sc 	.= '</td>';

			$i++;

			$sc 	.= $i%2 == 0 ? '</tr>' : '';

		}// end while

		$sc	.= "</table>";

		return $sc;
	}





	private function orderGridTwoAttribute($id_style, $isVisual, $view, $id_order = '')
	{

		$colors	= $this->getAllColors($id_style);
		$sizes 	= $this->getAllSizes($id_style);

		$sc 		= '';
		$sc 		.= '<table class="table table-bordered">';
		$sc 		.= $this->gridHeader($colors);

		$sCol   = array();
		$total  = 0;
		foreach( $sizes as $id_size => $size )
		{
			$sc 	.= '<tr style="font-size:12px;">';
			$sc 	.= '<td class="text-center middle" style="width:70px;"><strong>'.$size['code'].'</strong></td>';

			$c = 0;
			$sRow   = 0;
			foreach( $colors as $id_color => $color )
			{
				$id = FALSE;
				$stock = 0;
				$qs = dbQuery("SELECT * FROM tbl_product WHERE id_style = '".$id_style."' AND id_size = '".$id_size."' AND id_color = '".$id_color."' LIMIT 1");
				if( dbNumRows($qs) == 1 )
				{
					$rs = dbFetchObject($qs);
					$id 		= $rs->id;
					$stock	= $this->getStock($id); //---- สต็อกทั้งหมดทุกคลัง
					$sc 	.= '<td class="order-grid">';
					$sc 	.= '<span class="green">'.ac_format(number($stock)).'</span>';
					$sc 	.= '</td>';
				}
				else
				{
					$sc .= '<td class="order-grid">NA</td>';
				}
				$sRow += $stock;
				$sCol[$c] = isset($sCol[$c]) ? $sCol[$c] + $stock : $stock;
				$total += $stock;
				$c++;
			} //--- End foreach $colors

			$sc 	.= '<td class="order-grid">';
			$sc 	.= '<span class="blue">'.ac_format(number($sRow)).'</span>';
			$sc 	.= '</td>';

			$sc .= '</tr>';

		} //--- end foreach $sizes

		$sc 	.= '<tr style="font-size:12px;">';
		$sc 	.= '<td class="text-center middle" style="width:70px;"><strong>รวม</strong></td>';

		foreach($sCol as $value)
		{
			$sc 	.= '<td class="order-grid">';
			$sc 	.= '<span class="blue">'.ac_format(number($value)).'</span>';
			$sc 	.= '</td>';
		}

		$sc 	.= '<td class="order-grid">';
		$sc 	.= '<span class="blue">'.ac_format(number($total)).'</span>';
		$sc 	.= '</td>';

		$sc .= '</tr>';

		$sc .= '</table>';

	return $sc;
	}



}//---- End Class
