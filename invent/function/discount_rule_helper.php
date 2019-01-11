<?php

function showItemDiscountLabel($item_price, $item_disc, $unit)
{
	$disc = 0.00;
	//---	ถ้าเป็นการกำหนดราคาขาย
	if($item_price > 0)
	{
		$disc = 'Price '.$item_price;
	}
	else
	{
		$symbal = $unit == 'percent' ? '%' : getConfig('CURRENCY');
		$disc = $item_disc.' '.$symbal;
	}

	return $disc;
}


function getPolicyIn($txt)
{
	$sc = 500000;
	$qs = dbQuery("SELECT id FROM tbl_discount_policy WHERE reference LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if(dbNumRows($qs) > 0)
	{
		$sc = '';
		$i = 1;
		while($rs = dbFetchObject($qs))
		{
			$sc .= $i == 1 ? $rs->id : ', '.$rs->id;
			$i++;
		}
	}

	return $sc;
}



function getRuleCustomerId($id_rule)
{
		$sc = array();
		$qs = dbQuery("SELECT * FROM tbl_discount_rule_customers WHERE id_rule = '".$id_rule."'");
		if(dbNumRows($qs) > 0)
		{
			while($rs = dbFetchObject($qs))
			{
				$sc[$rs->id_customer] = $rs->id_customer;
			}
		}

		return $sc;
}



function getRuleCustomerClass($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_customer_class WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_customer_class] = $rs->id_customer_class;
		}
	}

	return $sc;
}


function getRuleCustomerGroup($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_customer_group WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_customer_group] = $rs->id_customer_group;
		}
	}

	return $sc;
}


function getRuleCustomerKind($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_customer_kind WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_customer_kind] = $rs->id_customer_kind;
		}
	}

	return $sc;
}


function getRuleCustomerType($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_customer_type WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_customer_type] = $rs->id_customer_type;
		}
	}

	return $sc;
}



function getRuleCustomerArea($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_customer_area WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_customer_area] = $rs->id_customer_area;
		}
	}

	return $sc;
}


function setAllCustomer($id_rule, $value)
{
	return dbQuery("UPDATE tbl_discount_rule SET all_customer = ".$value." WHERE id = ".$id_rule);
}



function dropCustomerListRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_customers WHERE id_rule = '".$id_rule."'");
}




function dropCustomerGroupRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_customer_group WHERE id_rule = '".$id_rule."'");
}




function dropCustomerTypeRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_customer_type WHERE id_rule = '".$id_rule."'");
}




function dropCustomerKindRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_customer_kind WHERE id_rule = '".$id_rule."'");
}



function dropCustomerAreaRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_customer_area WHERE id_rule = '".$id_rule."'");
}



function dropCustomerClassRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_customer_class WHERE id_rule = '".$id_rule."'");
}








function getRuleProductId($id_rule)
{
		$sc = array();
		$qs = dbQuery("SELECT * FROM tbl_discount_rule_items WHERE id_rule = '".$id_rule."'");
		if(dbNumRows($qs) > 0)
		{
			while($rs = dbFetchObject($qs))
			{
				$sc[$rs->id_product] = $rs->id_product;
			}
		}

		return $sc;
}



function getRuleProductBrand($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_brand WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_brand] = $rs->id_product_brand;
		}
	}

	return $sc;
}


function getRuleProductGroup($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_group WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_group] = $rs->id_product_group;
		}
	}

	return $sc;
}


function getRuleProductKind($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_kind WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_kind] = $rs->id_product_kind;
		}
	}

	return $sc;
}


function getRuleProductType($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_type WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_type] = $rs->id_product_type;
		}
	}

	return $sc;
}



function getRuleProductCategory($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_category WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_category] = $rs->id_product_category;
		}
	}

	return $sc;
}



function getRuleProductStyle($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_style WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_style] = $rs->id_product_style;
		}
	}

	return $sc;
}



function getRuleProductSubGroup($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_sub_group WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->id_product_sub_group] = $rs->id_product_sub_group;
		}
	}

	return $sc;
}


function getRuleProductYear($id_rule)
{
	$sc = array();
	$qs = dbQuery("SELECT * FROM tbl_discount_rule_product_year WHERE id_rule = '".$id_rule."'");
	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[$rs->year] = $rs->year;
		}
	}

	return $sc;
}







function setAllProduct($id_rule, $value)
{
	return dbQuery("UPDATE tbl_discount_rule SET all_product = ".$value." WHERE id = ".$id_rule);
}






function dropProductListRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_items WHERE id_rule = ".$id_rule);
}



function dropProductStyleRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_style WHERE id_rule = ".$id_rule);
}





function dropProductBrandRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_brand WHERE id_rule = ".$id_rule);
}





function dropProductYearRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_year WHERE id_rule = ".$id_rule);
}


function dropProductGroupRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_group WHERE id_rule = ".$id_rule);
}




function dropProductTypeRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_type WHERE id_rule = ".$id_rule);
}




function dropProductKindRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_kind WHERE id_rule = ".$id_rule);
}





function dropProductCategoryRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_category WHERE id_rule = ".$id_rule);
}





function dropProductSubGroupRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_product_sub_group WHERE id_rule = ".$id_rule);
}



function getRuleChannels($id_rule)
{
		$sc = array();
		$qs = dbQuery("SELECT * FROM tbl_discount_rule_channels WHERE id_rule = '".$id_rule."'");
		if(dbNumRows($qs) > 0)
		{
			while($rs = dbFetchObject($qs))
			{
				$sc[$rs->id_channels] = $rs->id_channels;
			}
		}

		return $sc;
}


function dropChannelsRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_channels WHERE id_rule = ".$id_rule);
}



function setAllChannels($id_rule, $val)
{
	return dbQuery("UPDATE tbl_discount_rule SET all_channels = ".$val." WHERE id = ".$id_rule);
}



function getRulePayment($id_rule)
{
		$sc = array();
		$qs = dbQuery("SELECT * FROM tbl_discount_rule_payment WHERE id_rule = '".$id_rule."'");
		if(dbNumRows($qs) > 0)
		{
			while($rs = dbFetchObject($qs))
			{
				$sc[$rs->id_payment] = $rs->id_payment;
			}
		}

		return $sc;
}



function dropPaymentRule($id_rule)
{
	return dbQuery("DELETE FROM tbl_discount_rule_payment WHERE id_rule = ".$id_rule);
}


function setAllPayment($id_rule, $val)
{
	return dbQuery("UPDATE tbl_discount_rule SET all_payment = ".$val." WHERE id = ".$id_rule);
}

 ?>
