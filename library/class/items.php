<?php
class items{
	
	public function __construct(){}

	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ". $field;
				$values	.= $i == 1 ? "'".$value."'" : ", '". $value . "'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_products_attribute(".$fields.") VALUES (".$values.")");
				
		}
		return $sc;			
	}
	
	public function update($code, array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$set = "";
			$i	= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;	
			}
			
			$sc = dbQuery("UPDATE tbl_product_attribute SET ".$set." WHERE reference = '".$code."'");
			
		}
		
		return $sc;
	}
	
	public function isExists($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_product_attribute WHERE reference = '".$code."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		
		return $sc;
	}
	
	

	
	
}

?>