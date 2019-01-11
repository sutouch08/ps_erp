<?php
class company
{
	public function __construct()
	{
			
	}
	
	public function getName()
	{
		$qs = dbQuery("SELECT value FROM tbl_config WHERE config_name = 'COMPANY_NAME'");
		list( $rs ) = dbFetchArray($qs);	
		return $rs;
	}
}

?>