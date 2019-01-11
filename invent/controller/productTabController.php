<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require "../function/productTab_helper.php";

//------- Add new Tab
if( isset( $_GET['addTab'] ) )
{
	$sc 		= 'success';
	$parent 	= $_POST['tabs'];
	$name 	= $_POST['addName'];
	$cs 		= new product_tab();
	$exists	= $cs->isExists('name', $name);
	if( $exists === TRUE )
	{
		$sc = 'nameError';
	}
		
	if( $exists === FALSE )
	{
		$arr = array( 
							'name' => $name, 
							'id_parent' => $parent
						 );
						 
		if( $cs->add($arr) === FALSE )
		{
			$sc = 'เพิ่มรายการไม่สำเร็จ';
		}
	}
	echo $sc;
}



if( isset( $_GET['saveEditTab'] ) )
{
	$sc = 'success';
	$id			= $_POST['id_tab'];
	$name	= $_POST['editName'];	
	$parent	= $_POST['tabs'];
	$cs = new product_tab();
	$nameExists = $cs->isExists('name', $name, $id);
	if( $nameExists === TRUE )
	{
		$sc = 'nameError';
	}
	
	
	if( $nameExists === FALSE )
	{
		$arr = array(
							'name' => $name,
							'id_parent'	=> $parent
						);
		if( $cs->update($id, $arr) === FALSE )
		{
			$sc = 'บันทึกรายการไม่สำเร็จ';
		}
	}
	echo $sc;
	
}


if( isset( $_GET['deleteTab'] ) )
{
	$sc = 'fail';
	$id = $_GET['id'];
	$cs = new product_tab();	
	$id_parent = $cs->getParentId($id);
	if( $cs->delete($id) === TRUE )
	{
		//----- เปลียน id_product_tab ในสินค้าที่อ้างถึง ให้เป็น 0
		$cs->updateChild($id, $id_parent);	
		$sc = 'success';
	}
	echo $sc;
}


//----------- Get product_tab data for edit
if( isset( $_GET['getData'] ) )
{
	$sc = "";
	$id = $_GET['id'];
	$cs = new product_tab($id);
	$parentTree = getEditTabsTree($id);
	if( $cs->id != '' )
	{
		$sc = $cs->id .' | ' . $cs->name . ' | ' . $parentTree;	
	}
	echo $sc;
}


if( isset( $_GET['getTabsTree'] ) )
{
	echo getTabsTree();	
}

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sParent');
	deleteCookie('sTabName');
	echo 'done';	
}

?>