<?php

function productTabMenu($mode = 'order')
{
	$ajax = $mode == 'order' ? 'getOrderTabs' : ($mode == 'sale' ? 'getSaleOrderTabs' : 'getViewTabs');
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = 0");
	while( $rs = dbFetchObject($qs))
	{
		if( hasChild($rs->id) === TRUE)
		{
			$sc .= '<li class="dropdown" onmouseover="expandTab((this))" onmouseout="collapseTab((this))">';
			$sc .= '<a id="ul-'.$rs->id.'" class="dropdown-toggle" role="tab" data-toggle="tab" href="#cat-'.$rs->id.'" onClick="'.$ajax.'(\''.$rs->id.'\')" >';
			$sc .=  $rs->name.'<span class="caret"></span></a>';
			$sc .= 	'<ul class="dropdown-menu" role="menu" aria-labelledby="ul-'.$rs->id.'">';
			$sc .= 	getSubTab($rs->id, $ajax);
			$sc .=  '</ul>';
			$sc .= '</li>';
		}
		else
		{
			$sc .= '<li class="menu"><a href="#cat-'.$rs->id.'" role="tab" data-toggle="tab" onClick="'.$ajax.'(\''.$rs->id.'\')">'.$rs->name.'</a></li>';
		}
	}
	return $sc;

}

//-- this function to view category product in order page
function getSubTab($parent, $ajax)
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = ".$parent);

	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			if( hasChild($rs->id) === TRUE ) //----- ถ้ามี sub category
			{
				$sc .= '<li class="dropdown-submenu" >';
				$sc .= '<a id="ul-'.$rs->id.'" class="dropdown-toggle" href="#cat-'.$rs->id.'" role="tab" data-toggle="tab" onClick="'.$ajax.'(\''.$rs->id.'\')">';
				$sc .=  $rs->name.'</a>';
				$sc .= 	'<ul class="dropdown-menu" role="menu" aria-labelledby="ul-'.$rs->id.'">';
				$sc .= 	getSubTab($rs->id, $ajax);
				$sc .=  '</ul>';
				$sc .= '</li>';
			}
			else
			{
				$sc .= '<li class="menu"><a href="#cat-'.$rs->id.'" role="menu" data-toggle="tab" onClick="'.$ajax.'(\''.$rs->id.'\')">'.$rs->name.'</a></li>';
			}

		}
	}
	return $sc;
}


//-- this function to view category product in order page
function getSubCategoryTab($parent, $ajax)
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = ".$parent);

	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			if( hasChild($rs->id) === TRUE ) //----- ถ้ามี sub category
			{
				$sc .= '<li class="dropdown-submenu" >';
				$sc .= '<a id="ul-'.$rs->id.'" class="dropdown-toggle" href="#cat-'.$rs->id.'" data-toggle="tab" onClick="'.$ajax.'(\''.$rs->id.'\')">';
				$sc .=  $rs->name.'</a>';
				$sc .= 	'<ul class="dropdown-menu" role="menu" aria-labelledby="ul-'.$rs->id.'">';
				$sc .= 	subCategoryTab($rs->id, $ajax);
				$sc .=  '</ul>';
				$sc .= '</li>';
			}
			else
			{
				$sc .= '<li class="menu"><a href="#cat-'.$rs->id.'" role="tab" data-toggle="tab" onClick="'.$ajax.'(\''.$rs->id.'\')">'.$rs->name.'</a></li>';
			}

		}
	}
	return $sc;
}



function getProductTabs()
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id != 0");
	while($rs = dbFetchObject($qs))
	{
		$sc .= '<div class="tab-pane" id="cat-'.$rs->id.'"></div>';
	}

	return $sc;
}




function selectLevel($level = '' )
{
	$sc = '<option value="">ทั้งหมด</option>';
	for( $i = 1; $i <= 5; $i++ )
	{
		$sc .= '<option value="'.$i.'" ' . isSelected($i, $level) . '>' . $i . '</option>';
	}
	return $sc;
}


function parentIn($txt)
{
	$sc = -1;
	$cs = new product_tab();
	$qs = $cs->getSearchResult($txt);
	$i = 1;
	if( dbNumRows($qs) > 0 )
	{
		$sc = '';
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= $i == 1 ? $rs->id : ", ".$rs->id;
		}
	}

	return $sc;
}


function getTabsTree($id = 0, $script = TRUE)
{
	$uid = uniqid();
	$cs = new product_tab();
	$sc	= '<ul class="tree">';
	$sc 	.= '<li>';
	$sc 	.= '<i class="fa fa-minus-square-o" id="catbox-0" onClick="toggleTree(0)"></i>';
	$sc 	.= '<label class="padding-10"><input type="radio" class="padding-10 margin-right-10" name="tabs" value="0" '. isChecked($id, 0) .' /> TOP LEVEL </label>';
	$sc 	.= '<ul id="catchild-0">';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = 0");
	$i = 1;
	while( $rs = dbFetchObject($qs) )
	{
		$sc .= '<li class="'. ($i == 1 ? '' : 'margin-top-15').'">';
		$i++;

		//----- Next Level
		if( hasChild($rs->id) === TRUE )
		{
			$sc .= '<i class="fa fa-plus-square-o" id="catbox-'.$rs->id.'" onClick="toggleTree('.$rs->id.')"></i>';
			$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id, $rs->id) .' />'.$rs->name.'</label>';
			$sc .= '<ul id="catchild-'.$rs->id.'" class="hide">';
			$sc .= getChild($rs->id, $id) ;
			$sc .= '</ul>';
		}
		else
		{
			$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id, $rs->id) .' />'.$rs->name.'</label>';
		}//---- has sub cate
		$sc .= '</li>';
	}
	$sc 	.= '</ul></li>';
	$sc	.= '</ul>';
	if( $script === TRUE)
	{
		$sc .= '<script>';
		$sc .= 'function toggleTree(id){';
		$sc .= 'var ul 	= $("#catchild-"+id);';
		$sc .= 'var rs 	= ul.hasClass("hide");';
		$sc .= 'if( rs == true){';
		$sc .= 'ul.removeClass("hide");';
		$sc .= '$("#catbox-"+id).removeClass("fa-plus-square-o");';
		$sc .= '$("#catbox-"+id).addClass("fa-minus-square-o");';
		$sc .= '}else	{';
		$sc .= 'ul.addClass("hide");';
		$sc .= '$("#catbox-"+id).removeClass("fa-minus-square-o");';
		$sc .= '$("#catbox-"+id).addClass("fa-plus-square-o");';
		$sc .= '} ';
		$sc .= '}';
		$sc .= '</script>';
	}
	return $sc;
}




function getEditTabsTree($id, $script = TRUE)
{
	$cs = new product_tab();
	$id_parent = $cs->getParentId($id);
	$parent = $cs->getAllParent($id);
	$sc	= '<ul class="tree">';
	$sc 	.= '<li>';
	$sc 	.= '<i class="fa fa-minus-square-o" id="edit-catbox-0" onClick="toggleEditTree(0)"></i>';
	$sc 	.= '<label class="padding-10"><input type="radio" class="padding-10 margin-right-10" name="tabs" value="0" '. isChecked($id_parent, 0) .' /> TOP LEVEL </label>';
	$sc 	.= '<ul id="edit-catchild-0">';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = 0");
	$i = 1;
	while( $rs = dbFetchObject($qs) )
	{
		if( $rs->id != $id )
		{
			$sc .= '<li class="'. ($i == 1 ? '' : 'margin-top-15').'">';
			$i++;
			$ex = isset( $parent[$rs->id] ) ? '' : 'hide';
			$ep = isset( $parent[$rs->id] ) ? 'minus' : 'plus';
			//----- Next Level
			if( hasEditChild($rs->id, $id) === TRUE )
			{
				$sc .= '<i class="fa fa-'.$ep.'-square-o" id="edit-catbox-'.$rs->id.'" onClick="toggleEditTree('.$rs->id.')"></i>';
				$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id_parent, $rs->id) .' />'.$rs->name.'</label>';
				$sc .= '<ul id="edit-catchild-'.$rs->id.'" class="'.$ex.'">';
				$sc .= getEditChild($rs->id, $parent, $id_parent, $id) ;
				$sc .= '</ul>';
			}
			else
			{
				$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id_parent, $rs->id) .' />'.$rs->name.'</label>';
			}//---- has sub cate
			$sc .= '</li>';
		}
	}
	$sc 	.= '</ul></li>';
	$sc	.= '</ul>';
	if( $script === TRUE)
	{
		$sc .= '<script>';
		$sc .= 'function toggleEditTree(id){';
		$sc .= 'var ul 	= $("#edit-catchild-"+id);';
		$sc .= 'var rs 	= ul.hasClass("hide");';
		$sc .= 'if( rs == true){';
		$sc .= 'ul.removeClass("hide");';
		$sc .= '$("#edit-catbox-"+id).removeClass("fa-plus-square-o");';
		$sc .= '$("#edit-catbox-"+id).addClass("fa-minus-square-o");';
		$sc .= '}else	{';
		$sc .= 'ul.addClass("hide");';
		$sc .= '$("#edit-catbox-"+id).removeClass("fa-minus-square-o");';
		$sc .= '$("#edit-catbox-"+id).addClass("fa-plus-square-o");';
		$sc .= '} ';
		$sc .= '}';
		$sc .= '</script>';
	}
	return $sc;
}



function hasChild($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT id FROM tbl_product_tab WHERE id_parent = ".$id);
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;
}



function hasEditChild($id, $id_tab)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT id FROM tbl_product_tab WHERE id_parent = ".$id." AND id != ".$id_tab);
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;
}



function getChild($id_parent, $id )
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = ".$id_parent);
	if( dbNumRows($qs) > 0 )
	{

		while( $rs = dbFetchObject($qs) )
		{
				$sc .= '<li>';
				//----- Next Level
			if( hasChild($rs->id) === TRUE )
			{
				$sc .= '<i class="fa fa-plus-square-o" id="catbox-'.$rs->id.'" onClick="toggleTree('.$rs->id.')"></i>';
				$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id, $rs->id) .' />' .$rs->name. '</label>';
				$sc .= '<ul id="catchild-'.$rs->id.'" class="hide">';
				$sc .= getChild($rs->id, $id) ;
				$sc .= '</ul>';
			}
			else
			{
				$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id, $rs->id) .' />'.$rs->name. '</label>';
			}//---- has sub cate
			$sc .= '</li>';
		}
	}
	return $sc;
}



function getEditChild($id_parent, $parent, $id, $id_tab )
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = ".$id_parent);
	if( dbNumRows($qs) > 0 )
	{

		while( $rs = dbFetchObject($qs) )
		{
			if( $rs->id != $id_tab )
			{
				$ex = isset( $parent[$rs->id] ) ? '' : 'hide';
				$ep = isset( $parent[$rs->id] ) ? 'minus' : 'plus';
				$sc .= '<li>';
				//----- Next Level
				if( hasEditChild($rs->id, $id_tab) === TRUE )
				{

					$sc .= '<i class="fa fa-'.$ep.'-square-o" id="edit-catbox-'.$rs->id.'" onClick="toggleEditTree('.$rs->id.')"></i>';
					$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id, $rs->id) .' />' .$rs->name. '</label>';
					$sc .= '<ul id="edit-catchild-'.$rs->id.'" class="'.$ex.'">';
					$sc .= getEditChild($rs->id, $parent, $id, $id_tab) ;
					$sc .= '</ul>';
				}
				else //-- if hasChild
				{
					$sc .= '<label class="padding-10"><input type="radio" class="margin-right-10" name="tabs" value="'.$rs->id.'" '. isChecked($id, $rs->id) .' />'.$rs->name. '</label>';
				}//---- if has Child
				$sc .= '</li>';
			}
		}//--- end while
	}//-- endif
	return $sc;
}



function productTabsTree($id_style = 0, $script = TRUE)
{
	$cs = new product_tab();

	//----- รายการที่ติ๊ก
	$se = $cs->getStyleTabsId($id_style);

	//------- รายการที่ต้อง expan
	$parent = $cs->getParentTabsId($id_style);

	$sc	= '<ul class="tree">';
	$sc 	.= '<li>';
	$sc 	.= '<i class="fa fa-minus-square-o" id="catbox-0" onClick="toggleTree(0)"></i>';
	$sc 	.= '<label class="padding-10"> TOP LEVEL </label>';
	$sc 	.= '<ul id="catchild-0">';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = 0");
	$i = 1;
	while( $rs = dbFetchObject($qs) )
	{
		$isChecked = isset( $se[$rs->id] ) ? 'checked' : '';
		$sc .= '<li class="'. ($i == 1 ? '' : 'margin-top-15').'">';
		$i++;

		$ex = isset( $parent[$rs->id] ) ? '' : 'hide';
		$ep = isset( $parent[$rs->id] ) ? 'minus' : 'plus';
		//----- Next Level
		if( hasChild($rs->id) === TRUE )
		{
			$sc .= '<i class="fa fa-'.$ep.'-square-o" id="catbox-'.$rs->id.'" onClick="toggleTree('.$rs->id.')"></i>';
			$sc .= '<label class="padding-10"><input type="checkbox" class="margin-right-10" name="tabs[]" value="'.$rs->id.'" '. $isChecked .' />'.$rs->name.'</label>';
			$sc .= '<ul id="catchild-'.$rs->id.'" class="'.$ex.'">';
			$sc .= productTabChild($rs->id, $id_style, $parent, $se) ;
			$sc .= '</ul>';
		}
		else
		{
			$sc .= '<label class="padding-10"><input type="checkbox" class="margin-right-10" name="tabs[]" value="'.$rs->id.'" '. $isChecked .' />'.$rs->name.'</label>';
		}//---- has sub cate
		$sc .= '</li>';
	}
	$sc 	.= '</ul></li>';
	$sc	.= '</ul>';
	if( $script === TRUE)
	{
		$sc .= '<script>';
		$sc .= 'function toggleTree(id){';
		$sc .= 'var ul 	= $("#catchild-"+id);';
		$sc .= 'var rs 	= ul.hasClass("hide");';
		$sc .= 'if( rs == true){';
		$sc .= 'ul.removeClass("hide");';
		$sc .= '$("#catbox-"+id).removeClass("fa-plus-square-o");';
		$sc .= '$("#catbox-"+id).addClass("fa-minus-square-o");';
		$sc .= '}else	{';
		$sc .= 'ul.addClass("hide");';
		$sc .= '$("#catbox-"+id).removeClass("fa-minus-square-o");';
		$sc .= '$("#catbox-"+id).addClass("fa-plus-square-o");';
		$sc .= '} ';
		$sc .= '}';
		$sc .= '</script>';
	}
	return $sc;
}


function productTabChild($id_parent, $id_style, $parent, $se )
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id_parent = ".$id_parent);
	if( dbNumRows($qs) > 0 )
	{

		while( $rs = dbFetchObject($qs) )
		{
				$sc .= '<li>';
				//----- Next Level
				$ex = isset( $parent[$rs->id] ) ? '' : 'hide';
				$ep = isset( $parent[$rs->id] ) ? 'minus' : 'plus';
				$isChecked = isset( $se[$rs->id] ) ? 'checked' : '';
			if( hasChild($rs->id) === TRUE )
			{
				$sc .= '<i class="fa fa-'.$ep.'-square-o" id="catbox-'.$rs->id.'" onClick="toggleTree('.$rs->id.')"></i>';
				$sc .= '<label class="padding-10"><input type="checkbox" class="margin-right-10" name="tabs[]" value="'.$rs->id.'" '. $isChecked .' />' .$rs->name. '</label>';
				$sc .= '<ul id="catchild-'.$rs->id.'" class="'.$ex.'">';
				$sc .= productTabChild($rs->id, $id_style, $parent, $se) ;
				$sc .= '</ul>';
			}
			else
			{
				$sc .= '<label class="padding-10"><input type="checkbox" class="margin-right-10" name="tabs[]" value="'.$rs->id.'" '. $isChecked .' />'.$rs->name. '</label>';
			}//---- has sub cate
			$sc .= '</li>';
		}
	}
	return $sc;
}



function isInTab($id_style, $id_tab)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT * FROM tbl_tab_product WHERE id_style = ".$id_style." AND id_product_tab = ".$id_tab);
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;
}


?>
