<?php
class bank_account
{
	public $id_account;
	public $bank_code;
	public $bank_name;
	public $branch;
	public $acc_name;
	public $acc_no;
	public $swift_code;
	public $active;
	public function __construct($id="")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_bank_account WHERE id_account = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchArray($qs);
				foreach( $rs as $key => $value )
				{
					$this->$key = $value;					
				}
			}
		}
	}
	
	
	public function getData()
	{
		return dbQuery("SELECT * FROM tbl_bank_account");
	}
	
	
	public function search($txt, $fields = "")
	{
		if( $fields == "" )
		{
			return dbQuery("SELECT * FROM tbl_bank_account WHERE bank_name LIKE '%".$txt."%' OR branch LIKE '%".$txt."%' OR acc_name LIKE '%".$txt."%' OR acc_no LIKE '%".$txt."%'");
		}
		else
		{
			return dbQuery("SELECT ".$fields." FROM tbl_bank_account WHERE bank_name LIKE '%".$txt."%' OR branch LIKE '%".$txt."%' OR acc_name LIKE '%".$txt."%' OR acc_no LIKE '%".$txt."%'");
		}	
	}
	
}/// end class

?>