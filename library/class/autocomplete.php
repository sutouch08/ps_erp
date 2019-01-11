<?php
class autocomplete
{
	public function __construct(){}
	
	public function customer($txt, $useFields)
	{
		if( $txt == '*' )
		{
			$sc = dbQuery("SELECT ".$userFields." FROM tbl_customer");
		}
		else
		{
			$sc = dbQuery("SELECT ".$useFields." FROM tbl_customer WHERE code LIKE'%".$txt."%' OR name LIKE '%".$txt."%'");
		}
		return $sc;
	}
	
	
}


?>