<?php
class product_grid extends product
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
		$sc = 800; //--- ชั้นต่ำ
		$tdWidth = 70;  //----- แต่ละช่อง
		$padding = 100; //----- สำหรับช่องแสดงไซส์
		$qs = dbQuery("SELECT id_color FROM tbl_product WHERE id_style = '".$id_style."' AND id_color != '0' AND id_color != '' GROUP BY id_color");
		$rs 	= dbNumRows($qs);
		if( $rs > 0 )
		{
			$sc = $rs * $tdWidth + $padding;
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
		$sc .= '</tr>';
		return $sc;
	}




	public function getOrderGrid($id_style, $view = FALSE, $id_branch = 0)
	{
		$sc = '';
		$isVisual = $this->isCountStock($id_style) === TRUE ? FALSE : TRUE;
		$attrs = $this->getAttribute($id_style);
		if( count($attrs) == 1  )
		{
			$sc .= $this->orderGridOneAttribute($id_style, $attrs[0], $isVisual, $view, $id_branch);
		}
		else if( count( $attrs ) == 2 )
		{
			$sc .= $this->orderGridTwoAttribute($id_style, $isVisual, $view, $id_branch);
		}
		return $sc;
	}






	private function orderGridOneAttribute($id_style, $attr, $isVisual, $view, $id_branch = 0)
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

			$active	= $rs->active == 0 ? 'Disactive' : ( $rs->can_sell == 0 ? 'Not for sell' : ( $rs->is_deleted == 1 ? 'Deleted' : TRUE ) );
			$stock	= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->getStock($id) )  : 0 ) : 0; //---- สต็อกทั้งหมดทุกคลัง
			$qty 		= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->getSellStock($id, $id_branch) ) : 0 ) : FALSE; //--- สต็อกที่สั่งซื้อได้
			$disabled  = $isVisual === TRUE  && $active == TRUE ? '' : ( ($active !== TRUE OR $qty < 1 ) ? 'disabled' : '');
			if( $qty < 1 && $active === TRUE )
			{
				$txt = '<p class="pull-right red">Sold out</p>';
			}
			else if( $qty > 0 && $active === TRUE )
			{
				$txt = '<p class="pull-right green">'. $qty .'  in stock</p>';
			}
			else
			{
				$txt = $active === TRUE ? '' : '<p class="pull-right blue">'.$active.'</p>';
			}


			$limit		= $qty === FALSE ? 1000000 : $qty;

			$sc 	.= '<td class="middle" style="border-right:0px;">';
			$sc 	.= '<strong>' .	$data[$id_attr]['code'] . '</strong>';
			$sc 	.= 	$qty === FALSE && $active === TRUE ? '' : ( ($qty < 1 || $active !== TRUE ) ? $txt : $qty);
			$sc 	.= '</td>';

			$sc 	.= '<td class="middle" class="one-attribute">';
			$sc 	.= $isVisual === FALSE ? '<center><span class="font-size-10 blue">('.($stock < 0 ? 0 : $stock).')</span></center>':'';
			if( $view === FALSE )
			{
			$sc 	.= '<input type="text" class="form-control input-sm order-grid" name="qty[0]['.$id.']" id="qty_'.$id.'" onkeyup="valid_qty($(this), '.($qty === FALSE ? 1000000 : $qty).')" '.$disabled.' />';
			}
			$sc 	.= '</td>';

			$i++;

			$sc 	.= $i%2 == 0 ? '</tr>' : '';

		}// end while

		$sc	.= "</table>";

		return $sc;
	}





	private function orderGridTwoAttribute($id_style, $isVisual, $view, $id_branch = 0)
	{

		$colors	= $this->getAllColors($id_style);
		$sizes 	= $this->getAllSizes($id_style);

		$sc 		= '';
		$sc 		.= '<table class="table table-bordered">';
		$sc 		.= $this->gridHeader($colors);

		foreach( $sizes as $id_size => $size )
		{
			$sc 	.= '<tr style="font-size:12px;">';
			$sc 	.= '<td class="text-center middle" style="width:70px;"><strong>'.$size['code'].'</strong></td>';

			foreach( $colors as $id_color => $color )
			{
				$id = FALSE;
				$qs = dbQuery("SELECT * FROM tbl_product WHERE id_style = '".$id_style."' AND id_size = '".$id_size."' AND id_color = '".$id_color."' LIMIT 1");
				if( dbNumRows($qs) == 1 )
				{
					$rs = dbFetchObject($qs);
					$id 		= $rs->id;
					$active	= $rs->active == 0 ? 'Disactive' : ( $rs->can_sell == 0 ? 'Not for sell' : ( $rs->is_deleted == 1 ? 'Deleted' : TRUE ) );
					$stock	= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->getStock($id) )  : 0 ) : 0; //---- สต็อกทั้งหมดทุกคลัง
					$qty 		= $isVisual === FALSE ? ( $active == TRUE ? $this->showStock( $this->getSellStock($id, $id_branch) ) : 0 ) : FALSE; //--- สต็อกที่สั่งซื้อได้
					$disabled  = $isVisual === TRUE  && $active == TRUE ? '' : ( ($active !== TRUE OR $qty < 1 ) ? 'disabled' : '');
					if( $qty < 1 && $active === TRUE )
					{
						$txt = '<span class="font-size-12 red">Sold out</span>';
					}
					else
					{
						$txt = $active === TRUE ? '' : '<span class="font-size-12 blue">'.$active.'</span>';
					}

					$available = $qty === FALSE && $active === TRUE ? '' : ( ($qty < 1 || $active !== TRUE ) ? $txt : $qty);
					$limit		= $qty === FALSE ? 1000000 : $qty;


					$sc 	.= '<td class="order-grid">';
					$sc 	.= $isVisual === FALSE ? '<center><span class="font-size-10 blue">('.$stock.')</span></center>' : '';
					if( $view === FALSE )
					{
						$sc 	.= '<input type="text" class="form-control order-grid" name="qty['.$id_color.']['.$id.']" id="qty_'.$id.'" onkeyup="valid_qty($(this), '.$limit.')" '.$disabled.' />';
					}
					$sc 	.= $isVisual === FALSE ? '<center>'.$available.'</center>' : '';
					$sc 	.= '</td>';
				}
				else
				{
					$sc .= '<td class="order-grid">Not Available</td>';
				}
			} //--- End foreach $colors

			$sc .= '</tr>';
		} //--- end foreach $sizes
	$sc .= '</table>';
	return $sc;
	}



}//---- End Class
