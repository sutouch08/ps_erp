<?php
class validate
{
	public function __construct(){}
	
	public function getApproveCode($id_tab, $sKey)
	{
		$sc = FALSE;
		$key = md5($sKey);
		$qs = dbQuery("SELECT id_employee, id_profile FROM tbl_employee	WHERE s_key = '".$key."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $id_employee, $id_profile ) = dbFetchArray($qs);
			$qr = dbQuery("SELECT tbl_access.add, tbl_access.edit, tbl_access.delete FROM tbl_access WHERE id_profile = ".$id_profile." AND id_tab = ".$id_tab);
			if( dbNumRows($qr) == 1 )
			{
				list( $add, $edit, $delete ) = dbFetchArray($qr);
				if( ($add + $edit + $delete) > 0 )
				{
					$sc = $id_employee.' | '.md5($id_employee);
				}
			}
		}
		return $sc;
	}
	
	
}//--- end class

?>